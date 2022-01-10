<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\File;
use App\Slot;
use App\Appointment;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use DateTime;
use PDF;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function patient_profile($id)
    {
        $slot = Slot::all();
        $files = File::all();
        //dd($files);
        $user = User::find($id);
        $appointment_history = Appointment::latest()
                    ->where('isbooked',1)
                    ->where('isServiced',1)
                    ->where('patient_id',$id)
                    ->get();
                    //dd($appointment_history);
        $patient_file = File::latest()
                    ->where('user_id',$id)
                    ->get();
        // dd($patient_file);        
        return view('admin.patient_profile', compact('user','appointment_history', 'slot', 'id', 'files', 'patient_file'));
    }

    

    public function prescriptionDownload($id)
    {

        $appointment = Appointment::find($id);

        // return view('admin.prescription_form', compact('appointment'));
        $pdf = PDF::loadView('admin.prescription_form', compact('appointment'));
        
        return $pdf->download('prescription.pdf');
    }
    Public function fileDownload($id){
        $dl = File::find($id);
        return Storage::download($dl->path, $dl->title);
    }
    


}
