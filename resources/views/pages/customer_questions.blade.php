@extends('layout')

@section('content')

    <main>
        <?php
        // Alert for success add new Category
        if (Session::get('msg')) {
            echo '<p class="alert alert-success text-right">';
            echo Session::get('msg');
            echo '</p>';

            Session::put('msg',null);
        }
        ?>
        <section class="container bg_white">
            <h2 class="title">

                سوالات ازمون

                <span class="pull-left"> <?php //echo 'ساعت جاری : '.jdate('H:i:s','','','','en'); ?></span>
            </h2>

            <form class="form-horizontal" action="{{URL::to('/customer/exam/save')}}" method="POST" enctype="multipart/form-data" id="myform">
                {{csrf_field()}}
                <input type="hidden" name="cusomer_id" value="{{$customer_id}}" />
                <input type="hidden" name="exam_id" value="{{$exam->exam_id}}" />
                <div class="row text-center">
                    <div class="col-12"><p class="text-primary">هنرستان شهید حسن قدمی</p></div>
                    <br/>
                    <div class="col-12 text-danger">َ


                        <ul class="text-right">
                            <li> هنرجویان گرامی موکدا عرض می‌شود نسبت به موارد مشروحه زیر توجه کامل داشته باشید</li>
                            <li>- برای پاسخگویی به سوالات میتوانید علاوه بر وارد کردن پاسخ هر سوال در جلوی هر سوال ، کل
                                سوالات را در قالب pdf  ارسال کنید.
                            </li>
                            <li> - بعد از ورود به آزمون حتما مشخصات هویتی و تحصیلی خود را توجه فرمایید.
                            </li>
                            <li> - در کلیه زمان آزمون مدت زمان باقیمانده آزمون را در نظر داشته باشید.</li>
                            <li> - بعد از پاسخ به هر سوال نسبت به ذخیره اطلاعات اقدام فرمایید.
                            </li>
                            <li> - درصورت اثبات تقلب نمره فرد خاطی صفر منظور می‌گردد.
                            </li>
                        </ul>
                        <input type="file" name="answer_file" class="form-control" />
                    </div>

                    <div class="row">
                        <img class="img-fluid" src="{{$customer->customer_image}}" />
                    </div>
                    <div class="col-sm-4 border-bottom p-1">نام درس:{{$course->course_name}}</div>
                    <div class="col-sm-4 border-bottom p-1">نام پایه:{{$class->class_name}}</div>
                    <div class="col-sm-4 border-bottom p-1">رشته تحصیلی:{{$field->field_name}}</div>

                    <div class="col-sm-4 border-bottom p-1">شماره هنرجو:{{$customer->customer_code}}</div>
                    <div class="col-sm-4 border-bottom p-1">نام هنرجو:{{$customer->customer_name}}</div>
                    <div class="col-sm-4 border-bottom p-1">نام خانوادگی هنرجو:{{$customer->customer_lname}}</div>


                    <div class="col-sm-4 border-bottom p-1" style="direction: ltr">نام آزمون:{{$exam->exam_name}}</div>
                    <div class="col-sm-4 border-bottom p-1" style="direction: ltr">تاریخ و ساعت آزمون:{{$exam->exam_time}} {{$exam->exam_date}}</div>
                    <div class="col-sm-4 border-bottom p-1">مدت زمان آزمون:{{$exam->exam_duration}}</div>

                    <div class="col-sm-4 border-bottom p-1">تعداد سوالات:{{$exam->exam_qNum}}</div>
                    <div class="col-sm-4 border-bottom p-1">نمره آزمون: {{$exam->exam_maxScore}}</div>
                    <div class="col-sm-4 border-bottom p-1">
                        مدت زمان باقیمانده:

                        <span id="time_remain">
                        <?php

                            $newTime=strtotime(jdate('H:i:s','','','','en'));

                            $startTime=strtotime($exam->exam_time);
                            $endTime = strtotime("+".$exam->exam_duration." minutes",strtotime($exam->exam_time));

                            //echo $newTime.'-'.$startTime.'-'.$endTime;


                            if($startTime<$newTime && $newTime<$endTime)
                            {
                                echo "<script type='text/javascript'>  setInterval(expTime('". $newTime."','". $startTime."','". $endTime."'),1000);</script>";
                            }
                            else{

                                $url='localhost:8000/customer/panel?message=مدت زمان آزمون به اتمام رسیده است';
                                ob_start();
                                header('Location: '.$url);
                                ob_end_flush();
                                die();
                            }
                            ?>
                    </span>

                        <span class="counter" style="direction: ltr" >

                        <span class="hours">
                            <span class="value">00</span>
                            :
                        </span>
                        <span class="minutes">
                            <span class="value">00</span>
                            :
                        </span>
                        <span class="seconds">
                            <span class="value">00</span>

                        </span>

                    </span>

                    </div>


                </div>


                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center table-sm">
                            <tr>
                                <td>شماره سوال</td>
                                <td>سوال</td>
                                <td>میزان بارم</td>
                                <td>پاسخ</td>
                            </tr>

                            @if(count($answers)>0)
                                answers here
                            @endif
                            @php($i=0)
                            @foreach($questions as $question)
                                @php($i=$i+1)
                                <tr>
                                    <input type="hidden" name="q_id[{{$i}}]" value="{{$question->q_id}}" />
                                    <td>{{$question->q_num}}</td>
                                    <td>@php(print_r($question->q_title))</td>
                                    <td>{{$question->q_grade}}</td>
                                    <td><textarea class="form_control" name="answer[{{$i}}]"></textarea></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4">
                                    <input class="btn btn-success" type="submit" value="ثبت پاسخ ها" />


                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </section>


    </main>
@endsection