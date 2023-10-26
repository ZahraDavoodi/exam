<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;

//session_start();

class CourseController extends Controller
{
    //
    public function all()
    {
    	$all_courses = DB::table('tbl_course')->get();
        return View('admin.all_courses')->with('all_courses',$all_courses);
  
    }
    public function add()
    {
   		return View('admin.add_course'); 	
    }

	public function save(Request $request)
    {
    	$data = array();

        $data['course_name'] = $request->course_name;
        $data['course_unit'] = $request->course_unit;
        
			if ($request->publication_status == 'on') {
    			$data['publication_status'] = 1;
    		}else
    		{
    			$data['publication_status'] = 0;
    		}

    		// Insert data to database
			if(DB::table('tbl_course')->insert($data))
       		 {
           	     session::put('msg','درس ذخیره شد');
           	     return Redirect::to('/okAdminShod/course/all'); 
       		 }else

      		  {
           	     session::put('msg','درس ذخیره نشد.');
           	     return Redirect::to('/okAdminShod/course/all'); 
     		   }

		}
	

 public function edit($course_id)
    {
        $data = DB::table('tbl_course')
            ->where('course_id', $course_id)
            ->get()->first();
            
   		return View('admin.edit_course')->with(['course_infos' => $data]); 	
    }

	public function done_edit(Request $request , $course_id)
    {
    	$update_info = array();
    	
        $update_info['course_name'] = $request->course_name;
        $update_info['course_unit'] = $request->course_unit;
     	
	    if ($request->publication_status == 'on') {
    			$update_info['publication_status'] = 1;
    		}else{
    			$update_info['publication_status'] = 0;
    		}
		
			// Insert data to database
			if(DB::table('tbl_course')->where('course_id',$course_id)->update($update_info))
       		 {
       		    
           	     session::put('msg','درس ذخیره شد');
           	     return Redirect::to('/okAdminShod/course/all'); 
       		 }else

      		  {
      		      
           	     session::put('msg','درس ذخیره نشد.');
           	     return Redirect::to('/okAdminShod/course/all'); 
     		   }

	}

  public function unactive($course_id)
    {
        SuperAdminController::AdminAuthCheck();
       // Make Publication_status =  0 
        DB::table('tbl_course')
        ->where('course_id',$course_id)
        ->update(['publication_status'=>0]);
        session::put('msg','وضعیت درس به حالت غیر فعال تغییر کرد ');
        return Redirect::to('okAdminShod/course/all');
    }

    public function active($course_id)
    {
        SuperAdminController::AdminAuthCheck();
       // Make Publication_status =  0 
        DB::table('tbl_course')
        ->where('course_id',$course_id)
        ->update(['publication_status'=>1]);
        session::put('msg','وضعیت درس به فعال تغییر کرد.');
        return Redirect::to('okAdminShod/course/all');
    }

    public function delete($course_id)
    {
        SuperAdminController::AdminAuthCheck();
            $isDeleted = DB::table('tbl_course')
            ->where('course_id',$course_id)
            ->delete();

            if ($isDeleted) {
                session::put('msg','درس با موفقیت حذف شد');
                return Redirect::to('/okAdminShod/course/all'); 
            }else
            {
                session::put('msg','امکان حذف این درس وجود ندارد');
                return Redirect::to('/okAdminShod/course/all');    
            }
    }

    public function select_ajax(Request $request)
    {
        if($request->ajax()){
            $cities = DB::table('tbl_city')->where(['course_id'=>$request->cat_id])->get();

            return response()->json(['cities'=>$cities]);
        }
    }
    public function select_ajax2(Request $request)
    {
        if($request->ajax()){
            $areas = DB::table('tbl_area')->where(['area_id'=>$request->cat_id])->get();

            return response()->json(['areas'=>$areas]);
        }
    }

}
