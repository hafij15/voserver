<?php

namespace App\Http\Controllers;

use App\Company;
use App\Role;
use App\User;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Mail;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        // if(Auth::user()->can('dashboard')){
        // $users = User::latest()->where('is_deleted','0')->get();
        if (Auth::user()->hasRole('admin')) {
            $users = User::with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('slug', '=', 'user');
                })
                ->orderBy('created_at', 'desc')
                ->where('is_deleted', '0')->get();
        } else {
            $users = User::latest()->where('is_deleted', '0')->get();
        }
        // $companies = Company::orderBy('created_at', 'desc')->get();
        // dd($companies);
        return view('admin.user', compact('users'));
        // }else{
        //     abort(404);
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->hasRole('admin')) {
            $roles = Role::find(4);
        } else {
            $roles = Role::all();
        }
        $companies = Company::orderBy('created_at', 'desc')->get();
        return view('admin.create_user', compact('roles', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'company' => 'required',
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users',
            'gender' => 'required',
            'age' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
        ]);
        set_time_limit(0);
        ini_set('max_execution_time', 180); //3 minutes
        if (Auth::user()->hasRole('super-admin')) {
            $password = $request->password;
        } else {
            $password = 'Pass@123';
        }
        if ($request->role_id == 4) {
            $html = '<html>
            <h3>Dear User,</h1>
            <h3>
            Thanks for Registering with Virtual Office.From now you can take advantage of our online office services by logging into your account.
            </h3>
            <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard!</h3>
            </html>';
            // dd('patient');
            $patient = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => $request->age,
                'gender' => $request->gender,
                'is_active' => 1,
                'password' => bcrypt($password),
                'company_id' => $request->company,
            ]);
            $to = $patient->email;
            $patient->roles()->attach($request->role_id);
            try {
                Mail::send([], [], function ($message) use ($html, $to) {
                    $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
                    $message->to($to);
                    $message->subject('Registration Successful!');
                    $message->setBody($html,
                        'text/html');
                });
            } catch (\Swift_TransportException $transportExp) {
                //   dd($transportExp->getMessage());
            }
            $moderator = User::with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('slug', '=', 'admin');
                })
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->get();
            foreach ($moderator as $data) {
                $mail_body = '<html>
                <h3>Dear ' . $data->name . ',</h1>
                <h3>A new employee has been registered to VirtualOffice!</h3>
                <h3>Name: ' . $patient->name . '</h3>
                <h3>Email: ' . $patient->email . '</h3>
                <h3>Phone: ' . $patient->phone . '</h3>
                <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard!</h3>
                </html>';
                $toModerator = $data->email;
                try {
                    Mail::send([], [], function ($message) use ($mail_body, $toModerator) {
                        $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
                        $message->to($toModerator);
                        $message->subject('New Employee Registration!');
                        $message->setBody($mail_body,
                            'text/html');
                    });
                } catch (\Swift_TransportException $transportExp) {
                    //   dd($transportExp->getMessage());
                }

            }
            if (Auth::user()->hasRole('admin')) {
                $patient_name = $patient->name;
                $patient_email = $to;
                $patient_phone = $patient->phone;
                $patient_pass = $password;
                // message
                define('API_ACCESS_KEY', 'AAAAq_wHm7E:APA91bGMsE-gkkC7Iq7XGxIkPhB33zJXco3DhisWaLfhgvx3h-XjRbuvxZfJgDYsoeg3C4jd5u6uEtrdC9XGfXeMAF5PMpaci1IWho7RYR_NZaGjHf4b0wL4Hzckozc_BropyyNhDU4b');
                $msg_number = $patient->phone;
                $phone_number = substr($msg_number, -11);
                $country_code = substr($msg_number, 0, -11);
                //$message = 'Dear '.$patient_name.',Your account has been created successfully. Please log in at this site https://www.virtualdr.com.bd . Email:'.$patient_email.' and password is '.$patient_pass. '. Please change your password after login.' ;
                $message = 'Dear ' . $patient_name . ',Thanks for Registering with Virtual Office.From now you can take advantage of our online office services by logging into your account.';
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
                Toastr::success('Account created  Successfully :)', 'success');
            }
        } else {
            $html = '<html>
            <h3>Dear User,</h1>
            <h3>Thanks for Registering with Virtual Office.You will receive another email from us confirming the account registration.</h3>
            <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard!</h3>
            </html>';
            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => $request->age,
                'gender' => $request->gender,
                'password' => bcrypt($password),
                'company_id' => $request->company,
            ]);
            $to = $data->email;
            $data->roles()->attach($request->role_id);
            try {
                Mail::send([], [], function ($message) use ($html, $to) {
                    $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
                    $message->to($to);
                    $message->subject('Registration Successful!');
                    $message->setBody($html,
                        'text/html');
                });
            } catch (\Swift_TransportException $transportExp) {
                //   dd($transportExp->getMessage());
            }

        }
        Toastr::success('Registration Completed Successfully :)', 'success');
        return redirect('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
        $user_info = User::find($id);
        return view('admin.edit_user', compact('user_info'));
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);
        $image = $request->file('image');
        $slug = $request->name;
        $user = User::find($id);
        if (isset($image)) {
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            //check post dir is exits
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            // delete old image dir
            if (Storage::disk('public')->exists('profile/' . $user->image)) {
                Storage::disk('public')->delete('profile/' . $user->image);
            }
            //resize image for post and upload
            $profile = Image::make($image)->resize(500, 500)->stream();
            Storage::disk('public')->put('profile/' . $imageName, $profile);
        } else {
            $imageName = $user->image;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->image = $imageName;
        // $user->about = $request->about;
        $user->save();
        Toastr::success('User updated successfully.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function approve_user($id)
    {
        set_time_limit(0);
        ini_set('max_execution_time', 180); //3 minutes
        $user = User::find($id);

        sendRegistrationMsg($user->phone);

        $html = '<html>
        <h3>Dear Mr. ' . $user->name . ',</h1>
        <h3>Your account has been approved successfully for virtualoffice.com.bd!</h1>
        <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard!</h3>
        </html>';
        $to = $user->email;
        $user->where('id', $id)->update(array('is_active' => 1));
        // send mail
        try {
            Mail::send([], [], function ($message) use ($html, $to) {
                $message->from('contact@virtualoffice.com.bd', 'Virtual Office');
                $message->to($to);
                $message->subject('Account Activated!');
                $message->setBody($html,
                    'text/html');
            });
        } catch (\Swift_TransportException $transportExp) {
            //   dd($transportExp->getMessage());
        }

        Toastr::success('User Approved successfully.');
        return redirect()->back();
    }

    public function pending_user($id)
    {
        $user = User::find($id);
        $user->where('id', $id)->update(array('is_active' => 0));
        Toastr::success('User Pending successfully.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        // dd('deleted');
        $user = User::find($id);
        $user->where('id', $id)->update(array('is_deleted' => 1));
        Toastr::success('User Deleted successfully.');
        return redirect()->back();
    }

}
