<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use Auth;
use App\User;
use App\Role;
use App\Message;
use Brian2694\Toastr\Facades\Toastr;

class MessageController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('role:admin');
    }
	public function index(){
		if(Auth::user()->can('dashboard')){
			$users = User::latest()->get();
			return view('admin.message',compact('users'));
		}else{
			abort(404);
		}
	}

    public function message(Request $request){
    	$message = $request->input('message');
    	//$mobile = $request->input('mobile');
    	
    	//$encodeMessage = urlencode($message);
    	$tot_num='';
    	$authkey ='';
    	$senderId = '';
    	$route = 4;
    	$postData = $request->all();

    	$tot_num = count($postData['mobile']);

    	define( 'API_ACCESS_KEY', 'AAAAq_wHm7E:APA91bGMsE-gkkC7Iq7XGxIkPhB33zJXco3DhisWaLfhgvx3h-XjRbuvxZfJgDYsoeg3C4jd5u6uEtrdC9XGfXeMAF5PMpaci1IWho7RYR_NZaGjHf4b0wL4Hzckozc_BropyyNhDU4b' );
    	
    	for ($h=0; $h < $tot_num; $h++) { 

			$msg_number = $postData['mobile'][$h];
			$phone_number = substr($msg_number, -11);

			$country_code = substr($msg_number, 0, -11);

			$registrationIds =  'eCGR2SJWoC8:APA91bFBfcWNmTeNWdl3BGKxNDtDV7Bt8TWDZttZAMS3liU_b1ynG-TRay4iIc9KYoP2_RhUg_UCboo2cr8Bw3Ew3Bgsa7zQYfFN20pmASwiD5cMj7hKg6BqlwE1M-jLXzuHlcuMXqHe' ;
			// prep the bundle
			$msg = array(
				'to' => $registrationIds,
				'data' => array(
					'code' => $message,
					'country' => $country_code,
					'host_number' => $phone_number
				)
			);

			
			$headers = array
			(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
			);
			
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $msg ) );
			$result = curl_exec($ch );
			// echo $result;
			// exit();
			curl_close( $ch );
		}   

		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
	       
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		//curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		//curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $msg ) );
		//$result = curl_exec($ch );
		// echo $result;
		// exit();
		//curl_close( $ch );

		//   	$url = "https://user.mobireach.com.bd/";
		//   	$ch = curl_init();
		//   	curl_setopt_array($ch, array(
		//   	CURlOPT_URL => $url, 
		//   	CURlOPT_RETURNTRANSFER =>true,
		//   	CURlOPT_POST => true,
		//   	CURlOPT_POSTFIELDS => $postData
		//   	//CURlOPT_FOLLOWLOCATION => true

			//    ));

			// //Ignore SSL certificate verification
			// curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 0);
			// curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);

		//GET RESPONSE
		$output = curl_exec($ch);

		//Print error if any
		if(curl_errno($ch))
		{
			echo 'error:' . curl_error($ch);	
			return redirect('message')->with('response','Message Error');		
		}

		curl_close($ch);
		return redirect('message')->with('response','Message Sent Successfully');
    }
}
