<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;

session_start();


class SuperAdminController extends Controller
{
    //

    public function logout()
    {
    	Session::put('admin_name',null);
    	Session::put('admin_id',null);
    	Session::put('admin_email',null);
    	Session::flush();
    	return Redirect::to('/okAdminShod');

    }


    public static function AdminAuthCheck()
    {
        $admin_auth_id = Session::get('admin_id');
        if ($admin_auth_id) {
           return;
        }else
        {
            session::put('msg','جهت دسترسی به این بخش شما ابتدا باید وارد شوید ');
            return Redirect::to('/okAdminShod')->send();
        }
    }


    public static function UserAuthCheck()
    {
        $user_auth_id = Session::get('hotel_id');
        if ($user_auth_id) {
            return;
        }else
        {
            session::put('msg','برای دسترسی به این بخش ابتدا باید وارد شوید ');
            return Redirect::to('/userHotel')->send();
        }
    }
    
}
