<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        include 'jdf.php';
        $now = jdate('Y-m-d', '', '', '', 'en');
        $now = (string)$now;
        $today = $now;

        $min_price = $request->min_price;
        $max_price = $request->max_price;

        $min_rent = $request->min_rent;
        $max_rent = $request->max_rent;

        $min_size = $request->min_size;
        $max_size = $request->max_size;

//        $min_substruction = $request->min_substruction;
//        $max_substruction = $request->max_substruction;

        $min_age = $request->min_age;
        $max_age = $request->max_age;


        if($min_price==''){
            $min_price=0;
        }
        if($max_price==''){
            $max_price=9999999999;
        }
        if($min_rent==''){
            $min_rent=0;
        }
        if($max_rent==''){
            $max_rent=9999999999;
        }

        if($min_size==''){
            $min_size=0;
        }
        if($max_size==''){
            $max_size=9999999999;
        }

//        if($min_substruction==''){
//            $min_substruction=0;
//        }
//        if($max_substruction==''){
//            $max_substruction=9999999999;
//        }

        if($min_age==''){
            $min_age=0;
        }
        if($max_age==''){
            $max_age=9999999999;
        }

        session::put('search_minPrice', $min_price);
        session::put('search_maxPrice', $max_price);
        session::put('search_minRent', $min_rent);
        session::put('search_maxRent', $max_rent);
        session::put('search_minSize', $min_size);
        session::put('search_maxSize', $max_size);
//        session::put('search_minSubstruction', $min_substruction);
//        session::put('search_maxSubstruction', $max_substruction);
        session::put('search_minAge', $min_age);
        session::put('search_maxAge', $max_age);

        $keywords = 'دسته بندی املاک';
        $seo_description = '';
        $seo_title = '';

        session::put('keywords', $keywords);
        session::put('seo_description', $seo_description);
        session::put('seo_title', $seo_title);

        $property_ids=array();
        $transaction_ids=array();
        $city_ids=array();
        $area_ids=array();



        $transaction_ids[0] = $request->transactiontype;
        $property_ids[0] = $request->propertytype;
        $city_ids[0] = $request->city;
        $area_ids[0] = $request->area;


        $all_propertytypes = DB::table('tbl_propertytype')->get();
        $all_transactiontypes = DB::table('tbl_transactiontype')->get();
        $all_cities = DB::table('tbl_city')->get();
        $all_areas = DB::table('tbl_area')->get();

        if($property_ids[0]==0)
        {

            foreach ($all_propertytypes as $pt)
            {
                array_push($property_ids,$pt->pt_id);
            }
        }
        if($transaction_ids[0]==0)
        {

            foreach ($all_transactiontypes as $tt)
            {
                array_push($transaction_ids,$tt->tt_id);
            }
        }
        if($city_ids[0]==0)
        {

            foreach ($all_cities as $city)
            {
                array_push($city_ids,$city->city_id);
            }
        }
        if($area_ids[0]==0)
        {

            foreach ($all_areas as $area)
            {
                array_push($area_ids,$area->area_id);
            }
        }


        session::put('search_tt', $transaction_ids);
        session::put('search_pt', $property_ids);
        session::put('search_city', $city_ids);
        session::put('search_area', $area_ids);





        $all_properties = DB::table('tbl_property')
            ->WhereIn('tt_id',  $transaction_ids)
            ->WhereIn('pt_id',  $property_ids)
            ->WhereIn('city_id',  $city_ids)
            ->WhereIn('area_id',  $area_ids)
            ->where('property_price','<=',$max_price)->where('property_price','>=',$min_price)
            ->where('property_rent','<=',$max_rent)->where('property_rent','>=',$min_rent)
            ->where('property_size','<=',$max_size)->where('property_size','>=',$min_size)
//            ->where('property_substruction','<=',$max_substruction)->where('property_substruction','>=',$min_substruction)
            ->where('property_age','<=',$max_age)->where('property_age','>=',$min_age)
            ->whereDate('property_showDate','>',$today)
            ->where('publication_status',1)
            ->paginate();


        $rates=array();
        $all_cities1=array();
        $all_areas1=array();
        $all_prices1=array();
        $all_names1=array();


        $all_properties1 = DB::table('tbl_property')
            ->WhereIn('tt_id',  $transaction_ids)
            ->WhereIn('pt_id',  $property_ids)
            ->WhereIn('city_id',  $city_ids)
            ->WhereIn('area_id',  $area_ids)
            ->where('property_price','<=',$max_price)->where('property_price','>=',$min_price)
            ->where('property_rent','<=',$max_rent)->where('property_rent','>=',$min_rent)
            ->where('property_size','<=',$max_size)->where('property_size','>=',$min_size)
