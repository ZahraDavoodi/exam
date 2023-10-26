@extends('layout')

@section('content')

    <section >
        <div class="container">

            <div class="row bg_white ">
                
                @if($error==0)
                <h6 class="title">از خرید شما متشکریم</h6>
                <div class="container">
                    <div class="col-xs-12">
                    
                      <p class="text-center">
                         ایمیلی شامل این کد برای شما ارسال شده است
                          
                        <h2 class="text-center">کد پیگیری خرید شما : <?php echo $msg; ?></h2> 
                      </p>
                    </div>
          </div>
               @else
               خطا:
               {{$msg}}
               @endif
    </section>
@endsection