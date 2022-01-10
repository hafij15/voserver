<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Slot;
use App\Appointment;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use DateTime;
use PDF;

class AppointmentController extends Controller
{


    public function index()
    {
        $patients = '';
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin')) {
            $patients = Appointment::latest()
                ->where('isbooked', 1)
                ->where('isServiced', 1)
                ->get();
        }
        if (Auth::user()->hasRole('power-user')) {
            $patients = Appointment::latest()
                ->where('isbooked', 1)
                ->where('isServiced', 1)
                ->where('doctor_id', Auth::user()->id)
                ->get();
        }
        if (Auth::user()->hasRole('user')) {
            $patients = Appointment::latest()
                ->where('isbooked', 1)
                ->where('isServiced', 1)
                ->where('patient_id', Auth::user()->id)
                ->get();
        }
        return view('admin.patient_history', compact('patients'));
    }

    public function set_appointment($id)
    {
        $slot = Slot::all();
        $user = User::find($id);
        $doctor = User::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'power-user');
            })
            ->orderBy('created_at', 'desc')
            ->where('is_deleted', '0')->get();
        // dd($projects);
        return view('admin.create_appointment', compact('user', 'doctor', 'slot', 'id'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Dhaka");
        $uniqid = Str::random(9);
        // dd($uniqid);
        $visit_date = $request->visit_date;
        $patientinfo = Appointment::latest()
            ->where('patient_id', $request->patient_id)
            ->limit(1, 0)
            ->first();

        if (!empty($patientinfo)) {
            $exiting_doctor = $patientinfo->doctor_id;
        } else {
            $exiting_doctor = '';
        }

        if (Auth::user()->hasRole('user')) {
            $is_approved = 0;
        } else {
            $is_approved = 1;
        }

        // dd($exiting_doctor);
        if ($exiting_doctor) {

            if ($exiting_doctor == $request->doctor_id) {

                if ($request->slot_id === 'create_schedule') {

                    $slotInfo = Slot::create([
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                        'created_at' => new DateTime()
                    ]);

                    if ($request->slot_id === 'create_schedule') {
                        $slot_id = $slotInfo->id;
                    } else {
                        $slot_id = $request->slot_id;
                    }

                    $data = Appointment::create([
                        'patient_id' => $request->patient_id,
                        'doctor_id' => $request->doctor_id,
                        'room_id' => $uniqid,
                        'patient_type' => $request->patient_type,
                        'patient_symptoms' => $request->patient_symptoms,
                        'visit_date' => $request->visit_date,
                        'slot_id' => $slot_id,
                        'isbooked' => 1,
                        'isApproved' => $is_approved,
                    ]);
                    $doctor = User::find($data['doctor_id']);
                    $patient = User::find($data['patient_id']);
                    // dd($doctor);
                    $slot = Slot::find($slot_id);


                    if (Auth::user()->hasRole('user')) {

                        $moderators = User::with('roles')
                            ->whereHas('roles', function($q) {
                                $q->where('slug', '=', 'admin');
                            })
                            ->where('is_active', 1)
                            ->where('is_deleted', 0)
                            ->get();
                        
                        foreach($moderators as $data){

                            $mail_body='<html>
                            <h3>Dear '.$data->name.',</h1>                            
                            <h3>A employee named '.$patient->name.', Phone: '.$patient->phone.' has been requested to get an appointment with '.$doctor->name.'</h3>
                            <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard and Approve the request.</h3>
                            </html>';

                            $toModerator = $data->email;

                            Mail::send([],[], function($message) use ($mail_body,$toModerator){
                                $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
                                $message->to($toModerator);
                                $message->subject('New Appointment Request!');
                                $message->setBody($mail_body,
                                    'text/html' );
                    
                            });
                        }

                        sendSMSToRequest($doctor,$patient,$slot,$visit_date);


                    } else {

                        sendSMSToDoctorPatient($doctor,$patient,$slot,$visit_date);
                    }


                } else {
                    $slot_id = $request->slot_id;
                    $data = Appointment::create([
                        'patient_id' => $request->patient_id,
                        'doctor_id' => $request->doctor_id,
                        'room_id' => $uniqid,
                        'patient_type' => $request->patient_type,
                        'patient_symptoms' => $request->patient_symptoms,
                        'visit_date' => $request->visit_date,
                        'slot_id' => $slot_id,
                        'isbooked' => 1,
                        'isApproved' => $is_approved,
                    ]);

                    $doctor = User::find($data['doctor_id']);
                    $patient = User::find($data['patient_id']);
                    // dd($doctor);
                    $slot = Slot::find($slot_id);

                    if (Auth::user()->hasRole('user')) {

                        $moderators = User::with('roles')
                            ->whereHas('roles', function($q) {
                                $q->where('slug', '=', 'admin');
                            })
                            ->where('is_active', 1)
                            ->where('is_deleted', 0)
                            ->get();
                        
                        foreach($moderators as $data){

                            $mail_body='<html>
                            <h3>Dear '.$data->name.',</h1>                            
                            <h3>A employee named '.$patient->name.', Phone: '.$patient->phone.' has been requested to get an appointment with '.$doctor->name.'</h3>
                            <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard and Approve the request.</h3>
                            </html>';

                            $toModerator = $data->email;

                            Mail::send([],[], function($message) use ($mail_body,$toModerator){
                                $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
                                $message->to($toModerator);
                                $message->subject('New Appointment Request!');
                                $message->setBody($mail_body,
                                    'text/html' );
                    
                            });
                        }
                        sendSMSToRequest($doctor,$patient,$slot,$visit_date);


                    } else {

                        sendSMSToDoctorPatient($doctor,$patient,$slot,$visit_date);
                    }

                }

                Toastr::success('Appointment Setup Completed Successfully :)', 'success');
            } else {
                Toastr::warning('Please select right pm :)', 'unsuccess');
            }
        } else {

            if ($request->start_time && $request->end_time) {
                $slotInfo = Slot::create([
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'created_at' => new DateTime()
                ]);

                $slotid = $slotInfo->id;
            }

            if ($request->slot_id === 'create_schedule') {
                $slot_id = $slotid;
            } else {
                $slot_id = $request->slot_id;
            }

            $data = Appointment::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'room_id' => $uniqid,
                'patient_type' => $request->patient_type,
                'patient_symptoms' => $request->patient_symptoms,
                'visit_date' => $request->visit_date,
                'slot_id' => $slot_id,
                'isbooked' => 1,
                'isApproved' => $is_approved,
            ]);
            $doctor = User::find($data['doctor_id']);
            $patient = User::find($data['patient_id']);
            // dd($doctor);
            $slot = Slot::find($slot_id);


            if (Auth::user()->hasRole('user')) {

                $moderators = User::with('roles')
                            ->whereHas('roles', function($q) {
                                $q->where('slug', '=', 'admin');
                            })
                            ->where('is_active', 1)
                            ->where('is_deleted', 0)
                            ->get();
                        
                foreach($moderators as $data){

                    $mail_body='<html>
                            <h3>Dear '.$data->name.',</h1>                            
                            <h3>A employee named '.$patient->name.', Phone: '.$patient->phone.' has been requested to get an appointment with '.$doctor->name.'</h3>
                            <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard and Approve the request.</h3>
                            </html>';

                            $toModerator = $data->email;

                            Mail::send([],[], function($message) use ($mail_body,$toModerator){
                                $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
                                $message->to($toModerator);
                                $message->subject('New Appointment Request!');
                                $message->setBody($mail_body,
                                    'text/html' );
                    
                            });
                }
                sendSMSToRequest($doctor,$patient,$slot,$visit_date);


            } else {

                sendSMSToDoctorPatient($doctor,$patient,$slot,$visit_date);
            }


            Toastr::success('Appointment Setup Completed Successfully :)', 'success');
        }


        return redirect('dashboard');
    }


    public function show(Appointment $appointment)
    {
        //
    }


    public function edit(Appointment $appointment)
    {
        $sloti_id = array();

        $patientinfo = Appointment::where('doctor_id', $appointment->doctor_id)
            ->whereDate('visit_date', $appointment->visit_date)
            ->get();
        foreach ($patientinfo as $value) {
            if ($value->slot_id != $appointment->slot_id) {
                $sloti_id[] = $value->slot_id;
            }

        }


        // $sloti_id[] = $appointment->slot_id;

        $slot = Slot::latest()->whereNotIn('id', $sloti_id)->get();


        $doctor = User::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'power-user');
            })
            ->orderBy('created_at', 'desc')
            ->where('is_deleted', '0')->get();
        return view('admin.edit_appointment', compact('appointment', 'doctor', 'slot'));
    }


    public function update(Request $request, Appointment $appointment)
    {
        date_default_timezone_set("Asia/Dhaka");
        $visit_date = $request->visit_date;
        $appoint_info = Appointment::find($appointment->id);
        $isApproved = $appoint_info->isApproved;
        $slot_id = $appoint_info->slot_id;

        if ($request->slot_id === 'create_schedule') {

            $slotInfo = Slot::create([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'created_at' => new DateTime()
            ]);

            $slotid = $slotInfo->id;

            $appointment->patient_id = $request->patient_id;
            $appointment->doctor_id = $request->doctor_id;
            $appointment->patient_symptoms = $request->patient_symptoms;
            $appointment->visit_date = $request->visit_date;
            $appointment->slot_id = $slotid;
            $appointment->isApproved = $request->is_approved;
            $appointment->save();
            
            

            $slot = Slot::find($slotid);
            $doctor = User::find($request->patient_id);
            $patient = User::find($request->doctor_id);


        

            if($isApproved == 0 && $appointment->isApproved == 1)
            {
                sendSMSToDoctorPatient($doctor,$patient,$slot,$visit_date);
            }
            else if($slot_id != $appointment->slot_id && $isApproved == 1 && $appointment->isApproved == 1)
            {
                sendSMSToDoctorPatient($doctor,$patient,$slot,$visit_date);
            }


        } else {
            $appointment->patient_id = $request->patient_id;
            $appointment->doctor_id = $request->doctor_id;
            $appointment->patient_symptoms = $request->patient_symptoms;
            $appointment->visit_date = $request->visit_date;
            $appointment->slot_id = $request->slot_id;
            $appointment->isApproved = $request->is_approved;
            $appointment->save();


            $slot = Slot::find($request->slot_id);
            $doctor = User::find($request->patient_id);
            $patient = User::find($request->doctor_id);


            if($isApproved == 0 && $appointment->isApproved == 1)
            {
                sendSMSToDoctorPatient($doctor,$patient,$slot,$visit_date);
            }
            else if($slot_id != $appointment->slot_id && $isApproved == 1 && $appointment->isApproved == 1)
            {
                sendSMSToDoctorPatient($doctor,$patient,$slot,$visit_date);
            }
        }






        Toastr::success('Appointment Updated Successfully :)', 'success');
        return redirect('dashboard');
    }

    public function prescription_edit($id)
    {
        $appointment = Appointment::find($id);
        return view('admin.edit_prescription', compact('appointment'));
    }

    public function prescription_update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        $appointment->where('id', $id)->update(array(
            'prescribe_medicines' => $request->prescribe_medicines
        ));
        Toastr::success('Appointment Updated successfully.');
        return redirect('appointments');
    }

    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        $appointment->where('id', $id)->update(array('isbooked' => '0'));
        Toastr::success('Appointment Cancelled successfully!');
        return redirect()->back();
    }


    public function doctorAvailbleSlot($id, $doc_id, $visit_date)
    {
        $sloti_id = array();

        $patientinfo = Appointment::where('doctor_id', $doc_id)
            ->whereDate('visit_date', $visit_date)
            ->get();
        foreach ($patientinfo as $value) {
            $sloti_id[] = $value->slot_id;
        }

        $slot = Slot::latest()->whereNotIn('id', $sloti_id)->get();

        return view('admin.available_slot', compact('slot'));
    }


    public function prescriptionDownload($id)
    {

        $appointment = Appointment::find($id);

        // return view('admin.prescription_form', compact('appointment'));
        $pdf = PDF::loadView('admin.prescription_form', compact('appointment'));
        
        return $pdf->download('prescription.pdf');
    }


}
