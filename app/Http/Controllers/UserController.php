<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;

session_start();

class userController extends Controller
{
    public function dashboard(Request $request)
    {
        if (session::get('user_email')) {

            return View ('user.dashboard');
        }else
        {  
        	// return View('user.dashboard');	
        	$user_email = $request->user_email;
        	$user_password =md5($request->user_password);
        	echo $user_email .'-'.$user_password;

        	$resutl = DB::table('tbl_user')
        	->where('user_email',$user_email)
        	->where('user_password',$user_password)
        	->first();

        	if ($resutl) {
        		session::put('user_name',$resutl->user_name);
        		session::put('user_id',$resutl->user_id);
                session::put('hotel_id',$resutl->hotel_id);
                session::put('user_email',$resutl->user_email);
        		return Redirect('userHotel/dashboard');
        	}else
        	{
        			session::put('msg','ایمیل یا رمز شما اشتباه هست ');
        			return Redirect('userHotel/');//user login
        	}

        }
    }

    public function login()
    {
    	return View ('user.login');
    }
    
      public function logout()
    {
    	Session::put('user_name',null);
    	Session::put('user_id',null);
    	Session::put('user_email',null);
    	Session::flush();
    	return Redirect::to('/userHotel');

    }
    
    
      public function changePassword()
    {
       
    	
    	  $user_id=session::get('user_id');
    	  
        $user= DB::table('tbl_hotel')->where('hotel_id',$user_id)->first();
        
       return View('user.changePassWord')->with(['user' => $user]);

    }
    
       public function done_changePassword(Request $request)
    {
        
    	
    	 $ajancy_id=session::get('ajancy_id');
    	   $ajancy= DB::table('tbl_ajancy')->where('ajancy_id',$ajancy_id)->first();
    	 $ajancy_email=$request->ajancy_email;
    	 $ajancy_pass=md5($request->pass);
    	 
    	 if($request->pass == $request->rePass)
    	 {
    	     $ajancy1= DB::table('tbl_ajancy')->where('ajancy_email',$ajancy_email)->update(['ajancy_password'=>$ajancy_pass]);
        
            session::put('msg', 'رمز عبور با موفقیت تغییر کرد ');
            return View('user.changePassWord')->with(['ajancy' => $ajancy]);
    	 }else
    	 {
    	      session::put('msg', 'رمز عبور جدید با تکرار آن همخوانی ندارد ');
            return View('user.changePassWord')->with(['ajancy' => $ajancy]);
    	 }
    }
    
}
