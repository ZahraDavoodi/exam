<?php
namespace App\Http\Controllers;
use App;
use phpDocumentor\Reflection\Types\Null_;
use Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Http\Controllers\SuperAdminController;
use Mail;
include( public_path().'/jdf.php');

session_start();

class HomeController extends Controller
{
    public function index() {
        $now=jdate('Y-m-d','','','','en');
        $now=(string) $now;
        $today=$now;

        $keywords="";
        $seo_description="";
        $seo_title="سامانه آزمون ";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);
        $all_sliders = DB::table('tbl_slider')->where('publication_status',1)->orderBy('slider_id','DESC')->get();
        $info=DB::table('tbl_info')->first();
        $all_fields=DB::table('tbl_field')->get();
        $all_classes=DB::table('tbl_class')->get();
        return View('pages.home')->with([
            'all_sliders'=>$all_sliders,
            'all_classes'=>$all_classes,
            'all_fields'=>$all_fields,
            'info'=>$info,
        ]);

    }

    public function rules(){
        //menu
        $info = DB::table('tbl_info')->get();

        $keywords="";
        $seo_description="صفحه قوانین و مقررات سایت ملک";
        $seo_title="قوانین و مقررات";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);


        return View('pages.rules')->with([
            'info'=>$info
        ]);

    }
    public function contact(){

        $keywords="";
        $seo_description=" صفحه ارتباط با ما سایت ملک";
        $seo_title="ارتباط با ما";         session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('pages.contact')->with([

        ]);

    }
    public function about(){
        //menu



        $info= DB::table('tbl_info')->get();


        $keywords="";
        $seo_description="صفحه درباره ما سایت ملک";
        $seo_title="درباره ما";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('pages.about')->with([
            'info'=>$info
        ]);

    }
    public function cooperation(){


        $info= DB::table('tbl_info')->get();

        $keywords="";
        $seo_description="صفحه فرصتهای شغلی سایت ملک";
        $seo_title="فرصت های شغلی";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('pages.coorporatin')->with([
            'info'=>$info
        ]);

    }
    public function help(){
        //menu

        $keywords="";
        $seo_description="صفحه راهنمای سایت";
        $seo_title="راهنمای سایت";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);


        return View('pages.help');

    }
    public function showChangePasswordForm(){

        $keywords="";
        $seo_description="تغییر رمز عبور";
        $seo_title="تغییر رمز عبور";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('auth.passwords.email');

    }
    public function ChangePassword(Request $request){

        $email = $request->email;
        $customer = DB::table('tbl_customer')->where('customer_email',$email)->get();
        //$ajancy = DB::table('tbl_ajancy')->where('ajancy_email',$email)->get();
        if(count($customer)==1 ){

            foreach ($customer as $c)
            {
                $code=$c->changePassword;

                $subject = " لینک بازیابی رمز عبور ";
                $header = "reserveproperty.com";
                $body ="جهت تغییر رمز عبور میتوانید از لینک زیر وارد شوید"."https://reserveproperty.com/recovery"."/customer/".$c->customer_id."/".$code;
                $data = array('name'=>$body);
                Mail::send('mails.mail', $data, function($message) use ($c) {
                    $message->to($c->customer_email, 'reserveproperty.com')->subject('فروش ملکهای داخلی و خارجی -');
                    $message->from('info@reserveproperty.com','reserveproperty');
                });

                session::put('msg','پیامی به ایمیل شما ارسال شد. جهت ادامه مراحل ایمیلتان را بررسی کنید');
                return View('auth.passwords.email');

            }

        }
//        if(count($ajancy)==1 ){
//
//            foreach ($ajancy as $c)
//            {
//                $code=$c->changePassword;
//
//                $subject = " لینک بازیابی رمز عبور ";
//                $header = "reserveproperty.com";
//                $body ="جهت تغییر رمز عبور میتوانید از لینک زیر وارد شوید"."https://reserveproperty.com/recovery"."/ajancy/".$c->ajancy_id."/".$code;
//                $data = array('name'=>$body);
//                Mail::send('mails.mail', $data, function($message) use ($c) {
//                    $message->to($c->ajancy_email, 'reserveproperty.com')->subject('فروش ملکهای داخلی و خارجی -');
//                    $message->from('info@reserveproperty.com','reserveproperty');
//                });
//
//                session::put('msg','پیامی به ایمیل شما ارسال شد. جهت ادامه مراحل ایمیلتان را بررسی کنید');
//                return View('auth.passwords.email');
//
//            }
//
//        }
        else
        {
            session::put('msg','آدرس ایمیل شما در سیستم یافت نشد. لطفا آدرس ایمیل معتبری وارد نمایید');
            return View('auth.passwords.email');
        }

    }
    public function recovery(Request $request)  {

        $keywords="";
        $seo_description="بازیابی رمز عبور";
        $seo_title="بازیابی رمز عبور";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        $code=$request->code;
        $customer_id=$request->customer_id;
        $type=$request->type;

        if($type=='customer'){
            $customer = DB::table('tbl_customer')->where(['changePassword'=>$code,'customer_id'=>$customer_id])->get();

            if(count($customer)==1)
            {
                foreach($customer as $customer)
                {
                    $change=md5(uniqid(mt_rand()));
                    $result=DB::table('tbl_customer')->where('customer_id', $customer->customer_id)->update(['changePassword' => $change]);
                    if($result==1)
                    {
                        return View('pages.changePassword')->with([
                            'email'=>$customer->customer_email,
                            'type'=>'customer',
                        ]);
                    }

                }


            }else
            {

                session::put('msg','لینک وارد شده درست نمیباشد. لطفا جهت تغییر رمز عبور مجددا تلاش کنید');
                return View('auth.passwords.email');
            }
        }
//        else{
//            $ajancy = DB::table('tbl_ajancy')->where(['changePassword'=>$code,'ajancy_id'=>$customer_id])->get();
//
//            if(count($ajancy)==1)
//            {
//                foreach($ajancy as $a)
//                {
//                    $change=md5(uniqid(mt_rand()));
//                    $result=DB::table('tbl_ajancy')->where('ajancy_id', $a->ajancy_id)->update(['changePassword' => $change]);
//                    if($result==1)
//                    {
//                        return View('pages.changePassword')->with([
//
//                            'email'=>$a->ajancy_email,
//                            'type'=>'ajancy',
//
//                        ]);
//                    }
//
//                }
//
//
//            }else
//            {
//
//                session::put('msg','لینک وارد شده درست نمیباشد. لطفا جهت تغییر رمز عبور مجددا تلاش کنید');
//                return View('auth.passwords.email');
//
//            }
//        }

    }
    public function updatePass(Request $request)  {

        if($request->customer_password ==$request->customer_repassword)
        {
            if($request->type=='customer'){


                $u = DB::table('tbl_customer')->where('customer_email',$request->customer_email)->update(['customer_password'=>md5($request->customer_password)]);

                if($u==1)
                {

                    session::put('msg','رمز عبور شما با موفقیت تغییر کرد');

                    if(isset($request->id)){
                        $all_transactiontypes = DB::table('tbl_transactiontype')->get();
                        $all_propertytypes = DB::table('tbl_propertytype')->get();
                        $all_propertyattrs = DB::table('tbl_property_attr')->get();
                        $all_states = DB::table('tbl_state')->get();
                        $all_cities = DB::table('tbl_city')->get();
                        $all_areas = DB::table('tbl_area')->get();
                        $all_properties=DB::table('tbl_property')->where('customer_id',session::get("customer_id"))->orderby('property_updatedat','DESC')->paginate(10);
                        return View ('pages.customer_panel')->with(['customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id'),'all_properties'=>$all_properties,'all_transactiontypes' =>$all_transactiontypes,'all_propertytypes' =>$all_propertytypes,'all_propertyattrs' =>$all_propertyattrs, 'all_states' =>$all_states,'all_cities' =>$all_cities,'all_areas' => $all_areas,'customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id')]);
                    }
                    return View('pages.changePassword')->with([ 'email'=>$request->customer_email,'type'=>'customer' ]);

                }else
                {

                    session::put('msg','رمز عبور شما با موفقیت تغییر نکرد. لطفا مجددا تلاش کنید');
                    if(isset($request->id)){

                        $all_properties=DB::table('tbl_property')->where('customer_id',session::get("customer_id"))->orderby('property_updatedat','DESC')->paginate(10);
                        $all_transactiontypes = DB::table('tbl_transactiontype')->get();
                        $all_propertytypes = DB::table('tbl_propertytype')->get();
                        $all_propertyattrs = DB::table('tbl_property_attr')->get();
                        $all_states = DB::table('tbl_state')->get();
                        $all_cities = DB::table('tbl_city')->get();
                        $all_areas = DB::table('tbl_area')->get();
                        return View ('pages.customer_panel')->with(['customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id'),'all_properties'=>$all_properties,'all_transactiontypes' =>$all_transactiontypes,'all_propertytypes' =>$all_propertytypes,'all_propertyattrs' =>$all_propertyattrs, 'all_states' =>$all_states,'all_cities' =>$all_cities,'all_areas' => $all_areas,'customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id'),'customer'=>session::get('customer_email')]);


                    }
                    return View('pages.changePassword')->with(['email'=>$request->customer_email,'type'=>'customer' ]);
                }
            }
