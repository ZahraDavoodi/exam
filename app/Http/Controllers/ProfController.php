<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Http\Controllers\SuperAdminController;
use Session as s;
use Mail;
use File;
use Illuminate\Filesystem\Filesystem;

include( public_path().'/jdf.php');

class ProfController extends Controller
{
    public function add()
    {
        SuperAdminController::AdminAuthCheck();
        $all_profs = DB::table('tbl_prof')->get();
        return View('admin.add_prof')->with(['all_profs'=>$all_profs]);
    }


    public function all()
    {
        SuperAdminController::AdminAuthCheck();
        $all_profs = DB::table('tbl_prof')->get();
        return View('admin.all_profs')->with('all_profs',$all_profs);

    }

    public function save(Request $request)
    {
        SuperAdminController::AdminAuthCheck();

        // add all new prof data in DB ::
        $data = array();
        $data['prof_name'] = $request->prof_name;
        $data['prof_phone'] = $request->prof_phone;

        $data['prof_email'] = $request->prof_email;

        $data['prof_password'] = md5($request->prof_password);
        $data['changePassword'] =substr(md5(uniqid(mt_rand(), true)), 0, 5);
        if ($request->publication_status == 'on') {
            $data['publication_status'] = 1;
        }else
        {
            $data['publication_status'] = 0;
        }


        if(DB::table('tbl_prof')->insert($data))
        {
            session::put('msg','مربی جدید با موفقیت اضافه شد');
            return Redirect::to('/okAdminShod/prof/add');
        }else{ echo 'false'; }

    }


    public function unactive($prof_id)
    {
        SuperAdminController::AdminAuthCheck();
        // Make Publication_status =  0
        DB::table('tbl_prof')
            ->where('prof_id',$prof_id)
            ->update(['publication_status'=>0]);
        session::put('msg','وضعیت مربی به حالت غیرفعال تغییر یافت.');
        return Redirect::to('okAdminShod/prof/all');
    }

    public function active($prof_id)
    {
        SuperAdminController::AdminAuthCheck();
        // Make Publication_status =  1
        DB::table('tbl_prof')
            ->where('prof_id',$prof_id)
            ->update(['publication_status'=>1]);
        session::put('msg','وضعیت مربی به حالت فعال تغییر یافت.');
        return Redirect::to('okAdminShod/prof/all');
    }

    public function edit($prof_id)
    {
        SuperAdminController::AdminAuthCheck();
        //echo $prof_id;
        $data =  DB::table('tbl_prof')
            ->where('prof_id',$prof_id)
            ->get()->first();
        $all_categories = DB::table('tbl_prof')->get();
        return View('admin.edit_prof')->with(['prof_infos'=>$data]);

    }
    public function done_edit(Request $request , $prof_id)
    {
        SuperAdminController::AdminAuthCheck();
        //echo $prof_id;

        $update_info = array();
        $update_info['prof_name']=$request->prof_name;
        $update_info['prof_phone']=$request->prof_phone;

        
        $data =  DB::table('tbl_prof')
            ->where('prof_id',$prof_id)
            ->get()->first();
        $old_password=$data->prof_password;
echo $old_password;
        echo '<br/>';
        echo md5($request->prof_password);
        if($old_password==md5($request->prof_password))
        {
                $update_info['prof_password']=$old_password;
        }
        else
            {
                $update_info['prof_password']=md5($request->prof_password);
            }


        $update_info['prof_email'] = $request->prof_email;

        if ($request->publication_status == 'on') {
            $update_info['publication_status'] = 1;
        }else
        {
            $update_info['publication_status'] = 0;
        }

        $isUpdated = DB::table('tbl_prof')
            ->where('prof_id',$prof_id)
            ->update($update_info);
        if ($isUpdated) {
            session::put('msg','مربی مورد نظر با موفقیت ویرایش شد.');
        return Redirect::to('/okAdminShod/prof/all');
        }else
        {
            session::put('msg','ویرایش مربی به درستی انجام نشد');
            return Redirect::to('/okAdminShod/prof/all');
        }
    }
    public function delete($prof_id)
    {
        SuperAdminController::AdminAuthCheck();
        $isDeleted = DB::table('tbl_prof')
            ->where('prof_id',$prof_id)
            ->delete();

        if ($isDeleted) {
            session::put('msg','مربی با موفقیت حذف شد.');
            return Redirect::to('/okAdminShod/prof/all');
        }else
        {
            session::put('msg','مربی حذف نشد.');
            return Redirect::to('/okAdminShod/prof/all');
        }
    }
   public function auth()
    {
    	return View('pages.prof_auth');
    }

