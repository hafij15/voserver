<?php

namespace App\Http\Controllers;

use App\File;
use App\Appointment;
use App\User;
use Auth;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chat($id, $room, $name)
    {
        $appointment = Appointment::find($id);
        $ch = curl_init();
        $url = "https://teleassiststunturn.herokuapp.com/";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $result = curl_exec($ch);
        if (Auth::check() && Auth::user()->hasRole('power-user')) {
            $ispatients = false;
        } else {
            $ispatients = true;
        }
        if (Auth::check()) {
            // dd(Auth::user()->name);
            $userName = Auth::user()->name;
        }
        $doctorInfo = User::find($appointment->pm_id);
        // dd($result);
        return view('admin.chat', compact('result', 'id', 'room', 'userName', 'appointment', 'ispatients', 'doctorInfo'));
    }

    public function send_session_data(Request $request)
    {
        $appointmentId = $request->appointmentId;
        $diagonosis = $request->diagnosis;
        $prescribe_medicines = $request->medicine;
        $follow_up_visit_date = $request->followUpDate;
        $spent_hour = $request->spentHour;
        $cc = $request->complains;
        $investigation = $request->investigation;
        $instructions = $request->instruction;
        $vitalSign = $request->vitalSign;
        $appointment = Appointment::find($appointmentId);
        $isService = 0;
        // dd($spent_hour);
        $appointment->where('id', $appointmentId)->update(array(
            'prescribe_medicines' => $prescribe_medicines,
            'diagonosis' => $diagonosis,
            'isServiced' => $isService,
            'follow_up_visit_date' => $follow_up_visit_date,
            'spent_hour' => $spent_hour,
            'cc' => $cc,
            'investigation' => $investigation,
            'instructions' => $instructions,
            'vital_signs' => $vitalSign,
        ));
        return "success";
    }

    public function send_session_status_patient(Request $request)
    {
        $appointmentId = $request->appointmentId;

        return "success";

    }
    public function send_meeting_mins(Request $request)
    {
        $meetingId = $request->meetingId;
        $spent_hour = $request->spentHour;
        $meetingMin = $request->meetingMin;
        $meeting = Appointment::find($meetingId);
        $isService = 1;
        // dd($request);
        $meeting->where('id', $meetingId)->update(array(
            'isServiced' => $isService,
            'spent_hour' => $spent_hour,
            'meeting_mins' => $meetingMin,
        ));
        return "success";
    }

    public function send_session_record(Request $request)
    {
        $files = $request->file('video');
        // dd($request->name);

        $name = $request->name.'_'.$files->getClientOriginalName().".mp4";

        File::create([
                'sub_task_id' => ($request->subtask_id !=null?$request->subtask_id:0),
                'title' => $name,
                'description' => '',
                'path' => $files->storeAs('public/storage', $name),
            ]);
        return "success";
    }


}
