<?php

namespace App\Console\Commands;

use App\Models\Participation;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResponsableDailyTodo;

class SendNotificationTodoToResponsables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:responsables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to Responsables when new content';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $nb_jours_notif = 10;

        $participationsByStructure = Participation::with('mission.structure.responsables')->where('state', 'En attente de validation')
          ->where('created_at', '>', Carbon::now()->subDays($nb_jours_notif)->startOfDay())
          ->get()
          ->groupBy('mission.structure.id');

        foreach ($participationsByStructure as $structureId => $participations) {
            Notification::send($participations[0]->mission->structure->responsables, new ResponsableDailyTodo($participations));
        }
    }
}