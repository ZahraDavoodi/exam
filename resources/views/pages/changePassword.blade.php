@extends('layout')

@section('content')
<main>

    <section id="ajancy_info" >
        <div class="container">

            <div class="row shadow p-2 ">
                <h4 class="title">تغییر رمز عبور</h4>
                <div class="container">


                    <form role="form" class=" form-group col-12"  method="POST" action="{{URL::to('/updatePass')}}">
                        {{ csrf_field() }}
                        <div class="form-group ">

                            <input type="hidden" class="form-control"  name="type" value="{{$type}}" readonly>
                        </div>
                        <div class="form-group ">
                            <label for="username">آدرس ایمیل</label>
                            <input type="email" class="form-control" id="username" placeholder="نام کاربری خود را وارد نمایید" name="customer_email" value="{{$email}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="pwd">رمز عبور</label>
                            <input type="password" class="form-control" id="pwd1" name="customer_password">
                        </div>
                        <div class="form-group">
                            <label for="pwd">تکرار رمز عبور</label>
                            <input type="password" class="form-control" id="pwd2" name="customer_repassword">
                        </div>

                        <button type="submit" class="">تایید</button>
                    </form>
                </div>


            </div>
        </div>
    </section>
</main>

@endsection