<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\ProjectPlan;
use App\ProjectPlanDetail;
use App\SubTask;
use App\Tdo;
use App\User;
use App\Wbs;
use App\WbsHourCalculation;
use App\WbsMaster;
use App\WorkPackage;
use App\TimeCard;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;
use Illuminate\Http\Request;
use Mail;

class WbsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wbs = Wbs::all();
        return view('admin.wbs', compact('wbs'));
    }

    public function create()
    {
        $all_WP = WorkPackage::all();
        $allProjects = ProjectPlan::all();
        $pmTdo = [];
        $pmSubtask=[];
        $pmWP =[];
        $pmProjectAssignee = [];
        $fetch_assignee=[];
        $count = 0;
        
        $myProjectPlans = [];
        $myProjects_wp=[];
        $myTeam = [];
       
        $subTaskNames = [];
        $projectPlanDetails = [];
        
        if (Auth::user()->hasRole('user')) {
            foreach ($allProjects as $projectPlan) {
                $emp_list=[];
                $emp_list=explode(',', $projectPlan->project_plan_assignee);
                foreach ($emp_list as $item) {
                    $item = (int) $item;
                    if ($item == Auth::user()->id) {
                        $myProjects_wp[] =  $projectPlan->project_plan_work_package_id;
                    }
                }
            }
            
            // dd($myProjectPlans);
            //12, 21, 13, 14, 8, 16, 28
            foreach ($myProjects_wp as $key => $value) {
                    foreach ($allProjects as $project) {
                        if((int)$project->project_plan_work_package_id == (int)$value){
                            $myTeam[] =  $project->project_plan_assignee;
                            // $myProjectPlans[] = $project;
                            array_push($myProjectPlans,$project);

                        }
                    }  
                }
                // $myTeam = array_unique($myTeam);
                // dd(array_unique($myProjectPlans));
            $filterTeam=[];
            $searchForValue = ',';// split for multi assignee
            foreach ($myTeam as $key => $team) {
                $assigned_emp=[];
                $stringValue = $team;
            //remove white space
            if( strpos($stringValue, $searchForValue) !== false ) {
                $assigned_emp = explode(',',  preg_replace("/\s+/", "", $team));
                    foreach ($assigned_emp as $em) {
                    array_push($filterTeam,$em);
                    }
                }else{
                    array_push($filterTeam,$team);
                }
            }
            // dump($filterTeam);
            $fetch_assignee = array_unique($filterTeam);

            $my_subTask_with_wp=[];
            $myProjects_wp= array_unique($myProjects_wp) ;
            // $myProjectPlans = array_unique($myProjectPlans) ;
            foreach ($myProjects_wp as $myWp) {
                foreach ($all_WP as $wp) {
                   if($wp->id == $myWp){
                        $my_subTask_with_wp[]=$wp;
                   }
                }
            }
            // dd($fetch_assignee);
            $all_subtask = SubTask::all();
            $my_subTasks=[];
            foreach ($my_subTask_with_wp as $myWp) {
                foreach ($all_subtask as $val) {
                    if($val->id == $myWp->work_package_subtask_id){
                        $my_subTasks[]=$val;
                    }
                }
            }
        $projectPlans = collect($myProjectPlans);
        //$projectPlanDetails = collect($myProjectPlans);
        foreach ($projectPlans as $key => $value) {
                // dump($value);
                $projectPlanDetails[$key] = $projectPlans[$key]->projectPlanDetails->unique('project_plan_project_plan_id')[0];
            }
            // dd($projectPlanDetails);

            // dd(array_unique($projectPlanDetails));
        $projectPlanDetails = collect($projectPlanDetails);        
        }
        if(Auth::user()->hasRole('power-user')){
            $pmTdo = Tdo::where('created_by', Auth::user()->id)->select('id')->get();
            foreach ($pmTdo as $key => $value) {
                foreach ($pmTdo[$key]->subTask as $key2 => $value2) {
                    foreach ($pmTdo[$key]->subTask[$key2]->workPackage as $key3 => $value3) {
                        // dump($value3->id);
                        $pmWP[] = $value3->id;
                        foreach ($pmTdo[$key]->subTask[$key2]->workPackage[$key3]->projectPlan as $key4 => $value4) {
                            $pmProjectAssignee[$count] = $pmTdo[$key]->subTask[$key2]->workPackage[$key3]->projectPlan[$key4]->project_plan_assignee;
                            $count++;
                        }
                    }
                }
            }
            // dd($pmWP);

            // die();


            // //Tdo::all();
            // //Tdo::where('created_by', Auth::user()->id)->get();
            // foreach ($assignedemployee as $key => $value) {
            //     foreach ($assignedemployee[$key]->subTask as $key2 => $value2) {
            //         foreach ($assignedemployee[$key]->subTask[$key2]->workPackage as $key3 => $value3) {
            //             foreach ($assignedemployee[$key]->subTask[$key2]->workPackage[$key3]->projectPlan as $key4 => $value4) {
            //                 $assignedemployeeArray[$count] = $assignedemployee[$key]->subTask[$key2]->workPackage[$key3]->projectPlan[$key4]->project_plan_assignee;
            //                 $count++;
            //             }
            //         }
            //     }
            // }

            $assignedWorkPackages = [];
            // $assignedWorkPackages[] = ProjectPlan::whereIn('project_plan_work_package_id', $pmWP)
            //     ->select('project_plan_work_package_id')
            //     ->distinct()->get();
            $assignedWorkPackages = ProjectPlan::whereIn('project_plan_work_package_id', $pmWP)->get();
            foreach ($assignedWorkPackages as $key => $value) {
                $subTaskNames[$key] = $value->workPackage->subTask;
            }
            $subTaskNames = collect($subTaskNames)->unique('sub_task_name');
            // get all unique workPackage from work_packages table
            $workPackageNumbers = [];
            foreach ($assignedWorkPackages as $key => $value) {
                $workPackageNumbers[$key] = $value->workPackage;
                //$assignedWorkPackages[$key]->workPackage;
            }
            $workPackageNumbers = collect($workPackageNumbers);
            $projectPlans = collect($assignedWorkPackages);
            //ProjectPlan::whereIn('project_plan_work_package_id', $pmWP)->get();
            foreach ($projectPlans as $key => $value) {
                // dump($value);
                $projectPlanDetails[$key] = $projectPlans[$key]->projectPlanDetails->unique('project_plan_project_plan_id')[0];
            }
            
            $projectPlanDetails = collect($projectPlanDetails);
            $pm_plan_ids=[];
            foreach ($projectPlanDetails as $key => $value) {
                $pm_plan_ids[]=$value->id;
            }
            $all_wbs_master_PM = WbsMaster::whereIn('wbs_master_project_plan_details_id',$pm_plan_ids)->get();
            $all_wbs_master_wbsId =[];
            foreach ($all_wbs_master_PM as $key => $value) {
                $all_wbs_master_wbsId[]=$value->wbs_master_wbs_id;
            }

            $all_wbs_PM = Wbs::whereIn('id',$all_wbs_master_wbsId)->get();
            // dd($all_wbs_PM[1]);
        }

        if (Auth::user()->hasRole('admin')) {
            $allTdo = Tdo::get();
            // foreach ($allTdo as $key => $value) {
            //     foreach ($allTdo[$key]->subTask as $key2 => $value2) {
            //         foreach ($allTdo[$key]->subTask[$key2]->workPackage as $key3 => $value3) {
            //             // dump($value3->id);
            //            // $pmWP[] = $value3->id;
            //             foreach ($allTdo[$key]->subTask[$key2]->workPackage[$key3]->projectPlan as $key4 => $value4) {
            //                 $pmProjectAssignee[$count] = $allTdo[$key]->subTask[$key2]->workPackage[$key3]->projectPlan[$key4]->project_plan_assignee;
            //                 $count++;
            //             }
            //         }
            //     }
            // }
            
            $allWp = WorkPackage::select('id')->get();
            // $assignedWorkPackages = [];
            // $assignedWorkPackages = ProjectPlan::get();//whereIn('project_plan_work_package_id', $allWp)->

            // foreach ($assignedWorkPackages as $key => $value) {
            //     $subTaskNames[$key] = $value->workPackage->subTask;
            // }

            $subTaskNames = SubTask::get()->unique('sub_task_name');
            //collect($subTaskNames)->unique('sub_task_name');

            // get all unique workPackage from work_packages table
            $workPackageNumbers = WorkPackage::get();
            // foreach ($assignedWorkPackages as $key => $value) {
            //     $workPackageNumbers[$key] = $value->workPackage;
            //     //$assignedWorkPackages[$key]->workPackage;
            // }

            // $workPackageNumbers = collect($workPackageNumbers);
            $projectPlans = ProjectPlan::get();
            //ProjectPlan::whereIn('project_plan_work_package_id', $pmWP)->get();
            // foreach ($projectPlans as $key => $value) {
            //     // dump($value);
            //     $projectPlanDetails[$key] = $projectPlans[$key]->projectPlanDetails->unique('project_plan_project_plan_id')[0];
            // }

            // dd($projectPlans);


            $projectPlanDetails = ProjectPlanDetail::get();
            //collect($projectPlanDetails);
            $pm_plan_ids = [];
            foreach ($projectPlanDetails as $key => $value) {
                $pm_plan_ids[] = $value->id;
            }
            $all_wbs_master_PM = WbsMaster::whereIn('wbs_master_project_plan_details_id', $pm_plan_ids)->get();
            $all_wbs_master_wbsId = [];
            foreach ($all_wbs_master_PM as $key => $value) {
                $all_wbs_master_wbsId[] = $value->wbs_master_wbs_id;
            }

            $all_wbs_PM = Wbs::whereIn('id', $all_wbs_master_wbsId)->get();
            // dd($all_wbs_PM[1]);
        }
        
        // dump($assignedemployeeArray);
        // get assigned project_plan_work_package_id from project_plans table
        // if (Auth::user()->hasRole('power-user')) {
        // foreach ($assignedemployeeArray as $key => $value) {
        //     dump($value);
        //     $assignedWorkPackages[] = ProjectPlan::where('project_plan_assignee', $value)
        //         ->select('project_plan_work_package_id')
        //         ->distinct()->get();
        //     //    dump($assignedWorkPackages);
        // }

        // // ------------------
        // $assignedWorkPackages = [];
        // $assignedWorkPackages[] = ProjectPlan::whereIn('project_plan_assignee', $assignedemployeeArray)
        //         ->select('project_plan_work_package_id')
        //         ->distinct()->get();
        // // ------------------
        // $assignedWorkPackages = ProjectPlan::whereIn('project_plan_assignee', array_unique($assignedemployeeArray))
        //     ->select('project_plan_work_package_id')
        //     ->distinct()
        //     ->get();
        // }
        // if (Auth::user()->hasRole('user')) {
        //  foreach ($assignedemployeeArray as $key => $value) {
        //     $assignedWorkPackages = ProjectPlan::where('project_plan_assignee', Auth::user()->id)
        //         ->select('project_plan_work_package_id')
        //         ->distinct()
        //         ->get();
        //         }
        //     dd($assignedWorkPackages);
        // }
        // dd($assignedWorkPackages);
        // get all unique subtask from sub_tasks table
        // $array_assi = $assignedWorkPackages->all();
        //  dump(count($assignedWorkPackages[0]));
        // //----------------
        // $subTaskNames = [];
        // foreach ($assignedWorkPackages[0] as $key => $value) {
        //     // dump($value->project_plan_work_package_id);
        //     $subTaskNames[$key] = $value->workPackage->subTask;
        // }
        
        // $subTaskNames = collect($subTaskNames)->unique('sub_task_name');
        // // get all unique workPackage from work_packages table
        // $workPackageNumbers = [];
        // foreach ($assignedWorkPackages[0] as $key => $value) {
        //     $workPackageNumbers[$key] = $value->workPackage;
        //     //$assignedWorkPackages[$key]->workPackage;
        // }
        // $workPackageNumbers = collect($workPackageNumbers);
        // $projectPlans = ProjectPlan::whereIn('project_plan_assignee', $assignedemployeeArray)->get();
        // //----------------
        //if (Auth::user()->hasRole('power-user')) {
        // dd($assignedemployeeArray);
        
        //}
        // dd($projectPlans);

        // $team_members = [];
        // if (Auth::user()->hasRole('user')) {
        //     // where('project_plan_assignee', Auth::user()->id)->
        //     // $projectPlans = ProjectPlan::get();
        //     $emp_list=[];
        //     $fetch_assignee=[];
        //     foreach ($projectPlans as $projectPlan) {
        //         $emp_list=[];
        //         $emp_list=explode(',', $projectPlan->project_plan_assignee);
        //         foreach ($emp_list as $item) {
        //             $item = (int) $item;
        //             if ($item == Auth::user()->id) {
        //                 $team_members[] =  $projectPlan->project_plan_assignee;
        //                  foreach ($team_members as $member_per_project) {
        //                     foreach ($emp_list as $value) {
        //                         $fetch_assignee[] = (int) $value;
        //                     }
        //                  }
        //             }

        //         }
        //     }
        //     $fetch_assignee = array_unique($fetch_assignee);
        //     // dd($fetch_assignee);
        //     //dump($projectPlan->project_plan_assignee);
        //     //die();
        //     // $members=[];
        //     // $fetch_assignee=[];
        //     // foreach ($team_members as $member_per_project) {
        //     //    $members = explode(',', $member_per_project);
        //     //     foreach ($members as $value) {
        //     //         $fetch_assignee[] = (int) $value;
        //     //     }
        //     // }
        //     // $fetch_assignee = array_unique($fetch_assignee);
        // }
        // get all details from project_plan_details table
        
        // get all user
        $users = User::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('slug', '!=', 'super-admin');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        // $projectPlans = $allProjects;
        $workPackageNumbers = $all_WP;
        $all_subtask = SubTask::all();
        // dd($projectPlanDetails);

        // dd(collect($myProjectPlans));
        // dd(gettype($projectPlans),gettype(collect($myProjectPlans)));
        return view('admin.create_wbs', compact('subTaskNames', 'workPackageNumbers', 'projectPlans', 'projectPlanDetails', 'users','fetch_assignee','all_subtask'));
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $data = Wbs::create([
            'wbs_task_details' => $request->wbs_task_details,
            'task_start_date' => $request->task_start_date,
            'task_end_date'=> $request->task_end_date
        ]);
        $data2 = WbsMaster::create([
            'wbs_master_wbs_id' => $data->id,
            'reporter' => Auth::user()->id,
            'wbs_master_assignee_id' => $request->project_assignee_id,
            'wbs_master_project_plan_details_id' => $request->project_plan_details,
            'created_by' => Auth::user()->id,
        ]);
        if (Auth::user()->hasRole('power-user')) {
            // email sending
            $usrid = Auth::user()->id;
            $pm_email = User::select('email', 'name')->where('id', $usrid)->first();
            $assinee_email = User::select('email', 'name')->where('id', $request->project_assignee_id)->first();
            $toemail = $assinee_email->email;
            $from_email = $pm_email->email;
            $formname = $pm_email->name;
            //$test_email = "hafij.sabuj@gmail.com";
            $mail_body = '<html>
                <h3>Dear ' . $assinee_email->name . ',</h1>
                <h3>A Task has been assigned.</h3>
                <h4>Task details: '.$request->wbs_task_details.'</h4>
                <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                </html>';
            try {
                Mail::send([], [], function ($message) use ($mail_body, $toemail, $from_email, $formname) {
                    $message->from($from_email, $formname);
                    //dd($message->from($from_email, $formname));
                    $message->to($toemail);
                    $message->subject('New Task Assigned!');
                    $message->setBody($mail_body,
                        'text/html');
                });
            } catch (\Swift_TransportException $transportExp) {
                //   dd($transportExp->getMessage());
            }

            $assineename = $assinee_email->name;
            $mail_pm_body = '<html>
                <h3>Dear ' . $formname . ',</h1>
                <h3>A Task has been assigned for ' . $assineename . ' </h3>
                <h4>Task details: '.$request->wbs_task_details.'</h4>
                <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                </html>';
            $topmemail = $from_email;
            $fromassinee = $toemail;
            try {
                Mail::send([], [], function ($message) use ($mail_pm_body, $topmemail, $fromassinee, $assineename) {
                    $message->from($fromassinee, $assineename);
                    $message->to($topmemail);
                    $message->subject('New Task Assigned!');
                    $message->setBody($mail_pm_body,
                        'text/html');
                });
            } catch (\Swift_TransportException $transportExp) {
                //   dd($transportExp->getMessage());
            }
        }
        if (Auth::user()->hasRole('user')) {
            // email sending
            $usrid = Auth::user()->id;
            $assinee_email = User::select('email', 'name')->where('id', $usrid)->first();
            // dd($pm_email);
            $sutaskid = $request->project_name_select;
            $tdos_id = SubTask::select('subtask_tdo_id')->where('id', $sutaskid)->first();
            $pm_id = Tdo::select('created_by')->where('id', $tdos_id->subtask_tdo_id)->first();
            $pm_email = User::select('email', 'name')->where('id', $pm_id->created_by)->first();
            $from_email = $assinee_email->email;
            $toemail = $pm_email->email;
            $formname = $assinee_email->name;
            //$test_email = "hafij.sabuj@gmail.com";
            $mail_body = '<html>
                <h3>Dear ' . $pm_email->name . ',</h1>
                <h3>A Task has been assigned</h3>
                <h4>Task details: '.$request->wbs_task_details.'</h4>
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
                    //dd($message->from($from_email, $formname));
                    $message->to($toemail);
                    $message->subject('New Task Assigned!');
                    $message->setBody($mail_body,
                        'text/html');
                });
            } catch (\Swift_TransportException $transportExp) {
                //   dd($transportExp->getMessage());
            }
            // end email
        }
        Toastr::success('Wbs task Created Successfully :)', 'success');
        return redirect('dashboard');
    }

    /*
     * Display the specified resource.
     */
    public function show($id)
    {
        // dd("show wbs", $id);
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
    public function update(Request $request)
    {
        // dd($request);
        $wbs = Wbs::find($request->wbs_id);
        $tot_status = $request->status;
        $tot_actual_hour = $wbs->actual_hours_worked + $request->actual_hours_today;
        $planhour = $request->planned_hours;
        if ($tot_actual_hour == $planhour && $tot_status != 100) {
            $usrid = Auth::user()->id;
            $assinee_email = User::select('email', 'name')->where('id', $usrid)->first();
            $sutaskid = $request->subtask_id;
            $tdos_id = SubTask::select('subtask_tdo_id')->where('id', $sutaskid)->first();
            $pm_id = Tdo::select('created_by')->where('id', $tdos_id->subtask_tdo_id)->first();
            $pm_email = User::select('email', 'name')->where('id', $pm_id->created_by)->first();
            $from_email = $assinee_email->email;
            $toemail = $pm_email->email;
            $formname = $assinee_email->name;
            //$test_email = "hafij.sabuj@gmail.com";
            $mail_body = '<html>
                <h3>Dear ' . $pm_email->name . ',</h1>
                <h3>No hours left for this task. You may need to plan more hours for this task.</h3>
                <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                </html>';
            try {
                Mail::send([], [], function ($message) use ($mail_body, $toemail, $from_email, $formname) {
                    $message->from($from_email, $formname);
                    //dd($message->from($from_email, $formname));
                    $message->to($toemail);
                    $message->subject('WBS Added to the New Assignee!');
                    $message->setBody($mail_body,
                        'text/html');
                });
            } catch (\Swift_TransportException $transportExp) {
                //   dd($transportExp->getMessage());
            }

        }
        if ($request->task_start_date == '') {
            $task_start_date = new DateTime();
        } else {
            $task_start_date = $request->task_start_date;
        }
        date_default_timezone_set("Asia/Dhaka");
        // if(wbs_user_id != project_assignee_id)
        $wbsMaster = WbsMaster::where('wbs_master_assignee_id', $request->project_assignee_id)
            ->where('wbs_master_wbs_id', $request->wbs_id)
            ->get();
        // update WBS for existing WBS assignee
        if (count($wbsMaster) > 0) {
            $wbs->where('id', $request->wbs_id)->update(array(
                'wbs_task_details' => $request->wbs_task_details,
                'actual_hours_worked' => $wbs->actual_hours_worked + $request->actual_hours_today,
                'actual_hours_today' => $request->actual_hours_today,
                'task_start_date' => $task_start_date,
                'task_end_date' => $request->task_end_date,
                'status' => $request->status,
                'product_deliverable' => $request->product_deliverable,
                'task_comments' => $request->task_comments,
            ));
            $wbs->touch();
            $projectPlan = ProjectPlan::find($request->project_plan);
            $projectPlan->where('id', $request->project_plan)->update(array(
                'planned_hours' => $request->planned_hours,
            ));

            $workPkg = WorkPackage::find($projectPlan->project_plan_work_package_id);
            $subTask = SubTask::find($workPkg->work_package_subtask_id);
            // dd($wbsMaster[0]->id);

            $timedata = TimeCard::create([
            'time_card_wbs_id' => $request->wbs_id,
            'time_card_wbs_master_id' => $wbsMaster[0]->id,
            'time_card_wbs_assignee_id'=> $request->project_assignee_id,
            'time_card_project_plan_id'=> $projectPlan->id,
            'time_card_work_package_id'=> $workPkg->id,
            'time_card_subtask_id'=> $subTask->id,
            'time_card_wbs_task_details'=> $request->wbs_task_details,
            'time_card_plan_title'=> $projectPlan->plan_title,
            'time_card_actual_hours_today'=> $request->actual_hours_today,
            'task_status'=>$request->status,
            'wbs_update_date'=> $wbs->updated_at
        ]);
        // dd($wbs->updated_at);

            if (Auth::user()->hasRole('power-user')) {
                // email sending
                $usrid = Auth::user()->id;
                $pm_email = User::select('email', 'name')->where('id', $usrid)->first();
                // dd($pm_email);
                $assinee_email = User::select('email', 'name')->where('id', $request->project_assignee_id)->first();
                $toemail = $assinee_email->email;
                $from_email = $pm_email->email;
                $formname = $pm_email->name;
                //$test_email = "hafij.sabuj@gmail.com";
                // dd($from_email);
                $mail_body = '<html>
                    <h3>Dear ' . $assinee_email->name . ',</h1>
                    <h3>A Task has been updated.</h3>
                    <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                    </html>';
                try {
                    Mail::send([], [], function ($message) use ($mail_body, $toemail, $from_email, $formname) {
                        $message->from($from_email, $formname);
                        //dd($message->from($from_email, $formname));
                        $message->to($toemail);
                        $message->subject('A Task Updated!');
                        $message->setBody($mail_body,
                            'text/html');
                    });
                } catch (\Swift_TransportException $transportExp) {
                    //   dd($transportExp->getMessage());
                }

                $assineename = $assinee_email->name;
                $mail_pm_body = '<html>
                    <h3>Dear ' . $formname . ',</h1>
                    <h3>A Task has been updated for ' . $assineename . ' </h3>
                    <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                    </html>';
                $topmemail = $from_email;
                $fromassinee = $toemail;
                try {
                    Mail::send([], [], function ($message) use ($mail_pm_body, $topmemail, $fromassinee, $assineename) {
                        $message->from($fromassinee, $assineename);
                        //dd($message->from($from_email, $formname));
                        $message->to($topmemail);
                        $message->subject('A Task Updated!');
                        $message->setBody($mail_pm_body,
                            'text/html');
                    });
                } catch (\Swift_TransportException $transportExp) {
                    //   dd($transportExp->getMessage());
                }
            }
            if (Auth::user()->hasRole('user')) {
                // email sending
                $usrid = Auth::user()->id;
                $assinee_email = User::select('email', 'name')->where('id', $usrid)->first();
                // dd($pm_email);
                $sutaskid = $request->subtask_id;
                $tdos_id = SubTask::select('subtask_tdo_id')->where('id', $sutaskid)->first();
                $pm_id = Tdo::select('created_by')->where('id', $tdos_id->subtask_tdo_id)->first();
                $pm_email = User::select('email', 'name')->where('id', $pm_id->created_by)->first();
                $from_email = $assinee_email->email;
                $toemail = $pm_email->email;
                $formname = $assinee_email->name;
                //$test_email = "hafij.sabuj@gmail.com";
                $mail_body = '<html>
                    <h3>Dear ' . $pm_email->name . ',</h1>
                    <h3>A Task has been updated.</h3>
                    <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                    </html>';
                try {
                    Mail::send([], [], function ($message) use ($mail_body, $toemail, $from_email, $formname) {
                        $message->from($from_email, $formname);
                        //dd($message->from($from_email, $formname));
                        $message->to($toemail);
                        $message->subject('A Task Updated!');
                        $message->setBody($mail_body,
                            'text/html');
                    });
                } catch (\Swift_TransportException $transportExp) {
                    //   dd($transportExp->getMessage());
                }
            }
            Toastr::success('WBS updated successfully :)', 'success');
            return redirect()->back();
            // assigne WBS to new user and if this is a new user for this project
        } else {
            $projectPlan = ProjectPlan::find($request->project_plan);
            $projectPlan->where('id', $request->project_plan)
                ->where('project_plan_assignee', $request->project_assignee_id)
                ->get();
            // if once a user assigned to a new project
            if (empty($projectPlan) == true) {
                $data = ProjectPlan::create([
                    "plan_title" => $projectPlan->plan_title,
                    "planned_hours" => $projectPlan->planned_hours,
                    "planned_ep" => $projectPlan->planned_ep,
                    "project_plan_assignee" => $request->project_assignee_id,
                    "project_plan_work_package_id" => $projectPlan->project_plan_work_package_id,
                    "planned_delivery_date" => $projectPlan->planned_delivery_date,
                ]);
                $data2 = ProjectPlanDetail::create([
                    "project_task_details" => $request->project_plan_details,
                    "project_plan_project_plan_id" => $data->id,
                ]);
                $data4 = WbsMaster::create([
                    'wbs_master_wbs_id' => $request->wbs_id,
                    'reporter' => Auth::user()->id,
                    'wbs_master_assignee_id' => $request->project_assignee_id,
                    'wbs_master_project_plan_details_id' => $request->project_plan_details_id,
                    'created_by' => Auth::user()->id,
                ]);
            } else {
                $data5 = WbsMaster::create([
                    'wbs_master_wbs_id' => $request->wbs_id,
                    'reporter' => Auth::user()->id,
                    'wbs_master_assignee_id' => $request->project_assignee_id,
                    'wbs_master_project_plan_details_id' => $request->project_plan_details_id,
                    'created_by' => Auth::user()->id,
                ]);
            }
            if (Auth::user()->hasRole('power-user')) {
                // email sending
                $usrid = Auth::user()->id;
                $pm_email = User::select('email', 'name')->where('id', $usrid)->first();
                // dd($pm_email);
                $assinee_email = User::select('email', 'name')->where('id', $request->project_assignee_id)->first();
                $toemail = $assinee_email->email;
                $from_email = $pm_email->email;
                $formname = $pm_email->name;
                //$test_email = "hafij.sabuj@gmail.com";
                // dd($from_email);
                $mail_body = '<html>
                    <h3>Dear ' . $assinee_email->name . ',</h1>
                    <h3>WBS Added to the New Assignee</h3>
                    <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                    </html>';
                try {
                    Mail::send([], [], function ($message) use ($mail_body, $toemail, $from_email, $formname) {
                        $message->from($from_email, $formname);
                        //dd($message->from($from_email, $formname));
                        $message->to($toemail);
                        $message->subject('WBS Added to the New Assignee!');
                        $message->setBody($mail_body,
                            'text/html');
                    });
                } catch (\Swift_TransportException $transportExp) {
                    //   dd($transportExp->getMessage());
                }
                $assineename = $assinee_email->name;
                $mail_pm_body = '<html>
                    <h3>Dear ' . $formname . ',</h1>
                    <h3>WBS Added to the New Assignee for ' . $assineename . ' </h3>
                    <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                    </html>';
                $topmemail = $from_email;
                $fromassinee = $toemail;
                try {
                    Mail::send([], [], function ($message) use ($mail_pm_body, $topmemail, $fromassinee, $assineename) {
                        $message->from($fromassinee, $assineename);
                        //dd($message->from($from_email, $formname));
                        $message->to($topmemail);
                        $message->subject('WBS Added to the New Assignee!');
                        $message->setBody($mail_pm_body,
                            'text/html');
                    });
                } catch (\Swift_TransportException $transportExp) {
                    //   dd($transportExp->getMessage());
                }
            }
            if (Auth::user()->hasRole('user')) {
                // email sending
                $usrid = Auth::user()->id;
                $assinee_email = User::select('email', 'name')->where('id', $usrid)->first();
                // dd($pm_email);
                $sutaskid = $request->subtask_id;
                $tdos_id = SubTask::select('subtask_tdo_id')->where('id', $sutaskid)->first();
                $pm_id = Tdo::select('created_by')->where('id', $tdos_id->subtask_tdo_id)->first();
                $pm_email = User::select('email', 'name')->where('id', $pm_id->created_by)->first();
                $from_email = $assinee_email->email;
                $toemail = $pm_email->email;
                $formname = $assinee_email->name;
                //$test_email = "hafij.sabuj@gmail.com";
                $mail_body = '<html>
                    <h3>Dear ' . $pm_email->name . ',</h1>
                    <h3>WBS Added to the New Assignee</h3>
                    <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                    </html>';
                try {
                    Mail::send([], [], function ($message) use ($mail_body, $toemail, $from_email, $formname) {
                        $message->from($from_email, $formname);
                        //dd($message->from($from_email, $formname));
                        $message->to($toemail);
                        $message->subject('WBS Added to the New Assignee!');
                        $message->setBody($mail_body,
                            'text/html');
                    });
                } catch (\Swift_TransportException $transportExp) {
                    //   dd($transportExp->getMessage());
                }
                $new_assinee_email = User::select('email', 'name')->where('id', $request->project_assignee_id)->first();
                $mail_new_assignee_body = '<html>
                    <h3>Dear ' . $new_assinee_email->name . ',</h1>
                    <h3>A new WBS has been added</h3>
                    <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to see details!</h3>
                    </html>';
                $newassiemail = $new_assinee_email->email;
                try {
                    Mail::send([], [], function ($message) use ($mail_new_assignee_body, $newassiemail, $from_email, $formname) {
                        $message->from($from_email, $formname);
                        //dd($message->from($from_email, $formname));
                        $message->to($newassiemail);
                        $message->subject('WBS Added to the New Assignee!');
                        $message->setBody($mail_new_assignee_body,
                            'text/html');
                    });
                } catch (\Swift_TransportException $transportExp) {
                    //   dd($transportExp->getMessage());
                }

                // end email
            }
            Toastr::success('WBS added to the new assignee successfully :)', 'success');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        //
    }

    public function wbs_add_hour($id)
    {
        $wbs = Wbs::find($id);
        return view('admin.create_wbs_hour', compact('wbs'));
    }

    public function wbs_store_hour(Request $request)
    {
        Wbs::whereId($request->wbs_id)->update([
            'task_comments' => $request->task_comments,
        ]);
        WbsHourCalculation::where('wbs_id', $request->wbs_id)->update([
            'wbs_date' => $request->wbs_date,
            'wbs_hour' => $request->wbs_hour,
        ]);
        Toastr::success('Effort Hour Updated Successfully :)', 'success');
        return redirect('wbs');
    }

    
    public function getEmpTimecard(Request $request)
    {
        //dd($request);
        $timeCardData = TimeCard::where('time_card_wbs_assignee_id',$request->empId)
        ->whereBetween('wbs_update_date',[$request->wbs_start_date, $request->wbs_end_date])
        ->orderBy('wbs_update_date','ASC')
        ->get();
        $timeDetails = [];
        // $totalHour = 0;
        foreach ($timeCardData as $key => $value) {
            $subtask = SubTask::find($value->time_card_subtask_id);
            // $wbs_status = Wbs::find($value->time_card_wbs_id);
            // dd($status);
            // $totalHour = $totalHour+(int)$value->time_card_actual_hours_today;
            $timeDetails [] = array_merge($value->toarray(),
                                array('time_card_sub_task_name'=>$subtask->sub_task_name)) ;
            // dd($timeDetails);
        }
        return $timeDetails;
    }


    //  $all_emp = [];
    //     $all_emp = User::all();
    //     $wbs_master_user = [];
    //     $all_wbs = Wbs::all();
    //     $wbs_assignee_with_plandetails=[];
        

    //     foreach ($all_emp as $key => $emp) {
    //         if(WbsMaster:: where('wbs_master_assignee_id','=',$emp->id)->first() != null){
    //             $wbs_master_user[] = WbsMaster:: where('wbs_master_assignee_id',$emp->id)->get();
    //         }
    //     }
    //     foreach ($wbs_master_user as $key => $value) {
    //         // dump($value);
    //                 foreach ($value as $key => $wbsMaster) {
    //                     // dump($wbsMaster);//wbs_master_wbs_id,wbs_master_assignee_id,
    //                     foreach ($all_wbs as $key => $wbs) {
    //                         if($wbsMaster->wbs_master_wbs_id == $wbs->id){
    //                             // $wbs_array[] =  (array) $wbs;
    //                             // dd($wbs->updated_at->toarray()['formatted']);
    //                             $wbs_assignee_with_plandetails [] = array_merge(array("id" => $wbs->id,
    //                             "wbs_task_details" => $wbs->wbs_task_details,
    //                             "task_start_date" => $wbs->task_start_date,
    //                             "task_end_date" => $wbs->task_end_date,
    //                             "updated_at" => $wbs->updated_at->toarray()['formatted'],
    //                             "actual_hours_worked" => $wbs->actual_hours_worked,
    //                             "actual_hours_today" => $wbs->actual_hours_today,
    //                             "status" => $wbs->status),
    //                             array('assignee_id'=>$wbsMaster->wbs_master_assignee_id,'Project_plan_details_id'=>$wbsMaster->wbs_master_project_plan_details_id)) ;
    //                             // $data = array_merge($data, array("cat"=>"wagon","foo"=>"baar"));
    //                             //dump($wbs_assignee_with_plandetails);
    //                         }
    //                     }

    //                 }

    //     }
    //    // die();

}
