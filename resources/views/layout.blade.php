<!DOCTYPE html>
<!-- Microdata markup added by Google Structured Data Markup Helper. -->
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{Session::get('seo_title')}} | exam | آزمون</title>


    <meta name="description" content=" {{Session::get('seo_description')}}">
    <meta name="keywords" content="{{Session::get('keywords')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="" sizes="16x16" type="image/png">
    <!--alertify.rtl.css,bootstrap.min.css,bootstrap-3.2.rtl.css,owl.carousel.min.css,owl.theme.default.min.css,persianDatepicker-default.css,font-awesome.min.css,style.css-->
    <link href="{{asset('frontend/css/font-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{asset('frontend/css/owl.carousel.min.css')}}" rel="stylesheet" />
    <link href="{{asset('frontend/css/owl.theme.default.min.css')}}" rel="stylesheet" />
    <link href="{{asset('frontend/css/alertify.rtl.css')}}" rel="stylesheet" />
    <link href="{{asset('frontend/css/persianDatepicker-default.css')}}" rel="stylesheet" />
    <link href="{{asset('frontend/css/xzoom.css')}}" rel="stylesheet" />
    <link href="{{asset('frontend/css/jquery-customselect-1.9.1.css')}}" rel="stylesheet" />
    <link href="{{asset('frontend/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('frontend/css/bootstrap-rtl.min.css')}}" rel="stylesheet" />
    <link href="{{asset('frontend/css/ion.rangeSlider.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('frontend/css/style.css')}}">

{!! NoCaptcha::renderJs() !!}
<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145795272-1"></script>
<!--<script async src="{{asset('frontend/js/gtag.js')}}"></script>-->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-145795272-1');

        function expTime(newTime,startTime,endTime){

                // var timeStart = new Date().toLocaleDateString('fa-IR').replace(/([۰-۹])/g, token => String.fromCharCode(token.charCodeAt(0) - 1728));
                // newTime=timeStart;

            setInterval(function(){
                if(newTime>startTime && newTime<endTime){
                    newTime=parseInt(newTime)+1;
                    var diff = endTime - newTime;
                    console.log(diff);
                    var h = Math.floor(diff / 3600);
                    var m = Math.floor((diff - (h * 3600)) / 60);
                    var s = Math.floor((diff - (h * 3600) - (m * 60)));
                    $('.counter').text(h+':'+m+':'+s);
                }
                else{
                    alert('زمان ازمون به پایان رسید');
                    var url = "http://localhost:8000";
                    $(location).attr('href',url);
                }
            }, 1000);

            setInterval(function (){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'http://localhost:8000/exam/save',
                    method: 'POST',
                    data: {property_name:selectedVal1,order_val:order_val,arrange:arrange,city_id:city_id,area_id:area_id,property_rate:property_rate,property_attr:property_attr,from_range:from_range,to_range:to_range},
                    success: function(data) {
                        console.log(data.msg);

                        $('.result-holder').html(data.result);
                        $('.pagination').rpmPagination({domElement:'.search-result-box',limit:10,currentPage: 1});
                    },
                    async: false
                });

            },6000)
        }
    </script>
</head>
<body>

@include('items.main_modal')

<nav class="sticky">
    <div class="content ">
        @include('items.main_menu')
    </div>
    </div>
</nav>
<?php

if (Session::get('msg')) {
    echo '<p class="alert alert-success">';
    echo Session::get('msg');
    echo '</p>';
    Session::put('msg',null);
}
// $session_time='';
// if (Session::get('refresh')=='active')
// {  
//     // echo '<p class="alert alert-success">';
//     // echo 'set shodeh'.Session::get('refresh');//Session::get('msg');
//     // echo '</p>';
// }else
// {
//     session::put('refresh','active');
//     $session_time=time()+600;
//     header("Refresh:40");
//     // echo '<p class="alert alert-success">';
//     // echo 'set nashodeh'.Session::get('refresh')=='active';//Session::get('msg');
//     // echo '</p>';
// }
?>
@yield('content')
@include('items.footer')

<script src="{{asset('frontend/js/jquery.min.js')}}"></script>
<script src="{{asset('frontend/js/popper.min.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap.min.js')}}"></script>
<script src="{{asset('frontend/js/easing.js')}}"></script>
<script src="{{asset('frontend/js/owl.carousel.js')}}"></script>
<script src="{{asset('frontend/js/migrate.min.js')}}"></script>
<script src="{{asset('frontend/js/persianDatepicker.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.mask.js')}}"></script>
<script src="{{asset('frontend/js/xzoom.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery-customselect-1.9.1.min.js')}}"></script>
<script src="{{asset('frontend/js/alertify.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery_session.js')}}"></script>
<script src="{{asset('frontend/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('frontend/js/pagination.min.js')}}"></script>
<script src="{{asset('frontend/js/custom.js')}}"></script>

<script>
    $(document).ready(function () {
        setInterval(saveExam,10800);

        function saveExam(){
            var cat_id = $(this).find('select').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },
                url: '/okAdminShod/category/select_ajax2',
                method: 'POST',
                data: {cat_id: cat_id},
                success: function (data) {

                    if (data) {

                        $('#area_id').html('');
                        $('#area_id').append('<select  id="area_id1" class="form-control" name="area_id" data-rel="chosen" ><option value="0">یک دسته بندی انتخاب کنید</option></select>');


                        $.each(data, function (index, val) {

                            for (k = 0; k < val.length; k++) {
                                //console.log(val[k].category_name);
                                var option = '<option value="' + val[k].area_id + '">' + val[k].area_name + '</option>';
                                $('[name=area_id]').append(option);
                            }
                        });
                    } else {
                        alert('مرورگر از اجکس پشتیبانی نمیکند');
                    }
        }


    })


</script>

<script>
    (function () {
        var urlParams = new URLSearchParams(window.location.search);
        //alert(urlParams)
        if(urlParams.has('section')){
            var section='#'+urlParams.get('section')
            $('html, body').animate({
                scrollTop: $(section).offset().top-50
            }, 1000);
            if(urlParams.has('type')){
                var type=urlParams.get('type')
                $('a[href="#' + type + '"]').tab('show');
            }
        }
    })()
</script>
<script type='text/javascript'>
    var onWebChat={ar:[], set: function(a,b){if (typeof onWebChat_==='undefined'){this.ar.
    push([a,b]);}else{onWebChat_.set(a,b);}},get:function(a){return(onWebChat_.get(a));},
        w:(function(){ var ga=document.createElement('script'); ga.type = 'text/javascript';
            ga.async=1;ga.src=('https:'==document.location.protocol?'https:':'http:') +
                    '//www.onwebchat.com/clientchat/d01a7394583435ecee1a09c31236c534';var s=
                    document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga,s);})()}
</script>
</body>
</html>



