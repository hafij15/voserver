<?php

namespace App\Http\Controllers;

use App\File;
use App\SubTask;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $files = File::orderBy('created_at', 'DESC')->paginate(30);
        return view('file.index', ['files' => $files], ['subTasks' => $subTasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('file.dropzone');
    }

    public function dropzone(Request $request)
    {
        $file = $request->file('file');
        File::create([
            'title' => $file->getClientOriginalName(),
            'description' => 'Upload with dropzone.js',
            'path' => $file->store('public/storage'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $files = $request->file('file');
        foreach ($files as $file) {
            File::create([
                'title' => $file->getClientOriginalName(),
                'description' => '',
                'path' => $file->store('public/storage'),
            ]);
        }
        return redirect('/file')->with('success', 'File telah diupload');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dl = File::find($id);
        return Storage::download($dl->path, $dl->title);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fl = File::find($id);
        $data = array('title' => $fl->title, 'path' => $fl->path);
        try {
            Mail::send('emails.attachment', $data, function ($message) use ($fl) {
                $message->to('contact@virtualdr.com.bd', 'Virtual Doctor')->subject('You file has been uploaded successfully for Virtual Doctor!');
                $message->attach(storage_path('app/' . $fl->path));
                $message->from('tedirghazali@gmail.com', 'Tedir Ghazali');
            });
        } catch (\Swift_TransportException $transportExp) {
            //   dd($transportExp->getMessage());
        }
        return redirect('/file')->with('success', 'File attachment has been sent to your email');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = File::find($id);
        Storage::delete($del->path);
        $del->delete();
        return redirect('/file');
    }

    public function downloadfile($id)
    {
        $dl = File::find($id);
        return Storage::download($dl->path, $dl->title);
    }

    public function viewFile()
    {
        $subTasks = SubTask::all();
        return view('file.view', compact('subTasks'));
    }

    // public function getSubTaskName($id){
    //     $subTasks = SubTask::latest()
    //                 ->where('sub_task_id',$id)
    //                 ->get();
    // }

    public function storeFile(Request $request)
    {
        //dd(Auth()->user()->id);
        //dd($request);
        //$subTasks = SubTask::all();
        //->where('sub_task_id',$id)

        //dd($subTasks);
        $files = $request->file('file');
        //$subTaskID = $request->project_name_select;
        //dd($subTaskID);
        foreach ($files as $file) {

            $data = File::create([
                'user_id' => Auth()->user()->id,
                'sub_task_id' => $request->project_name_select,
                'title' => $file->getClientOriginalName(),
                'description' => '',
                'path' => $file->store('public/storage'),
                'type' => 'null',
            ]);
            //dd($data);
        }
        Toastr::success('File uploaded successfully.');
        return redirect('/dashboard')->with('success', 'File uploaded successfully.');
        //Toastr::success('File uploaded successfully.');
        //return view('admin.dashboard',compact('subTasks'));
        //return view('admin.dashboard')->with(['subTasks' => $subTasks]);
    }
}
