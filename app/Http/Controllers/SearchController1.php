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

        $data = array();
        $data1 = array();

        $adults = array();
        $children = array();
        $ages = array();
        $hotels_id = array();
        $sum_room = array();
        $sum_room1 = array();
        $rooms_id1 = array();
        $rooms_id = array();
        $room_price = array();
        $min1 = array();


        $desinition = $request->desinition;
        $inDate = $request->inDate;

        $outDate = $request->outDate;
        $room_number = $request->room_number;


        for ($i = 1; $i <= $room_number; $i++) {
            //$hr_info[$i][1]
            $data['adult_number'] = $request->adult_number[$i];
            //$hr_info[$i][2]
            $data['child_number'] = $request->child_number[$i];

            for ($j = 0; $j <= $request->child_number[$i]; $j++) {
                $data['child_age'][$j] = $request->child_age;
            }
        }

        if ($inDate == '') {
            $inDate = '1395/01/01';
        }

        if ($outDate == '') {
            $outDate = '1500/12/29';
        }

        $adult_number = $request->adult_number;
        $child_number = $request->child_number;

        if (isset($adult_number)) {
            for ($j = 1; $j <= count($adult_number); $j++) {
                array_push($adults, $adult_number[$j]);

                    array_push($children, $child_number[$j]);

            }
        }
        $ages = array();

        if (isset($request->child_age)) {
            for ($i = 1; $i <= count($child_number); $i++) {
                $ages[$i] = array();
                if ($child_number[$i] != 0) {
                    $child_age = $request->child_age;

                    for ($j = 1; $j <= count($child_age[$i]); $j++) {
                        array_push($ages[$i], $child_age[$i][$j]);
                    }
                }
            }
        }

        session::put('search_desinition', $desinition);
        session::put('search_inDate', $inDate);
        session::put('search_outDate', $outDate);
        session::put('search_roomNumber', $room_number);

        session::put('search_adultNumber', $adults);
        session::put('search_childNumber', $children);
        session::put('search_childrenAge', $ages);
        session::put('search_allRooms', '');
        session::put('search_allPrices', '');
        session::put('search_minPrice', '');


        $keywords = 'دسته بندی هتل ها';
        $seo_description = '';
        $seo_title = 'هتل های';



        session::put('keywords', $keywords);
        session::put('seo_description', $seo_description);
        session::put('seo_title', $seo_title);


        $hotels = DB::table('tbl_hotel')
            ->orWhere(function ($query) use ($desinition) {
                $query->where('publication_status', 1)->where('category_parent3', '=', "$desinition");
            })
            ->orWhere(function ($query) use ($desinition) {
                $query->where('publication_status', 1)->where('category_parent1', '=', "$desinition");
            })
            ->orWhere(function ($query) use ($desinition) {
                $query->where('publication_status', 1)->where('category_parent2', '=', "$desinition");
            })
            ->pluck('hotel_id')->toArray();

        if(!empty($hotels)) {
            $hotels1 = DB::table('tbl_hotel_room')->select('hotel_id', DB::raw('count(*) as room_count'),'publication_status')->groupBy('hotel_id')->having('publication_status',1)->get();
            if (!empty($hotels1))
            {
                foreach ($hotels1 as $hotel) {
                    $flag = 0;
                    if (in_array($hotel->hotel_id, $hotels)) {
                        if ($hotel->room_count >= $request->room_number) {

                            $sum_room = array();
                            $rooms_id = array();
                            unset($sum_room);
                            unset($rooms_id);
                            for ($j = 0; $j < $request->room_number; $j++) {
                                $sum_room[$j] = array();
                                $rooms_id[$j] = array();
                                $rooms = DB::table('tbl_hotel_room')
                                    ->where('hotel_id', $hotel->hotel_id)
                                    ->where('child_capacity', '>=', $children[$j])
                                    ->where('adult_capacity', '>=', $adults[$j])
                                    ->where('room_number', '>=', 1)
                                    ->where('publication_status', 1)
                                    ->get();

                                $z=-1;
                                $sum_room2=array();
                                $count=array();
                                foreach ($rooms as $r) {
                                    $z=$z+1;
                                    $rooms_count = DB::table('tbl_bookedroom')
                                        ->where('room_id', $r->room_id)
                                        ->whereDate('br_inDate', '>=', date('Y/m/d', strtotime($inDate)))
                                        ->whereDate('br_outDate', '<=', date('Y/m/d', strtotime($outDate)))
                                        ->orderBy('room_price','ASC')->count();


                                    if($rooms_count <= $r->room_number){
                                        array_push($rooms_id[$j], $r->room_id);
                                        array_push($sum_room2, $r);
                                    }

                                }
                                array_push($sum_room[$j], $sum_room2);

                                if (count($rooms) == 0 || count($sum_room2) < $room_number) {
                                    $flag = 1;
                                    continue 2;
                                }
                            }
                            if ($flag == 0) {

                                array_push($hotels_id, $hotel->hotel_id);

                                array_push($sum_room1,$sum_room);
                                array_push($rooms_id1, $rooms_id);
                            }
                        }
                    }
                }

                $all_hotels = DB::table('tbl_hotel')->whereIn('hotel_id', $hotels_id)->paginate(10);

                $rates = array();
                foreach ($all_hotels as $at) {
                    $hotel_id = $at->hotel_id;
                    $rate_value = 0;
                    $sum = 0;
                    //rate value
                    $rate1 = DB::table('tbl_hotel_rate')->where('hotel_id', $hotel_id)->get();
                    if (count($rate1) > 0) {
                        $sum = 0;
                        foreach ($rate1 as $r1) {
                            $sum += $r1->rate_value;
                        }
                        $avg = $sum / count($rate1);
                        array_push($rates, $avg);
                    } else {
                        array_push($rates, 0);
                    }
                }

                // get price of selected room  room_price[][]
                $room_price = array();
                $sum_price = 0;
                //k is selected hotel number
                for ($k = 0; $k < count($rooms_id1); $k++) {
                    $room_price[$k] = array();
                    $i = -1;
                    //j is room number that customer is selected in search box

                    for ($j = 0; $j < count($rooms_id1[$k]); $j++) {
                        $room_price[$k][$j] = array();

                        //Z is selected list of rooms that have condition of search box
                        for ($z = 0; $z < count($rooms_id1[$k][$j]); $z++) {
                            $room_price[$k][$j][$z] = array();
                            $i = -1;

                            $room = DB::table('tbl_room_price')
                                ->where('room_id', $rooms_id1[$k][$j][$z])
                                ->where('room_toDate','>=', date('Y/m/d', strtotime($outDate)))
                                ->where('room_fromDate','<=', date('Y/m/d', strtotime($inDate)))
                                ->orderBy('room_price','ASC')->first();
                            if ($room != NULL) {
                                
                                
                                 $diff = date('d', strtotime($outDate) - strtotime($inDate))+1;
                                $room_price[$k][$j][$z] = $room->room_price * $diff;
                            }
                            else{
                                $room1 = DB::table('tbl_room_price')->where('room_id', $rooms_id1[$k][$j][$z])->where('room_fromDate','<=', date('Y/m/d', strtotime($inDate)))->orderBy('room_price','ASC')->first();
                                
                                $room2 = DB::table('tbl_room_price')->where('room_id', $rooms_id1[$k][$j][$z])->where('room_toDate','>=', date('Y/m/d', strtotime($outDate)))->orderBy('room_price','ASC')->first();
                                
                                if($room1 !==NULL && $room2 !==  NULL){
                                
                                    $room1_fromDate=$room1->room_fromDate;
                                    $room1_toDate=$room1->room_toDate;
                                    $room1_price=$room1->room_price;
                                    
                                    $room2_fromDate=$room2->room_fromDate;
                                    $room2_toDate=$room2->room_toDate;
                                    $room2_price=$room2->room_price;   
                                    
                                    
                                    
                                   $inDate1=date_create($inDate); 
                                   $room1_toDate1= date_create($room1_toDate);
                                   
                                   
                                   
                                   $diff1= date_diff($room1_toDate1 , $inDate1);
                                   
                                   $diff1=$diff1->y*365+$diff1->m*12+$diff1->d+1;
                                   
                                   
                                   $outDate1=date_create($outDate); 
                                   $room2_fromDate1= date_create($room2_fromDate);
                                   
                                   
                                   $diff2=date_diff ($outDate1 , $room2_fromDate1);
                                $diff2=$diff2->y*365+$diff2->m*12+$diff2->d+1;
                                 $room_price[$k][$j][$z] = $diff1*$room1_price+$diff2*$room2_price;
                                }
                                else{$room_price[$k][$j][$z] =0;}
                                
                            }
                        }
                    }
                }


                $min1 = array();
                
                // get min of hotels price for searched items
                if ($hotels1 && count($room_price) != 0) {
                    for ($i = 0; $i < count($room_price); $i++) {
                        for ($j = 0; $j < count($room_price[$i]); $j++) {

                           $min[$j] = (min(array_filter($room_price[$i][$j])));

                        }
                        $min1[$i] = array_sum($min);
                    }
                }
            } else {

                $all_hotels = '';
                $rates = '';
            }
        } else {
            $all_hotels = '';
            $rates = '';
        }


        $diff = date('d', strtotime($outDate) - strtotime($inDate))+1;

        session::put('search_stayDay', $diff);
        session::put('search_allRooms', $sum_room1);
        session::put('search_allPrices', $room_price);
        session::put('search_minPrice', $min1);
        session::put('search_hotelsId', $hotels_id);



        //menu
        $all_categories = DB::table('tbl_category')->where('publication_status', 1)->get();
        $internal_categories = DB::table('tbl_category')->where('publication_status', 1)->where('category_parent1', 1)->get();
        $external_categories = DB::table('tbl_category')->where('publication_status', 1)->where('category_parent1', 2)->get();
        $cat_parent1 = DB::table('tbl_category')->where('publication_status', 1)->where('category_parent1', 0)->get();
        $cats = DB::table('tbl_category')->where('publication_status', 1)->where('category_id', $desinition)->first();



        session::put('cat', $cats);
        return View('pages.hotels')->with([
            'all_categories' => $all_categories,
            'internal_categories' => $internal_categories,
            'external_categories' => $external_categories,
            'cat_parent1' => $cat_parent1,
            'all_hotels' => $all_hotels,
            'rates' => $rates,
            'cat' => $cats,
            'room_price' => $room_price,
            'min' => $min1,


        ]);
    }

    public function search_ajax(Request $request)
    {
        include 'jdf.php';
        $now = jdate('Y/m/d', '', '', '', 'en');

        $data = array();
        $data1 = array();
        $adults = array();
        $children = array();
        $ages = array();
        $hotels_id = array();
        $sum_room = array();
        $sum_room1 = array();
        $rooms_id1 = array();
        $rooms_id = array();
        $room_price = array();
        $min1 = array();
        $min = array();


        $desinition = session::get('search_desinition');
        $inDate = session::get('search_inDate');
        $outDate = session::get('search_outDate');
        $room_number = session::get('search_roomNumber');

        $adults = session::get('search_adultNumber');
        $children = session::get('search_childNumber');
        $ages = session::get('search_childrenAge');

        session::put('search_allRooms', '');
        session::put('search_allPrices', '');
        session::put('search_minPrice', '');

        $keywords = 'دسته بندی هتل ها';
        $seo_description = '';
        $seo_title = 'هتل های';


        session::put('keywords', $keywords);
        session::put('seo_description', $seo_description);
        session::put('seo_title', $seo_title);
        $hotel_name = $request->hotel_name;

         if($hotel_name != 'none'){

             $hotels = DB::table('tbl_hotel')
                 ->orWhere(function ($query) use ($desinition, $hotel_name) {
                     $query->where('publication_status', 1)->where('category_parent3', '=', "$desinition")->where('hotel_pName', 'like', "%" . $hotel_name . "%");
                 })
                 ->orWhere(function ($query) use ($desinition, $hotel_name) {
                     $query->where('publication_status', 1)->where('category_parent1', '=', "$desinition")->where('hotel_pName', 'like', "%" . $hotel_name . "%");
                 })
                 ->orWhere(function ($query) use ($desinition, $hotel_name) {
                     $query->where('publication_status', 1)->where('category_parent2', '=', "$desinition")->where('hotel_pName', 'like', "%" . $hotel_name . "%");
                 })
                 ->orWhere(function ($query) use ($desinition, $hotel_name) {
                     $query->where('publication_status', 1)->where('category_parent3', '=', "$desinition")->where('category_parent3', '=', "$desinition")->where('hotel_eName', 'like', "%" . $hotel_name . "%");
                 })
                 ->orWhere(function ($query) use ($desinition, $hotel_name) {
                     $query->where('publication_status', 1)->where('category_parent1', '=', "$desinition")->where('hotel_eName', 'like', "%" . $hotel_name . "%");
                 })
                 ->orWhere(function ($query) use ($desinition, $hotel_name) {
                     $query->where('publication_status', 1)->where('category_parent2', '=', "$desinition")->where('hotel_eName', 'like', "%" . $hotel_name . "%");
                 })
                 ->pluck('hotel_id')->toArray();

         }
         else{
             $hotels = DB::table('tbl_hotel')
                 ->orWhere(function ($query) use ($desinition) {
                     $query->where('publication_status', 1)->where('category_parent3', '=', "$desinition");
                 })
                 ->orWhere(function ($query) use ($desinition) {
                     $query->where('publication_status', 1)->where('category_parent1', '=', "$desinition");
                 })
                 ->orWhere(function ($query) use ($desinition) {
                     $query->where('publication_status', 1)->where('category_parent2', '=', "$desinition");
                 })
                 ->pluck('hotel_id')->toArray();
         }

         $ids=array();
        if (!empty($hotels)) {
            $hotels1 = DB::table('tbl_hotel_room')->select('hotel_id', DB::raw('count(*) as room_count'),'publication_status')->groupBy('hotel_id')->having('publication_status',1)->get();
            if (!empty($hotels1)) {
                foreach ($hotels1 as $hotel) {
                    $flag = 0;
                    if (in_array($hotel->hotel_id, $hotels)) {
                        if ($hotel->room_count >= $room_number) {

                            $sum_room = array();
                            $rooms_id = array();

                            unset($sum_room);
                            unset($rooms_id);
                            for ($j = 0; $j < $room_number; $j++) {
                                $sum_room[$j] = array();
                                $rooms_id[$j] = array();
                                $rooms = DB::table('tbl_hotel_room')
                                    ->where('hotel_id', $hotel->hotel_id)
                                    ->where('child_capacity', '>=', $children[$j])
                                    ->where('adult_capacity', '>=', $adults[$j])
                                    ->where('room_number', '>=', 1)
                                    ->where('publication_status', 1)
                                    ->get();
                                array_push($ids,$hotel->hotel_id);
                                $z=-1;
                                $count_roommm=array();
                                $sum_room2=array();
                                foreach ($rooms as $r) {
                                    $z=$z+1;
                                    $rooms_count = DB::table('tbl_bookedroom')
                                        ->where('room_id', $r->room_id)
                                        ->where('br_inDate', '>=', date('Y/m/d', strtotime($inDate)))
                                        ->where('br_outDate', '<=', date('Y/m/d', strtotime($outDate)))
                                        ->orderBy('room_price','ASC')->count();

                                    array_push($count_roommm,$rooms);
                                    if($rooms_count <= $r->room_number){
                                        array_push($rooms_id[$j], $r->room_id);
                                        array_push($sum_room2, $r);
                                    }
                                }
                                array_push($sum_room[$j], $sum_room2);
                                if (count($rooms) ==0 || count($sum_room2) < $room_number ){
                                    $flag = 1;
                                    continue;
                                }
                            }
                            if ($flag == 0) {
                                array_push($hotels_id, $hotel->hotel_id);
                                array_push($sum_room1, $sum_room);
                                array_push($rooms_id1, $rooms_id);
                            }
                        }
                    }
                }

                if(isset($request->arrange) && $request->arrange !=''){$arrange   = $request->arrange;}else{$arrange   ='ASC';}
                if(isset($request->order_val) && $request->order_val !=''){$order_val = $request->order_val;}else{$order_val = 'hotel_pName';}
                $hotel_rate=$request->hotel_rate;


                $all_hotels = DB::table('tbl_hotel')->whereIn('hotel_id', $hotels_id)->orderBy($order_val,$arrange)->get();

                $rates = array();
                $rates_hotelId = array();
                foreach ($all_hotels as $at) {
                    $hotel_id = $at->hotel_id;
                    array_push($rates_hotelId, $hotel_id);
                    $rate_value = 0;
                    $sum = 0;
                    //rate value
                    $rate1 = DB::table('tbl_hotel_rate')->where('hotel_id', $hotel_id)->get();
                    if (count($rate1) > 0) {
                        $sum = 0;
                        foreach ($rate1 as $r1) {
                            $sum += $r1->rate_value;
                        }
                        $avg = $sum / count($rate1);
                        array_push($rates, $avg);

                    } else {
                        array_push($rates, 0);

                    }
                }

                // get price of selected room  room_price[][]
                $room_price = array();
                $sum_price = 0;
                //k is selected hotel number
                for ($k = 0; $k < count($rooms_id1); $k++) {
                    $room_price[$k] = array();
                    $i = -1;
                    //j is room number that customer is selected in search box
                    for ($j = 0; $j < count($rooms_id1[$k]); $j++) {
                        $room_price[$k][$j] = array();
                        //Z is selected list of rooms that have condition of search box
                        for ($z = 0; $z < count($rooms_id1[$k][$j]); $z++) {
                            $room_price[$k][$j][$z] = array();
                            $i = -1;
                            $room = DB::table('tbl_room_price')
                                ->where('room_id', $rooms_id1[$k][$j][$z])
                                ->where('room_toDate', '>=', date('Y/m/d', strtotime($outDate)))
                                ->where('room_fromDate', '<=', date('Y/m/d', strtotime($inDate)))
                                ->orderby('room_price','ASC')->first();

                            if ($room != NULL) {
                                $room_price[$k][$j][$z] = $room->room_price;
                            }
                        }
                    }
                }
                $min1 = array();
                // get min of hotels price for searched items
                if ($hotels1 && count($room_price) != 0) {
                    for ($i = 0; $i < count($room_price); $i++) {
                        for ($j = 0; $j < count($room_price[$i]); $j++) {
                            $min[$j] = (min($room_price[$i][$j]));
                        }
                        $min1[$i] = array_sum($min);
                    }
                }
            } else {
                $all_hotels = '';
                $rates = '';
            }
        } else {
            $all_hotels = '';
            $rates = '';
        }

        $diff = date('d', strtotime($outDate) - strtotime($inDate))+1;

        session::put('search_stayDay', $diff);
        session::put('search_allRooms', $sum_room1);


        session::put('search_allPrices', $room_price);
        session::put('search_minPrice', $min1);
        session::put('search_hotelsId', $hotels_id);

        $str = '';

        if($all_hotels && count($all_hotels) > 0) {

            $ordered_hotel=array();

            foreach($all_hotels as $hotel){
                array_push($ordered_hotel,$hotel);
            }
            for($u=0;$u<count($ordered_hotel);$u++){
                $prop = 'min_price';
                $prop1='user_rate';
                for($w=0;$w<count($hotels_id);$w++){
                    if($ordered_hotel[$u]->hotel_id == $hotels_id[$w]){
                        $ordered_hotel[$u]->{$prop} = $min1[$w];

                    }
                    if($ordered_hotel[$u]->hotel_id == $rates_hotelId[$w]){
                        $ordered_hotel[$u]->{$prop1}=$rates[$w];
                    }


                }
            }

            if(isset($request->price_rate)){
                $price_rate=$request->price_rate;
                if($price_rate == 'priceASC'){
                    usort($ordered_hotel,function($first,$second){
                        return $first->min_price > $second->min_price;
                    });
                }elseif ($price_rate == 'priceDESC'){
                    usort($ordered_hotel,function($first,$second){
                        return $first->min_price < $second->min_price;
                    });
                }
            }

            $a=-1;
            $n=0;
            $final_hotels=array();
            $all_hotels=$ordered_hotel;
            $hotel_rate=$request->hotel_rate;
            if(empty($hotel_rate) || in_array(0,$hotel_rate)){
                $hotel_rate=[1,2,3,4,5];
            }
                foreach ($all_hotels as $hotel){
                    $rate = '';
                    $a=$a+1;
                    for ($k = 0; $k < $hotel->hotel_rate; $k++) {$rate = $rate . '<i class="fa fa-star text-warning" ></i >';}
                    if($hotel->min_price*$diff >= $request->from_range && $hotel->min_price*$diff <= $request->to_range && in_array($hotel->hotel_rate,$hotel_rate) ){
                            $n+=1;
                           array_push($final_hotels,$hotel);
                            $str = $str . '<div class="search-result-box border rounded "><div class="row"><div class="col-md-3 col-4"><img src="/images/hotels/thumbnail/' . $hotel->hotel_id . '.jpg"  alt="' . $hotel->alt_image . '" class="img-fluid" /></div><div class="col-md-9 col-8"><h2>' . $hotel->hotel_pName . '/' . $hotel->hotel_eName . $rate .'</h2><span class="hotel-rate"><b>امتیاز کاربران</b><label>' . $hotel->user_rate . '</label></span><p class="address-search-box text-muted"><i class="fa fa-map-marker"></i>&nbsp;' . $hotel->hotel_address . '</p><p class="text-danger"><i class=" fa fa-location-arrow"></i><a class="text-danger" data-toggle="modal" href="#map' . $hotel->hotel_id . '">نمایش روی نقشه</a></p><div class="modal fade" id="map' . $hotel->hotel_id . '"><div class="modal-dialog map-modal"><div class="modal-content"><div class="modal-header"><h4 class="modal-title text-right"> نقشه هتل '.$hotel->hotel_pName.'</h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></div><div class="modal-body">' . $hotel->hotel_map . '</div></div></div></div><div class="row border-top  pt-3"><div class="col-12 text-left"><span class="hotel-price"> شروع قیمت برای ' . ltrim(session::get('search_stayDay'), '0') . ' شب ' . number_format($hotel->min_price * session::get('search_stayDay')) . 'تومان &nbsp;</span><a href="https://it-maskoob.ir/hotel_info/' . $hotel->slug . '" class="btn btn-outline-primary float-left ">مشاهده لیست اتاق ها</a></div></div></div></div></div></div>';
                    }
                }
                if($str==''){ $str = '<div class="search-result-box border rounded "><p class="text-center"> موردی یافت نشد</p></div>';}
        }
        else {
                $str = '<div class="search-result-box border rounded "><p class="text-center"> موردی یافت نشد</p></div>';
        }
//
        $data='';
        // $data = $this->paginate($final_hotels);
//

        //$str=$request->hotel_name.'-'.$request->order_val.'-'.$request->arrange.'-'.$request->hotel_rate.'-'.$request->price_rate.'-'.$request->from_range.'-'.$request->to_range;
        return response()->json(['error' => '0', 'result' => $str,'msg'=>$data,'msg1'=>$count_roommm]);
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