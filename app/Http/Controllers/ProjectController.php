<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Controllers\Controller;
use App\Project;
use App\ProjectPlan;
use App\ProjectPlanDetail;
use App\SubTask;
use App\Tdo;
use App\User;
use App\Role;
use App\UsersRoles;
use App\Wbs;
use App\WorkPackage;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;

class ProjectController extends Controller
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
        $users = User::with('roles')->whereHas('roles', function ($q) {
            $q->where('slug', '!=', 'super-admin');
        })->get();
       
        if(auth()->user()->hasRole('admin')){
            $tdos = Tdo::get();
        }else{
            $tdos = Tdo::where('created_by', Auth::user()->id)->get();
        }
        $wbs = Wbs::get();
        $subTasks = SubTask::get();
        $workPackages = WorkPackage::get();
        $projectPlans = ProjectPlan::get();
        // dd(Auth::user()->id);
        return view('admin.create_project', compact('users', 'tdos', 'wbs', 'subTasks', 'workPackages', 'projectPlans'));
    }

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
        if ($project_plan != '') {
            $project_assignne_list = $request->assignee;
            $project_assignne_list_string = implode(', ', $project_assignne_list);
            $assignee_id = explode(',', $project_assignne_list_string);
            $flag = false;
            foreach ($assignee_id as $val) {
                if ($val == Auth::user()->id) {
                    $flag = true;
                    break;
                }
            };
            // if (!$flag) {
            //     $project_assignne_list_string = $project_assignne_list_string . ', ' . Auth::user()->id;
            // }
            $data = ProjectPlan::create([
                "plan_title" => $project_plan,
                "planned_hours" => $request->planned_labor_hrs,
                "planned_ep" => $request->planned_ep,
                "project_plan_assignee" => $project_assignne_list_string,
                "project_plan_work_package_id" => $workPackage_id,
                "planned_delivery_date" => $request->planned_due_date,
            ]);
            // dd($data);
        } else {
            // dd($request);
            $project_plan = ProjectPlan::Find($request->project_plan_select);
            $project_plan->where('id', $request->project_plan_select);
            $project_assignne_list_string = implode(', ', $request->assignee);

            $data = ProjectPlan::create([
                "plan_title" => $project_plan->plan_title,
                "planned_hours" => $request->planned_labor_hrs,
                "planned_ep" => $request->planned_ep,
                "project_plan_assignee" => $project_assignne_list_string,
                "project_plan_work_package_id" => $workPackage_id,
                "planned_delivery_date" => $request->planned_due_date,
            ]);
        }
        return $data->id;
    }

    public function storeProjectPlanDetail($request, $projectPlan_id)
    {
        $data = ProjectPlanDetail::create([
            "project_task_details" => $request->task_details,
            "project_plan_project_plan_id" => $projectPlan_id,
        ]);
    }

    public function storeFile($request)
    {
        $files = $request->file('project_doc');
        if (isset($files)) {
            foreach ($files as $file) {
                File::create([
                    'user_id' => Auth::user()->id,
                    'sub_task_id' => $request->project_name_select,
                    'title' => $file->getClientOriginalName(),
                    'description' => '',
                    'path' => $file->storeAs('public/storage', $file->getClientOriginalName()),
                ]);
            }
        }
    }

    public function newSubtaskStoreFile($request, $subTask_id)
    {
        $files = $request->file('project_doc');
        if (isset($files)) {
            foreach ($files as $file) {
                File::create([
                    'user_id' => Auth::user()->id,
                    'sub_task_id' => $subTask_id,
                    'title' => $file->getClientOriginalName(),
                    'description' => '',
                    'path' => $file->storeAs('public/storage', $file->getClientOriginalName()),
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $project_assignne_list = $request->assignee;
        // foreach ($project_assignne_list as $value) {
        //     $assinee_email = User::select('email', 'name')->where('id', $value)->first();
        //     dump($assinee_email->email);
        // }
        // die();

        $tdo = $request->tdo_select == "New" ? $request->tdo_input : $request->tdo_select;
        $project_name = $request->project_name_select == "New" ? $request->project_name_input : $request->project_name_select;
        $work_package = $request->work_package_select == "New" ? $request->work_package_input : $request->work_package_select;
        //remove dot from last index
        $work_package = rtrim($work_package, '.');
        // dd($work_package);

        $project_plan = $request->project_plan_select == "New" ? $request->project_plan_input : $request->project_plan_select;
        $project_title_mail = $request->project_name_select == "New" ? $request->project_name_input :
        SubTask::select('sub_task_name')->where('id',(int)$request->project_name_select)->first()->sub_task_name;
        // dd($request);
        if ($request->tdo_select != "New" && $request->project_name_select != "New" && $request->work_package_select != "New" && $request->project_plan_select != "New") {
            // dd($request->project_name_select);
            $projectPlan_id = $this->storeProjectPlan($request, '', $work_package);
            // echo "block 1";
            // dd($projectPlan_id);
            $this->storeProjectPlanDetail($request, $projectPlan_id);
            $this->storeFile($request);
        } else if ($request->tdo_select == "New") {
            // dd($request, $request->tdo_input);
            $tdo_id = $this->storeTdo($tdo, $request->tdo_details);
            $subTask_id = $this->storeSubTask($project_name, $tdo_id);
            $workPackage_id = $this->storeworkPackage($work_package, $subTask_id);
            $projectPlan_id = $this->storeProjectPlan($request, $request->project_plan_input, $workPackage_id);
            // echo "block 2";
            // dd($projectPlan_id);
            $this->storeProjectPlanDetail($request, $projectPlan_id);
            $this->newSubtaskStoreFile($request, $subTask_id);
        } else if ($request->project_name_select == "New") {
            // dd($request, $request->project_name_input);
            $subTask_id = $this->storeSubTask($project_name, $tdo);
            $workPackage_id = $this->storeworkPackage($work_package, $subTask_id);
            $projectPlan_id = $this->storeProjectPlan($request, $request->project_plan_input, $workPackage_id);
            // echo "block 3";
            // dd($projectPlan_id);
            $this->storeProjectPlanDetail($request, $projectPlan_id);
            $this->newSubtaskStoreFile($request, $subTask_id);
        } else if ($request->work_package_select == "New") {
            // dd($request, $request->work_package_input);
            $workPackage_id = $this->storeworkPackage($work_package, $project_name);
            $projectPlan_id = $this->storeProjectPlan($request, $request->project_plan_input, $workPackage_id);
            // echo "block 4";
            // dd($projectPlan_id);
            $this->storeProjectPlanDetail($request, $projectPlan_id);
            $this->storeFile($request);
        } else if ($request->project_plan_select == "New") {
            // dd($request, $request->project_plan_input);
            $projectPlan_id = $this->storeProjectPlan($request, $request->project_plan_input, $work_package);
            // echo "block 5";
            // dd($projectPlan_id);
            $this->storeProjectPlanDetail($request, $projectPlan_id);
            $this->storeFile($request);
        }

        // email sending
        $usrid = Auth::user()->id;
        $pm_email = User::select('email', 'name')->where('id', $usrid)->first();
        $from_email = $pm_email->email;
        $formname = $pm_email->name;

        // dd($from_email);
        // $pm_email = User::select('email','name')->where('id',$usrid)->first();
        // $assinee_email = User::select('email','name')->where('id',$request->assignee)->first();
        // $toemail = $assinee_email->email;
        // // $fromemail = $pm_email->email;
        // $formname = $pm_email->name;
        // dd($from_email);

        $topmemail = $pm_email->email;

        //multiple assignee mail
        foreach ($project_assignne_list as $value) {
            $assinee_email = User::select('email', 'name')->where('id', $value)->first();
            $toemail = $assinee_email->email;

            $mail_body = '<html>
                <h3>Dear ' . $assinee_email->name . ',</h1>
                <h3>A project named ' . $project_title_mail . ' has been assigned.</h3>
                <h4>Project Task Details : ' . $request->task_details . ' </h4>
                <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                </html>';
            try {
                Mail::send([], [], function ($message) use ($mail_body, $toemail, $from_email, $formname) {
                    $message->from($from_email, $formname);
                    $message->to($toemail);
                    $message->subject('New Project Assigned!');
                    $message->setBody($mail_body,
                        'text/html');
                });
            } catch (\Swift_TransportException $transportExp) {
                //   dd($transportExp->getMessage());
            }

            $frompmemail = $assinee_email->email;
            $mail_pm_body = '<html>
                <h3>Dear ' . $formname . ',</h1>
                <h3>A project named ' . $project_title_mail . ' has been assigned to ' . $assinee_email->name . '.</h3>
                <h4>Project Task Details : ' . $request->task_details . ' </h4>
                <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                </html>';

            try {
                Mail::send([], [], function ($message) use ($mail_pm_body, $topmemail, $frompmemail, $formname) {
                    $message->from($frompmemail, $formname);
                    $message->to($topmemail);
                    $message->subject('New Project Assigned!');
                    $message->setBody($mail_pm_body,
                        'text/html');
                });
            } catch (\Swift_TransportException $transportExp) {
                //   dd($transportExp->getMessage());
            }
        }

        // end email
        //  dd(Mail::send([], [], function ($message) use ($mail_body, $toemail, $from_email, $formname) {
        //     $message->from($from_email, $formname);
        //     //dd($message);
        //     $message->to($toemail);
        //     $message->subject('New Project Assigned!');
        //     $message->setBody($mail_body,
        //         'text/html');

        // }));
        Toastr::success('Project Created Successfully :)', 'success');
        return redirect('dashboard');
    }

    /*
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        //
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request, $id);
        // update tdo table
        
        if (isset($request->tdo_details)) {
            $tdo = Tdo::find($request->tdo_id);
            $tdo->where('id', $request->tdo_id)->update(array(
                'details' => $request->tdo_details,
            ));
        }
        $project_assignne_list = $request->assignee;
        $project_assignne_list_string = implode(', ', $project_assignne_list);
        $assignee_id = explode(',', $project_assignne_list_string);
        $flag = false;
        foreach ($assignee_id as $val) {
            if ($val == Auth::user()->id) {
                $flag = true;
                break;
            }
        };
        // if (!$flag) {
        //     $project_assignne_list_string = $project_assignne_list_string . ', ' . Auth::user()->id;
        // }
        // dd($project_assignne_list_string);
        //find subtask name for mail
        $subtask_name = SubTask::select('sub_task_name')->where('id',(int)$request->subtask_id)->first()->sub_task_name;
       
        // update plan table
        $project_plan = ProjectPlan::find($request->project_plan_id);
        $project_plan->where('id', $request->project_plan_id)->update(array(
            "planned_ep" => $request->planned_ep,
            "planned_hours" => $request->planned_labor_hrs,
            "planned_delivery_date" => $request->planned_due_date,
            "project_plan_assignee" => $project_assignne_list_string,
        ));
        // update plan details table
        $project_plan_details = ProjectPlanDetail::find($request->project_plan_details_id);
        $project_plan_details->where('id', $request->project_plan_details_id)->update(array(
            'project_task_details' => $request->task_details,
        ));
        // update file table
        if ($request->delete_pro_doc != null) {
            $deleteFileIdArray = explode(",", $request->delete_pro_doc);
            if (count($deleteFileIdArray) > 0) {
                for ($i = 0; $i < count($deleteFileIdArray); $i++) {
                    // dump($deleteFileIdArray[$i]);
                    $del = File::find($deleteFileIdArray[$i]);
                    Storage::delete($del->path);
                    $del->delete();
                }
            }
        }
        $files = $request->file('project_doc');
        // dd($files);
        if ($files != null) {
            foreach ($files as $file) {
                File::create([
                    'user_id' => Auth::user()->id,
                    'sub_task_id' => $request->subtask_id,
                    'title' => $file->getClientOriginalName(),
                    'description' => '',
                    'path' => $file->storeAs('public/storage', $file->getClientOriginalName()),
                ]);
            }
        }

        // email sending
        $usrid = Auth::user()->id;
        $pm_email = User::select('email', 'name')->where('id', $usrid)->first();
        $assinee_email = User::select('email', 'name')->where('id', $request->assignee)->first();
        $toemail = $assinee_email->email;
        $from_email = $pm_email->email;
        $formname = $pm_email->name;

        //$test_email = "hafij.sabuj@gmail.com";

        // dd($from_email);$subtask_name,$project_plan->plan_title

        $mail_body = '<html>
                 <h3>Dear ' . $assinee_email->name . ',</h1>
                 <h3>Project plan updated.</h3>
                 <h4>Project Name: '. $subtask_name .' .</h4>
                 <h5>Task Title: '. $project_plan->plan_title .' .</h5>
                 <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                 </html>';

        // $pm_email = User::select('email','name')->where('id',$usrid)->first();
        // $assinee_email = User::select('email','name')->where('id',$request->assignee)->first();
        // $toemail = $assinee_email->email;
        // // $fromemail = $pm_email->email;
        // $formname = $pm_email->name;
        // dd($from_email);

        try {
            Mail::send([], [], function ($message) use ($mail_body, $toemail, $from_email, $formname) {
                $message->from($from_email, $formname);
                //dd($message);
                $message->to($toemail);
                $message->subject('Project Assigned!');
                $message->setBody($mail_body,
                    'text/html');
            });
        } catch (\Swift_TransportException $transportExp) {
            //   dd($transportExp->getMessage());
        }

        $topmemail = $pm_email->email;
        $frompmemail = $assinee_email->email;

        $mail_pm_body = '<html>
                 <h3>Dear ' . $formname . ',</h1>
                 <h3>Project plan updated for ' . $assinee_email->name . '.</h3>
                 <h4>Project Name: '. $subtask_name .' .</h4>
                 <h5>Task Title: '. $project_plan->plan_title .' .</h5>
                 <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                 </html>';

        try {
            Mail::send([], [], function ($message) use ($mail_pm_body, $topmemail, $frompmemail, $formname) {
                $message->from($frompmemail, $formname);
                //dd($message);
                $message->to($topmemail);
                $message->subject('New Project Assigned!');
                $message->setBody($mail_pm_body,
                    'text/html');
            });
        } catch (\Swift_TransportException $transportExp) {
            //   dd($transportExp->getMessage());
        }

        Toastr::success('Project plan updated successfully :)', 'success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        //
    }

    // public function multipleAssigeeStore($request, $subTask_id){
    //     $assignees = $request->assignee('assignee');
    //     //dd($assignees);
    //     if(isset($assignees)){
    //         foreach ($assignees as $assignee) {
    //             ProjectPlan::create([
    //                 "plan_title" => $project_plan,
    //                 "planned_hours" => $request->planned_labor_hrs,
    //                 "planned_ep" => $request->planned_ep,
    //                 "project_plan_assignee" => $assignee->assignee,
    //                 "project_plan_work_package_id" => $workPackage_id,
    //                 "planned_delivery_date" => $request->planned_due_date,
    //             ]);
    //         }
    //     }
    // }
}