//            ->where('property_substruction','<=',$max_substruction)->where('property_substruction','>=',$min_substruction)
            ->where('property_age','<=',$max_age)->where('property_age','>=',$min_age)
            ->whereDate('property_showDate','>',$today)
            ->where('publication_status',1)
            ->get();
        foreach($all_properties1 as $p) {
            array_push($all_prices1,$p->property_price);
        }

            foreach($all_properties as $at)
        {
            $property_id=$at->property_id;
            array_push($all_cities1,$at->city_id);
            array_push($all_areas1,$at->area_id);

            array_push($all_names1,$at->property_name);

            $rate_value=0;
            $sum=0;
            //rate value
            $rate1= DB::table('tbl_property_rate')->where('property_id',$property_id)->get();
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


        $all_transactiontypes = DB::table('tbl_transactiontype')->get();
        $all_propertytypes = DB::table('tbl_propertytype')->get();
        $all_propertyattrs = DB::table('tbl_property_attr');
        $all_propertygallery = DB::table('tbl_property_gallery');
        $all_states = DB::table('tbl_state')->get();
        $all_cities = DB::table('tbl_city')->orderby('city_name','ASC')->get();
        $all_areas = DB::table('tbl_area')->orderby('area_name','ASC')->get();



        return View('pages.properties')->with([
            'all_properties'=>$all_properties,
            'rates'=>$rates,

            'all_transactiontypes' =>$all_transactiontypes,
            'all_properties' =>$all_properties,
            'all_propertytypes' =>$all_propertytypes,
            'all_propertyattrs' =>$all_propertyattrs,
            'all_propertygallery'=>$all_propertygallery ,
            'all_states' =>$all_states,
            'all_cities' =>$all_cities,
            'all_areas' => $all_areas,
            'all_cities1' =>$all_cities1,
            'all_areas1' => $all_areas1,
            'all_prices1' =>$all_prices1,

        ]);
    }
    public function search_ajax(Request $request)
    {
        include 'jdf.php';
        $now = jdate('Y-m-d', '', '', '', 'en');
        $now = (string)$now;
        $today = $now;

        $min_price=session::get('search_minPrice');
        $max_price=session::get('search_maxPrice');

        $min_rent=session::get('search_minRent');
        $max_rent=session::get('search_maxRent');

        $min_size=session::get('search_minSize');
        $max_size=session::get('search_maxSize');
//        $min_substruction=session::get('search_minSubstruction');
//        $max_substruction=session::get('search_maxSubstruction');
        $min_age=session::get('search_minAge');
        $max_age=session::get('search_maxAge');



        $keywords = 'دسته بندی املاک';
        $seo_description = '';
        $seo_title = '';



        session::put('keywords', $keywords);
        session::put('seo_description', $seo_description);
        session::put('seo_title', $seo_title);

        $property_ids=array();
        $transaction_ids=array();
        $city_ids=array();
        $area_ids=array();



        $transaction_ids[0] = $request->transactiontype;
        $property_ids[0] = $request->propertytype;
        $city_ids[0] = $request->city;
        $area_ids[0] = $request->area;


        session::put('search_tt', $transaction_ids[0]);
        session::put('search_pt', $property_ids[0]);
        session::put('search_city', $city_ids[0]);
        session::put('search_area', $area_ids[0]);

        $all_propertytypes = DB::table('tbl_propertytype')->get();
        $all_transactiontypes = DB::table('tbl_transactiontype')->get();
        $all_cities = DB::table('tbl_city')->get();
        $all_areas = DB::table('tbl_area')->get();

        if($property_ids[0]==0)
        {

            foreach ($all_propertytypes as $pt)
            {
                array_push($property_ids,$pt->pt_id);
            }
        }
        if($transaction_ids[0]==0)
        {

            foreach ($all_transactiontypes as $tt)
            {
                array_push($transaction_ids,$tt->tt_id);
            }
        }
        if($city_ids[0]==0)
        {

            foreach ($all_cities as $city)
            {
                array_push($city_ids,$city->city_id);
            }
        }
        if($area_ids[0]==0)
        {

            foreach ($all_areas as $area)
            {
                array_push($area_ids,$area->area_id);
            }
        }
        $today=date('Y-m-d H:i:s');

        $property_name = $request->property_name;
        $order_val = $request->order_val;
        $arrange = $request->arrange;
        $city_id = $request->city_id;
        $area_id = $request->area_id;
        $property_rate=$request->property_rate;
        $from_range=$request->from_range;
        $to_range=$request->to_range;

        if($city_id!=''){
            $city_ids=$city_id;
        }

        if($area_id!=''){
            $area_ids=$area_id;
        }

        if($property_name !='none'){

            $all_properties = DB::table('tbl_property')
                ->where('property_name', 'like', "%" . $property_name . "%")
                ->WhereIn('tt_id',  $transaction_ids)
                ->WhereIn('pt_id',  $property_ids)
                ->WhereIn('city_id',  $city_ids)
                ->WhereIn('area_id',  $area_ids)
                ->where('property_price','<=',$to_range)->where('property_price','>=',$from_range)
                ->where('property_size','<=',$max_size)->where('property_size','>=',$min_size)
                ->where('property_rent','<=',$max_rent)->where('property_rent','>=',$min_rent)
//                ->where('property_substruction','<=',$max_substruction)->where('property_substruction','>=',$min_substruction)
                ->where('property_age','<=',$max_age)->where('property_age','>=',$min_age)
                ->whereDate('property_showDate','>',$today)
                ->orderBy($order_val,$arrange)
                ->get();
        }
        else{
            $all_properties = DB::table('tbl_property')
                ->WhereIn('tt_id',  $transaction_ids)
                ->WhereIn('pt_id',  $property_ids)
                ->WhereIn('city_id',  $city_ids)
                ->WhereIn('area_id',  $area_ids)
                ->where('property_price','<=',$to_range)->where('property_price','>=',$from_range)
                ->where('property_rent','<=',$max_rent)->where('property_rent','>=',$min_rent)
                ->where('property_size','<=',$max_size)->where('property_size','>=',$min_size)
//                ->where('property_substruction','<=',$max_substruction)->where('property_substruction','>=',$min_substruction)
                ->where('property_age','<=',$max_age)->where('property_age','>=',$min_age)
                ->whereDate('property_showDate','>',$today)
                ->orderBy($order_val,$arrange)
                ->get();
//                ->pluck('hotel_id')->toArray();
        }

        $all_attrs= array();
        $attr_array=$request->property_attr;
        if($request->property_attr ){
            $all_propertyattrs = DB::table('tbl_property_attr')->get();
            foreach ($all_propertyattrs as $pa){
                $pa_array=(array) $pa;
                $pa_array=array_values($pa_array);

                    for ($i = 1; $i < sizeof($pa_array); $i++) {
                        for ($j = 1; $j < sizeof($attr_array); $j++) {
//
                            if($pa_array[$i] == $attr_array[$j]){

                                array_push($all_attrs,$pa_array[0]);
                            }
                        }
                    }
                }

            }
        $all_attrs=array_unique($all_attrs);





                $r=array();

        $a=-1;
        $n=0;
        $str='';


        foreach ($all_properties as $property){
            if(in_array($property->property_id, $all_attrs) || $attr_array[0]==1){

                if(isset($property->property_rent) && $property->property_rent !=0){
                    $rent=' /<span class="property-price my-2">قیمت اجاره :'.$property->property_rent.'تومان</span>';

                }else{
                    $rent='';
                }

                if($property->property_roomNum !=0){
                   $room_num='<div class="col-4" title="تعداد اتاق"><span class="fa fa-user"></span><p>'.$property->property_roomNum.'</p></div>';
                }else{
                   $room_num='';
                }
                $str=$str.'<div class="search-result-box border rounded "><div class="row"><div class="col-md-3 col-4"><img src="/'.$property->property_image.'" alt="'.$property->property_name.'" class="img-fluid"><p class="text-center mt-3"><a class="text-danger " data-toggle="modal" href="#map112"><i class=" fa fa-location-arrow mx-1"></i>نمایش روی نقشه</a></p> </div><div class="col-md-9 col-8"><div class="row my-2 "><h2 class="col-sm-6">'.$property->property_name.'</h2><div class="col-sm-6 property-rate"></div></div><p class="address-search-box text-muted"><i class="fa fa-map-marker"></i>'.$property->property_address.'</p><div class="row text-center">'.$room_num.'<div class="col-4" title="متراژ"><span class="fa fa-building-o"></span><p>'.$property->property_size.'  متر </p></div><div class="col-4" title="سن بنا"><span class="fa fa-clock-o"></span><p>'.$property->property_age.'  سال</p></div></div><div class="modal fade" id="map112"><div class="modal-dialog map-modal"><div class="modal-content"><div class="modal-header"><h4 class="modal-title text-right"> نقشه ملک  '.$property->property_name.'</h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></div><div class="modal-body">'.$property->property_map.'</div></div><!-- /.modal-content --></div><!-- /.modal-dialog --></div><!-- /.modal --><div class="row border-top  pt-3"><div class="col-12 text-center"><span class="property-price my-2">'.$property->property_price.' تومان&nbsp;</span>'.$rent.'<a href="http://localhost:8000/property_info/'.$property->slug.'" class="btn btn-outline-primary float-left  my-2">بیشتر</a></div></div></div></div></div>';
            }

        }

        if($str==''){
                $str = '<div class="search-result-box border rounded "><p class="text-center"> موردی یافت نشد</p></div>';

        }


        return response()->json(['error' => '0', 'result' => $str,'msg'=>$r]);
    }
    public function paginate($items, $perPage = 1, $page = null, $options = [])
    {

        $perPage = 1;
        $page = null;
        $options = [];
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}