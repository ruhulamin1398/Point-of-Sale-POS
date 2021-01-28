<?php

namespace App\Http\Controllers;

use App\Models\calculationAnalysisDaily;
use App\Models\calculationAnalysisMonthly;
use App\Models\calculationAnalysisYearly;
use App\Models\customer;
use App\Models\customerDueReceive;
use App\Models\onlineSync;
use App\Models\sellAnalysisDaily;
use App\Models\sellAnalysisMonthly;
use App\Models\sellAnalysisYearly;
use App\Models\setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class CustomerDueReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        
        if(! auth()->user()->hasPermissionTo('Customer Due Receive Page')){
            return abort(401);
        }
        $monthStart=Carbon::now()->format('Y-m-01 00:00:00');
        $monthEnd=Carbon::now()->format('Y-m-31 23:59:59');
        if( ! is_null($request->month)){
            $monthStart=Carbon::parse($request->month)->format('Y-m-01 00:00:00');
            $monthEnd=Carbon::parse($request->month)->format('Y-m-31 23:59:59');
        }
       // return compact('monthStart','monthEnd');
        $settings = setting::where('table_name', 'customer_due_receives')->first();
        $settings->setting = json_decode(json_decode($settings->setting, true), true);
        $dues =  customerDueReceive::where('created_at','>=',$monthStart)->where('created_at','<=',$monthEnd)->get();
        $dataArray = [
            'settings' => $settings,
            'items' =>$dues,
            'page_name' => 'Customer Due Receive',
        ];
         return view('due.customer.index',compact('dataArray'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        if(! auth()->user()->hasPermissionTo('Customer Due Receive Create Page')){
            return abort(401);
        }
        $roles = Role::all();
        return view('due.customer.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $due = new customerDueReceive;
        $customer = customer::find($request->customer_id);

        if($customer->due < $request->amount){
            return redirect()->back()->withErrors(['Due is less than Amount']);
        }

        $due->user_id = Auth::user()->id;
        $due->customer_id = $request->customer_id;
        $due->amount = $request->amount;
        $due->comment = $request->comment;
        $due->pre_due = $customer->due;
        $customer->due = $customer->due - $request->amount;
        $due->save();
        $customer->save();
        $this->sellAnalysis( $request->amount);

        $this->onlineSync('customerDueReceive','create',$due->id);


        $this->onlineSync('customer','update',$customer->id);


   
        return redirect(route('customer-due-receives.index'))->withSuccess(['Successfully Received']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\customerDueReceive  $customerDueReceive
     * @return \Illuminate\Http\Response
     */
    public function show(customerDueReceive $customerDueReceive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\customerDueReceive  $customerDueReceive
     * @return \Illuminate\Http\Response
     */
    public function edit(customerDueReceive $customerDueReceive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\customerDueReceive  $customerDueReceive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, customerDueReceive $customerDueReceive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\customerDueReceive  $customerDueReceive
     * @return \Illuminate\Http\Response
     */
    public function destroy(customerDueReceive $customerDueReceive)
    {
        //
    }
    
    public function sellAnalysis($amount){

        $daily_method_type = $monthly_method_type = $yearly_method_type = 'update';

        $month = Carbon::now()->format('Y-m-01');
        $date = Carbon::now()->format('Y-m-d');
        $year = Carbon::now()->format('Y');
        $sellDaily= sellAnalysisDaily::where('date',$date)->first();
        $sellMonthly= sellAnalysisMonthly::where('month',$month)->first();
        $sellYearly= sellAnalysisYearly::where('year',$year)->first();
        if(is_null($sellDaily)){
            $sellDaily= new sellAnalysisDaily;
            $sellDaily->date=$date;
            $daily_method_type = 'create';
        }
        if(is_null($sellMonthly)){
            $sellMonthly= new sellAnalysisMonthly;
            $sellMonthly->month=$month;
            $monthly_method_type = 'create';
        }
        if(is_null($sellYearly)){
            $sellYearly= new sellAnalysisYearly;
            $sellYearly->year=$year;
            $yearly_method_type = 'create';

        }
        $sellDaily->cash_received += $amount;	
        $sellMonthly->cash_received += $amount;
        $sellYearly->cash_received += $amount;

        $sellDaily->save();
        $sellMonthly->save();
        $sellYearly->save();

        
      $this->onlineSync('sellAnalysisDaily',$daily_method_type,$sellDaily->id);


      $this->onlineSync('sellAnalysisMonthly',$monthly_method_type,$sellMonthly->id);

        // $online_sync_monthly = new onlineSync;
        // $online_sync_monthly->model = 'App\Models\sellAnalysisMonthly';
        // $online_sync_monthly->action_type = $monthly_method_type;
        // $online_sync_monthly->reference_id = $sellMonthly->id;
        // $online_sync_monthly->save();



      $this->onlineSync('sellAnalysisYearly',$yearly_method_type,$sellYearly->id);
        
        // $online_sync_yearly = new onlineSync;
        // $online_sync_yearly->model = 'App\Models\sellAnalysisYearly';
        // $online_sync_yearly->action_type = $yearly_method_type;
        // $online_sync_yearly->reference_id = $sellYearly->id;
        // $online_sync_yearly->save();



    }

    
}
