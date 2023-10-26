$(document).ready(function () {

    /* menu */
    var i = 10, t, n;
    $(window).scroll(function () {

        $(window).scrollTop() > i ? ($(".top-menu").slideUp(), $(".mega-menu").addClass("fixed"), $(".mega-menu .logo img").css("width", "100px")) : ($(".top-menu").slideDown(), $(".mega-menu").removeClass("fixed"), $(".mega-menu .logo img").css("width", "120px"));
        $(this).scrollTop() > i ? $(".scrollUp").show() : $(".scrollUp").hide()
    });
    $(".top-menu .login-link").click(function () {
        $(".top-menu .login-layer").slideToggle()
    });

    $('.mega-menu-responsive .fa-plus').click(function () {
        $(this).parent().find('ul').slideToggle()
    })
    $('.close-menu,.responsive-menu-layer').click(function () {
        $(".mega-menu-responsive").animate({right: "-500px"});
        $(".responsive-menu-layer").fadeOut("slow")
    })
    $('.open-menu').click(function () {
        $(".mega-menu-responsive").animate({right: "0px"});
        $(".responsive-menu-layer").fadeIn("slow")
    })


    jQuery(".owl-carousel").owlCarousel({
        autoplay: true,
        lazyLoad: true,
        loop: true,
        margin: 20,
        navText: [
            "<i class='fa fa-caret-left'></i>",
            "<i class='fa fa-caret-right'></i>"
        ],
        /*
         animateOut: 'fadeOut',
         animateIn: 'fadeIn',
         */
        responsiveClass: true,

        autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: true,
        responsive: {
            0: {
                items: 1
            },

            600: {
                items: 3
            },

            1024: {
                items: 4
            },

            1366: {
                items: 4
            }
        }
    });

   if($(".xzoom-container").length >0){
       $('.xzoom, .xzoom-gallery').xzoom({lens:true, title: true, tint: '#333', Xoffset: 15});

   }


   /*search box*/
    var p = new persianDate();
    var endDate='today';

    $('.inDate').persianDatepicker({
        startDate: 'today',
        formatDate: "YYYY-0M-0D",
        endDate: p.now().addDay(365).toString("YYYY/MM/DD"),
        calendarPosition: {
            x:20,
            y: 0,
        },
        onSelect: function (){ endDate=$('[name="inDate"]').val(); $('.outDate').focus() },
    });

    $('.outDate').persianDatepicker({
        startDate:endDate,
        formatDate: "YYYY-0M-0D",
        endDate: p.now().addDay(365).toString("YYYY/MM/DD"),
        calendarPosition: {
            x:20,
            y: 0,
        },
        onSelect: function (){ if($('[name="inDate"]').val() > $('[name="outDate"]').val()){  alertify.myAlert('تاریخ ورود نباید از تاریخ خروج کمتر باشد'); $('[name="outDate"]').val(''); }},
    })

    $('.form-search').find('select').customselect({"search":true });
    $('.form-search').find('button[type="submit"]').click(function(e) {
        // if ($(this).closest('.form-search').find('select').val() == 0)
        // {       e.preventDefault();
        //     alertify.myAlert('مقصد را وارد کنید');
        // }
    })



  /* rating and comment */

    $('.rating').find('input').click(function(){
        var value=$(this).val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'{{url("/property/rating")}}',
            method: 'POST',
            data: {value:value},
            success: function(data) {
                if(data.data>0)
                {
                    var rate=value.split('-')[0];
                    alertify.myAlert('رای شما با موفقیت ثبت شد');
                    $('.tour_rating').empty();
                    $('.tour_rating').append('امتیاز شما به این تور '+rate +'  از 5');
                    var stars='';
                    var i=1;


                    for(i=1;i<=5;i++)
                    {
                        if(i<=data.rating_value)
                        {
                            stars =stars+'<i class="fa fa-star text-warning"></i>';
                        }else
                        {
                            stars =stars+'<i class="fa fa-star text-muted"></i>';

                        }


                    }


                    $('.tour_rating').append('<p>'+stars+'</p>')
                }else
                {
                    alertify.myAlert('لطفا مجددا تلاش کنید')
                }
            },
            async: false
        });

    })


    $('.deal-comment__score-btn').click(function(){
        var property_id=$('.property_id').text();
        var customer_id=$('.customer_id').text();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/property/ifComment',
            method: 'POST',

            data: {property_id:property_id , customer_id:customer_id},
            success: function(data) {
                console.log(data);
                if(data.data ==='ok'){

                    $('#commentModal').modal('show');
                }else{
                    // alertify.myAlert(data.msg);
                    alert(data.msg);
                }
            },

        });
    });


    /* mask input */

    $('.traveler_code').mask('0000000000');
    setTimeout(function(){ $('.alert').fadeOut(); }, 3000);
    //setTimeout(function(){ location.reload(); }, 40000);
    $('.sign_code').mask('0000000000');

    $( ".sign_code" ).focusout(function() {
        if($(this).val().replace(/\s/g, '').length !=10){
            alert('تعداد اعداد کد ملی باید 10 رقم باشد')
            $('.sign_code').val('');

        }});
    $('.sign_mobile,.traveler_mobile').mask('09000000000');


    $("#email").focusout(function() {
        if ($(this).is(':valid')) {}else {$(this).val(''); alert('آدرس پست الکترونیک معتبری وارد کنید')}
    })

    //mask input
    //	$('[data-rel="chosen"],[rel="chosen"]').chosen();
    //     	$('.date').persianDatepicker();

    $('.traveler_code').mask('0000000000');
    setTimeout(function(){ $('.alert').fadeOut(); }, 3000);
    //setTimeout(function(){ location.reload(); }, 40000);
    $('.sign_code').mask('0000000000');

    $( ".sign_code" ).focusout(function() {
        if($(this).val().replace(/\s/g, '').length !=10){
            alert('تعداد اعداد کد ملی باید 10 رقم باشد')
            $('.sign_code').val('');

        }});
    $('.sign_mobile,.traveler_mobile').mask('09000000000');


    $("#email").focusout(function() {
        if ($(this).is(':valid')) {}else {$(this).val(''); alert('آدرس پست الکترونیک معتبری وارد کنید')}
    })







   //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches
        $(".next").click(function(event){

            var id = event.target.id;
            if(id==1){order_user(); }
            if(id==1.5){activate_user(customer_id);}
            if(id==2){ order_traveler(); }
            if(id==3){ add_order(customer_id);}

            if(error_ajax==0) {
                if(animating){   return false;}
                animating = true;
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                if($(this).hasClass('activate_btn'))
                {
                    alert('activate');
                    current_fs =$('.add_user_btn').parent();
                    next_fs = $('.add_user_btn').parent().next();
                }
                //activate next step on progressbar using the index of next_fs
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({opacity: 0}, {
                    step: function(now, mx) {
                        //as the opacity of current_fs reduces to 0 - stored in "now"
                        //1. scale current_fs down to 80%
                        scale = 1 - (1 - now) * 0.2;
                        //2. bring next_fs from the right(50%)
                        left = (now * 50)+"%";
                        //3. increase opacity of next_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({
                            'transform': 'scale('+scale+')',
                            'position': 'absolute'
                        });
                        next_fs.css({'left': left, 'opacity': opacity});
                    },
                    duration: 800,
                    complete: function(){
                        current_fs.hide();
                        animating = false;
                    },
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
            }else{
                // alertify.myAlert('خطایی رخ داده است. لطفا اطلاعات را بررسی کرده و مجددا امتحان کنید')
            }

        });
        $(".previous").click(function(){
            if(animating) return false;
            animating = true;

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function(now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1-now) * 50)+"%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({'left': left});
                    previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
                },
                duration: 800,
                complete: function(){
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });
        $(".submit").click(function(){
            return false;
        });
})

