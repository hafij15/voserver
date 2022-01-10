<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Designation;
use Auth;
use Brian2694\Toastr\Facades\Toastr;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin')){
            $company_name = Company::select('name','id')->orderBy('created_at', 'desc')->get();
            $designations = Designation::orderBy('created_at', 'desc')->get();
        }
        return view('admin.designation',compact('designations','company_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Designation::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'description' => $request->description
        ]);

        Toastr::success('Save successfully.');
        return redirect()->back();
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
        $designations = Designation::find($request->edit_id);  
        $designations->where('id', $request->edit_id)->update(array(
            'name' => $request->edit_name,
            'company_id' => $request->company_id,
            'description' => $request->edit_description,
        ));
        Toastr::success('Updated successfully.');
        return redirect()->back(); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
