<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collectivity;
use App\Models\Release;
use App\Models\Structure;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Thematique;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConfigController extends Controller
{
    public function bootstrap()
    {
        return response()->json([
            'user' => User::currentUser(),
            'release' => $this->release(),
            'taxonomies' => $this->taxonomies(),
            'thematiques' => $this->thematiques(),
            'reseaux' => $this->reseaux(),
            'collectivities' => $this->collectivities(),
        ]);
    }

    public function reminders(Request $request)
    {
        if ($request->header('Context-Role') == 'referent') {
            $structures = Structure::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count();
            $missions = Mission::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count();
            return [
                'total' => $structures + $missions,
                'structures' => $structures,
                'missions' => $missions
            ];
        }

        if ($request->header('Context-Role') == 'responsable') {
            $participations = Participation::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count();
            return [
                'total' => $participations,
                'participations' => $participations
            ];
        }
    }

    private function release()
    {
        return Release::orderBy('date', 'desc')->first();
    }

    private function thematiques()
    {
        return Thematique::all();
    }

    private function reseaux()
    {
        return Structure::where('is_reseau', true)->orderBy('name')->get()->map(function ($structure) {
            return [
                'id' => $structure->id,
                'name' => $structure->name
            ];
        });
    }

    private function collectivities()
    {
        return Collectivity::where('type', 'commune')->get()->map(function ($collectivity) {
            return [
                'id' => $collectivity->id,
                'name' => $collectivity->name
            ];
        });
    }

    private function taxonomies()
    {
        $taxonomies = config('taxonomies');
        foreach ($taxonomies as $key => $taxonomy) {
            if (isset($taxonomy['terms'])) {
                $terms = [];
                foreach ($taxonomy['terms'] as $key2 => $term) {
                    $terms[] = [
                        'value' => (string) $key2,
                        'label' => $term,
                    ];
                }
                $taxonomies[$key]['terms'] = $terms;
            }
        }
        return $taxonomies;
    }

    public function export(Request $request, $table)
    {
        $output = '';

        switch ($table) {
            case 'structures':
                $rows = Structure::cursor();
                $columns = ['id','user_id','name','is_reseau','reseau_id','siret','statut_juridique','association_types','structure_publique_type','structure_publique_etat_type','structure_privee_type','address','zip','city','department','latitude','longitude','website','facebook','twitter','instagram','state','created_at','updated_at','deleted_at'];
            break;
            case 'missions':
                $rows = Mission::cursor();
                $columns = ['id','user_id','name','structure_id','responsable_id','participations_max','format','start_date','end_date','address','zip','city','department','country','latitude','longitude','state','created_at','updated_at','deleted_at','periodicite','publics_beneficiaires','publics_volontaires','type','places_left'];
            break;
            case 'profiles':
                $rows = Profile::cursor();
                $columns = ['id', 'first_name', 'last_name', 'email','birthday','mobile','zip','created_at'];
            break;
            case 'participations':
                $rows = Participation::cursor();
                $columns = ['id','profile_id','mission_id','state','created_at','deleted_at'];
            break;
        }

        if ($rows) {
            $output .=  implode(";", $columns) . "\n";
            foreach ($rows as $row) {
                $array = [];
                foreach ($columns as $column) {
                    $array[$column] = is_array($row->{$column}) ? implode(',', $row->{$column}) : $row->{$column};
                }
                $output .=  implode(";", $array) . "\n";
            }
        }

        return response()->streamDownload(function () use ($output) {
            echo $output;
        }, 'output.csv');
    }
}
