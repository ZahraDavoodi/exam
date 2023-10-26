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
        <ul class="nav nav-tabs">
          <li class="nav-tab"><a class="nav-link active" data-toggle="tab" href="#home">لیست امتحانات</a></li>
          <li class="nav-tab"><a  class="nav-link" data-toggle="tab" href="#menu2">تغییر رمز عبور</a></li>
          
        </ul>
        
        <div class="tab-content customer_panel p-4 shadow-sm">
          <div id="home" class="customer_order tab-pane  active table-responsive">
              <table class="table table-bordered table-striped ">
                  <tr class="bg-primary text-white">
                      <td>نام آزمون</td>
                      <td>تاریخ آزمون</td>
                      <td>نام استاد</td>
                      <td>وضعیت</td>
                  </tr>
              @foreach($all_exams as $exam)
                     <tr>
                         <td>{{$exam->exam_name}}</td>
                         <td>{{$exam->exam_time}} {{$exam->exam_date}}</td>
                         <td>
                             @foreach($all_profs as $prof)
                                 @if($prof->prof_id == $exam->prof_id)
                                     {{$prof->prof_name}}
                                 @endif
                             @endforeach
                         </td>
                         <td>
                         <?php
                                $ExpTime    = $exam->exam_time;
                                $ExpDate    = $exam->exam_date;
                                $ExpDuration= $exam->exam_duration;

                                $now=jdate('Y-m-d H:i:s','','','','en');
                               //echo "<script type='text/javascript'> var elem=$('.counter');  expTime(elem,'". $ExpDate."');</script>";
                                $exam_date=$exam->exam_date.' '.$exam->exam_time.':00';

                                $date1 = new DateTime($now);

                                $date2 = new DateTime($exam_date);

                                $interval = $date1->diff($date2);
                                $month=$interval->m;
                                $day=$interval->d;
                                $hour=$interval->h;
                                $minute=$interval->i;
                                $second=$interval->s;

//                                print_r($interval);
                                if($month ==0 && $day ==0){
                                    $newTime   = strtotime(jdate('H:i:s','','','','en'));
                                    $startTime = strtotime($exam->exam_time);
                                    $endTime   = strtotime("+".$ExpDuration." minutes",strtotime($exam->exam_time));
                                    //echo $newTime.'-'.$startTime.'-'.$endTime;
                                    if($startTime<$newTime && $newTime<$endTime){



                                        echo '<a class="btn btn-primary" href="http://localhost:8000/customer/runExam/'.$exam->exam_id.'">ورود به آزمون</a>';

                                        ?>
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

                                 }
                                 ?>
                    </span>
                                        <?php

                                    }else{
                                        //echo $newTime.'-'.$startTime.'-'.$endTime;
                                        echo 'ساعت امتحان فرا نرسیده یا گذشته است';
                                    }

                                }
                                else{
                                    echo 'تاریخ امتحان هنوز فرا نرسیده و یا از آن گذشته است';
                                    echo '<br/>';
                                    echo "مدت زمان  " . $interval->d . " روز, " . $interval->h." ساعت, ".$interval->i." دقیقه ".$interval->s." ثانیه ";
}

/*{{--                                }--}}

{{--// shows the total amount of days (not divided into years, months and days like above)--}}

{{--                                /*$exam_date=$exam->exam_date.' '.$exam->exam_time;--}}
{{--                                echo $now;--}}
{{--                                echo '<br/>';--}}
{{--                                echo $exam_date;--}}
{{--                                $now=date_create($now);--}}
{{--                                $exam_date=date_create($exam_date);--}}
{{--                                $diff=date_diff($now,$exam_date);--}}
{{--                                echo '<br/>';--}}
{{--                                print_r( $diff);*/
                                //var_dump($diff);--}}
                           ?>

                         </td>
                     </tr>

              @endforeach
              </table>
          </div>
          <div id="menu1" class="tab-pane fade">
              <form class="form-group" action="{{URL::to('/customer/property/save')}}" method="POST" enctype="multipart/form-data" id="myform">
                  {{csrf_field()}}
                  <fieldset class="row">
                      <div class="row col-12">
                          <p class="text-center">کاربران عزیز ، لطفا پس از افزودن اطلاعات ، مبلغ آگهی را به شماره حساب 2222222  واریز نموده و تصویر واریزی را به همراه کد آگهی به شماره تلفن 09127070210 از طریق واتس اپ ارسال کنید. </p>
                      </div>




                  </fieldset>
              </form>
          </div>
          <div id="menu2" class="tab-pane fade">
              
            <form role="form" class=" form-group col-12"  method="POST" action="{{URL::to('/updatePass')}}">
                           {{ csrf_field() }}
                           <input type="hidden" name="id" value="{{$customer_id}}">
                           <input type="hidden" name="type" value="customer">
                            <div class="form-group ">
                                <label for="username">آدرس ایمیل</label>
                                <input type="email" class="form-control" id="username" placeholder="نام کاربری خود را وارد نمایید" name="customer_email" value="{{$customer_email}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="pwd">رمز عبور</label>
                                <input type="password" class="form-control" id="pwd1" name="customer_password">
                            </div>
                           <div class="form-group">
                                <label for="pwd">تکرار رمز عبور</label>
                                <input type="password" class="form-control" id="pwd2" name="customer_repassword">
                            </div>

                            <button type="submit" class="btn btn-primary">تایید</button>
                        </form>
          </div>
        </div>
    </section> 
   
   <div class="modal" id="myModal_travelers">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p class="float-right"></p>
                           </div>
            <div class="modal-body">
               <tabel class="table  table-responsive">
                   <tr>
                      <td>نام مسافر</td>
                      <td>کد ملی</td>
                      <td>سن مسافر</td>
                   </tr>
               </tabel>
            </div>

        </div>

    </div>
</div>
</main>


@endsection