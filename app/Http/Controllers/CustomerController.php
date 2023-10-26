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

class CustomerController extends Controller
{
    public function add()
    {
        SuperAdminController::AdminAuthCheck();
        $all_customers = DB::table('tbl_customer')->get();
        $all_classes = DB::table('tbl_class')->get();
        $all_fields = DB::table('tbl_field')->get();
        return View('admin.add_customer')->with(['all_customers'=>$all_customers,'all_classes'=>$all_classes,'all_fields'=>$all_fields]);
    }


    public function all()
    {
        SuperAdminController::AdminAuthCheck();
        $all_customers = DB::table('tbl_customer')->get();
        return View('admin.all_customers')->with('all_customers',$all_customers);

    }

    public function save(Request $request)
    {
        SuperAdminController::AdminAuthCheck();

        // add all new customer data in DB ::
        $data = array();
        $data['customer_name'] = $request->customer_name;
        $data['customer_phone'] = $request->customer_phone;
        $data['customer_code'] = $request->customer_code;
        $data['class_id'] = $request->class_id;
        $data['field_id'] = $request->field_id;
        $data['customer_lname'] = $request->customer_lname;
        $data['customer_email'] = $request->customer_email;

        $data['customer_password'] = md5($request->customer_password);
        $data['changePassword'] =substr(md5(uniqid(mt_rand(), true)), 0, 5);
        if ($request->publication_status == 'on') {
            $data['publication_status'] = 1;
        }else
        {
            $data['publication_status'] = 0;
        }


        $insertedId = DB::table('tbl_customer')
            ->insertGetId($data);
        $image = $request->file('customer_image');

        $ext = strtolower($image->getClientOriginalExtension());
        $data['customer_image'] = '';


        if ($image) {
            $image_name = $insertedId;
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name . "." . $ext;
            $upload_url = 'images/customers/';
            $image_url = $upload_url . $image_full_name;
            $isUploaded =  $image->move($upload_url, $image_full_name);;
            if ($isUploaded) {
                $customer_image = $image_url;
                $src=$image_url;
                $dest="images/cusotmers/thumbnail/".$insertedId.".jpg";

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
                $customer_image = null;
            }
            DB::table('tbl_customer')->where('customer_id', $insertedId)->update(['customer_image' => $customer_image]);
        }

        if($insertedId)
        {
            session::put('msg','دانشجو جدید با موفقیت اضافه شد');
            return Redirect::to('/okAdminShod/customer/add');
        }else{ echo 'false'; }

    }


    public function unactive($customer_id)
    {
        SuperAdminController::AdminAuthCheck();
        // Make Publication_status =  0
        DB::table('tbl_customer')
            ->where('customer_id',$customer_id)
            ->update(['publication_status'=>0]);
        session::put('msg','وضعیت دانشجو به حالت غیرفعال تغییر یافت.');
        return Redirect::to('okAdminShod/customer/all');
    }
    public function active($customer_id)
    {
        SuperAdminController::AdminAuthCheck();
        // Make Publication_status =  1
        DB::table('tbl_customer')
            ->where('customer_id',$customer_id)
            ->update(['publication_status'=>1]);
        session::put('msg','وضعیت دانشجو به حالت فعال تغییر یافت.');
        return Redirect::to('okAdminShod/customer/all');
    }
    public function edit($customer_id)
    {
        SuperAdminController::AdminAuthCheck();
        //echo $customer_id;
        $data =  DB::table('tbl_customer')
            ->where('customer_id',$customer_id)
            ->get()->first();

        $all_classes = DB::table('tbl_class')->get();
        $all_fields = DB::table('tbl_field')->get();
        return View('admin.edit_customer')->with(['customer_infos'=>$data,'all_classes'=>$all_classes,'all_fields'=>$all_fields]);

    }
    public function done_edit(Request $request , $customer_id)
    {
        SuperAdminController::AdminAuthCheck();
        //echo $customer_id;

        $update_info = array();
        $update_info['customer_name']=$request->customer_name;
        $update_info['customer_phone']=$request->customer_phone;
        $update_info['customer_code']=$request->customer_code;
        $update_info['class_id']=$request->class_id;
        $update_info['field_id'] = $request->field_id;
        $update_info['customer_lname'] = $request->customer_lname;
        $data =  DB::table('tbl_customer')
            ->where('customer_id',$customer_id)
            ->get()->first();
        $old_password=$data->customer_password;
        echo $old_password;
        echo '<br/>';
        echo md5($request->customer_password);
        if($old_password==md5($request->customer_password))
        {
            $update_info['customer_password']=$old_password;
        }
        else
        {
            $update_info['customer_password']=md5($request->customer_password);
        }


        $update_info['customer_email'] = $request->customer_email;

        if ($request->publication_status == 'on') {
            $update_info['publication_status'] = 1;
        }else
        {
            $update_info['publication_status'] = 0;
        }

        $image = $request->file('customer_image');

        if ($image) {
            $image_name = $customer_id;
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name . "." . $ext;
            $upload_url = 'images/customers/';
            $image_url = $upload_url . $image_full_name;
            $isUploaded = $image->move($upload_url, $image_full_name);
            if ($isUploaded) {
                $update_info['customer_image'] = $image_url;
            } else {
                $update_info['customer_image'] = null;
            }
        }

        $isUpdated = DB::table('tbl_customer')
            ->where('customer_id', $customer_id)
            ->update($update_info);

        if ($isUpdated) {
            session::put('msg','دانشجو مورد نظر با موفقیت ویرایش شد.');
            return Redirect::to('/okAdminShod/customer/all');
        }else
        {
            session::put('msg','ویرایش دانشجو به درستی انجام نشد');
            return Redirect::to('/okAdminShod/customer/all');
        }
    }
    public function delete($customer_id)
    {
        SuperAdminController::AdminAuthCheck();
        $isDeleted = DB::table('tbl_customer')
            ->where('customer_id',$customer_id)
            ->delete();

        if ($isDeleted) {
            session::put('msg','دانشجو با موفقیت حذف شد.');
            return Redirect::to('/okAdminShod/customer/all');
        }else
        {
            session::put('msg','دانشجو حذف نشد.');
            return Redirect::to('/okAdminShod/customer/all');
        }
    }
    public function auth()
    {
        return View('pages.customer_auth');
    }

