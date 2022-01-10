<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Project;
use App\ProjectPlan;
use App\ProjectPlanDetail;
use App\SubTask;
use App\Tdo;
use App\User;
use App\WorkPackage;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SebastianBergmann\Environment\Console;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $projects = Project::all();
        // dd($projects);
        return view('admin.project', compact('projects'));
    }

    public function create()
    {
        // $users = User::get();
        $users = User::with('roles')->whereHas('roles', function ($q) {
            $q->where('slug', '!=', 'super-admin');
        })->get();
        $projectAssigneeArray = [];
        $all_subtask = [];
        $subtaskAll = [];
        if (Auth::user()->hasRole('power-user')) {
            $tdos = Tdo::where('created_by', Auth::user()->id)->get();
            $count = 0;
            if ($tdos != null) {
                foreach ($tdos as $key => $value) {
                    foreach ($tdos[$key]->subTask as $key1 => $value1) {
                        $all_subtask[]=$value1;
                        // foreach ($tdos[$key]->subTask[$key1]->WorkPackage as $key2 => $value2) {
                        //     foreach ($tdos[$key]->subTask[$key1]->WorkPackage[$key2]->projectPlan as $key3 => $value3) {
                        //         $projectAssigneeArray[$count] = $tdos[$key]->subTask[$key1]->WorkPackage[$key2]->projectPlan[$key3]->project_plan_assignee;
                        //         $count++;
                        //     }
                        // }
                    }
                }
            }
        }elseif (Auth::user()->hasRole('user')) {
            $all_project_plan = ProjectPlan::all('project_plan_work_package_id', 'project_plan_assignee');
            $assigne_id = [];
            $assignedWorkPackages = [];
            $projectPlans = [];
            foreach ($all_project_plan as $plan) {
                $assigne_id = [];
                $assigne_id[] = explode(',', $plan->project_plan_assignee);
                foreach ($assigne_id as $array_assign_id) {
                    //dump($plan->project_plan_assignee);

                    foreach ($array_assign_id as $val) {
                        //dump((int) $val);
                        //dump((int) $val == Auth::user()->id);

                        if ((int) $val == Auth::user()->id) {
                            array_push($projectPlans, ProjectPlan::where('project_plan_assignee', $plan->project_plan_assignee)->get());

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
        // dd($assignedWorkPackages);
        foreach ($assignedWorkPackages as $key => $value) {
            foreach ($value as $wp) {
                // dump($wp->project_plan_work_package_id);
            $subtaskinfo = WorkPackage::select('work_package_subtask_id')->where('id', $wp->project_plan_work_package_id)->get();
            foreach ($subtaskinfo as $subtaskinof_value) {
                    $subtaskAll[] = SubTask::where('id', $subtaskinof_value->work_package_subtask_id)->get();
                    // dump($subtaskAll);
               
             }
            }

        }
        // die();
    //    dd($subtaskAll);
        $subtaskAll_ = collect($subtaskAll);
        // dd($subtaskAll_->unique());
        $subtaskAll = $subtaskAll_->unique();
        }


        $tdos = Tdo::where('created_by', Auth::user()->id)->get();
        // // $wbs = Wbs::get();
        // $subTasks = SubTask::get();
        // // $workPackages = WorkPackage::get();
        // $subtaskAll=[];
        // $projectPlans = ProjectPlan::where('project_plan_assignee', Auth::user()->id)->get();
        // $projectassign = ProjectPlan::select('project_plan_work_package_id')->where('project_plan_assignee', Auth::user()->id)->distinct()->get();
        //     foreach ($projectassign as $projectassign_value) {
        //         $subtaskinfo = WorkPackage::select('work_package_subtask_id')->where('id', $projectassign_value->project_plan_work_package_id)->get();
        //         foreach ($subtaskinfo as $subtaskinof_value) {
        //             $subtaskAll[] = SubTask::where('id', $subtaskinof_value->work_package_subtask_id)->get();
        //             // foreach ($subtaskinfo as $subtaskinfo_value) {
        //             //     $pminfo = Tdo::select('created_by')->where('id', $subtaskinfo_value->subtask_tdo_id)->get();
        //             //     foreach ($pminfo as $pminfo_value) {
        //             //         $meeting_information[] = Appointment::where('pm_id', $pminfo_value->created_by)->where('isServiced', 0)->orderBy('meeting_dateTime', 'asc')->get();
        //             //     }
        //             // }
        //             //  dump(($subtaskAll));
        //         }
        //     }
           
        // $subtaskAll_ = collect($subtaskAll);
     

        return view('admin.create_meeting', compact('users', 'tdos','subtaskAll','all_subtask'));
    }

    public function history()
    {

        $meetings = Appointment::where('pm_id', Auth::user()->id)
            ->where('isServiced', 1)
            ->orderBy('meeting_dateTime', 'asc')
            ->get();
        return view('admin.meeting_history', compact('meetings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Appointment $meeting)
    {
        $tdos = Tdo::where('created_by', Auth::user()->id)->get();
        $meeting_info = Appointment::where('id', $meeting->id)->get();
        // dd($meeting);
        return view('admin.edit_meeting', compact('meeting_info', 'tdos'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // update meeting(appointment) table
        $meeting_info = Appointment::find($id);
        $meeting_dateTime = $request->meeting_date . ' ' . $request->meeting_time;
        $meeting_info->meeting_dateTime = $meeting_dateTime;
        $meeting_info->save();

        Toastr::success('Meeting Time Updated Successfully :)', 'success');
        return redirect('dashboard'); // return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Dhaka");
        $uniqid = Str::random(9);

        $pm_id = Auth::user()->id;
        $invitation_list = $request->assignee;
        $meeting_dateTime = $request->meeting_date . ' ' . $request->meeting_time;
        $invitation_list_string = implode(', ', $invitation_list);
        $inv_id = explode(',', $invitation_list_string);
        $flag = false;
        foreach ($inv_id as $val) {
            if ($val == Auth::user()->id) {
                $flag = true;
                break;
            }
        };
        if (!$flag) {
            $invitation_list_string = $invitation_list_string . ', ' . Auth::user()->id;
        }
        // dd($request);
        $subtask_id = ($request->subtask_id==0?null:$request->subtask_id);
        $data = Appointment::create([
            'pm_id' => $pm_id,
            'subtask_id' => $subtask_id,
            'participant_id' => $invitation_list_string,
            'agenda' => $request->agenda,
            'room_id' => $uniqid,
            'meeting_dateTime' => $meeting_dateTime,
            'approvedBy' => $pm_id,
            'isCancelled' => 0,
            'isServiced' => 0,
        ]);

        // $workpkg = WorkPackage::where('work_package_subtask_id', $request->subtask_id)->get();
        $subtasktitle = SubTask::select('sub_task_name')->where('id', $request->subtask_id)->first();
        $task_name = ($request->subtask_id==0?'General Meeting':$subtasktitle->sub_task_name); 
      
        $pm_name = User::select('name')->where('id', $pm_id)->first();
        // dd($request->meeting_time);

        foreach ($invitation_list as $value) {
            $user_info = User::select('phone', 'email')->where('id', $value)->first();
            sendSMSToEmployeeUnderProject($user_info, $request->meeting_date,$request->meeting_time, $task_name, $pm_name->name);
            sleep(2);
        }

        // foreach ($workpkg as $workpkg_value) {
        //     $Projectplan = ProjectPlan::select('project_plan_assignee')->where('project_plan_work_package_id', $workpkg_value->id)->distinct()->get();
        //     foreach ($Projectplan as $Projectplan_value) {
        //         $user_info = User::select('phone', 'email')->where('id', $Projectplan_value->project_plan_assignee)->first();
        //         sendSMSToEmployeeUnderProject($user_info, $request->meeting_date, $subtasktitle->sub_task_name, $pm_name->name);
        //     }
        // }

        Toastr::success('Meeting Schedule has been Set Successfully :)', 'success');
        return redirect('dashboard');
    }

    // public function create_project_form_req(Request $request) {
    //     $tdoId = $request->tdoId;
    //     $subTask = SubTask::find($tdoId);
    //     $subTask->where('subtask_tdo_id', $tdoId);
    //     $project = Project::find($subTask->subtask_project_id);
    //     $project->where('id', $subTask->subtask_project_id);
    //     return [$subTask, $project];
    // }

    public function storeTdo($tdo, $tdo_details)
    {
        $data = Tdo::create([
            "title" => $tdo,
            "details" => $tdo_details,
            "created_by" => Auth::user()->id,
        ]);
        return $data->id;
    }

    public function storeSubTask($project_name, $tdo_id)
    {
        $data = SubTask::create([
            "sub_task_name" => $project_name,
            "subtask_tdo_id" => $tdo_id,
        ]);
        return $data->id;
    }

    public function storeworkPackage($work_package, $subTask_id)
    {
        $data = WorkPackage::create([
            "work_package_number" => $work_package,
            "work_package_subtask_id" => $subTask_id,
        ]);
        return $data->id;
    }

    public function storeProjectPlan($request, $project_plan, $workPackage_id)
    {
        $data = ProjectPlan::create([
            "plan_title" => $project_plan,
            "planned_hours" => $request->planned_labor_hrs,
            "planned_ep" => $request->planned_ep,
            "project_plan_assignee" => $request->assignee,
            "project_plan_work_package_id" => $workPackage_id,
            "planned_delivery_date" => $request->planned_due_date,
        ]);
        return $data->id;
    }

    public function storeProjectPlanDetail($request, $projectPlan_id)
    {
        $data = ProjectPlanDetail::create([
            "project_task_details" => $request->task_details,
            "project_plan_project_plan_id" => $projectPlan_id,
        ]);
    }

    /*
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $meeting_info = Appointment::find($id);
        // dd($meeting_info);
        $meeting_info->delete();
        Toastr::success('Meeting Deleted successfully.');
        return redirect('dashboard');
    }

    public function getAssignedEmployees($sub_task_id)
    {
        //Console.log($sub_task_id);
        $workpkg = WorkPackage::where('work_package_subtask_id', $sub_task_id)->get();
        // $subtasktitle = SubTask::select('sub_task_name')->where('id', $sub_task_id)->first();
        // $pm_name = User::select('name')->where('id', $pm_id)->first();
        $assigne_list = [];
        foreach ($workpkg as $workpkg_value) {
            $Projectplan = ProjectPlan::select('project_plan_assignee')->where('project_plan_work_package_id', $workpkg_value->id)->distinct()->get();
            foreach ($Projectplan as $Projectplan_value) {
                $assigne_list = [];
                $assigne_list = explode(',', $Projectplan_value->project_plan_assignee);
                foreach ($assigne_list as $key => $value) {
                    // dump((int)$value);
                    $user_info[] = User::select('id', 'name', 'email')->where('id', (int)$value)->first();
                }
            }
        }
        // dd($user_info);
        // dd($user_info);
        if (isset($user_info)) {
            return $user_info;
        } else {
            return 0;
        }
    }
}
