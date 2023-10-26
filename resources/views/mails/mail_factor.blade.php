<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>okShod | اوکی شد</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        @font-face{font-family:'hiw';src:url('../fonts/hiw.ttf')}


        body
        {
            direction:rtl !important;
        }
        .container
        {
            direction:rtl;
            width:90%;
            margin:auto;
            text-align:right;
            font-family:'Tahoma';
        }

        li img
        {
            width:20px;
            height: 20px;
        }
        li{
            list-style-type: none;
        }

        table
        {
            width:100%;
            border:1px solid rgba(0,0,0,0.2);
        }
        table tr:first-child
        {
            background:steelblue;
            color:#FFF;
        }
        table td
        {
            padding:10px;
            border:1px solid rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row" style="width: 100%; position: relative; padding: 10px; text-align: right">



        <h2 style="text-align:center">فاکتور نهایی ثبت سفارش سایت اوکی شد</h2>

        <h3 class="tour_name text-center">{{$tour->tour_name}}</h3>

        <div class="row">
            <div class="col-sm-6 ">
                کد پیگیری :
                <span class="tracking_code">{{$order->order_factorNum}}</span>
            </div>

            <div class="col-sm-6 ">
                تاریخ صدور فاکتور :
                <span class="order_date">{{$order->created_at}}</span>
            </div>
        </div>

        <table class="">
            <tbody><tr>
                <td> نام نماینده</td>
                <td class="agent_name">{{$customer->customer_name}}</td>
            </tr>

            <tr>
                <td> تعداد افراد </td>
                <td class="order_num">{{$order->order_num}} نفر</td>
            </tr>

            <tr>
                <td> رفت</td>

                <td class="tour_wentTime"> {{$tour->tour_wentTime}}-{{$tour->tour_wentHour}}-{{$tour->tour_wentDevice}}</td>
            </tr>
            <tr>
                <td> برگشت</td>
                <td class="tour_backTime"> {{$tour->tour_backTime}}-{{$tour->tour_backHour}}-{{$tour->tour_backDevice}}</td>
            </tr>
            <tr>
                <td> نام آژانس </td>
                <td class="ajancy_name"> {{$ajancy->ajancy_name}}</td>
            </tr>
            <tr>
                <td> تلفن آژانس </td>
                <td class="ajancy_phone">{{$ajancy->ajancy_phone}}</td>
            </tr>
            <tr>
                <td> آدرس ایمیل آژانس </td>
                <td class="ajancy_email">{{$ajancy->ajancy_email}}</td>
            </tr>
            <tr>
                <td> آدرس آژانس</td>
                <td class="ajancy_address"><?php print($ajancy->ajancy_address); ?></td>
            </tr>

            <tr>
                <td>مدارک مورد نیاز تور</td>
                <td class="tour_document"><?php print($tour->tour_document) ?></div></td>
    </tr>

    <tr>
        <td>خدمات تور</td
        <td><?php print($tour->tour_services) ?></td>
    </tr>

    <tr>
        <td>هزینه کل</td>
        <td class="order_price"><?php print($order->order_total) ?>تومان
        
        @foreach($coupons as $coupon)
          @if($coupon->coupon_id == $order->coupon_id)
             با استفاده از  
              {{$coupon->coupon_description}}
          @endif
        @endforeach
        
        </td>
    </tr>
    </tbody></table>

    <h3 class="title">اطلاعات مسافرین</h3>
    <table class="table table-stripped table-hover order_table_traveler">
        <tr><td>ردیف</td><td>نام مسافر</td><td>کد ملی</td><td>سن</td><td>قیمت</td><td>توضیحات</td></tr >

        <?php $i=0; ?>
         @foreach($traveler as $t)
         
         <?php $i=$i+1; ?>
         
         @if($t->traveler_age==1)
          
                  <?php $age='نوزاد'; ?>
         
         @elseif($t->traveler_age==2)
          <?php $age='خردسال 2 تا 6 سال'; ?>
         @elseif($t->traveler_age==3)
         <?php $age='کودک 6 تا 12 سال' ?>
         
         @elseif($t->traveler_age==4)
         
          <?php $age='بزرگسال'; ?>
         
         @endif
               
            <tr><td>{{$i}}</td><td>{{$t->traveler_name}}</td><td>{{$t->traveler_code}}</td><td>{{$age}}</td><td>{{$t->traveler_price}}</td><td>{{$t->traveler_des}}</td></tr>
         @endforeach
    </table>


</div>

<div class="row" style="width: 100%; position: relative; padding: 10px">
    <span class="col-sm-4" style="width: 30%; float: left"><a href="http://okshod.ir"><img src="http://okshod.com/frontend/images/logo.png" class="img-responsive"></a></span>
    <span class="col-sm-8" style="width: 70%; float: left;text-align: right; direction: rtl">
            <ul>
                <li><img src="https://cdn1.iconfinder.com/data/icons/office-2/512/phone_symbol_2-512.png"> 021-77990930  </li>
                <li><img src="https://image.flaticon.com/icons/png/512/54/54718.png"> 0912-4493811 </li>
                <li><img src="https://cdn3.iconfinder.com/data/icons/project-management-32/48/51-512.png"> info@okshod.ir </li>
                <li><img src="https://cdn0.iconfinder.com/data/icons/map-3/1024/pin9-512.png">تهران - پایین تر از میدان رسالت.خیابان شهید مدنی.روبروی مترو فدک.مجتمع تجاری اداری پالمیرا.طبقه سوم.واحد ۳۲۶ </li>
            </ul>
        </span>
</div>
</div>

</body>
</html>