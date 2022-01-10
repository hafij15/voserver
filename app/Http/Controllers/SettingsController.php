<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\User;
use Hash;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
    	return view('admin.settings');
    }
    public function updateProfile(Request $request)
    {
    	//return $request;
    	$this->validate($request,[
    		'name' => 'required',
    		'email' => 'required|email',
    	]);

        $is_image = $request->is_image;

        $user = User::findOrFail(Auth::id());
        
        if($is_image === 'on')
        {
        	$image = $request->file('image');
        	$slug = $request->name;

        	if(isset($image)){
                //make unique name for image
                $currentDate = Carbon::now()->toDateString();
                $imageName   = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
                //check post dir is exits
                if(!Storage::disk('public')->exists('profile')){
                    Storage::disk('public')->makeDirectory('profile');
                }

                // delete old image dir
                if(Storage::disk('public')->exists('profile/'.$user->image))
                {
                    if($user->image != 'no_profile.png')
                    {
                        Storage::disk('public')->delete('profile/'.$user->image);
                    }
                }

                //resize image for post and upload
                $profile = Image::make($image)->resize(500,500)->stream();
                Storage::disk('public')->put('profile/'.$imageName, $profile);
                
            }else{
                $imageName = $user->image;
            }
        }
        else
        {
            $imageName = 'no_profile.png';
        }
        $user->name = $request->name;
        $user->designations_id = $request->designation_id;
        $user->email = $request->email;
        $user->image = $imageName;
        // $user->about = $request->about;
        $user->save();
        Toastr::success('Profile updated successfully.');
        return redirect()->back();
    }

    public function updatePassword(Request $request)
    {
        //return $request;
        $this->validate($request,[
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $hashedPassword = Auth::user()->password;
        if(Hash::check($request->old_password,$hashedPassword))
        {
            if(!Hash::check($request->password,$hashedPassword))
            {
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();
                Toastr::success('Password Successfulluy changed','Success');
                Auth::logout();
                return redirect()->back();
            } else {
                Toastr::error('New password cannot be the same as old password','Error');
                return redirect()->back();
            }
        } else {
            Toastr::error('Current password not match','Error');
            return redirect()->back();
        }

    }
}
