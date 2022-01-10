<?php

namespace App\Http\Controllers;

use App\Slot;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slot = Slot::latest()->get();     
        return view('admin.slot',compact('slot'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create_slot');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Dhaka");
        $start_time = date('d-m-Y h:i:s A', strtotime($request->start_time));
        $end_time = date('d-m-Y h:i:s A', strtotime($request->end_time));

        if($start_time < $end_time)
        {
            
                $data= Slot::create([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'created_at' => new DateTime()
                
            ]);
            
            Toastr::success('Schedule Inserted Successfully :)','success');
            return redirect('slot');
        }
        else
        {
            Toastr::warning('Enter valid end time :)','unsuccess');
            return redirect()->back(); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function show(Slot $slot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function edit(Slot $slot)
    {
        return view('admin.edit_slot',compact('slot'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slot $slot)
    {
        date_default_timezone_set("Asia/Dhaka");
        $start_time = date('d-m-Y h:i:s A', strtotime($request->start_time));
        $end_time = date('d-m-Y h:i:s A', strtotime($request->end_time));

        if($start_time < $end_time)
        {

            $slot->start_time = $request->start_time;
            $slot->end_time = $request->end_time;
            $slot->updated_at = new DateTime();

            $slot->save();
            
            Toastr::success('Schedule Updated Successfully :)','success');
            return redirect('slot');
        }
        else
        {
            Toastr::warning('Enter valid end time :)','unsuccess');
            return redirect()->back(); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slot $slot)
    {
        $id = $slot->id;
        Slot::destroy($id);
        Toastr::success('Slot Deleted successfully.');
        return redirect()->back(); 
    }
}
