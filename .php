<?php

namespace App\Http\Controllers;
use App;
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


        $now=jdate('Y/m/d');
        $now=(string) $now;
        $num_a=array('0','1','2','3','4','5','6','7','8','9');
        $key_a=array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        $now=str_replace($key_a,$num_a,$now);
        $today=$now;

        $keywords="تور داخلی,تور خارجی,تور طبیعتگردی,غار نوردی,جنگل نوردی,کوه نوردی,تور های یکروزه,تور های چند روزه,تور دریاچه عروس,تور دریاچه حلیمه جان,تور دریاچه شورمست,تور کویر مصر,تور کویر مرنجاب,تور ماسال,تور کویر ابوزیدآباد,تور کویر یزد,okshod,اوکی شد";
        $seo_description=" | okshod مرجع تبلیغات آنلاین تو و فروش انواع تور های داخلی ، خارجی ، طبیعتگردی ، incoming و ...";
        $seo_title="مجری تورهای داخلی و خارجی";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('category_parent1',0)->where('publication_status',1)->get();

        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();

        $all_stayTime= DB::table('tbl_staytime')->where('publication_status',1)->get();
        $all_ajancies = DB::table('tbl_ajancy')->where('publication_status',1)->get();
        $all_airlines= DB::table('tbl_airline')->where('publication_status',1)->get();
        $all_travels= DB::table('tbl_traveltype')->where('publication_status',1)->get();
        $all_tours= DB::table('tbl_tour')->where('publication_status',1)->where('tour_startShow','=<',$today)->where('tour_endShow','>=',$today)->where('tour_draft',0)->get();
        $latest_tour1=DB::table('tbl_tour_show')->where('plan_id',3)->get();
        $latest_tour=DB::table('tbl_tour')->where('publication_status',1)->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0)->orderBy('tour_views','DESC')->limit(10)->get();
        $all_populars=DB::table('tbl_popular')->where('publication_status',1)->get();
        $all_hotels= DB::table('tbl_hotel')->where('publication_status',1)->get();
        $all_hrs= DB::table('tbl_hotel_room')->orderBy('hr_ms_price','ASC')->get();
        $all_attractions= DB::table('tbl_attraction')->where('publication_status',1)->get();
        $all_sliders = DB::table('tbl_slider')->where('publication_status',1)->get();
        $latestTable_tour=DB::table('tbl_tour')->where('publication_status',1)->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0)->orderBy('tour_update_date','DESC')->limit(10)->get();
        $lastSecond_tour=DB::table('tbl_tour')->where('publication_status',1)->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0)->where('tour_lastSecond',1)->orderBy('tour_update_date','DESC')->limit(10)->get();
        $all_desinitions=DB::table('tbl_desinition')->where('publication_status',1)->orderBy('des_name','ASC')->get();

        $info=DB::table('tbl_info')->first();

        $rates=array();
        foreach($latestTable_tour as $at)
        {
            $tour_id=$at->tour_id;

            $rate_value=0;
            $sum=0;
            //rate value
            $rate1= DB::table('tbl_tour_rate')->where('tour_id',$tour_id)->get();
            if(count($rate1)>0) {
                $sum=0;
                foreach($rate1 as $r1)
                {
                    $sum+=$r1->rate_value;
                }
                $avg=$sum/count($rate1);
                array_push($rates,$avg);
            }
            else {
                array_push($rates,0);
            }
        }

        $l_rates=array();
        foreach ($lastSecond_tour as $at)
        {
            $tour_id=$at->tour_id;

            $rate_value=0;
            $sum=0;
            //rate value
            $rate1= DB::table('tbl_tour_rate')->where('tour_id',$tour_id)->get();
            if(count($rate1)>0) {
                $sum=0;
                foreach($rate1 as $r1)
                {
                    $sum+=$r1->rate_value;
                }
                $avg=$sum/count($rate1);
                array_push($l_rates,$avg);
            }
            else {
                array_push($l_rates,0);
            }
        }


        return View('pages.home')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=>$cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'all_stayTime'=>$all_stayTime,
            'all_ajancies'=>$all_ajancies,
            'all_airlines'=>$all_airlines,
            'all_travels'=>$all_travels,
            'all_hotels'=>$all_hotels,
            'all_hrs'=>$all_hrs,
            'all_tours'=>$all_tours,
            'latest_tour'=>$latest_tour,
            'latestTable_tour'=>$latestTable_tour,
            'lastSecond_tour'=> $lastSecond_tour,
            'all_attractions'=>$all_attractions,
            'all_sliders'=>$all_sliders,
            'all_populars'=>$all_populars,
            'info'=>$info,
            'rate_value'=>$rates,
            'l_rate_value'=>$l_rates,
            'all_desinitions'=>$all_desinitions

        ]);

    }
    public function  tour_info($slug){

        $now=jdate('Y/m/d');
        $now=(string) $now;
        $num_a=array('0','1','2','3','4','5','6','7','8','9');
        $key_a=array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        $now=str_replace($key_a,$num_a,$now);
        $today=$now;

        $tour = DB::table('tbl_tour')->where('slug',$slug)->get();

        if(count($tour)>0){


            $keywords='';
            $seo_description='';
            $seo_title='';
            foreach($tour as $t)
            {
                $keywords=$t->keywords;
                $seo_description=$t->seo_description;
                $seo_title=$t->tour_name;
                $tour_id=$t->tour_id;
                $ajancy_id=$t->ajancy_id;

            }
            $result = DB::table('tbl_tour')->where('tour_id',$tour_id)->increment('tour_views');

            session::put('keywords',$keywords);
            session::put('seo_description',$seo_description);
            session::put('seo_title',$seo_title);

            $all_tours = DB::table('tbl_tour')->where('publication_status',1)->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0)->get();
            $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
            $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
            $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
            $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();
            $all_airlines= DB::table('tbl_airline')->where('publication_status',1)->get();
            $all_hotels= DB::table('tbl_hotel')->where('publication_status',1)->get();
            $all_hrs= DB::table('tbl_hotel_room')->orderBy('hr_ms_price','ASC')->get();
            $all_hrs1= DB::table('tbl_hotel_room')->where('tour_id',$tour_id)->get();
            $all_galleries= DB::table('tbl_tour_gallery')->where('tour_id',$tour_id)->get();
            $tour_ajancy= DB::table('tbl_ajancy')->where('ajancy_id',$ajancy_id)->first();
            $all_customers=DB::table('tbl_customer')->get();
            $all_comments= DB::table('tbl_comment')->where('tour_id',$tour_id)->where('publication_status',1)->get();
            $all_desinitions= DB::table('tbl_desinition')->get();
            $rate = DB::table('tbl_order')->where('customer_id',Session::get('customer_id'))->where('tour_id',$tour_id)->get();
            // do rate
            $your_rate='none';
            if(count($rate)>0){
                $rate1= DB::table('tbl_tour_rate')->where('tour_id',$tour_id)->where('customer_id',Session::get('customer_id'))->get();
                if(count($rate1)>0)
                {
                    foreach($rate1 as $r2)
                    {
                        $your_rate=$r2->rate_value;
                    }

                }else
                {
                    $your_rate=0;
                }
            }

            $rate_value=0;

            //rate value
            $rate1= DB::table('tbl_tour_rate')->where('tour_id',$tour_id)->get();
            if(count($rate1)>0) {
                $sum=0;
                foreach($rate1 as $r1)
                {
                    $sum+=$r1->rate_value;
                }
                $avg=$sum/count($rate1);
                $avg=$avg;
            }
            else {
                $avg=0;
            }

            return View('pages.tour_info')->with([
                'all_tours'=>$all_tours,
                'all_categories'=>$all_categories,
                'cat_parent1'=> $cat_parent1,
                'all_hoteCategories'=>$all_hoteCategories,
                'cat_hotelParent1'=>$cat_hotelParent1,
                'all_airlines'=>$all_airlines,
                'all_hotels'=>$all_hotels,
                'all_hrs'=>$all_hrs,
                'all_hrs1'=>$all_hrs1,
                'tour'=>$tour,
                'all_galleries'=>$all_galleries,
                'tour_ajancy'=>$tour_ajancy,
                'all_comments'=>$all_comments,
                'all_desinitions'=>$all_desinitions,
                'all_customers'=>$all_customers,
                'if_rate'=>$your_rate,
                'rate_value'=>$avg,
                'counter_num'=>count($rate1)

            ]);
        }else
        {
            $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
            $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
            $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
            $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();
            return View('pages.tour_info')->with([
                'tour'=>0,
                'all_categories'=>$all_categories,
                'cat_parent1'=> $cat_parent1,
                'all_hoteCategories'=>$all_hoteCategories,
                'cat_hotelParent1'=>$cat_hotelParent1,

            ]);
        }
    }
    public function tours($slug){


        $now=jdate('Y/m/d');
        $now=(string) $now;
        $num_a=array('0','1','2','3','4','5','6','7','8','9');
        $key_a=array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        $now=str_replace($key_a,$num_a,$now);
        $today=$now;
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();

        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();


        //search box
        $all_stayTime= DB::table('tbl_staytime')->where('publication_status',1)->get();
        $all_ajancies = DB::table('tbl_ajancy')->where('publication_status',1)->get();
        $all_airlines= DB::table('tbl_airline')->where('publication_status',1)->get();
        $all_travels= DB::table('tbl_traveltype')->where('publication_status',1)->get();
        $all_desinitions=DB::table('tbl_desinition')->where('publication_status',1)->orderBy('des_name','ASC')->get();


        //table tour
        $cat_id='';
        $cat = DB::table('tbl_category')->where('publication_status',1)->where('slug',$slug)->get();
        if(count($cat))
        {
            foreach($cat as $c)
            {
                $cat_id= $c->category_id;
            }

            $cat=DB::table('tbl_category')->where('category_id',$cat_id)->get();
            $tour = DB::table('tbl_tour')->where('publication_status',1)->where('category_id',$cat_id)->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0)->get();

            $keywords='';
            $seo_description='';
            foreach($cat as $c)
            {
                $keywords='دسته بندی تور ها';
                $seo_description=$c->category_description;
                $seo_title='تور '.$c->category_name;
            }

            session::put('keywords',$keywords);
            session::put('seo_description',$seo_description);
            session::put('seo_title',$seo_title);


            //$all_tours = DB::table('tbl_tour')->where('publication_status',1)->where('category_id','=',"$cat_id")->orWhere('category_parent1','=',"$cat_id")->orWhere('category_parent2','=',"$cat_id")->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->paginate(10);

            $all_tours=DB::table('tbl_tour')

                ->orWhere(function($query)use ($cat_id,$today) {
                    $query->where('publication_status',1)->where('category_id','=',"$cat_id")->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0);
                })
                ->orWhere(function($query) use ($cat_id,$today){
                    $query->where('publication_status',1)->where('category_parent1','=',"$cat_id")->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0);
                })
                ->orWhere(function($query) use ($cat_id,$today){
                    $query->where('publication_status',1)->where('category_parent2','=',"$cat_id")->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0);
                })
                ->get();



            $all_hotels= DB::table('tbl_hotel')->where('publication_status',1)->get();
            $all_hrs= DB::table('tbl_hotel_room')->orderBy('hr_ms_price','ASC')->get();
            $latestTable_tour=DB::table('tbl_tour')->where('publication_status',1)->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0)->limit(5)->get();

            $rates=array();
            foreach($all_tours as $at)
            {
                $tour_id=$at->tour_id;

                $rate_value=0;
                $sum=0;
                //rate value
                $rate1= DB::table('tbl_tour_rate')->where('tour_id',$tour_id)->get();
                if(count($rate1)>0) {
                    $sum=0;
                    foreach($rate1 as $r1)
                    {
                        $sum+=$r1->rate_value;
                    }
                    $avg=$sum/count($rate1);
                    array_push($rates,$avg);
                }
                else {
                    array_push($rates,0);
                }
            }



            return View('pages.tours')->with([
                'all_tours'=>$all_tours,
                'all_categories'=>$all_categories,
                'cat_parent1'=> $cat_parent1,
                'all_hoteCategories'=>$all_hoteCategories,
                'cat_hotelParent1'=>$cat_hotelParent1,
                'all_airlines'=>$all_airlines,
                'all_hotels'=>$all_hotels,
                'all_hrs'=>$all_hrs,
                'tour'=>$tour,
                'all_stayTime'=>$all_stayTime,
                'all_ajancies'=>$all_ajancies,
                'all_airlines'=>$all_airlines,
                'all_travels'=>$all_travels,
                'cat'=>$cat,
                'latestTable_tour'=>$latestTable_tour,
                'rate_value'=>$rates,
                'all_desinitions'=>$all_desinitions
            ]);

        }else
        {
            $cat=0;

            //menu
            $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
            $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();

            $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
            $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();


            //search box
            $all_stayTime= DB::table('tbl_staytime')->where('publication_status',1)->get();
            $all_ajancies = DB::table('tbl_ajancy')->where('publication_status',1)->get();
            $all_airlines= DB::table('tbl_airline')->where('publication_status',1)->get();
            $all_travels= DB::table('tbl_traveltype')->where('publication_status',1)->get();

            return View('pages.tours')->with([
                'all_categories'=>$all_categories,
                'cat_parent1'=> $cat_parent1,
                'all_hoteCategories'=>$all_hoteCategories,
                'cat_hotelParent1'=>$cat_hotelParent1,

                'all_stayTime'=>$all_stayTime,
                'all_ajancies'=>$all_ajancies,
                'all_airlines'=>$all_airlines,
                'all_travels'=>$all_travels,
                'cat'=>$cat,

            ]);
        }

    }
    public function lastSecond(){


        $now=jdate('Y/m/d');
        $now=(string) $now;
        $num_a=array('0','1','2','3','4','5','6','7','8','9');
        $key_a=array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        $now=str_replace($key_a,$num_a,$now);
        $today=$now;
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();

        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();


        //search box
        $all_stayTime= DB::table('tbl_staytime')->where('publication_status',1)->get();
        $all_ajancies = DB::table('tbl_ajancy')->where('publication_status',1)->get();
        $all_airlines= DB::table('tbl_airline')->where('publication_status',1)->get();
        $all_travels= DB::table('tbl_traveltype')->where('publication_status',1)->get();




        $keywords="تورهای لحظه آخری ,لست سکند ,  تور ارزان , last second";
        $seo_description="خرید تورهای لحظه آخری داخلی ، خارجی ، طبیعت گردی ، incoming  با قیمت ارزان";
        $seo_title="تور های لحظه آخری";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        $all_tours = DB::table('tbl_tour')->where('publication_status',1)->where('tour_lastSecond',1)->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0)->get();

        $keywords='';
        $seo_description='';
        $seo_title='';

        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);



        $all_hotels= DB::table('tbl_hotel')->where('publication_status',1)->get();
        $all_hrs= DB::table('tbl_hotel_room')->orderBy('hr_ms_price','ASC')->get();
        $latestTable_tour=DB::table('tbl_tour')->where('publication_status',1)->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0)->limit(5)->get();

        $rates=array();
        foreach($all_tours as $at)
        {
            $tour_id=$at->tour_id;

            $rate_value=0;
            $sum=0;
            //rate value
            $rate1= DB::table('tbl_tour_rate')->where('tour_id',$tour_id)->get();
            if(count($rate1)>0) {
                $sum=0;
                foreach($rate1 as $r1)
                {
                    $sum+=$r1->rate_value;
                }
                $avg=$sum/count($rate1);
                array_push($rates,$avg);
            }
            else {
                array_push($rates,0);
            }
        }




        return View('pages.lastSecond')->with([
            'all_tours'=>$all_tours,
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'all_airlines'=>$all_airlines,
            'all_hotels'=>$all_hotels,
            'all_hrs'=>$all_hrs,

            'all_stayTime'=>$all_stayTime,
            'all_ajancies'=>$all_ajancies,
            'all_airlines'=>$all_airlines,
            'all_travels'=>$all_travels,

            'latestTable_tour'=>$all_tours,
            'rate_value'=>$rates
        ]);



    }
    public function  hotel_info($slug){


        $hotel = DB::table('tbl_hotel')->where('slug',$slug)->get();
        $keywords='';
        $seo_description='';
        $seo_title='';
        $hotel_id='';

        foreach($hotel as $h)
        {
            $keywords=$h->keywords;
            $seo_description=$h->seo_description;
            $seo_title=$h->hotel_pName.'|'.$h->hotel_eName;
            $hotel_id=$h->hotel_id;

        }
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);


        $all_hotels = DB::table('tbl_hotel')->where('publication_status',1)->get();

        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();


        $all_attr= DB::table('tbl_hotel_attr')->where('hotel_id',$hotel_id)->get();

        $all_galleries= DB::table('tbl_hotel_gallery')->where('hotel_id',$hotel_id)->get();
        $all_airlines= DB::table('tbl_airline')->get();



        return View('pages.hotel_info')->with([

            'all_hotels'=>$all_hotels,
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'all_hotels'=>$all_hotels,
            'all_attr'=>$all_attr,
            'hotel'=>$hotel,
            'all_galleries'=>$all_galleries,
            'all_airlines'=>$all_airlines,

        ]);
    }
    public function  ajancy_info($slug){
        $ajancy = DB::table('tbl_ajancy')->where('slug',$slug)->get();

        $keywords='';
        $seo_description='';
        $seo_title='';
        $ajancy_id='';

        foreach($ajancy as $a)
        {
            $keywords=$a->keywords;
            $seo_description=$a->seo_description;
            $seo_title=$a->ajancy_name;
            $ajancy_id=$a->ajancy_id;

        }
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        $all_ajancies = DB::table('tbl_ajancy')->where('publication_status',1)->get();


        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();


        $all_tours = DB::table('tbl_tour')->where('publication_status',1)->where('tour_draft',0)->where('ajancy_id',$ajancy_id)->get();
        $all_airlines= DB::table('tbl_airline')->where('publication_status',1)->get();

        return View('pages.ajancy_info')->with([

            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'ajancy'=>$ajancy,
            'all_tours'=>$all_tours,
            'all_airlines'=>$all_airlines,
            'all_ajancies'=>$all_ajancies

        ]);
    }
    public function hotels($slug){
        $cats = DB::table('tbl_hotelCategory')->where('publication_status',1)->where('slug',$slug)->first();
        $cat_id=$cats->category_id;





        $hotels=DB::table('tbl_hotel')

            ->orWhere(function($query)use ($cat_id) {
                $query->where('publication_status',1)->where('category_parent3','=',"$cat_id");
            })
            ->orWhere(function($query) use ($cat_id){
                $query->where('publication_status',1)->where('category_parent1','=',"$cat_id");
            })
            ->orWhere(function($query) use ($cat_id){
                $query->where('publication_status',1)->where('category_parent2','=',"$cat_id");
            })
            ->get();

        //  $hotels= DB::table("tbl_hotel as T1")
        // ->join("tbl_hotel as T2", "T1.hotel_id", "=", "T2.hotel_id")
        // ->where(function($query) use ($userid) {
        //       $query->where("t1.userid", $userid)
        //              ->whereIn("t1.unitid", [2,3]);
        // })->orWhere(function($query) use ($userid) {
        //       $query->where("t2.requesterid", $userid)
        //              ->whereIn("t2.requestertype", [1,5]);
        // });

        // $hotels = DB::table('tbl_hotel')
        // ->where(['category_parent1'=>$cat_id,'publication_status'=>1])
        // ->orWhere($cat_id,function ($query) use ($cat_id) {
        //     $query->where(['category_parent2'=>$cat_id])
        //           ->where(['publication_status'=>1]);
        // })
        // ->get();

        // $hotels = DB::table('tbl_hotel')
        //     ->where('category_id', function ($query) use ($cat_id) {
        //         return $query->where('category_parent3', $cat_id)->orWhere('category_parent2', $cat_id)->orWhere('category_parent1', $cat_id);
        //     })
        //     ->get();

        //$hotels = DB::table('tbl_hotel')->where(['category_parent1'=>"$cat_id",'publication_status'=>1])->orWhere(['category_parent2'=>"$cat_id",'publication_status'=>1])->orWhere(['category_parent3'=>"$cat_id",'publication_status'=>1])->paginate(10);
        // $where1 = ['category_parent1' => $cat_id, 'publication_status' => 1];
        // $where2 = ['category_parent2' => $cat_id, 'publication_status' => 1];
        // $where3 = ['category_parent3' => $cat_id, 'publication_status' => 1];
        // $hotels = DB::table('tbl_hotel')->where($where1)->orWhere($where2)->orWhere($where3)->paginate(10);
        //$hotels = DB::table('tbl_hotel')->where('publication_status',1)->paginate(10);
        //         $hotels=DB::table('tbl_hotel')->where('publication_status', 1)->where(function($query) {
        //     $query->where('category_parent1',$cat_id)
        //         ->orWhere('category_parent2',$cat_id)
        //         ->orWhere('category_parent3',$cat_id);
        // })->get();

        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();

        $keywords='دسته بندی هتل ها';
        $seo_description=$cats->category_description;
        $seo_title='هتل های'.' '.$cats->category_name;


        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);


        return View('pages.hotels')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'all_hotels'=>$hotels,
            'cat'=>$cats

        ]);
    }
    public function ajancies(){
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $ajancies = DB::table('tbl_ajancy')->where('publication_status',1)->paginate(12);
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();


        $keywords="";
        $seo_description="لیست آژانسهای ثبت شده در اوکی شد";
        $seo_title="لیست آژانس ها";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('pages.ajancies')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'all_ajancies'=>$ajancies,
        ]);
    }
    public function comments() {
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();


        $keywords="";
        $seo_description="صفحه  ثبت شکایت سایت اوکی شد";
        $seo_title="ثبت شکایات";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('pages.comments')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
        ]);

    }
    public function attraction($attraction_id) {
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();

        $attraction = DB::table('tbl_attraction')->where('publication_status',1)->where('attraction_id',$attraction_id)->get();

        return View('pages.attraction')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'attraction'=>$attraction
        ]);
    }
    public function order($tour_id,$hotel_id,$ajancy_id) {
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();

        $keywords="";
        $seo_description="صفحه  ثبت سفارش سایت اوکی شد";
        $seo_title="ثبت سفارش";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);


        $all_rooms = DB::table('tbl_room')->where('publication_status',1)->get();
        $room_exit = DB::table('tbl_tour_room')->where('tour_id',$tour_id)->get();



        return View('pages.order')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'all_rooms'=> $all_rooms,
            'tour_id'=>$tour_id,
            'hotel_id'=>$hotel_id,
            'ajancy_id'=>$ajancy_id,
            'room_exit'=>$room_exit
            //'hotel_info'=>json_encode($hotel_info)
        ]);

    }
    public function save_comment(Request $request){

        // add all new ajancy data in DB ::
        $data = array();
        $data['comment_name'] = $request->comment_name;
        $data['comment_mobile'] = $request->comment_mobile;
        $data['comment_email'] = $request->comment_email;
        $data['comment_subject'] = $request->comment_subject;
        $data['comment_message'] = $request->comment_message;
        $data['comment_date']='';
        $now=jdate('Y/m/d');
        $now=(string) $now;
        $num_a=array('0','1','2','3','4','5','6','7','8','9');
        $key_a=array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        $now=str_replace($key_a,$num_a,$now);

        $data['comment_type'] = $now;

        print_r($data);
        if(DB::table('tbl_comment')->insert($data))
        {
            session::put('msg','اطلاعات شما با موفقیت ثبت شد.');
            return Redirect::to('/comments');
        }else{
            session::put('msg','اطلاعات شما با موفقیت ثبت نشد.');
            return Redirect::to('/comments');
        }

    }
    public function rules(){
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $info = DB::table('tbl_info')->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();

        $keywords="";
        $seo_description="صفحه قوانین و مقررات سایت اوکی شد";
        $seo_title="قوانین و مقررات";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);


        return View('pages.rules')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'info'=>$info
        ]);

    }
    public function contact(){
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();

        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();


        $keywords="";
        $seo_description=" صفحه ارتباط با ما سایت اوکی شد";
        $seo_title="ارتباط با ما";         session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('pages.contact')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
        ]);

    }
    public function about(){
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();

        $info= DB::table('tbl_info')->get();


        $keywords="";
        $seo_description="صفحه درباره ما سایت اوکی شد";
        $seo_title="درباره ما";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('pages.about')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'info'=>$info
        ]);

    }
    public function cooperation(){
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();

        $info= DB::table('tbl_info')->get();

        $keywords="";
        $seo_description="صفحه فرصتهای شغلی سایت اوکی شد";
        $seo_title="فرصت های شغلی";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('pages.coorporatin')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
            'info'=>$info
        ]);

    }
    public function help(){
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();


        $keywords="";
        $seo_description="صفحه راهنمای سایت";
        $seo_title="راهنمای سایت";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);


        return View('pages.help')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,

        ]);

    }
    public function showChangePasswordForm(){
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();

        $keywords="";
        $seo_description="تغییر رمز عبور";
        $seo_title="تغییر رمز عبور";
        session::put('keywords',$keywords);
        session::put('seo_description',$seo_description);
        session::put('seo_title',$seo_title);

        return View('auth.passwords.email')->with([
            'all_categories'=>$all_categories,
            'cat_parent1'=> $cat_parent1,
            'all_hoteCategories'=>$all_hoteCategories,
            'cat_hotelParent1'=>$cat_hotelParent1,
        ]);

    }
    public function ChangePassword(Request $request){
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();

        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();

        //menu

        $email = $request->email;
        $customer = DB::table('tbl_customer')->where('customer_email',$email)->get();
        $ajancy = DB::table('tbl_ajancy')->where('ajancy_email',$email)->get();
        if(count($customer)==1 ){

            foreach ($customer as $c)
            {
                $code=$c->changePassword;

                $subject = " لینک بازیابی رمز عبور ";
                $header = "okShod.com";
                $body ="جهت تغییر رمز عبور میتوانید از لینک زیر وارد شوید"."https://okshod.com/recovery"."/customer/".$c->customer_id."/".$code;
                $data = array('name'=>$body);
                Mail::send('mails.mail', $data, function($message) use ($c) {
                    $message->to($c->customer_email, 'okshod.com')->subject('فروش تورهای داخلی و خارجی -');
                    $message->from('info@okshod.com','okShod');
                });

                session::put('msg','پیامی به ایمیل شما ارسال شد. جهت ادامه مراحل ایمیلتان را بررسی کنید');
                return View('auth.passwords.email')->with([
                    'all_categories'=>$all_categories,
                    'cat_parent1'=> $cat_parent1,
                    'all_hoteCategories'=>$all_hoteCategories,
                    'cat_hotelParent1'=>$cat_hotelParent1,
                ]);

            }

        }
        if(count($ajancy)==1 ){

            foreach ($ajancy as $c)
            {
                $code=$c->changePassword;

                $subject = " لینک بازیابی رمز عبور ";
                $header = "okShod.com";
                $body ="جهت تغییر رمز عبور میتوانید از لینک زیر وارد شوید"."https://okshod.com/recovery"."/ajancy/".$c->ajancy_id."/".$code;
                $data = array('name'=>$body);
                Mail::send('mails.mail', $data, function($message) use ($c) {
                    $message->to($c->ajancy_email, 'okshod.com')->subject('فروش تورهای داخلی و خارجی -');
                    $message->from('info@okshod.com','okShod');
                });

                session::put('msg','پیامی به ایمیل شما ارسال شد. جهت ادامه مراحل ایمیلتان را بررسی کنید');
                return View('auth.passwords.email')->with([
                    'all_categories'=>$all_categories,
                    'cat_parent1'=> $cat_parent1,
                    'all_hoteCategories'=>$all_hoteCategories,
                    'cat_hotelParent1'=>$cat_hotelParent1,
                ]);

            }

        }
        else
        {
            session::put('msg','آدرس ایمیل شما در سیستم یافت نشد. لطفا آدرس ایمیل معتبری وارد نمایید');
            return View('auth.passwords.email')->with([
                'all_categories'=>$all_categories,
                'cat_parent1'=> $cat_parent1,
                'all_hoteCategories'=>$all_hoteCategories,
                'cat_hotelParent1'=>$cat_hotelParent1,
            ]);
        }

    }
    public function recovery(Request $request)  {
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();

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
                            'all_categories'=>$all_categories,
                            'cat_parent1'=> $cat_parent1,
                            'email'=>$customer->customer_email,
                            'type'=>'customer',
                            'all_hoteCategories'=>$all_hoteCategories,'cat_hotelParent1'=>$cat_hotelParent1,
                        ]);
                    }

                }


            }else
            {

                session::put('msg','لینک وارد شده درست نمیباشد. لطفا جهت تغییر رمز عبور مجددا تلاش کنید');
                return View('auth.passwords.email')->with([
                    'all_categories'=>$all_categories,
                    'cat_parent1'=> $cat_parent1,

                    'all_hoteCategories'=>$all_hoteCategories,'cat_hotelParent1'=>$cat_hotelParent1,
                ]);

            }
        }
        else{
            $ajancy = DB::table('tbl_ajancy')->where(['changePassword'=>$code,'ajancy_id'=>$customer_id])->get();

            if(count($ajancy)==1)
            {
                foreach($ajancy as $a)
                {
                    $change=md5(uniqid(mt_rand()));
                    $result=DB::table('tbl_ajancy')->where('ajancy_id', $a->ajancy_id)->update(['changePassword' => $change]);
                    if($result==1)
                    {
                        return View('pages.changePassword')->with([
                            'all_categories'=>$all_categories,
                            'cat_parent1'=> $cat_parent1,
                            'email'=>$a->ajancy_email,
                            'type'=>'ajancy',
                            'all_hoteCategories'=>$all_hoteCategories,'cat_hotelParent1'=>$cat_hotelParent1,
                        ]);
                    }

                }


            }else
            {

                session::put('msg','لینک وارد شده درست نمیباشد. لطفا جهت تغییر رمز عبور مجددا تلاش کنید');
                return View('auth.passwords.email')->with([
                    'all_categories'=>$all_categories,
                    'cat_parent1'=> $cat_parent1,
                    'all_hoteCategories'=>$all_hoteCategories,'cat_hotelParent1'=>$cat_hotelParent1,
                ]);

            }
        }






    }
    public function updatePass(Request $request)  {
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();



        if($request->customer_password ==$request->customer_repassword)
        {
            if($request->type=='customer'){


                $u = DB::table('tbl_customer')->where('customer_email',$request->customer_email)->update(['customer_password'=>md5($request->customer_password)]);

                if($u==1)
                {

                    session::put('msg','رمز عبور شما با موفقیت تغییر کرد');

                    if(isset($request->id)){

                        $all_orders = DB::table('tbl_order')->where('customer_id',$request->id)->get();
                        $all_requests = DB::table('tbl_request')->where('customer_id',$request->id)->get();
                        $customer=DB::table('tbl_customer')->where('customer_id',$request->id)->first();
                        $all_tours=DB::table('tbl_tour')->get();
                        $all_ajancies=DB::table('tbl_ajancy')->get();
                        $all_travelers =DB::table('tbl_traveler')->get();
                        $all_hotels=DB::table('tbl_hotel')->get();
                        $all_rooms=DB::table('tbl_room')->get();
                        return View ('pages.customer_panel')->with(['all_rooms'=>$all_rooms,'all_hotels'=>$all_hotels,'all_travelers'=>$all_travelers,'all_requests'=>$all_requests,'all_orders'=>$all_orders,'customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id') ,'all_tours'=>$all_tours,'all_ajancies'=>$all_ajancies, 'all_categories'=>$all_categories,'cat_parent1'=> $cat_parent1,  'all_hoteCategories'=>$all_hoteCategories,  'cat_hotelParent1'=>$cat_hotelParent1,'type'=>'customer']);

                    }
                    return View('pages.changePassword')->with([ 'all_categories'=>$all_categories, 'cat_parent1'=> $cat_parent1, 'email'=>$request->customer_email,  'all_hoteCategories'=>$all_hoteCategories,'cat_hotelParent1'=>$cat_hotelParent1,'type'=>'customer' ]);

                }else
                {

                    session::put('msg','رمز عبور شما با موفقیت تغییر نکرد. لطفا مجددا تلاش کنید');
                    if(isset($request->id)){

                        $all_orders = DB::table('tbl_order')->where('customer_id',$request->id)->get();
                        $all_requests = DB::table('tbl_request')->where('customer_id',$request->id)->get();
                        $customer=DB::table('tbl_customer')->where('customer_id',$request->id)->first();
                        $all_tours=DB::table('tbl_tour')->where('tour_draft',0)->get();
                        $all_ajancies=DB::table('tbl_ajancy')->get();
                        $all_travelers=DB::table('tbl_traveler')->get();
                        $all_hotels=DB::table('tbl_hotel')->get();
                        $all_rooms=DB::table('tbl_room')->get();

                        return View ('pages.customer_panel')->with(['all_requests'=>$all_requests,'all_orders'=>$all_orders,'all_hotels'=>$all_hotels,'all_rooms'=>$all_rooms,'all_travelers'=>$all_travelers,'customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id') ,'all_tours'=>$all_tours,'all_ajancies'=>$all_ajancies, 'all_categories'=>$all_categories,'cat_parent1'=> $cat_parent1,  'all_hoteCategories'=>$all_hoteCategories,  'cat_hotelParent1'=>$cat_hotelParent1]);

                    }
                    return View('pages.changePassword')->with([ 'all_categories'=>$all_categories, 'cat_parent1'=> $cat_parent1,'email'=>$request->customer_email,  'all_hoteCategories'=>$all_hoteCategories, 'cat_hotelParent1'=>$cat_hotelParent1,'type'=>'customer' ]);
                }
            }
            else{
                $u = DB::table('tbl_ajancy')->where('ajancy_email',$request->customer_email)->update(['ajancy_password'=>md5($request->customer_password)]);

                if($u==1)
                {

                    session::put('msg','رمز عبور شما با موفقیت تغییر کرد');

                    return View('pages.changePassword')->with([ 'all_categories'=>$all_categories, 'cat_parent1'=> $cat_parent1, 'email'=>$request->customer_email,  'all_hoteCategories'=>$all_hoteCategories,'cat_hotelParent1'=>$cat_hotelParent1,'type'=>'ajancy' ]);

                }
                else {

                    session::put('msg','رمز عبور شما با موفقیت تغییر نکرد. لطفا مجددا تلاش کنید');
                    return View('pages.changePassword')->with([ 'all_categories'=>$all_categories, 'cat_parent1'=> $cat_parent1,'email'=>$request->customer_email,  'all_hoteCategories'=>$all_hoteCategories, 'cat_hotelParent1'=>$cat_hotelParent1, 'type'=>'ajancy']);
                }
            }



        }else
        {
            if($request->type=='customer'){

                if(isset($request->id)){


                    session::put('msg','رمز عبور با تکرار آن یکسان نیست. مجددا تلاش کنید');

                    $all_orders = DB::table('tbl_order')->where('customer_id',$request->id)->get();
                    $all_requests = DB::table('tbl_request')->where('customer_id',$request->id)->get();
                    $customer=DB::table('tbl_customer')->where('customer_id',$request->id)->first();
                    $all_tours=DB::table('tbl_tour')->get();
                    $all_ajancies=DB::table('tbl_ajancy')->get();
                    $all_travelers =DB::table('tbl_traveler')->get();
                    $all_hotels=DB::table('tbl_hotel')->get();
                    $all_rooms=DB::table('tbl_room')->get();
                    return View ('pages.customer_panel')->with(['all_rooms'=>$all_rooms,'all_hotels'=>$all_hotels,'all_travelers'=>$all_travelers,'all_requests'=>$all_requests,'all_orders'=>$all_orders,'customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id') ,'all_tours'=>$all_tours,'all_ajancies'=>$all_ajancies, 'all_categories'=>$all_categories,'cat_parent1'=> $cat_parent1,  'all_hoteCategories'=>$all_hoteCategories,  'cat_hotelParent1'=>$cat_hotelParent1,'type'=>'customer']);
                }else{


                    session::put('msg','رمز عبور با تکرار آن یکسان نیست. مجددا تلاش کنید');
                    return View('pages.changePassword')->with([
                        'all_categories'=>$all_categories,
                        'cat_parent1'=> $cat_parent1,'email'=>$request->customer_email,'all_hoteCategories'=>$all_hoteCategories, 'cat_hotelParent1'=>$cat_hotelParent1, 'type'=>'customer','email'=>$request->customer_email,'customer'=>session::get('customer_email'),'customer_id'=>session::get('customer_id'),'type'=>'customer'  ]);

                }
            }
            else{

                session::put('msg','رمز ورودی با تکرار آن برابر نیست ');
                return View('pages.changePassword')->with([ 'all_categories'=>$all_categories, 'cat_parent1'=> $cat_parent1,'email'=>$request->customer_email,  'all_hoteCategories'=>$all_hoteCategories, 'cat_hotelParent1'=>$cat_hotelParent1, 'type'=>'ajancy']);

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
        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status',1)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status',1)->where('category_parent1',0)->get();
        $all_hoteCategories = DB::table('tbl_hotelCategory')->where('publication_status',1)->get();
        $cat_hotelParent1 = DB::table('tbl_hotelCategory')->where('category_parent1',0)->where('publication_status',1)->get();



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
                        return View('pages.activate')->with([
                            'all_categories'=>$all_categories,   'cat_parent1'=>$cat_parent1, 'msg'=>$msg, 'all_hoteCategories'=>$all_hoteCategories,'cat_hotelParent1'=>$cat_hotelParent1, ]);
                    }
                }else
                {

                    return View('pages.activate')->with([
                        'all_categories'=>$all_categories,
                        'cat_parent1'=> $cat_parent1,
                        'all_hoteCategories'=>$all_hoteCategories,
                        'cat_hotelParent1'=>$cat_hotelParent1,
                        'msg'=>'حساب شما فعال است. برای استفاده از امکانات سایت ، وارد شوید'
                    ]);
                }


            }

        }
        else{

            return View('pages.activate')->with([
                'all_categories'=>$all_categories,
                'cat_parent1'=> $cat_parent1,
                'all_hoteCategories'=>$all_hoteCategories,
                'cat_hotelParent1'=>$cat_hotelParent1,
                'msg'=>'لینک شما اشتباه است.برای هماهنگی با مدیر وب سایت ارتباط برقرار کنید.'
            ]);

        }

    }
    public function  hotel_filter_ajax(Request $request){

        if($request->ajax()){
            $cat_id=$request->cat_id;
            $table='';
            $hotels = DB::table('tbl_hotel')->where('publication_status',1)->where('category_parent3','=',"$cat_id")->orWhere('category_parent1','=',"$cat_id")->orWhere('category_parent2','=',"$cat_id")->orderby($request->orderby,$request->arrange)->paginate(10);

            foreach($hotels as $hotel)
            {
                $rate='';
                for($i=0;$i<$hotel->hotel_rate;$i++)
                {
                    $rate=$rate.'<i class="fa fa-star"></i>';

                }
                $table=$table.'<tr role="row"><td><img src="https://okshod.com/images/hotels/'.$hotel->hotel_id.'.jpg" alt="'.$hotel->hotel_eName.'"></td><td><span>'.$hotel->hotel_pName.'</span><br><p>'.$hotel->hotel_eName.'</p></td><td>'.$rate.'</td><td><i class="fa fa-coffee"></i>'.$hotel->hotel_type.'</td><td class="td_phone">'.$hotel->hotel_phone.'</td><td><a href="http://okshod.com/hotel_info/'.$hotel->slug.'" target="_blank">جزئیات بیشتر</a></td></tr>';

            }

            return response()->json(['data'=>$table]);
        }


    }
    public function  tour_filter_ajax(Request $request){

        $now=jdate('Y/m/d');
        $now=(string) $now;
        $num_a=array('0','1','2','3','4','5','6','7','8','9');
        $key_a=array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
        $now=str_replace($key_a,$num_a,$now);
        $today=$now;




        if($request->ajax()){
            $cat_id=$request->cat_id;
            $table='';
            $travel_type;

            $tours = DB::table('tbl_tour')->where('category_id','=',"$cat_id")->orWhere('category_parent1','=',"$cat_id")->orWhere('category_parent2','=',"$cat_id")->whereDate('tour_startShow','<=',$today)->whereDate('tour_endShow','>=',$today)->where('tour_draft',0)->orderby($request->orderby,$request->arrange)->paginate(10);

            $all_tours1=array();
            foreach($tours as $tour1)
            {
                if($tour1->publication_status==1)
                {
                    array_push($all_tours1,$tour1);
                }
            }
            $tours=$all_tours1;


            $rates=array();

            foreach($tours as $tour)
            {
                if($tour->tour_stayTime==1000){ $tour->tour_stayTime=0; }
                if($tour->travel_id==1)
                {
                    $air_back=$tour->airLineback_id;
                    $airlines_back= DB::table('tbl_airline')->where(['publication_status'=>1,'air_id'=>$air_back])->first();
                    $travel_type='<i class="fa fa-plane"></i>'.$airlines_back->air_name;

                    $air_went=$tour->airLineWent_id;
                    $airlines_went= DB::table('tbl_airline')->where(['publication_status'=>1,'air_id'=>$air_went])->first();
                    $travel_type1='<i class="fa fa-plane"></i>'.$airlines_went->air_name;

                }
                elseif($tour->travel_id==2)
                {
                    $travel_type='<i class="fa fa-bus"></i>';
                    $travel_type1='<i class="fa fa-bus"></i>';
                }
                elseif($tour->travel_id==3)
                {
                    $travel_type='<i class="fa fa-subway"></i>';
                    $travel_type1='<i class="fa fa-bus"></i>';
                }
                $hotel= DB::table('tbl_hotel_room')->where(['tour_id'=>$tour->tour_id])->orderBy('hr_ms_price','DESC')->first();

                if($hotel){
                    $hotel_name= DB::table('tbl_hotel')->where(['publication_status'=>1,'hotel_id'=>$hotel->hotel_id])->first();
                    $stars='<i class="fa fa-star"></i>';
                    $i=1;
                    for($i=1;$i<$hotel_name->hotel_rate;$i++)
                    {
                        $stars=$stars.'<i class="fa fa-star"></i>';
                    }
                    $hotel_info='<div>'.$hotel_name->hotel_eName.'</div><div class="col-xs-12"><div class="col-sm-6 stars">'.$stars.'</div><div class="col-sm-6">'.$hotel_name->hotel_rate.'<span>'.$hotel_name->hotel_type.'</span><i class="fa fa-coffee"></i></div></div>';


                }else {
                    $hotel_info='';
                }

                $marked= DB::table('tbl_tour_show')->where(['tour_id'=>$tour->tour_id,'plan_id'=>4])->get();

                if(count($marked)>=1)
                {
                    $marked='marked_tour';
                }else
                {
                    $marked='';
                }


                $rate_value=0;
                $sum=0;
                $avg=0;
                //rate value
                $rate1= DB::table('tbl_tour_rate')->where('tour_id',$tour->tour_id)->get();
                if(count($rate1)>0) {
                    $sum=0;
                    foreach($rate1 as $r1)
                    {
                        $sum+=$r1->rate_value;
                    }
                    $avg=$sum/count($rate1);

                }
                else {
                    $avg=0;
                }
                $table=$table.'<tr class="'.$marked.'" role="row"><td><img src="https://okshod.com/images/tours/'.$tour->tour_id.'.jpg" alt="'.$tour->tour_name.'"></td><td>'.$tour->tour_name.'</td><td> '.$tour->tour_stayTime.' شب</td><td><div><i class="fa fa-calendar-o"></i>'.$tour->tour_wentTime.'</div>'.$travel_type1.'</td><td><div><i class="fa fa-calendar-o"></i>'.$tour->tour_backTime.'</div>'.$travel_type1.'</td><td>'.$hotel_info.'</td><td><span>'.$tour->tour_price.'</span> تومان</td><td>'.$tour->tour_comision.'</td><td>'.$tour->tour_sellNumber.'</td><td>  <span itemprop="ratingValue"><i class="fa fa-star text-danger fa-2x"></i><br>'.$avg.'</span></td><td><a href="https://okshod.com/tour_info/'.$tour->slug.'" target="_blank" class="btn btn-success"><span class="fa fa-shopping-cart"></span>ثبت سفارش</a></td></tr>';}

            return response()->json(['data'=>$table]);
        }


    }
    public function rating(Request $request){
        if($request->ajax()){
            $a=explode('-',$request->value);
            $data['rate_value'] =$a[0];
            $data['tour_id']=$a[1];
            $data['customer_id']=$a[2];
            $rating = DB::table('tbl_tour_rate')->insertGetId($data);

            $sum=0;
            $avg=0;
            //rate value
            $rate1= DB::table('tbl_tour_rate')->where('tour_id',$a[1])->get();
            if(count($rate1)>0) {
                $sum=0;
                foreach($rate1 as $r1)
                {
                    $sum+=$r1->rate_value;
                }
                $avg=$sum/count($rate1);
            }
            else {
                $avg=0;
            }


            return response()->json(['data'=>$rating,'rating_value'=>$avg]);

        }


    }
    function sitemap(){



        $tours = DB::table('tbl_tour')->where('tour_draft',0)->orderBy('tour_id', 'desc')->get();
        $hotels = DB::table('tbl_hotel')->orderBy('hotel_id', 'desc')->get();

        $content= View('sitemap')->with(['tours'=>$tours,'hotels'=>$hotels]);
        return Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }

}
