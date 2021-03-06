<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\Collectivity;
use App\Models\Profile;
use App\Models\Structure;
use App\Notifications\CollectivityWaitingValidation;
use App\Notifications\StructureAssociationValidated;
use App\Notifications\StructureCollectivityValidated;
use App\Notifications\StructureSignaled;
use App\Notifications\StructureSubmitted;
use App\Notifications\StructureValidated;
use Illuminate\Support\Facades\Notification;

class StructureObserver
{
    /**
     * Listen to the Structure created event.
     *
     * @param  \App\Models\Structure  $structure
     * @return void
     */
    public function created(Structure $structure)
    {
        if ($structure->user->profile) {
            $structure->members()->attach($structure->user->profile, ['role' => 'responsable']);
        }

        if ($structure->state == 'En attente de validation') {
            if ($structure->department) {
                Profile::where('referent_department', $structure->department)->get()->map(function ($profile) use ($structure) {
                    $profile->notify(new StructureSubmitted($structure));
                });
            }
        }

        if ($structure->statut_juridique == 'Collectivité') {
            $this->createCollectivity($structure);
        }

        SendinblueSyncUser::dispatch($structure->user);
    }

    public function updated(Structure $structure)
    {
        // STATE
        $oldState = $structure->getOriginal('state');
        $newState = $structure->state;

        if ($oldState != $newState) {
            switch ($newState) {
                case 'Validée':
                    if ($structure->user->profile) {
                        $structure->user->profile->notify(new StructureValidated($structure));
                        if ($structure->statut_juridique == 'Collectivité') {
                            $structure->user->notify(new StructureCollectivityValidated($structure));
                        } else {
                            $structure->user->notify(new StructureAssociationValidated($structure));
                        }
                    }
                    if ($structure->missions) {
                        foreach ($structure->missions->where("state", "En attente de validation") as $mission) {
                            $mission->update(['state' => 'Validée']);
                        }
                        foreach ($structure->missions->where("state", "Validée") as $mission) {
                            $mission->searchable();
                        }
                    }
                    break;
                case 'Signalée':
                    if ($structure->user->profile) {
                        $structure->user->profile->notify(new StructureSignaled($structure));
                    }
                    if ($structure->missions) {
                        foreach ($structure->missions as $mission) {
                            $mission->update(['state' => 'Signalée']);
                        }
                    }
                    break;
                case 'Désinscrite':
                    $structure->members()->detach();
                    if ($structure->missions) {
                        foreach ($structure->missions->where("state", "En attente de validation") as $mission) {
                            $mission->update(['state' => 'Annulée']);
                        }
                        $structure->missions->unsearchable();
                    }
                    break;
            }
        }

        // DEPARTMENT
        $oldDepartment = $structure->getOriginal('department');
        $newDepartment = $structure->department;

        if ($oldDepartment != $newDepartment) {
            if ($structure->state == 'En attente de validation') {
                if ($structure->department) {
                    Profile::where('referent_department', $structure->department)->get()->map(function ($profile) use ($structure) {
                        $profile->notify(new StructureSubmitted($structure));
                    });
                }
            }
        }

        // STRUCTURE PUBLIQUE TYPE
        if (!$structure->getOriginal('statut_juridique') && $structure->statut_juridique) {
            if ($structure->statut_juridique == 'Collectivité') {
                $this->createCollectivity($structure);
            }
        }

        // Maj Sendinblue
        if ($structure->isDirty('name')) {
            $structure->responsables->each(function ($profile, $key) {
                if ($profile->user) { // Parfois il n'y a pas de user car ce sont des profiles invités
                    SendinblueSyncUser::dispatch($profile->user);
                }
            });
        }
    }

    private function createCollectivity($structure)
    {
        $collectivity = Collectivity::create([
            'name' => $structure->city ?? $structure->name,
            'zips' => $structure->zip ? [$structure->zip] : [],
            'structure_id' => $structure->id,
            'published' => false,
            'type' => 'commune',
            'state' => 'waiting'
        ]);
        $collectivity->save();
        Notification::route('mail', ['achkar.joe@hotmail.fr', 'sophie.hacktiv@gmail.com', 'nassim.merzouk@beta.gouv.fr'])
        ->route('slack', config('services.slack.hook_url'))
        ->notify(new CollectivityWaitingValidation($collectivity));
    }
}