    public function register(Request $request)
    {
        $resutl = DB::table('tbl_customer')->where('customer_code', $request->customer_code)->get();
        if (count($resutl) > 0) {

            session::put('msg', 'قبلا با این کد ملی عضو شدید، لطفا وارد شوید.');
            return Redirect::to('/');
        }
        else {



            $data = array();
            $data['customer_name'] = $request->customer_name;
            $data['customer_phone'] = $request->customer_phone;
            $data['customer_code'] = $request->customer_code;
            $data['class_id'] = $request->class_id;
            $data['field_id'] = $request->field_id;
            $data['customer_lname'] = $request->customer_lname;
            $data['customer_email'] = $request->customer_email;

            $data['customer_password'] = md5($request->customer_password);
            $data['changePassword'] =substr(md5(uniqid(mt_rand(), true)), 0, 5);


            if ($request->customer_password == $request->re_password) {
                if ($request->customer_password == $request->re_password) {
                    $data['customer_password'] = md5($request->customer_password);
                }
                else {

                    session::put('msg', 'رمز عبور با تکرار آن همخوانی ندارد');
                    return Redirect::back();
                }

                $image = $request->file('customer_image');

                $ext = strtolower($image->getClientOriginalExtension());
                $data['customer_image'] = '';


                $insertedId = DB::table('tbl_customer')
                    ->insertGetId($data);

                if ($image) {
                    $image_name = $insertedId;
                    $ext = strtolower($image->getClientOriginalExtension());
                    $image_full_name = $image_name . "." . $ext;
                    $upload_url = 'images/customers/';
                    $image_url = $upload_url . $image_full_name;
                    $isUploaded =  $image->move($upload_url, $image_full_name);;
                    if ($isUploaded) {
                        $customer_image = $image_url;
                        $src=$image_url;
                        $dest="images/customers/thumbnail/".$insertedId.".jpg";

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
                        $customer_image = null;
                    }
                    DB::table('tbl_customer')->where('customer_id', $insertedId)->update(['customer_image' => $customer_image]);
                }


                echo $insertedId;
                if ($insertedId > 0) {
                    session::put('customer_id', $insertedId);

                    $header = "parsa-perfume.ir";
                    $email = $data['customer_email'];

                    $body = "جهت فعالسازی حساب خود از لینک زیر وارد شوید" . "http://it-maskoob.ir/activate/" . $insertedId . "/" . $data['changePassword'] . "کد فعال سازی شما جهت ثبت نام " . " " . $data['changePassword'];


                    $data = array('name' => $body);


                    Mail::send('mails.mail', $data, function ($message) use ($email) {
                        $message->to($email, 'it-maskoob.ir')->subject('okShod روش تورهای داخلی و خارجی-');
                        $message->from('info@it-maskoob.ir', 'it-maskoob');
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
        $customer_code = $request->customer_code;
        $customer_password =md5($request->customer_password);


        $resutl = DB::table('tbl_customer')
            ->where('customer_code',$customer_code)
            ->where('customer_password',$customer_password)
            ->first();

        if ($resutl) {

            session::put('customer_email',$resutl->customer_email);
            session::put('customer_name',$resutl->customer_name.' '.$resutl->customer_lname);
            session::put('customer_id',$resutl->customer_id);
            session::put('customer_type','customer');
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
        if (session::get('customer_id')) {
            session::put('customer_id',null);
            session::put('customer_email',null);
            session::put('customer_name',null);

            session::put('msg','با موفقیت خارج شدید');

            $info=DB::table('tbl_info')->first();
            $all_fields=DB::table('tbl_field')->get();
            $all_classes=DB::table('tbl_class')->get();
            $all_sliders=DB::table('tbl_slider')->get();
            return View('pages.home')->with([
                'all_sliders'=>$all_sliders,
                'all_classes'=>$all_classes,
                'all_fields'=>$all_fields,
                'info'=>$info,
            ]);
            // return Redirect::back();
        }
    }
    public function ajax_activate(Request $request)
    {

        $customer_id = $request->customer_id;
        $customer_code = $request->code;

        $c=DB::table('tbl_customer')->where('customer_id',$customer_id)->first();
        if($c->changePassword==$customer_code)
        {
            $update=DB::table('tbl_customer')->where('customer_id',$customer_id)->update(['publication_status'=>1]);
            return response()->json(['error'=>0,'c'=>'کد وارد شده درست است']);
        }else
        {
            return response()->json(['error'=>1,'c'=>'کد وارد شده درست نیست']);
        }

        return response()->json(['error'=>1,'c1'=>$c1]);


    }
    ///customer panel
    public function dashboard(Request $request)
    {

        $keywords=' پنل کاربری مشتری';
        $seo_description='این صفحه مربوط به پنل کاربران عضو در سایت میباشد.';
        $seo_title='پنل کاربری مشتری';

        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);



        $customer_id=session::get('customer_id');
        $customer_email=session::get('customer_email');
        $all_customers=DB::table('tbl_customer')->where('customer_id',$customer_id)->first();
        $all_profs=DB::table('tbl_prof')->get();
        $all_exams=DB::table('tbl_exam')->where('class_id',$all_customers->class_id)->get();
        $all_fields=DB::table('tbl_field')->get();
        $all_classes=DB::table('tbl_class')->get();
        if (session::get('customer_email')) {
            return View ('pages.customer_panel')->with(['customer_id'=>$customer_id,'customer_email'=>$customer_email,'all_exams'=>$all_exams,'all_profs'=>$all_profs,'all_fields'=>$all_fields,'all_classes'=>$all_classes]);
        }else
        {
            session::put('msg','قبل از ورود به پنل مدیریت، باید نام کاربری و رمز عبور را از بخش ورود/عضویت  وارد نمایید ');
            return Redirect('/');//admin login
        }
    }
    public function runExam(Request $request,$exam_id){
        $customer_id=session::get('customer_id');
        $questions=DB::table('tbl_questions')->where('exam_id',$exam_id)->get();

        $exam=DB::table('tbl_exam')->where('exam_id',$exam_id)->first();
        $customer=DB::table('tbl_customer')->where('customer_id',$customer_id)->first();
        $field=DB::table('tbl_field')->where('field_id',$exam->field_id)->first();
        $course=DB::table('tbl_course')->where('course_id',$exam->course_id)->first();
        $class=DB::table('tbl_class')->where('class_id',$exam->class_id)->first();

        $answers=DB::table('tbl_answer')->where('exam_id',$exam_id)->where('customer_id',$customer_id)->get();

        $all_classes=DB::table('tbl_class')->get();
        $all_fields=DB::table('tbl_field')->get();

        return View ('pages.customer_questions')->
        with([
            'answers'=>$answers,
            'questions'=>$questions,
            'exam'=>$exam,
            'customer_id'=>$customer_id,
            'field'=>$field,
            'customer'=>$customer,
            'course'=>$course,
            'class'=>$class,
            'all_classes'=>$all_classes,
            'all_fields'=>$all_fields,
        ]);
    }
    public function saveExam(Request $request)
    {
        //var_dump($request);
        $data=array();
        $now=jdate('Y-m-d H:i:s','','','','en');
        $now=(string) $now;

        $answer = $request->answer;
        $q_id = $request->q_id;
        $data['answer_date']=$now;
        $data['customer_id']=session::get('customer_id');
        $customer_email=session::get('customer_email');
        $data['exam_id']=$request->exam_id;

        $count = count($answer);
        $q1_id=0;
        if($count!=0){
            $answers=DB::table('tbl_answer')->where('exam_id',$request->exam_id)->where('customer_id',$data['customer_id'])->get();

            if(count($answers)==0){
                for ($i = 0; $i < $count; $i++) {
                    $data['answer_answer'] = $answer[$i+1];
                    $data['q_id'] = $q_id[$i+1];
                    $q1_id = DB::table('tbl_answer')->insertGetId($data);
                }
            }else{
                $answers=DB::table('tbl_answer')->where('exam_id',$request->exam_id)->where('customer_id',$data['customer_id'])->delete();
                for ($i = 0; $i < $count; $i++) {
                    $data['answer_answer'] = $answer[$i+1];
                    $data['q_id'] = $q_id[$i+1];
                    $q1_id = DB::table('tbl_answer')->insertGetId($data);
                }
            }


        }



        $file = $request->file('answer_file');
        $isUploaded=0;
        if($file){
            //Move Uploaded File
            $destinationPath = 'exam_files';
            $fileName = $data['customer_id'].'-'.$data['exam_id'].'.'.$request->file('answer_file')->extension();
            $isUploaded=$file->move($destinationPath,$fileName);
        }

        $exam=DB::table('tbl_exam')->get();
        $all_profs=DB::table('tbl_prof')->get();
        $all_classes=DB::table('tbl_class')->get();
        $all_fields=DB::table('tbl_field')->get();

        if($isUploaded!=0 || $q1_id !=0){
            session::put('msg','جواب شما ثبت شد.');
            return View('pages.customer_panel')->with(['all_exams'=>$exam,'all_profs'=>$all_profs,'customer_id'=>$data['customer_id'],'customer_email'=>$customer_email,'all_classes'=>$all_classes,'all_fields'=>$all_fields]);
        }
    }

    public function saveExam_ajax(Request $request)
    {
        if($request->ajax()){
            $data=array();
            $now=jdate('Y-m-d H:i:s','','','','en');
            $now=(string) $now;

            $answer = $request->answer;
            $q_id = $request->q_id;
            $data['answer_date']=$now;
            $data['customer_id']=session::get('customer_id');
            $customer_email=session::get('customer_email');
            $data['exam_id']=$request->exam_id;

            $count = count($answer);
            $q1_id=0;
            if($count!=0){
                $answers=DB::table('tbl_answer')->where('exam_id',$request->exam_id)->where('customer_id',$data['customer_id'])->get();

                if(count($answers)==0){
                    for ($i = 0; $i < $count; $i++) {
                        $data['answer_answer'] = $answer[$i+1];
                        $data['q_id'] = $q_id[$i+1];
                        $q1_id = DB::table('tbl_answer')->insertGetId($data);
                    }
                }else{
                    $answers=DB::table('tbl_answer')->where('exam_id',$request->exam_id)->where('customer_id',$data['customer_id'])->delete();
                    for ($i = 0; $i < $count; $i++) {
                        $data['answer_answer'] = $answer[$i+1];
                        $data['q_id'] = $q_id[$i+1];
                        $q1_id = DB::table('tbl_answer')->insertGetId($data);
                    }
                }


            }



            $file = $request->file('answer_file');
            $isUploaded=0;
            if($file){
                //Move Uploaded File
                $destinationPath = 'exam_files';
                $fileName = $data['customer_id'].'-'.$data['exam_id'].'.'.$request->file('answer_file')->extension();
                $isUploaded=$file->move($destinationPath,$fileName);
            }

            $exam=DB::table('tbl_exam')->get();
            $all_profs=DB::table('tbl_prof')->get();
            $all_classes=DB::table('tbl_class')->get();
            $all_fields=DB::table('tbl_field')->get();

            if($isUploaded!=0 || $q1_id !=0){
                session::put('msg','جواب شما ثبت شد.');
                return response()->json(['data'=>'درخواست ثبت شد']);
            }


        }
        //var_dump($request);

    }
}