    public function register(Request $request)
    {
        $resutl = DB::table('tbl_prof')->where('prof_email', $request->prof_email)->get();
        if (count($resutl) > 0) {

            session::put('msg', 'قبلا با این آدرس ایمیل عضو شدید، لطفا وارد شوید.');
            return Redirect::to('/');
        } else {

            $data = array();
            $data['prof_name'] = $request->prof_name;
            $data['prof_email'] = $request->prof_email;
            $data['prof_phone'] = $request->prof_phone;

            $data['changePassword'] = substr(md5(uniqid(mt_rand(), true)), 0, 5);


            if ($request->prof_password == $request->re_password) {
                if ($request->prof_password == $request->re_password) {
                    $data['prof_password'] = md5($request->prof_password);
                } else {

                    session::put('msg', 'رمز عبور با تکرار آن همخوانی ندارد');
                    return Redirect::back();
                }

                $insertedId = DB::table('tbl_prof')
                    ->insertGetId($data);
                echo $insertedId;
                if ($insertedId > 0) {
                    session::put('prof_id', $insertedId);

                    $header = "parsa-perfume.ir";
                    $email = $data['prof_email'];

                    $body = "جهت فعالسازی حساب خود از لینک زیر وارد شوید" . "https://okshod.com/activate/" . $insertedId . "/" . $data['changePassword'] . "کد فعال سازی شما جهت ثبت نام " . " " . $data['changePassword'];


                    $data = array('name' => $body);


                    Mail::send('mails.mail', $data, function ($message) use ($email) {
                        $message->to($email, 'parsa-perfume.ir')->subject('okShod روش تورهای داخلی و خارجی-');
                        $message->from('info@parsa-perfume.ir', 'okShod');
                    });
                    session::put('msg', 'ایمیلی برای شما ارسال شده است. جهت تکمیل عضویت ،از طریق ایمیل ارسالی، اقدام نمایید');
                    return Redirect::back();
                } else {
                    session::put('msg_modal', 'کاربر جدید اضافه نشد.');
                    return Redirect::back();
                }
            }
        }
    }

    public function login(Request $request)
    {
            $prof_email = $request->prof_email;
            $prof_password =md5($request->prof_password);
            
            
            $resutl = DB::table('tbl_prof')
            ->where('prof_email',$prof_email)
            ->where('prof_password',$prof_password)
            ->first();

            if ($resutl) {
                
                session::put('prof_email',$resutl->prof_email);
                session::put('prof_name',$resutl->prof_name);
                session::put('prof_id',$resutl->prof_id);
                session::put('prof_type','prof');
                session::put('msg','با موفقیت وارد شدید');
                 return Redirect::back();
           
            }else
            {

                session::put('msg','ایمیل یا رمز شما اشتباه هست ');
                
                return Redirect::back();
            }
    }

    public function logout()
    {
        if (session::get('prof_id')) {
            session::put('prof_id',null);
            session::put('prof_email',null);
            session::put('prof_name',null);

            session::put('msg','با موفقیت خارج شدید');
            return Redirect::back();
        }
    }
   
     public function ajax_activate(Request $request)
    {

              $prof_id = $request->prof_id;

                
                $c=DB::table('tbl_prof')->where('prof_id',$prof_id)->first();
                if($c->changePassword==$prof_code)
                {
                    $update=DB::table('tbl_prof')->where('prof_id',$prof_id)->update(['publication_status'=>1]);
                     return response()->json(['error'=>0,'c'=>'کد وارد شده درست است']);
                }else
                {
                     return response()->json(['error'=>1,'c'=>'کد وارد شده درست نیست']);
                }

            return response()->json(['error'=>1,'c1'=>$c1]);
     

    }
   
    ///prof panel
    
