<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
// Start File Upload
Route::get('/file', 'FileController@index')->name('viewfile');
Route::get('/file/upload', 'FileController@create')->name('formfile');
Route::post('/file/upload', 'FileController@store')->name('uploadfile');
Route::delete('/file/{id}', 'FileController@destroy')->name('deletefile');
Route::get('/file/download/{id}', 'FileController@downloadfile')->name('downloadfile');
Route::get('/file/email/{id}', 'FileController@edit')->name('emailfile');
Route::post('/file/dropzone', 'FileController@dropzone')->name('dropzone');
// End File Upload
//Clear route cache:
Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return 'Routes cache cleared';
});

//Clear config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return 'Config cache cleared';
});

// Clear application cache:
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

// Clear view cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return 'View cache cleared';
});

Route::get('/logout', function () {
    Session::flush();
    return redirect('/');
});

Route::get('/run-migrations', function () {
    return Artisan::call('migrate', ["--force" => true]);
});

// Route::post('/send_session_data', function () {
//     return ['status'=>'success'];
//     // dd("session data routs worked.");
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('/');
Route::resource('register', 'RegisterController');
Route::post('registration', 'RegisterController@store')->name('registration');
// Route::get('/roles', 'PermissionController@Permission');

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::resource('company', 'CompanyController');
    Route::resource('designation', 'DesignationController');
    Route::resource('users', 'UserController');
    Route::resource('projects', 'ProjectController');
    Route::resource('meetings', 'MeetingController');
    Route::get('meeting_history', 'MeetingController@history')->name('meeting_history');
    Route::get('assigned_emp/{sub_task_id}', 'MeetingController@getAssignedEmployees')->name('assigned_emp');
    Route::resource('appointments', 'AppointmentController');
    Route::get('set_appointment/{id}', 'AppointmentController@set_appointment')->name('set_appointment');
    Route::get('prescription_edit/{id}', 'AppointmentController@prescription_edit')->name('prescription_edit');
    Route::post('prescription_update/{id}', 'AppointmentController@prescription_update')->name('prescription_update');
    Route::post('approve_user/{id}', 'UserController@approve_user')->name('approve_user');
    Route::post('pending_user/{id}', 'UserController@pending_user')->name('pending_user');
    // Route::post('company', 'CompanyController@store')->name('store');

    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::put('profile-update', 'SettingsController@updateProfile')->name('profile-update');
    Route::put('password-update', 'SettingsController@updatePassword')->name('password-update');
    Route::get('message', 'MessageController@index')->name('message');
    Route::post('send_message', 'MessageController@message')->name('send_message');
    Route::get('chat/{id}/{room}/{name}', 'LiveChatController@chat')->name('chat');
    Route::post('send_session_data', 'LiveChatController@send_session_data')->name('send_session_data');
    Route::post('send_session_record', 'LiveChatController@send_session_record')->name('send_session_record');
    Route::post('send_meeting_mins', 'LiveChatController@send_meeting_mins')->name('send_meeting_mins');
    Route::post('getEmpTimecard', 'WbsController@getEmpTimecard')->name('getEmpTimecard');
    // Route::post('send_session_data', function (Request $request) {
    //     return response()->json(['status' => $request->all()]);
    //     // dd("session data routs worked.");
    // });
    Route::post('create_project_form_req', 'ProjectController@create_project_form_req')->name('create_project_form_req');

// wbs

    Route::get('view_wbs/{id}', 'WbsController@show')->name('show');
    // Route::get('wbs/{id}', 'WbsController@update')->name('update');

    Route::resource('wbs', 'WbsController');

    // Route::get('wbs_add_hour/{id}', 'Admin\WbsController@wbs_add_hour')->name('wbs_add_hour');
    // Route::post('wbs_store_hour', 'Admin\WbsController@wbs_store_hour')->name('wbs_store_hour');
    // hasibul

    Route::resource('clinic', 'ClinicController');
    Route::resource('slot', 'SlotController');
    Route::resource('day', 'DayController');
    Route::get('followup_patient', 'Admin\DashboardController@followup_patient_list')->name('followup_patient');
    Route::get('new_patient', 'Admin\DashboardController@new_patient_list')->name('new_patient');
    Route::get('all_patient', 'Admin\DashboardController@all_patient_list')->name('all_patient');
    Route::get('emergency', 'Admin\DashboardController@emergency')->name('emergency');

    Route::get('set_appointment/{id}/doctor_slot/{doc_id}/{visit_date}', 'AppointmentController@doctorAvailbleSlot')->name('doctor_slot');

    Route::get('prescription_download/{id}', 'AppointmentController@prescriptionDownload')->name('prescription_download');

    Route::get('/file','FileController@index')->name('viewfile');
    Route::get('/file/upload','FileController@create')->name('formfile');
    Route::post('/file/upload/{id}','FileController@store')->name('uploadfile');
    Route::delete('/file/{id}','FileController@destroy')->name('deletefile');
    Route::get('/file/download/{id}','FileController@show')->name('downloadfile');
    Route::get('/file/email/{id}','FileController@edit')->name('emailfile');
    Route::post('/file/dropzone','FileController@dropzone')->name('dropzone');

    Route::get('/file','FileController@viewFile')->name('viewfile');
    Route::post('/file/upload','FileController@storeFile')->name('storefile');
    Route::get('/file/download/{id}','ProfileController@fileDownload')->name('downloadfile');
    
});
