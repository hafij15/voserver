<?php

namespace App\Console\Commands;

use App\Appointment;
use App\ProjectPlan;
use App\SubTask;
use App\User;
use App\WorkPackage;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $meetings_notify = '';
        $meetings_notify = Appointment::where('isServiced', 0)
            ->whereDate('meeting_dateTime', '=', Carbon::now())
            ->get();

        foreach ($meetings_notify as $key) {
            //  \Log::info($key->slot_id);
            $timenow_get = Carbon::now(new \DateTimeZone('Asia/Dhaka'))->toTimeString();
            $timenow = Carbon::parse($timenow_get);
            $createdDate = Carbon::parse($key->meeting_dateTime);
            $pm = User::select('phone', 'email')->where('id', $key->pm_id)->first();
            
            $timenowstr = $timenow->format('H:i');

            $visitTimestr = $createdDate->subMinutes(10)->format('H:i');
            if ($visitTimestr == $timenowstr) {
                $workpkg = WorkPackage::where('work_package_subtask_id', $key->subtask_id)->get();
                if($key->subtask_id != null){
                    $subtasktitle = SubTask::select('sub_task_name')->where('id', $key->subtask_id)->first();
                    $sms_subtasktitle = $subtasktitle->sub_task_name;
                }else{
                    $sms_subtasktitle = "General Meeting";
                }
                sendMeetingAlertToPM($pm, $createdDate, $sms_subtasktitle);
                foreach ($workpkg as $workpkg_value) {
                    $Projectplan = ProjectPlan::select('project_plan_assignee')->where('project_plan_work_package_id', $workpkg_value->id)->distinct()->get();
                    foreach ($Projectplan as $Projectplan_value) {
                        $user_info = User::select('phone', 'email')->where('id', $Projectplan_value->project_plan_assignee)->first();
                        // dd($user_info, $pm,$createdDate, $subtasktitle->sub_task_name);
                        sendMeetingAlertToEmployee($user_info, $pm, $createdDate, $sms_subtasktitle);
                    }
                }
                \Log::info($timenow);
            } else {
                // dd(false);
                \Log::info($visitTimestr);
            }

        }

        /*
        Write your database logic we bellow:
        Item::create(['name'=>'hello new']);
         */

        $this->info('Demo:Cron Cummand Run successfully!');
    }
}
