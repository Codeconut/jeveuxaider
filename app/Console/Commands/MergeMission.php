<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class MergeMission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:merge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge children mission in mother mission';

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
        $mainMissionId = $this->ask('Id of the main mission');
        $mainMission = Mission::find($mainMissionId);
        if (!$mainMission) {
            $this->error('Mission with id '.$mainMissionId.' does not exist.');
            return;
        }
        $childrenMissionsIds = $this->ask('Ids of the children missions. Separate id with space');
        $childrenMissionsIds = explode(' ', $childrenMissionsIds);

        $childrenMissions = Mission::find($childrenMissionsIds);

        $this->info(count($childrenMissionsIds) . ' children missions will be merge.');
        $this->info('Participations will be transfered to the main mission.');
        $this->info('Children missions will be canceled');

        if ($this->confirm('Do you wish to continue?')) {
            foreach ($childrenMissions as $childMission) {
                $this->info(count($childMission->participations) . 'participations transfered');
                $childMission->participations->each->update(['mission_id' => $mainMissionId]);
                $mainMission->update([
                    'participations_max' => $mainMission->participations_max + $childMission->participations_max
                ]);
                $childMission->delete();
            }
            $this->info('Sucessfully merged missions!');
        }
    }
}
