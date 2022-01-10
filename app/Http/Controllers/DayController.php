<?php

namespace App\Http\Controllers;

use App\Day;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $day = Day::latest()->get();     
        return view('admin.day',compact('day'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create_day');
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
        $data= Day::create([
            'name' => $request->name,
            'created_at' => new DateTime()
            
        ]);
        
        Toastr::success('Day Inserted Successfully :)','success');
        return redirect('day');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function show(Day $day)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function edit(Day $day)
    {
        return view('admin.edit_day',compact('day'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Day $day)
    {
        date_default_timezone_set("Asia/Dhaka");
        $day->name = $request->name;
        $day->updated_at = new DateTime();

        $day->save();
        
        Toastr::success('Day Updated Successfully :)','success');
        return redirect('day');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function destroy(Day $day)
    {
        $id = $day->id;
        Day::destroy($id);
        Toastr::success('Day Deleted successfully.');
        return redirect()->back(); 
    }
}
