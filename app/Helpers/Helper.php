<?php

use App\Appointment;
use App\Designation;
use App\File;
use App\User;

function getPatientAppointmentStatus($id)
{

    $data = Appointment::where('patient_id', $id)
        ->where('isbooked', 1)
        ->where('isServiced', 0)
        ->get()->count();

    if (isset($data)) {
        return $data;
    } else {
        return 0;
    }

}

function getDesignationInfo($company_id)
{
    $designation_info = Designation::where('company_id', $company_id)->get();
    return $designation_info;
}

function getMeetingRecordings($id)
{

    $data = File::where('sub_task_id', $id)
        ->get();

    if (isset($data)) {
        return $data;
    } else {
        return 0;
    }
}

function sendSMSToDoctorPatient($doctor, $patient, $slot, $visit_date)
{
    $doctor_name = $doctor->name;
    $patient_name = $patient->name;

    $visitDate = date('F j Y', strtotime($visit_date));

    $startTime = date('g:i A', strtotime($slot->start_time));
    $endTime = date('g:i A', strtotime($slot->end_time));
    // message
    if (!defined('API_ACCESS_KEY')) {
        define('API_ACCESS_KEY', 'AAAAq_wHm7E:APA91bGMsE-gkkC7Iq7XGxIkPhB33zJXco3DhisWaLfhgvx3h-XjRbuvxZfJgDYsoeg3C4jd5u6uEtrdC9XGfXeMAF5PMpaci1IWho7RYR_NZaGjHf4b0wL4Hzckozc_BropyyNhDU4b');
    }

    $msg_number = $patient->phone;
    $doc_numbr = $doctor->phone;

    $phone_number = substr($msg_number, -11);
    $country_code = substr($msg_number, 0, -11);

    $doc_phone_number = substr($doc_numbr, -11);
    $doc_country_code = substr($doc_numbr, 0, -11);

    $message = 'Dear ' . $patient_name . ', Your appointment with ' . $doctor_name . ' has been set at ' . $startTime . ' - ' . $endTime . ' on ' . $visitDate . ' Please log in at this site https://www.virtualoffice.com.bd';

    $registrationIds = 'eCGR2SJWoC8:APA91bFBfcWNmTeNWdl3BGKxNDtDV7Bt8TWDZttZAMS3liU_b1ynG-TRay4iIc9KYoP2_RhUg_UCboo2cr8Bw3Ew3Bgsa7zQYfFN20pmASwiD5cMj7hKg6BqlwE1M-jLXzuHlcuMXqHe';
    // prep the bundle
    $msg = array(
        'to' => $registrationIds,
        'data' => array(
            'code' => $message,
            'country' => $country_code,
            'host_number' => $phone_number,
        ),
    );

    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));
    $result = curl_exec($ch);
    // echo $result;
    // exit();
    curl_close($ch);

    $message2 = 'Dear ' . $doctor_name . ', Your appointment with  ' . $patient_name . ' has been set at ' . $startTime . ' - ' . $endTime . ' on ' . $visitDate . ' Please log in at this site https://www.virtualoffice.com.bd';

    $msg2 = array(
        'to' => $registrationIds,
        'data' => array(
            'code' => $message2,
            'country' => $doc_country_code,
            'host_number' => $doc_phone_number,
        ),
    );

    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($msg2));
    $result = curl_exec($ch2);
    // echo $result;
    // exit();
    curl_close($ch2);
}

