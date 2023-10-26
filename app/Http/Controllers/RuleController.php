<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Http\Controllers\SuperAdminController;

session_start();

class RuleController extends Controller
{
    public function edit()
    {
        
        
           
        $info = DB::table('tbl_info')->get();
    
        return View('admin.edit_rule')->with(['info'=>$info ]);

    }
     public function done_edit(Request $request)
    {
        $update_info['info_rule']=$request->rule;
         $update_info['info_about']=$request->about;
         $update_info['info_main_about']=$request->main_about;
         $update_info['info_cooperation']=$request->main_cooperation;
        
          $isUpdated = DB::table('tbl_info')
            ->update($update_info);
          session::put('msg', 'اطلاعات با موفقیت ویرایش شد');
          return Redirect::to('/okAdminShod/rule/edit');

    }
    
  
}
