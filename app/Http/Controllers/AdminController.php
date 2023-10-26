<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;
use App\Http\Controllers\SuperAdminController;
use File;
use Illuminate\Filesystem\Filesystem;

session_start();

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {



        if (session::get('admin_email')) {

            return View ('admin.dashboard')->with([]);
        }else
        {
        	// return View('admin.dashboard');
        	$admin_email = $request->admin_email;
        	$admin_password =md5($request->admin_password);

            if($request->isProf=='on'){


                $resutl = DB::table('tbl_prof')
                    ->where('prof_email',$admin_email)
                    ->where('prof_password',$admin_password)
                    ->first();

                session::put('isProf',true);

                if ($resutl) {
                    session::put('admin_name',$resutl->prof_name);
                    session::put('admin_id',$resutl->prof_id);
                    session::put('admin_email',$resutl->prof_email);
                    return Redirect('okAdminShod/dashboard');
                }
                else
                {

                    session::put('msg','ایمیل یا رمز شما اشتباه هست ');
                    return Redirect('/okAdminShod/');//admin login
                }
            }else{
                $resutl = DB::table('tbl_admin')
                    ->where('admin_email',$admin_email)
                    ->where('admin_password',$admin_password)
                    ->first();
                session::put('isProf',false);
                if ($resutl) {

                    session::put('admin_name',$resutl->admin_name);
                    session::put('admin_id',$resutl->admin_id);
                    session::put('admin_email',$resutl->admin_email);


                    return Redirect('okAdminShod/dashboard');


                }
                else
                {

                    session::put('msg','ایمیل یا رمز شما اشتباه هست ');
                    return Redirect('/okAdminShod/');//admin login
                }
            }



        }
        echo Session::get('isProf').'sdddddddddddd';
    }

    public function login()
    {
    	return View ('admin.login');
    }
    
      public function logout()
    {
    	Session::put('admin_name',null);
    
    	Session::put('admin_email',null);
        Session::put('isProf',null);
    	Session::flush();
    	return Redirect::to('/okAdminShod');

    }
    
}
