<?php

namespace App\Http\Controllers;

use App\Models\setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        // $a = '[{
        //     "componentDetails":{
        //         "title":"Due Paid List",
        //         "editTitle":"Edit Due Paid"
        //     },
        //     "routes":{
        //         "create":{
        //             "name":"supplier-due-pays.store",
        //             "link":"supplier-due-pays"
        //         },
        //         "update":{
        //             "name":"supplier-due-pays.update",
        //             "link":"supplier-due-pays"
        //         },
        //         "delete":{
        //             "name":"supplier-due-pays.destroy",
        //             "link":"supplier-due-pays"
        //         }
        //     },
        //     "fieldList":[{
                    
        //         "position":111,
    
        //         "create":"3",
        //         "read":"1",
        //         "update":"3",
        //         "require":"1",
    
        //        "input_type":"text",
        //        "name":"user_name",
        //        "title":"Reference",
    
    
        //        "database_name":"user_id"
        //     },{
                    
        //         "position":111,
    
        //         "create":"3",
        //         "read":"1",
        //         "update":"3",
        //         "require":"1",
    
        //        "input_type":"text",
        //        "name":"supplier",
        //        "title":"Supplier",
        //        "database_name":"supplier_id"
        //     },{
                    
        //         "position":111,
    
        //         "create":"3",
        //         "read":"1",
        //         "update":"3",
        //         "require":"0",
    
        //        "input_type":"number",
        //        "name":"amount",
        //        "title":"Amount",
        //        "database_name":"amount"
        //     },{
                    
        //         "position":111,
    
        //         "create":"3",
        //         "read":"1",
        //         "update":"3",
        //         "require":"0",
    
        //        "input_type":"number",
        //        "name":"pre_due",
        //        "title":"Previous Due",
        //        "database_name":"pre_due"
        //     },{
                    
        //         "position":111,
    
        //         "create":"3",
        //         "read":"1",
        //         "update":"3",
        //         "require":"0",
    
        //        "input_type":"text",
        //        "name":"comment",
        //        "title":"Comment",
        //        "database_name":"comment"
        //     },{
                    
        //         "position":111,
    
        //         "create":"3",
        //         "read":"1",
        //         "update":"3",
        //         "require":"0",
    
        //        "input_type":"text",
        //        "name":"time",
        //        "title":"Time",
        //        "database_name":"created_at"
        //     }
        //     ]
        // }]' ;

        $a= [
            "1" =>[
                 "Super Admin"=> 1, 
                 "Admin"=> 1, 
                 "Manager"=> 1, 
                 "Cashier"=> 1, 
                 "Staff"=> 1, 
             ],
             "2" =>[
                  "Super Admin"=> 1, 
                  "Admin"=> 1, 
                  "Manager"=> 1, 
                  "Cashier"=> 1, 
                  "Staff"=> 1, 
              ],
        ];
        
        
        // $setting = new setting;
        // $setting->setting = json_encode( $a);
        // $setting->table_name = 'categorized_products';
        // $setting->model = 'App\Models\Product';
        // $setting->save();
        // return  "Success";
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, setting $setting)
    {
        // echo "--------------";


        $data =  json_decode(json_decode($setting->setting, true), true);
        $fieldList = $data[0]['fieldList'];

        for ($i = 0; $i < count($fieldList); $i++) {
            $fieldName = $fieldList[$i]['name'];

            $fieldList[$i]['create'] = $request[$fieldName]['create'];
            $fieldList[$i]['read'] = $request[$fieldName]['read'];
            $fieldList[$i]['update'] = $request[$fieldName]['update'];
            $fieldList[$i]['position'] = $request[$fieldName]['position'];
        }

        usort($fieldList, function ($a, $b) {
            if ($a['position'] == $b['position']) {
                return 0;
            }
            return ($a['position'] < $b['position']) ? -1 : 1;
        });

        $data[0]['fieldList'] = $fieldList;
        $setting->setting = json_encode(json_encode($data));
        $setting->save();

        $this->onlineSync('setting','update',$setting->id);

        return;
        return $setting->setting;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(setting $setting)
    {
        //
    }
}
