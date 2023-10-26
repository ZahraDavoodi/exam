@extends('layout')

@section('content')

    <section id="ajancy_info" >
        <div class="container">

            <div class="row bg_white ">
                <h4 class="title">فعال سازی حساب</h4>
                <div class="container">
                   {{$msg}}
                    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="" title="ورود یا عضویت کاربران"><span class="fa fa-user"></span>
                    ورود کاربر

 </a>                    </div>


            </div>
        </div>
    </section>
@endsection