function sendRescheduleSMSToDoctorPatient($doctor, $patient, $slot, $visit_date)
{
    $doctor_name = $doctor->name;
    $patient_name = $patient->name;

    $visitDate = date('F j Y', strtotime($visit_date));

    $startTime = date('g:i A', strtotime($slot->start_time));
    $endTime = date('g:i A', strtotime($slot->end_time));
    // message
    if (!defined('API_ACCESS_KEY')) {
        define('API_ACCESS_KEY', 'AAAAq_wHm7E:APA91bGMsE-gkkC7Iq7XGxIkPhB33zJXco3DhisWaLfhgvx3h-XjRbuvxZfJgDYsoeg3C4jd5u6uEtrdC9XGfXeMAF5PMpaci1IWho7RYR_NZaGjHf4b0wL4Hzckozc_BropyyNhDU4b');
    }

    $msg_number = $patient->phone;
    $doc_numbr = $doctor->phone;

    $phone_number = substr($msg_number, -11);
    $country_code = substr($msg_number, 0, -11);

    $doc_phone_number = substr($doc_numbr, -11);
    $doc_country_code = substr($doc_numbr, 0, -11);

    $message = 'Dear ' . $patient_name . ', Your appointment with ' . $doctor_name . ' has been set reschedule at ' . $startTime . ' - ' . $endTime . ' on ' . $visitDate . ' Please log in at this site https://www.virtualoffice.com.bd';

    $registrationIds = 'eCGR2SJWoC8:APA91bFBfcWNmTeNWdl3BGKxNDtDV7Bt8TWDZttZAMS3liU_b1ynG-TRay4iIc9KYoP2_RhUg_UCboo2cr8Bw3Ew3Bgsa7zQYfFN20pmASwiD5cMj7hKg6BqlwE1M-jLXzuHlcuMXqHe';
    // prep the bundle
    $msg = array(
        'to' => $registrationIds,
        'data' => array(
            'code' => $message,
            'country' => $country_code,
            'host_number' => $phone_number,
        ),
    );

    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));
    $result = curl_exec($ch);
    // echo $result;
    // exit();
    curl_close($ch);

    $message2 = 'Dear ' . $doctor_name . ', Your appointment with  ' . $patient_name . ' has been set reschedule at ' . $startTime . ' - ' . $endTime . ' on ' . $visitDate . ' Please log in at this site https://www.virtualoffice.com.bd';

    $msg2 = array(
        'to' => $registrationIds,
        'data' => array(
            'code' => $message2,
            'country' => $doc_country_code,
            'host_number' => $doc_phone_number,
        ),
    );

    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($msg2));
    $result = curl_exec($ch2);
    // echo $result;
    // exit();
    curl_close($ch2);
}

function sendSMSToRequest($doctor, $patient, $slot, $visit_date)
{

    $doctor_name = $doctor->name;
    $patient_name = $patient->name;

    $visitDate = date('F j Y', strtotime($visit_date));

    $startTime = date('g:i A', strtotime($slot->start_time));
    $endTime = date('g:i A', strtotime($slot->end_time));
    // message
    if (!defined('API_ACCESS_KEY')) {
        define('API_ACCESS_KEY', 'AAAAq_wHm7E:APA91bGMsE-gkkC7Iq7XGxIkPhB33zJXco3DhisWaLfhgvx3h-XjRbuvxZfJgDYsoeg3C4jd5u6uEtrdC9XGfXeMAF5PMpaci1IWho7RYR_NZaGjHf4b0wL4Hzckozc_BropyyNhDU4b');
    }

    $msg_number = $patient->phone;
    $doc_numbr = $doctor->phone;

    $phone_number = substr($msg_number, -11);
    $country_code = substr($msg_number, 0, -11);

//    $doc_phone_number = substr($doc_numbr, -11);
    //    $doc_country_code = substr($doc_numbr, 0, -11);
    $message = 'Dear ' . $patient_name . ',Your appoinment request has sent to moderator for approval. Please wait for the confirmation sms. Thank you.';

    $registrationIds = 'eCGR2SJWoC8:APA91bFBfcWNmTeNWdl3BGKxNDtDV7Bt8TWDZttZAMS3liU_b1ynG-TRay4iIc9KYoP2_RhUg_UCboo2cr8Bw3Ew3Bgsa7zQYfFN20pmASwiD5cMj7hKg6BqlwE1M-jLXzuHlcuMXqHe';
    // prep the bundle
    $msg = array(
        'to' => $registrationIds,
        'data' => array(
            'code' => $message,
            'country' => $country_code,
            'host_number' => $phone_number,
        ),
    );

    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));
    $result = curl_exec($ch);
    // echo $result;
    // exit();
    curl_close($ch);

}