     public function dashboard(Request $request)
    {
      
      $keywords=' پنل کاربری مشتری';
      $seo_description='این صفحه مربوط به پنل کاربران عضو در سایت میباشد.';
      $seo_title='پنل کاربری مشتری';
      
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);
        if (session::get('prof_email')) {
            
            
            $all_properties=DB::table('tbl_property')->where('prof_id',session::get("prof_id"))->orderby('property_updatedat','DESC')->orderby('property_updatedat','DESC')->paginate(10);
            $all_transactiontypes = DB::table('tbl_transactiontype')->get();
            $all_propertytypes = DB::table('tbl_propertytype')->get();
            $all_propertyattrs = DB::table('tbl_property_attr')->get();
            $all_states = DB::table('tbl_state')->get();
            $all_cities = DB::table('tbl_city')->get();
            $all_areas = DB::table('tbl_area')->get();

            return View ('pages.prof_panel')->with(['prof'=>session::get('prof_email'),'prof_id'=>session::get('prof_id'),'all_properties'=>$all_properties,'all_transactiontypes' =>$all_transactiontypes,'all_propertytypes' =>$all_propertytypes,'all_propertyattrs' =>$all_propertyattrs, 'all_states' =>$all_states,'all_cities' =>$all_cities,'all_areas' => $all_areas]);
        }else
        {  
           session::put('msg','قبل از ورود به پنل مدیریت، باید نام کاربری و رمز عبور را از بخش ورود/عضویت  وارد نمایید ');
           return Redirect('/');//admin login
        }
    }

 public function save_propertyByprof(Request $request)
 {
        // add all new property data in DB ::
        $data = array();
        $attr = array();
        $data['property_name'] = $request->property_name;
        $data['property_address'] = $request->property_address;
        $data['property_map'] = $request->property_map;
        $data['property_image'] = $request->property_image;
        $data['alt_image'] = $request->alt_image;
        $data['property_description'] = $request->property_description;
        $data['tt_id'] = $request->trans_id;
        $data['pt_id'] = $request->pt_id;
        $data['prof_id'] = $request->prof_id;
        $data['state_id'] = $request->state_id;
        $data['city_id'] = $request->city_id;
        $data['area_id'] = $request->area_id;
        $data['slug']=uniqid(mt_rand(), true);
        $data['property_age'] = $request->property_age;
        $data['property_size'] = $request->property_size;
        $data['property_substruction'] =0;
        $data['property_price'] = $request->property_price;
        $data['property_rent'] = $request->property_rent;
        $data['property_roomNum'] = $request->property_roomNum;

        $data['seo_description'] = '';
        $data['keywords'] = '';

        $data['property_createdat'] = date('Y-m-d H:i:s');
        $data['property_updatedat'] = date('Y-m-d H:i:s');

//        include 'jdf.php';
//        $now = jdate('Y-m-d', '', '', '', 'en');
//        $now = (string)$now;
//        $today = $now;


        $data['property_showDate']=   date('y-m-d H:i:s', strtotime('+30 days'));
        $data['publication_status'] = 0;
        if (empty($request->property_description)) {
            $data['property_description'] = '';
        }




        //add to hotl-attribute

        if ($request->attr_parking == 'on') {
            $attr['attr_parking'] = 1;
        } else {
            $attr['attr_parking'] = 0;
        }

        if ($request->attr_elevator == 'on') {
            $attr['attr_elevator'] = 1;
        } else {
            $attr['attr_elevator'] = 0;
        }
        if ($request->attr_license == 'on') {
            $attr['attr_license'] = 1;
        } else {
            $attr['attr_license'] = 0;
        }
        if ($request->attr_warehouse == 'on') {
            $attr['attr_warehouse'] = 1;
        } else {
            $attr['attr_warehouse'] = 0;
        }


        $property_id = DB::table('tbl_property')->insertGetId($data);
        if ($property_id > 0) {
            $attr['property_id'] = $property_id;
            $image = $request->file('property_image');

            $ext = strtolower($image->getClientOriginalExtension());
            $data['property_image'] = '';



            if ($image) {
                $image_name = $property_id;
                $ext = strtolower($image->getClientOriginalExtension());
                $image_full_name = $image_name . "." . $ext;
                $upload_url = 'images/properties/';
                $image_url = $upload_url . $image_full_name;
                $isUploaded =  $image->move($upload_url, $image_full_name);;
                if ($isUploaded) {
                    $property_image = $image_url;
                    $src=$image_url;
                    $dest="images/properties/thumbnail/".$property_id.".jpg";

                    echo '<br/>'.$src.'---'.$dest;
                    $desired_width="260";
                    /* read the source image */
                    $source_image = imagecreatefromjpeg($src);
                    $res =$source_image;
                    if ( $res === false)
                        $res = @imagecreatefromgif($src);
                    if ( $res === false)
                        $res = @imagecreatefrompng($src);
                    if ( $res === false) {
                        $err[] = 'Cannot load file as JPEG, GIF or PNG!';
                    }
                    $width = imagesx($source_image);
                    $height = imagesy($source_image);

                    /* find the "desired height" of this thumbnail, relative to the desired width  */
                    $desired_height = floor($height * ($desired_width / $width));

                    /* create a new, "virtual" image */
                    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
                    /* copy source image at a resized size */
                    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

                    /* create the physical thumbnail image to its destination */
                    imagejpeg($virtual_image, $dest);
                } else {
                    $property_image = null;
                }
                DB::table('tbl_property')->where('property_id', $property_id)->update(['property_image' => $property_image]);
            }

            $hgs = array();
            $hgs['property_id']=$property_id;

            $gallery1 = $request->file('property_gallery');
            $count = count($gallery1);
            $alt=$request->alt;
            echo 'alt is:';
            var_dump($alt);

            if(isset($gallery1) && $request->hasFile('property_gallery'))
            {

                $count = count($gallery1);


                $all_hgs = DB::table('tbl_property_gallery')
                    ->where('property_id',$property_id)
                    ->get();

                $count2 = count($all_hgs);
                echo 'count2 is'.$count2;

                for ($i = 1; $i <= $count; $i++) {

                    $gall = $gallery1[$i];
                    echo $gall.'<br>';
                    $alt1= $alt[$i];
                    $image_name =  $count2+$i;
                    $ext = strtolower($gall->getClientOriginalExtension());
                    $image_full_name = $image_name . "." . $ext;
                    $upload_url = 'images/properties/gallery/'.$property_id .'/';

                    if (!file_exists($upload_url)) {
                        File::makeDirectory($upload_url);
                    }
                    $image_url = $upload_url . $image_full_name;
                    echo $image_url.'<br>';
                    $isUploaded = $gall->move($upload_url, $image_full_name);
                    if ($isUploaded) {
                        $hgs['property_gallery'] = $image_url;
                        $hgs['alt'] = $alt1;

                    } else {
                        $hgs['property_gallery'] = null;
                        $hg_id=0;
                    }

                    $hg_id = DB::table('tbl_property_gallery')->insertGetId($hgs);

                }
            }


            if (DB::table('tbl_property_attr')->insert($attr)) {

                session::put('msg', 'آگهی جدید ذخیره شد. لطفا جهت انجام ادامه مراحل هزینه آگهی را پرداخت کنید.');
                return Redirect::to('/prof/panel');
            }

        } else {
            session::put('msg', 'ملک جدید با موفقیت اضافه نشد');
            return Redirect::to('/prof/panel');
        }

    }

 public function edit_propertyByprof(Request $request , $property_id)
 {
     $data =  DB::table('tbl_property')
         ->where('property_id',$property_id)
         ->get()->first();


     $all_profs = DB::table('tbl_prof')->get();
     $all_transactiontypes = DB::table('tbl_transactiontype')->get();
     $all_propertytypes = DB::table('tbl_propertytype')->get();
     $all_propertyattrs = DB::table('tbl_property_attr')->where('property_id',$property_id)->get();
     $all_propertygallery = DB::table('tbl_property_gallery')->where('property_id',$property_id)->get();
     $all_states = DB::table('tbl_state')->get();
     $all_cities = DB::table('tbl_city')->get();
     $all_areas = DB::table('tbl_area')->get();
     return View('pages.edit_property')->with(['property'=>$data,'all_profs'=>$all_profs,'all_transactiontypes' =>$all_transactiontypes,'all_propertytypes' =>$all_propertytypes,'all_propertyattrs' =>$all_propertyattrs,'all_propertygallery'=>$all_propertygallery ,'all_states' =>$all_states,'all_cities' =>$all_cities,'all_areas' => $all_areas]);

 }

 public function done_edit_propertyByprof(Request $request , $property_id)
    {

        //echo $property_id;
        $pg_id=1;
        $update_info = array();
        $attr = array();

        $update_info['property_name'] = $request->property_name;
        $update_info['property_address'] = $request->property_address;
        $update_info['property_image'] = $request->property_image;
        $update_info['alt_image'] = $request->alt_image;
        $update_info['property_description'] = $request->property_description;
        $update_info['tt_id'] = $request->trans_id;
        $update_info['pt_id'] = $request->pt_id;
        $update_info['prof_id'] = session::get('prof_id');
        $update_info['state_id'] = $request->state_id;
        $update_info['city_id'] = $request->city_id;
        $update_info['area_id'] = $request->area_id;
        $update_info['property_age'] = $request->property_age;
        $update_info['property_size'] = $request->property_size;

        $update_info['property_price'] = $request->property_price;
        $update_info['property_rent'] = $request->property_rent;
        $update_info['property_roomNum'] = $request->property_roomNum;




        $update_info['property_description'] = $request->property_description;


        $update_info['property_map']=$request->property_map;

        $image = $request->file('property_image');
        echo 'image is'.$image.'<br/>';

        //      $validator = Validator::make(
        //           ['image' => $image],
        //           ['image' => ['required', 'size:5000']]
        //         );

        // $messages = $validator->messages();
        // echo 'message is:'.$messages;
        // $failed = $validator->failed();

        if ($image) {
            $image_name = $property_id;
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name . "." . $ext;
            $upload_url = 'images/properties/';
            $image_url = $upload_url . $image_full_name;
            echo $image_url;


            $isUploaded = $image->move($upload_url, $image_full_name);
            echo '<br>'.'is uploaded'.$isUploaded.'<br>';
            if ($isUploaded) {
                $update_info['property_image'] = $image_url;
                $src=$image_url;
                $dest="images/properties/thumbnail/".$property_id.".jpg";

                echo '<br/>'.$src.'---'.$dest;
                $desired_width="260";
                /* read the source image */
                $source_image = imagecreatefromjpeg($src);
                $res =$source_image;
                if ( $res === false)
                    $res = @imagecreatefromgif($src);
                if ( $res === false)
                    $res = @imagecreatefrompng($src);
                if ( $res === false) {
                    $err[] = 'Cannot load file as JPEG, GIF or PNG!';
                }
                $width = imagesx($source_image);
                $height = imagesy($source_image);

                /* find the "desired height" of this thumbnail, relative to the desired width  */
                $desired_height = floor($height * ($desired_width / $width));

                /* create a new, "virtual" image */
                $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
                /* copy source image at a resized size */
                imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

                /* create the physical thumbnail image to its destination */
                imagejpeg($virtual_image, $dest);
            } else{
                $update_info['property_image'] = null;
            }
        }


        $isUpdated = DB::table('tbl_property')
            ->where('property_id',$property_id)
            ->update($update_info);

//        $isDeleted = DB::table('tbl_property_gallery')
//            ->where('property_id',$property_id)
//            ->delete();


        $pgs = array();
        $pgs['property_id']=$property_id;
        $gallery1 = $request->file('property_gallery');

        $alt=$request->alt;
        var_dump($alt);

        if(isset($gallery1) && $request->hasFile('property_gallery'))
        {
            $count = count($gallery1);


            $all_pgs = DB::table('tbl_property_gallery')
                ->where('property_id',$property_id)
                ->get();

            $count2 = count($all_pgs);
            echo 'count2 is'.$count2;

            for ($i = 1; $i <= $count; $i++) {

                $gall = $gallery1[$i];
                echo 'gal is '.$gall.'<br>';
                $alt1= $alt[$i];
                $image_name =  $count2+$i;
                $ext = strtolower($gall->getClientOriginalExtension());
                $image_full_name = $image_name . "." . $ext;
                $upload_url = 'images/properties/gallery/'.$property_id .'/';

                if (!file_exists($upload_url)) {
                    File::makeDirectory($upload_url);
                }
                $image_url = $upload_url . $image_full_name;
                echo $image_url.'<br>';
                $isUploaded = $gall->move($upload_url, $image_full_name);
                if ($isUploaded) {
                    $pgs['property_gallery'] = $image_url;
                    $pgs['alt'] = $alt1;
                } else {
                    $pgs['property_gallery'] = null;
                    $pg_id=0;
                }

                $pg_id = DB::table('tbl_property_gallery')->insertGetId($pgs);

            }
        }


        $old_alt=$request->old_alt;

        if(isset($old_alt))
        {
            for($j=1;$j<=count($old_alt);$j++){

                if(isset($old_alt[$j]))
                {
                    foreach($old_alt[$j] as $key=>$value)
                    {
                        $pg_id=$key;
                        $pgs1['alt']=$value;


                        $isUpdated2 = DB::table('tbl_property_gallery')
                            ->where('pg_id',$pg_id)
                            ->update($pgs1);


                    }
                }



            }
        }



        //add to hotl-attribute



        if ($request->attr_parking == 'on') {
            $attr['attr_parking'] = 1;
        } else {
            $attr['attr_parking'] = 0;
        }

        if ($request->attr_elevator == 'on') {
            $attr['attr_elevator'] = 1;
        } else {
            $attr['attr_elevator'] = 0;
        }

        if ($request->attr_warehouse == 'on') {
            $attr['attr_warehouse'] = 1;
        } else {
            $attr['attr_warehouse'] = 0;
        }

        if ($request->attr_license == 'on') {
            $attr['attr_license'] = 1;
        } else {
            $attr['attr_license'] = 0;
        }

        $isUpdated1 = DB::table('tbl_property_attr')
            ->where('property_id',$property_id)
            ->update($attr);

        if ($isUpdated||$isUpdated1||$isUpdated2) {
            session::put('msg','ویرایش ملک به درستی انجام شد');
            return Redirect::to('/prof/panel');
        }else {
            session::put('msg','ویرایش ملک به درستی انجام نشد');
            return Redirect::to('/prof/panel');
        }
    }


}
