<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use Auth;
use Brian2694\Toastr\Facades\Toastr;

class CompanyController extends Controller
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
        if(Auth::user()->hasRole('super-admin')){
            $companies = Company::orderBy('created_at', 'desc')->get();
        }
        return view('admin.company',compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     */
    
    public function store(Request $request)
    {
        // dd($request);
        Company::create([
            'name' => $request->name,
            'address' => $request->address,
            'descriptionn' => $request->descriptionn
        ]);
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {   
        // dd($request->edit_id);
        $company = Company::find($request->edit_id);  
        $company->where('id', $request->edit_id)->update(array(
            'name' => $request->edit_name,
            'address' => $request->edit_address,
            'descriptionn' => $request->edit_description,
        ));
        Toastr::success('Updated successfully.');
        return redirect()->back(); 
    }
}
