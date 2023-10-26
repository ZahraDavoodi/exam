@extends('admin.layout')
@section('admin_area')

			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="{{URL::to('/okAdminShod/dashboard')}}">پیشخوان</a>
					<i class="icon-angle-right"></i> 
				</li>
				<li>
					<i class="icon-edit"></i>
					<a href="{{URL::to('/okAdminShod/prof/add')}}"> افزودن مربی</a>
				</li>
			</ul>

				<?php 
						// Alert for success add new prof
							if (Session::get('msg')) {
								echo '<p class="alert alert-success">';
								echo Session::get('msg');
								echo '</p>';

								Session::put('msg',null);
							}
						?>
						
			
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon edit"></i><span class="break"></span>افزودن مربی جدید</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					
					<div class="box-content">
						<form class="form-horizontal" action="{{URL::to('/okAdminShod/prof/save')}}" method="POST">
							{{csrf_field()}}
						  <fieldset>

							<div class="control-group">
							  <label class="control-label" for="prof_name">نام مربی <span>*</span> </label>
							  <div class="controls">
								<input type="text" class="input-xlarge" id="prof_name" name="prof_name" required>
							  </div>
							</div>
                              <div class="control-group">
                                  <label class="control-label" for="prof_code">کد ملی <span>*</span> </label>
                                  <div class="controls">
                                      <input type="text" class="input-xlarge" id="prof_code" name="prof_code" required>
                                  </div>
                              </div>
                              <div class="control-group">
                                  <label class="control-label" for="prof_password">رمز عبور <span>*</span> </label>
                                  <div class="controls">
                                      <input type="password" class="input-xlarge" id="prof_password" name="prof_password" required>
                                  </div>
                              </div>

                              <div class="control-group">
                                  <label class="control-label" for="prof_email">ایمیل<span>*</span> </label>
                                  <div class="controls">
                                      <input type="text" class="input-xlarge" id="prof_email" name="prof_email" required>
                                  </div>
                              </div>
							  <div class="control-group">
								  <label class="control-label" for="prof_phone">تلفن<span>*</span> </label>
								  <div class="controls">
									  <input type="text" class="input-xlarge" id="prof_phone" name="prof_phone" required>
								  </div>
							  </div>




					<div class="control-group hidden-phone">
						<label class="control-label" for="prof_publish">وضعیت</label>
						<div class="controls">

							<input type="checkbox" class="cleditor" id="publication_status" name="publication_status"/>

						</div>
					</div>




							<div class="form-actions">
							  <button type="submit" class="btn btn-primary">افزودن</button>
							  <button type="reset" class="btn">انصراف</button>
							</div>
						  </fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->

			
    
@endsection