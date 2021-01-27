<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesignationRequest;
use App\Models\designation;
use App\Models\onlineSync;
use App\Models\setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         
        $settings = setting::where('table_name','designations')->first();
        $settings->setting= json_decode(  json_decode(  $settings->setting,true),true);
        
                
                $dataArray=[
                    'settings'=>$settings,
                    'items' => designation::all(),
                    'page_name' => 'Designation',
                ];
        
        
                return view('employees.designation.index', compact('dataArray'));


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
    public function store(DesignationRequest $request)
    {
      

         $designation = designation::create($request->all());

         $this->onlineSync('designation','create',$designation->id);

        return redirect()->back()->withSuccess(['Successfully Created']);


        //  $designation = new designation;
        //  $designation->role = $request->role;
        //  $designation->description = $request->description;
        //  $designation->save();
        // return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function show(designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function edit(designation $designation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function update(DesignationRequest $request, designation $designation)
    {

        
        $designation->update($request->all());

        $this->onlineSync('designation','update',$designation->id);

        return redirect()->back()->withSuccess(['Successfully Updated']);


    //     $designation->role = $request->role;
    //     $designation->description = $request->description;
    //     $designation->save();
    //    return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function destroy(designation $designation)

    {
        $counts = $designation->employee->count();
        if( $counts != 0 ){
            return Redirect::back()->withErrors(["Can't delete.","Many Employee has this Designation. To delete it please change designation in Employee. " ]);
        }
        else{
            
            $designation->delete();

            $this->onlineSync('designation','delete',$designation->id);


            return Redirect::back()->withErrors(["Item Deleted" ]);
        }
    }
}
