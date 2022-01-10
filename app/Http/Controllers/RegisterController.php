<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Mail;

class RegisterController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $companies = Company::orderBy('created_at', 'desc')->get();
        return view('auth.register', compact('companies'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'company' => 'required',
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users',
            'gender' => 'required',
            'age' => 'required',
            'role_id' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $pm = User::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'power-user');
            })
            ->where('is_deleted', 0)
            ->where('is_active', 0)
            ->get();

        set_time_limit(0);
        ini_set('max_execution_time', 180); //3 minutes

        // dd($request->all());
        if ($request->role_id == 4) {
            sendRegistrationMsg($request->phone);
            $html = '<html>
            <h3>Dear User,</h1>
            <h3>
            Thanks for Registering with Virtual Office.From now you can take advantage of our online office services by logging into your account.
            </h3>
            <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard!</h3>
            </html>';
            // dd('employee');
            $employee = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => $request->age,
                'gender' => $request->gender,
                'is_active' => 0,
                'password' => bcrypt($request->password),
                'company_id' => $request->company,
            ]);
            $to = $employee->email;
            $employee->roles()->attach($request->role_id);
            // send mail to employee for account creation
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

            $mail_bodyb = '<html>
                <h3>Dear Admin,</h1>
                <h3>A new employee has been registered to VirtualOffice!</h3>
                <h3>Name: ' . $employee->name . '</h3>
                <h3>Email: ' . $employee->email . '</h3>
                <h3>Phone: ' . $employee->phone . '</h3>
                <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard!</h3>
                </html>';
            $toAd = 'adminvo@virtualoffice.com.bd';
            try {
                Mail::send([], [], function ($message) use ($mail_bodyb, $toAd) {
                    $message->from('contact@virtualoffice.com.bd', 'Virtual Doctor');
                    $message->to('adminvo@virtualoffice.com.bd');
                    $message->subject('New Employee Registration!');
                    $message->setBody($mail_bodyb,
                        'text/html');
                });
            } catch (\Swift_TransportException $transportExp) {
                //   dd($transportExp->getMessage());
            }

            // send mail to pm for new employee creation
            foreach ($pm as $data) {
                $mail_body = '<html>
                <h3>Dear ' . $data->name . ',</h1>
                <h3>A new employee has been registered to VirtualOffice!</h3>
                <h3>Name: ' . $employee->name . '</h3>
                <h3>Email: ' . $employee->email . '</h3>
                <h3>Phone: ' . $employee->phone . '</h3>
                <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard!</h3>
                </html>';
                $toPm = $data->email;
                try {
                    Mail::send([], [], function ($message) use ($mail_body, $toPm) {
                        $message->from('contact@virtualoffice.com.bd', 'Virtual Doctor');
                        $message->to($toPm);
                        $message->subject('New Employee Registration!');
                        $message->setBody($mail_body,
                            'text/html');
                    });
                } catch (\Swift_TransportException $transportExp) {
                    //   dd($transportExp->getMessage());
                }
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
                'password' => bcrypt($request->password),
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

        $mail_bodyb = '<html>
                <h3>Dear Admin,</h1>
                <h3>A new pm has been registered to VirtualOffice!</h3>
                <h3>Name: ' . $request->name . '</h3>
                <h3>Email: ' . $request->email . '</h3>
                <h3>Phone: ' . $request->phone . '</h3>
                <h3><a href="https://virtualoffice.com.bd/login">Click Here</a> to Login your Dashboard!</h3>
                </html>';
        $toAd = 'adminvo@virtualoffice.com.bd';
        try {
            Mail::send([], [], function ($message) use ($mail_bodyb, $toAd) {
                $message->from('contact@virtualoffice.com.bd', 'Virtual Doctor');
                $message->to('adminvo@virtualoffice.com.bd');
                $message->subject('New PM Registration!');
                $message->setBody($mail_bodyb,
                    'text/html');
            });
        } catch (\Swift_TransportException $transportExp) {
            //   dd($transportExp->getMessage());
        }
        Toastr::success('Registration Completed Successfully :)', 'success');
        return redirect('/login');
    }

}