function sendSMSToEmployeeUnderProject($user_info, $meeting_date, $meeting_time, $sub_task_name, $pm_name)
{

    $meetingDate = date('F j Y', strtotime($meeting_date));

    $meetingTime = date('g:i A', strtotime($meeting_time));
    // message
    if (!defined('API_ACCESS_KEY')) {
        define('API_ACCESS_KEY', 'AAAAq_wHm7E:APA91bGMsE-gkkC7Iq7XGxIkPhB33zJXco3DhisWaLfhgvx3h-XjRbuvxZfJgDYsoeg3C4jd5u6uEtrdC9XGfXeMAF5PMpaci1IWho7RYR_NZaGjHf4b0wL4Hzckozc_BropyyNhDU4b');
    }

    $msg_number = $user_info->phone;
    $email = $user_info->email;

    $phone_number = substr($msg_number, -11);
    $country_code = substr($msg_number, 0, -11);

    if ($sub_task_name == 'General Meeting') {
        $message = 'Hi, Lets have a meeting on ' . $meetingDate . ', ' . $meetingTime . '--' . $pm_name;

    } else {
        $message = 'Hi,
In order to discuss the work plan and highlight each team members role, a meeting has been scheduled on ' . $meetingDate . ', ' . $meetingTime . ', in the conference room for the project ' . $sub_task_name . '.
--' . $pm_name;

    }

    $registrationIds = 'eCGR2SJWoC8:APA91bFBfcWNmTeNWdl3BGKxNDtDV7Bt8TWDZttZAMS3liU_b1ynG-TRay4iIc9KYoP2_RhUg_UCboo2cr8Bw3Ew3Bgsa7zQYfFN20pmASwiD5cMj7hKg6BqlwE1M-jLXzuHlcuMXqHe';
    // prep the bundle
    $msg = array(
        'to' => $registrationIds,
        'data' => array(
            'code' => $message,
            'country' => $country_code,
            'host_number' => $phone_number,
        ),
    );

    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));
    $result = curl_exec($ch);
    // echo $result;
    // exit();
    curl_close($ch);

    $mail_body = '<html>
                <h3>Hi,</h3>
                <p>In order to discuss the work plan and highlight each team members role, a meeting has been scheduled on <strong><i>' . $meetingDate . ' ' . $meetingTime . '</i></strong>, in the conference room for the project <strong><i>' . $sub_task_name . '</i></strong></p>
                <h3><a href="https://virtualoffice.com.bd/login">JOIN MEETING</a></h3>
                <h3> -' . $pm_name . '</h3>
                </html>';

    try {
        Mail::send([], [], function ($message) use ($mail_body, $email) {
            $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
            $message->to($email);
            $message->subject('Project Meeting');
            $message->setBody($mail_body,
                'text/html');

        });
    } catch (\Swift_TransportException $transportExp) {
        // dd($transportExp->getMessage());
    }

}

