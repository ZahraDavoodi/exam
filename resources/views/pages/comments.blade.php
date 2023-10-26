@extends('layout')

@section('content')
    <main>

        {{ Breadcrumbs::render('comments') }}
        <section id="comment">

            <div class="container">
                <div class="row">


                    <div class="col-12 shadow p-2">

                        <h4 class="title">ثبت نظرات</h4>
                        <?php
                        // Alert for success add new airline
                        if (Session::get('msg')) {
                            echo '<p class="alert alert-success">';
                            echo Session::get('msg');
                            echo '</p>';

                            Session::put('msg',null);
                        }
                        ?>
                        <p>در صورتی که نظر و یا شکایتی در مورد هرکدام از آژانس ها و یا تورها دارید ، از طریق فرم زیر، اقدام نمایید. در بخش موضوع ، کد تور یا آژانس مورد نظر را وارد نمایید.همکاران ما اولین فرصت با شما تماس خواهند گرفت</p>
                        <form class="form-group" action="{{URL::to('/save_comment')}}" method="post">
                            {{csrf_field()}}
                            <div class="col-sm-12">
                                <label>نام*</label>
                                <input type="text" placeholder="لطفا نام خود را وارد نمایید" class="form-control" name="comment_name" required>
                            </div>
                            <div class="col-sm-12">
                                <label>آدرس ایمیل*</label>
                                <input type="text" placeholder="لطفا آدرس پست الکترونیک خود را وارد نمایید" class="form-control" name="comment_email" required>

                            </div>
                            <div class="col-sm-12">
                                <label>تلفن همراه*</label>
                                <input type="text" placeholder="لطفا شماره تلفن همراه خود را وارد نمایید" class="form-control" name="comment_mobile"  required>

                            </div>
                            <div class="col-sm-12">
                                <label>موضوع*</label>
                                <input type="text" placeholder="لطفا موضوع پیام خود را وارد نمایید" class="form-control"  name="comment_subject" required>
                            </div>
                            <div class="col-sm-12">
                                <label>متن پیام*</label>
                                <textarea  class="form-control" name="comment_message"  required></textarea>
                            </div>

                            <div class="col-sm-12">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4">
                                    <input type="submit" value="ارسال">
                                </div>
                                <div class="col-sm-4"></div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </main>

@endsection