//            else{
//                $u = DB::table('tbl_ajancy')->where('ajancy_email',$request->customer_email)->update(['ajancy_password'=>md5($request->customer_password)]);
//
//                if($u==1)
//                {
//
//                    session::put('msg','رمز عبور شما با موفقیت تغییر کرد');
//
//                    return View('pages.changePassword')->with([ 'email'=>$request->customer_email,'type'=>'ajancy' ]);
//
//                }
//                else {
//
//                    session::put('msg','رمز عبور شما با موفقیت تغییر نکرد. لطفا مجددا تلاش کنید');
//                    return View('pages.changePassword')->with(['email'=>$request->customer_email, 'type'=>'ajancy']);
//                }
//            }

        }else
        {
            if($request->type=='customer'){

                if(isset($request->id)){


                    session::put('msg','رمز عبور با تکرار آن یکسان نیست. مجددا تلاش کنید');

                    $customer=DB::table('tbl_customer')->where('customer_id',$request->id)->first();
                    $all_properties=DB::table('tbl_property')->where('customer_id',$request->id)->orderby('property_updatedat','DESC')->paginate(10);
                    $all_transactiontypes = DB::table('tbl_transactiontype')->get();
                    $all_propertytypes = DB::table('tbl_propertytype')->get();
                    $all_propertyattrs = DB::table('tbl_property_attr')->get();
                    $all_states = DB::table('tbl_state')->get();
                    $all_cities = DB::table('tbl_city')->get();
                    $all_areas = DB::table('tbl_area')->get();
                    return View ('pages.customer_panel')->with(['customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id'),'all_properties'=>$all_properties,'all_transactiontypes' =>$all_transactiontypes,'all_propertytypes' =>$all_propertytypes,'all_propertyattrs' =>$all_propertyattrs, 'all_states' =>$all_states,'all_cities' =>$all_cities,'all_areas' => $all_areas,'customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id'),'customer'=>session::get('customer_email'),  'type'=>'customer']);

                }else{


                    session::put('msg','رمز عبور با تکرار آن یکسان نیست. مجددا تلاش کنید');
                    return View('pages.changePassword')->with([
                        'email'=>$request->customer_email, 'type'=>'customer','email'=>$request->customer_email,'customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id'),'type'=>'customer'  ]);
                }
            }
            else{

                session::put('msg','رمز ورودی با تکرار آن برابر نیست ');
                return View('pages.changePassword')->with(['email'=>$request->customer_email, 'type'=>'ajancy']);
            }
        }
    }
    public function activate(Request $request){
        $keywords="";
        $seo_description="فعال سازی حساب کاربری";
        $seo_title="فعال سازی حساب کاربری";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);



        $code=$request->code;
        $customer_id=$request->customer_id;
        $customer = DB::table('tbl_customer')->where(['changePassword'=>$code,'customer_id'=>$customer_id])->get();


        if(count($customer)==1)
        {
            foreach($customer as $customer)
            {
                if($customer->publication_status==0)
                {
                    $result=DB::table('tbl_customer')->where('customer_id', $customer->customer_id)->update(['publication_status' => 1]);
                    if($result==1)
                    {
                        $msg='حساب شما با موفقیت فعال شد. جهت ورود از دکمه زیر استفاده کنید';
                        return View('pages.activate')->with(['msg'=>$msg]);
                    }
                }else
                {

                    return View('pages.activate')->with([

                        'msg'=>'حساب شما فعال است. برای استفاده از امکانات سایت ، وارد شوید'
                    ]);
                }
            }
        }
        else{

            return View('pages.activate')->with(['msg'=>'لینک شما اشتباه است.برای هماهنگی با مدیر وب سایت ارتباط برقرار کنید.']);
        }

    }

    function sitemap(){
        $properties = DB::table('tbl_exam')->orderBy('exam_id', 'desc')->get();
        $content= View('sitemap')->with(['properties'=>$properties]);
        return Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }
}