function sendMeetingAlertToEmployee($user_info, $pm, $meeting_date, $sub_task_name)
{
//$user_info, $pm, $subtasktitle->sub_task_name
    $meetingDate = date('F j Y', strtotime($meeting_date));

    $meetingTime = date('g:i A', strtotime($meeting_date));
    // dd($meetingTime);
    // message
    if (!defined('API_ACCESS_KEY')) {
        define('API_ACCESS_KEY', 'AAAAq_wHm7E:APA91bGMsE-gkkC7Iq7XGxIkPhB33zJXco3DhisWaLfhgvx3h-XjRbuvxZfJgDYsoeg3C4jd5u6uEtrdC9XGfXeMAF5PMpaci1IWho7RYR_NZaGjHf4b0wL4Hzckozc_BropyyNhDU4b');
    }

    $msg_number = $user_info->phone;
    $email = $user_info->email;

    $phone_number = substr($msg_number, -11);
    $country_code = substr($msg_number, 0, -11);

    $message = 'Reminder!!! You have a scheduled meeting today at ' . $meetingTime . ' on ' . $sub_task_name . '.';

    $registrationIds = 'eCGR2SJWoC8:APA91bFBfcWNmTeNWdl3BGKxNDtDV7Bt8TWDZttZAMS3liU_b1ynG-TRay4iIc9KYoP2_RhUg_UCboo2cr8Bw3Ew3Bgsa7zQYfFN20pmASwiD5cMj7hKg6BqlwE1M-jLXzuHlcuMXqHe';
    // prep the bundle
    $msg = array(
        'to' => $registrationIds,
        'data' => array(
            'code' => $message,
            'country' => $country_code,
            'host_number' => $phone_number,
        ),
    );

    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));
    $result = curl_exec($ch);
    // echo $result;
    // exit();
    curl_close($ch);

    $mail_body = '<html>
                <h3>Reminder !!!</h3>
                <p>You have a scheduled meeting today at <strong><i>' . $meetingTime . '</i></strong>, on <strong><i>' . $sub_task_name . '</i></strong></p>
                <h3><a href="https://virtualoffice.com.bd/login">JOIN MEETING</a></h3>
                </html>';

    try {
         Mail::send([], [], function ($message) use ($mail_body, $email) {
            $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
            $message->to($email);
            $message->subject('Project Meeting');
            $message->setBody($mail_body,
                'text/html');
    });
    } catch (\Swift_TransportException $transportExp) {
        //   dd($transportExp->getMessage());
    }
   

}
function sendMeetingAlertToPM($pm, $meeting_date, $sub_task_name)
{
//$user_info, $pm, $subtasktitle->sub_task_name
    $meetingDate = date('F j Y', strtotime($meeting_date));

    $meetingTime = date('g:i A', strtotime($meeting_date));
    // dd($meetingTime);
    // message
    if (!defined('API_ACCESS_KEY')) {
        define('API_ACCESS_KEY', 'AAAAq_wHm7E:APA91bGMsE-gkkC7Iq7XGxIkPhB33zJXco3DhisWaLfhgvx3h-XjRbuvxZfJgDYsoeg3C4jd5u6uEtrdC9XGfXeMAF5PMpaci1IWho7RYR_NZaGjHf4b0wL4Hzckozc_BropyyNhDU4b');
    }

    $msg_number = $pm->phone;
    $email = $pm->email;

    $phone_number = substr($msg_number, -11);
    $country_code = substr($msg_number, 0, -11);

    $message = 'Reminder!!! You have a scheduled meeting today at ' . $meetingTime . ' on ' . $sub_task_name . '.';

    $registrationIds = 'eCGR2SJWoC8:APA91bFBfcWNmTeNWdl3BGKxNDtDV7Bt8TWDZttZAMS3liU_b1ynG-TRay4iIc9KYoP2_RhUg_UCboo2cr8Bw3Ew3Bgsa7zQYfFN20pmASwiD5cMj7hKg6BqlwE1M-jLXzuHlcuMXqHe';
    // prep the bundle
    $msg = array(
        'to' => $registrationIds,
        'data' => array(
            'code' => $message,
            'country' => $country_code,
            'host_number' => $phone_number,
        ),
    );
    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));
    $result = curl_exec($ch);
    // echo $result;
    // exit();
    curl_close($ch);

    $mail_body = '<html>
                <h3>Reminder !!!</h3>
                <p>You have a scheduled meeting today at <strong><i>' . $meetingTime . '</i></strong>, on <strong><i>' . $sub_task_name . '</i></strong></p>
                <h3><a href="https://virtualoffice.com.bd/login">JOIN MEETING</a></h3>
                </html>';

    try {
        Mail::send([], [], function ($message) use ($mail_body, $email) {
            $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
            $message->to($email);
            $message->subject('Project Meeting');
            $message->setBody($mail_body,
                'text/html');
    });
    } catch (\Swift_TransportException $transportExp) {
        //   dd($transportExp->getMessage());
    }
   

}

function sendRegistrationMsg($phone)
{

    // message
    if (!defined('API_ACCESS_KEY')) {
        define('API_ACCESS_KEY', 'AAAAq_wHm7E:APA91bGMsE-gkkC7Iq7XGxIkPhB33zJXco3DhisWaLfhgvx3h-XjRbuvxZfJgDYsoeg3C4jd5u6uEtrdC9XGfXeMAF5PMpaci1IWho7RYR_NZaGjHf4b0wL4Hzckozc_BropyyNhDU4b');
    }

    $phone_number = substr($phone, -11);
    $country_code = substr($phone, 0, -11);

    $message = 'Dear User,
Thanks for Registering with Virtual Office.From now you can take advantage of our online office services by logging into your account. https://virtualoffice.com.bd/login Login your Dashboard!';

    $registrationIds = 'eCGR2SJWoC8:APA91bFBfcWNmTeNWdl3BGKxNDtDV7Bt8TWDZttZAMS3liU_b1ynG-TRay4iIc9KYoP2_RhUg_UCboo2cr8Bw3Ew3Bgsa7zQYfFN20pmASwiD5cMj7hKg6BqlwE1M-jLXzuHlcuMXqHe';
    // prep the bundle
    $msg = array(
        'to' => $registrationIds,
        'data' => array(
            'code' => $message,
            'country' => $country_code,
            'host_number' => $phone_number,
        ),
    );

    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));
    $result = curl_exec($ch);
    // echo $result;
    // exit();
    curl_close($ch);
}
