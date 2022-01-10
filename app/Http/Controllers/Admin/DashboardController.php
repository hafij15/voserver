<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\File;
use App\Http\Controllers\Controller;
use App\ProjectPlan;
use App\SubTask;
use App\Tdo;
use App\User;
use App\Wbs;
use App\WbsMaster;
use App\WorkPackage;
use Auth;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // VO variables
        $tdos = '';
        $subTasks = '';
        $workPackages = '';
        $projectPlans = '';
        $projectPlanDetails = '';
        $WbsMasters = '';
        $user = '';
        $users = '';
        $projectAssigneeArray = [];
        $meetings = '';
        $meeting_info = [];
        $meeting_information = [];
        $approval_users = '';
        $moderators = '';
        $pm = '';
        $employees = '';
        $dashboard_info = '';
        $my_file = 0;
        $wbsinfo = '';
        $total_complelte_wbs = '';
        $total_incomplelte_wbs = '';
        $total_wbs_pm = 0;
        $pm_wbs_complete = [];
        $wbs_total = [];//emp wbs
        //dd($my_file)

        if (Auth::user()->hasRole('super-admin')) {
            $approval_users = User::latest()
                ->where('is_active', 0)
                ->where('is_deleted', 0)
                ->get();

            $moderators = User::with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('slug', '=', 'admin');
                })
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            $pm = User::with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('slug', '=', 'power-user');
                })
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            $employees = User::with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('slug', '=', 'user');
                })
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->get();
            
            $projects = SubTask::get();

            $meetings = Appointment::where('isServiced', 0)
            ->whereDate('meeting_dateTime', '=', Carbon::now())->get();//meeting_dateTime
            //dd($meetings);
            $dashboard_info = array('employees' => count($employees) + count($pm), 'approval_users' => count($approval_users));
        }
        if(Auth::user()->hasRole('admin')){
            $tdos = Tdo::get();
            $WbsMasters = WbsMaster::get();
            $meetings_invite = Appointment::get();
            $all_subtask = SubTask::get();
            $inv_id = [];
            foreach ($meetings_invite as $value) {
                $inv_id = explode(',', $value->participant_id);
                foreach ($inv_id as $key => $val) {
                    if ($val == Auth::user()->id) {
                        $meeting_information[] = Appointment::orderBy('meeting_dateTime', 'asc')->get();
                    }
                }
            }
            $subTasks = $all_subtask->unique('sub_task_name');
            $workPackages = WorkPackage::get();
            $projectPlans = ProjectPlan::get();
            // dd($projectPlans);
            $meeting_info = collect($meeting_information);
            $todays_meetings = Appointment::select('id', 'pm_id', 'participant_id')
                ->where('isServiced', 0)
                ->whereDate('meeting_dateTime', '=', Carbon::now())
                ->get();
            $dashboard_info = array('projects' => count($all_subtask), 
            'meetings' => $meeting_info,'meeting_today'=>$todays_meetings);
            

        }

        if (Auth::user()->hasRole('power-user')) {
            $tdos = Tdo::where('created_by', Auth::user()->id)->get();
            $count = 0;
            $subtask_by_pm=[];
            $pmWP =[];
            $assignedWorkPackages = [];
            if ($tdos != null) {
                // foreach ($tdos as $key => $value) {
                //     foreach ($tdos[$key]->subTask as $key1 => $value1) {
                //         $subtask_by_pm [] = $value1;
                //         foreach ($tdos[$key]->subTask[$key1]->WorkPackage as $key2 => $value2) {
                //             foreach ($tdos[$key]->subTask[$key1]->WorkPackage[$key2]->projectPlan as $key3 => $value3) {
                //                 $projectAssigneeArray[$count] = $tdos[$key]->subTask[$key1]->WorkPackage[$key2]->projectPlan[$key3]->project_plan_assignee;
                //                 $count++;
                //             }
                //         }
                //     }
                // }

                foreach ($tdos as $key => $value) {
                    foreach ($tdos[$key]->subTask as $key2 => $value2) {
                        $subtask_by_pm [] = $value2;
                        foreach ($tdos[$key]->subTask[$key2]->workPackage as $key3 => $value3) {
                            $pmWP[] = $value3->id;
                            foreach ($tdos[$key]->subTask[$key2]->workPackage[$key3]->projectPlan as $key4 => $value4) {
                                $projectAssigneeArray[$count] = $tdos[$key]->subTask[$key2]->workPackage[$key3]->projectPlan[$key4]->project_plan_assignee;
                                $count++;
                            }
                        }
                    }
                }
            }
            //dd($pmWP);
            $assignedWorkPackages = ProjectPlan::whereIn('project_plan_work_package_id', $pmWP)->get();
            // get all unique subtask from sub_tasks table
            $subTasks = [];
            foreach ($assignedWorkPackages as $key => $value) {
                $subTasks[$key] = $value->workPackage->subTask;
            }
            $subTasks = collect($subTasks)->unique('sub_task_name');

            // get all unique workPackage from work_packages table
            $workPackages = [];
            foreach ($assignedWorkPackages as $key => $value) {
                $workPackages[$key] = $value->workPackage;
            }
            $workPackages = collect($workPackages);
            $projectPlans = collect($assignedWorkPackages);
          
            // $projectPlans = ProjectPlan::whereIn('project_plan_assignee', $projectAssigneeArray)->get();
            // get all details from project_plan_details table
            $projectPlanDetails = [];
            foreach ($projectPlans as $key => $value) {
                $projectPlanDetails[$key] = $projectPlans[$key]->projectPlanDetails->unique('project_plan_project_plan_id')[0];
            }
            $projectPlanDetails = collect($projectPlanDetails);
            // -----------------
            $pm_plan_ids=[];
            foreach ($projectPlanDetails as $key => $value) {
                $pm_plan_ids[]=$value->id;
            }
            $all_wbs_master_PM = WbsMaster::whereIn('wbs_master_project_plan_details_id',$pm_plan_ids)
            ->orderBy('created_at', 'asc')->get();
            //dd($all_wbs_master_PM);
            $total_wbs_pm = count($all_wbs_master_PM);
            $all_wbs_master_wbsId =[];
            foreach ($all_wbs_master_PM as $key => $value) {
                $all_wbs_master_wbsId[]=$value->wbs_master_wbs_id;
                // dump($value->wbs_master_wbs_id);
            }
            // die();
            $all_wbs_PM = Wbs::whereIn('id',$all_wbs_master_wbsId)->get();
            $pm_wbs_complete = Wbs::whereIn('id',$all_wbs_master_wbsId)->where('status','LIKE','%100%')->get();
            
            // dd(count($pm_wbs_complete));
            // -----------------

            // $WbsMasters = array_unique($WbsMasters);
            $WbsMasters = $all_wbs_master_PM;
            // dd(($WbsMasters));
            $todays_meetings = Appointment::select('id', 'pm_id', 'participant_id')
                ->where('isServiced', 0)
                ->whereDate('meeting_dateTime', '=', Carbon::now())
                ->get();
            // dd($todays_meetings);
            $meetings_invite = Appointment::select('id', 'pm_id', 'participant_id')
                ->where('isServiced', 0)
                ->whereDate('meeting_dateTime', '=', Carbon::now())
                ->orWhereDate('meeting_dateTime', '>', Carbon::now())
                ->get();
            $inv_id = [];
            foreach ($meetings_invite as $value) {
                $inv_id = explode(',', $value->participant_id);
                foreach ($inv_id as $key => $val) {
                    if ($val == Auth::user()->id) {
                        $meeting_information[] = Appointment::where('id', $value->id)->orderBy('meeting_dateTime', 'asc')->get();
                    }
                }
            }
            // $projectPlanDetails = collect($projectPlanDetails);
            $meeting_info = collect($meeting_information);
            $dashboard_info = array('employees' => count($projectAssigneeArray) - 1, 'projects' => count($subtask_by_pm), 
            'meetings' => $meeting_info,'meeting_today'=>$todays_meetings);
        }

        if (Auth::user()->hasRole('user')) {
            $WbsMasters = WbsMaster::latest()
                ->where('wbs_master_assignee_id', Auth::user()->id)
                ->orWhere('reporter', Auth::user()->id)
                ->get();
            $wbs_total = $WbsMasters;
            $all_project_plan = ProjectPlan::all('project_plan_work_package_id', 'project_plan_assignee');
            $assigne_id = [];
            $assignedWorkPackages = [];
            $projectPlans = [];

            foreach ($all_project_plan as $plan) {

                $assigne_id = [];
                $assigne_id[] = explode(',', $plan->project_plan_assignee);

                foreach ($assigne_id as $array_assign_id) {
                    // dump($array_assign_id);

                    foreach ($array_assign_id as $val) {

                        //dump((int) $val == Auth::user()->id);

                        if ((int) $val == Auth::user()->id) {

                            array_push($projectPlans, ProjectPlan::where('project_plan_assignee', $plan->project_plan_assignee)->get());
                            // dump($projectPlans);

                            // $projectPlans[] = ProjectPlan::where('project_plan_assignee', $plan->project_plan_assignee)->get();
                            
                            $assignedWorkPackages[] = ProjectPlan::select('project_plan_work_package_id', 'project_plan_assignee')
                                ->where('project_plan_assignee', $plan->project_plan_assignee)->get();
                            break;
                        }
                    }

                }
                
                // foreach ($assigne_id as $val) {
                //     // $val = (int) $val;
                //     // dump($val);

                //     if ((int) $val == Auth::user()->id) {
                //         dump((int) $val);

                //         $assignedWorkPackages = ProjectPlan::select('project_plan_work_package_id', 'project_plan_assignee')
                //         ->where('project_plan_assignee', $plan->project_plan_assignee)->get();
                //         $projectPlans = ProjectPlan::where('project_plan_assignee', $plan->project_plan_assignee)->get();

                //         //break;
                //     }
                // }

            }

            // die();
            // dd($projectPlans);

            // die();
            // dd($projectPlans);

            // $assignedWorkPackages = ProjectPlan::where('project_plan_assignee', Auth::user()->id)
            //     ->select('project_plan_work_package_id')
            //     ->distinct()
            //     ->get();

            $my_file = File::latest()
                ->where('user_id', Auth::user()->id)
                ->get();
            // get all unique subtask from sub_tasks table
            $subTasks = [];
            foreach ($assignedWorkPackages as $key => $value) {
                // $subTasks[$key] = $assignedWorkPackages[$key]->workPackage->subTask;
                // dump($value);
                foreach ($value as $key => $value1) {
                    $subTasks[] = $value1->workPackage->subTask;
                    
                }
                // $subTasks[$key] = $value[0]->workPackage->subTask;
                // dd($subTasks[$key]);

            }
            // die();
            $subTasks = collect($subTasks)->unique('sub_task_name');
            // dump($subTasks);
            // get all unique workPackage from work_packages table
            $workPackages = [];
            foreach ($assignedWorkPackages as $key => $value) {
                // $workPackages[$key] = $assignedWorkPackages[$key]->workPackage;
                foreach ($value as $key => $value1) {
                    $workPackages[] = $value1->workPackage;

                }

            }
            $workPackages = collect($workPackages);
            // dd($workPackages);
            // get all details from project_plan_details table
            $projectPlanDetails = [];

            foreach ($projectPlans as $key => $value) {
                foreach ($value as $key => $val) {
                    // dump($val);
                    $projectPlanDetails[] = $val->projectPlanDetails->unique('project_plan_project_plan_id')[0];
                }
                // $projectPlanDetails[$key] = $projectPlans[$key]->projectPlanDetails->unique('project_plan_project_plan_id')[0];
            }
            // die();

            $todays_meetings = Appointment::select('id', 'pm_id', 'participant_id')
                ->where('isServiced', 0)
                ->whereDate('meeting_dateTime', '=', Carbon::now())
                ->get();

            $meetings_invite = Appointment::select('id', 'pm_id', 'participant_id')
                ->where('isServiced', 0)
                ->whereDate('meeting_dateTime', '=', Carbon::now())
                ->orwhereDate('meeting_dateTime', '>', Carbon::now())
                ->get();
            $inv_id = [];
            
            // dd($meetings_invite);
            // $check_meeting_host = [];->add(['check_meeting_host' => '0'])
            foreach ($meetings_invite as $value) {
                $inv_id = explode(',', $value->participant_id);
                // if (Auth::user()->id == $value->pm_id) {
                //     // $check_meeting_host[]=1;
                //     $meeting_information[] = Appointment::where('pm_id', $value->pm_id)->orderBy('meeting_dateTime', 'asc')->get()->add(['check_meeting_host' => '1']);
                // }
                foreach ($inv_id as $key => $val) {
                    if ($val == Auth::user()->id) {
                        $meeting_information[] = Appointment::where('id', $value->id)->orderBy('meeting_dateTime', 'asc')->get();
                    }
                }
            }
            $projectPlanDetails = collect($projectPlanDetails);
            // $projectassign = ProjectPlan::select('project_plan_work_package_id')->where('project_plan_assignee', Auth::user()->id)->distinct()->get();
            // foreach ($projectassign as $projectassign_value) {
            //     $subtaskinfo = WorkPackage::select('work_package_subtask_id')->where('id', $projectassign_value->project_plan_work_package_id)->get();
            //     foreach ($subtaskinfo as $subtaskinof_value) {
            //         $subtaskinfo = SubTask::select('subtask_tdo_id')->where('id', $subtaskinof_value->work_package_subtask_id)->get();
            //         foreach ($subtaskinfo as $subtaskinfo_value) {
            //             $pminfo = Tdo::select('created_by')->where('id', $subtaskinfo_value->subtask_tdo_id)->get();
            //             foreach ($pminfo as $pminfo_value) {
            //                 $meeting_information[] = Appointment::where('pm_id', $pminfo_value->created_by)->where('isServiced', 0)->orderBy('meeting_dateTime', 'asc')->get();
            //             }
            //         }
            //     }
            // }
            // if (count($meeting_information) == 0) {
            //     $meeting_info = $meeting_information;
            // } else {
            //     $meeting_info = collect(($meeting_information));
            // }
            //
            // dump(array_unique($meeting_information));
            $meeting_info = collect($meeting_information);
            $projectPlans = collect($projectPlans);
            //array_unique($meeting_information);
            $dashboard_info = array('projects' => count($subTasks), 'meetings' => $meeting_info, 'meeting_today'=>$todays_meetings);
        }

        $users = User::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('slug', '!=', 'super-admin');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $loggedInUser = Auth::user()->id;
        $completewbs = array();
        $Incompletewbs = array();

        //$wbsinfo = Wbs::select('status')->get();'LIKE', '%'.$search.'%'
        $wbs_complete = DB::table('wbs_masters')->select('wbs_masters.wbs_master_assignee_id', 'wbs.status')
            ->join('wbs', 'wbs.id', '=', 'wbs_masters.wbs_master_wbs_id')
            ->where('wbs_masters.wbs_master_assignee_id', $loggedInUser)
            ->orWhere('wbs_masters.reporter', $loggedInUser)
            ->get();
        $wbs_complete_emp = [];
            foreach ($wbs_complete as $key => $value) {
               if($value->status == "100"){
                $wbs_complete_emp[]=$value;
               }
            }
        // $wbs_total = WbsMaster::latest()
        //         ->where('wbs_master_assignee_id', Auth::user()->id)
        //         ->orWhere('reporter', Auth::user()->id)
        //         ->get();
        // DB::table('wbs_masters')->select('wbs_masters.wbs_master_assignee_id', 'wbs.status')
        //     ->join('wbs', 'wbs.id', '=', 'wbs_masters.wbs_master_wbs_id')
        //     ->where('wbs_masters.wbs_master_assignee_id', $loggedInUser)
        //     ->orWhere('wbs_masters.reporter', $loggedInUser)
        //     ->get();
        // $pm_wbs_complete = Wbs::select('status')->where('status', 100)->get();

        // $pm_wbs_complete = DB::table('wbs_masters')->select('wbs_masters.reporter','Wbs.status')
        // ->join('Wbs','Wbs.id','=','wbs_masters.wbs_master_wbs_id')
        // ->where('wbs_masters.reporter',$loggedInUser)
        // ->where('Wbs.status',100)
        // ->get();
        // $pm_wbs_total = DB::table('wbs_masters')->select('wbs_masters.reporter', 'wbs.status')
        //     ->join('wbs', 'wbs.id', '=', 'wbs_masters.wbs_master_wbs_id')
        //     ->where('wbs_masters.reporter', $loggedInUser)
        //     ->get();
        $wbsinfo = Wbs::all();
        //dd($wbs_complete->count());

        // foreach($wbsinfo as $wbsinfo_value)
        // {
        //     //dd($wbsinfo_value);
        //     if($wbsinfo_value->status === '100')
        //     {
        //         $completewbs[] = $wbsinfo_value->status;
        //     }
        //     else{
        //         $Incompletewbs[] = $wbsinfo_value->status;
        //     }
        // }

        $total_complete_wbs = count($wbs_complete_emp);
        $total_wbs = count($wbs_total);
        $total_complete_wbs_pm = count($pm_wbs_complete);
        $all_projects = ProjectPlan::all();

        $all_wbs =count(Wbs::get()) ;
        $all_complete_wbs = count(Wbs::where('status','LIKE','%100%')->get());
        // $total_wbs_pm = count($pm_wbs_total);
        // echo $total_complete_wbs_pm;
        // exit();
        // dd($all_wbs,);

        return view('admin.dashboard', compact(
            'dashboard_info',
            'loggedInUser',
            'users',
            'tdos',
            'subTasks',
            'workPackages',
            'projectPlans',
            'projectPlanDetails',
            'WbsMasters',
            'meetings',
            'meeting_info',
            'approval_users',
            'moderators',
            'pm',
            'employees',
            'my_file',
            'total_complete_wbs',
            'total_incomplelte_wbs',
            'wbsinfo',
            'wbs_complete',
            'wbs_total',
            'total_wbs',
            'total_complete_wbs_pm',
            'total_wbs_pm',
            'all_projects',
            'all_wbs',
            'all_complete_wbs'
        ));
    }
}
