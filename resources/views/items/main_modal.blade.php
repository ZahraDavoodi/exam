<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <ul class="nav nav-tabs">
                    <li class="nav-item "><a class="nav-link active" data-toggle="tab" href="#login_tab"> ورود کاربران</a></li>
                    <li class="nav-item "><a  class="nav-link" data-toggle="tab" href="#signup_tab">عضویت کاربر</a></li>

                </ul>
                <button type="button" class="close float-left" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?php if (Session::get('msg_modal')) {?>
             
             <?php echo '<p class="alert alert-success">';
                    echo Session::get('msg_modal');
                    echo '</p>';
                Session::put('msg_modal',null);
                }
                ?>
                <div class="tab-content">
                    <div id="login_tab" class="tab-pane active">
                        <p> فرم زیر را تکمیل نمایید. وارد کردن تمام موارد ستاره دار (* ) الزامیست</p>
                        <form role="form" class=" form-group" action="{{URL::to('/login')}}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            {{ csrf_field() }}
                            <div class="form-group ">
                                <label for="username">کد ملی *</label>
                                <input type="text" class="form-control" id="username" placeholder="کد ملی خودتان را وارد کنید" name="customer_code">
                            </div>
                            <div class="form-group">
                                <label for="pwd">رمز عبور *</label>
                                <input type="password" class="form-control" id="pwd" name="customer_password">
                            </div>


                            <div class="checkbox">

                                <input type="hidden" name="check" value="sended">

                                <input type="checkbox" name="remember" value="true">
                                مرا به خاطر بسپار
                            </div>
                            <div class="forgot">
                                <a  href="{{URL::to('/changePassword')}}"> رمز عبور خود را فراموش کردید</a>
                            </div>


                            <button type="submit" class="button btn-block my-2">ورود</button>
                        </form>
                    </div>
                    <div id="signup_tab" class="tab-pane ">
                        <p> فرم زیر را تکمیل نمایید. وارد کردن تمام موارد ستاره دار (* ) الزامیست</p>
                        <form role="form" action="/register" method="post" class="text-right row" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="type" value="signUp">
                            <div class="col-md-6 col-12 ">
                                <label for="sign_lname">نام *</label>
                                <input type="text" class="form-control" id="sign_lname" name="customer_name" required>
                            </div>
                            <div class="col-md-6 col-12 ">
                                <label for="sign_lname">نام خانوادگی *</label>
                                <input type="text" class="form-control" id="sign_lname" name="customer_lname" required>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="sign_rpass">شماره همراه *</label>
                                <input type="text" class="form-control sign_mobile"  name="customer_phone" required>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="sign_rpass">کد ملی *</label>
                                <input type="text" class="form-control sign_code"  name="customer_code" required>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="sign_pass">رمز عبور *</label>
                                <input type="password" class="form-control" id="sign_pass" name="customer_password" required>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="sign_rpass">تکرار رمز عبور *</label>
                                <input type="password" class="form-control" id="sign_rpass" name="re_password">
                            </div>


                            <div class="col-md-6 col-12">
                                <label for="sign_rpass">مقطع تحصیلی</label>
                                <select id="class_id" name="class_id" data-rel="chosen" class="form-control" >
                                    <option value="0">یک مورد را انتخاب کنید</option>
                                    @foreach( $all_classes as $class)
                                        @if($class->publication_status)

                                            <option value="{{$class->class_id}}" >{{$class->class_name}}</option>

                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="sign_rpass">رشته تحصیلی</label>
                                <select id="field_id" name="field_id" data-rel="chosen" class="form-control" >
                                    <option value="0">یک مورد را انتخاب کنید</option>
                                    @foreach( $all_fields as $field)
                                        @if($field->publication_status)

                                            <option value="{{$field->field_id}}" >{{$field->field_name}}</option>

                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12">
                                <label for="sign_rpass">آدرس ایمیل</label>
                                <input type="email" class="form-control" id="sign_email" name="customer_email" required>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="sign_rpass">تصویر کاربر</label>
                                <input type="file" class="form-control" id="sign_image" name="customer_image" >
                            </div>

                            <button type="submit" class="button btn-block my-2" name="submit" onclick="auth();">تایید و مرحله بعدی</button>
                        </form>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="myModal_activate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                           </div>
            <div class="modal-body">
                کد ارسالی به ایمیل خود را در این کادر وارد نمایید
                <div class="col-12">
                
                    <input type="text" name="activate_user" class="form-control activate_user">
                    <br/>
                    <input type="button" name="submit" id="1.5" class=" next action-button activate_btn" value="فعال سازی" id="activate_btn">
                
                </div>
            </div>

        </div>

    </div>
</div>





