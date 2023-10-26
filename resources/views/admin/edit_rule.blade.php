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
					<a href="{{URL::to('/okAdminShod/add_ajancy')}}">بروزرسانی قوانین و مقررات </a>
				</li>
			</ul>

				<?php 
						// Alert for success add new ajancy
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
						<h2><i class="halflings-icon edit"></i><span class="break"></span> قوانین و مقررات</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					
					<div class="box-content">
						<form class="form-horizontal" action="
						{{
							URL::to(
								'/okAdminShod/rule/edit/done'
							)
						}}
							" method="POST" enctype="multipart/form-data" >
							{{csrf_field()}}
						  <fieldset>
						    @foreach ($info as $info)
							<div class="control-group hidden-phone">
							  <label class="control-label" for="ajancy_description">قوانین و مقررات</label>
							  <div class="controls">
								<textarea  id="ajancy_description" name="rule" class="cleditor" >
									{{$info->info_rule}}
								</textarea>
							  </div>
							</div>
							
							
								<div class="control-group hidden-phone">
							  <label class="control-label" for="ajancy_description">متن درباره ما</label>
							  <div class="controls">
								<textarea  id="ajancy_description" name="about" class="cleditor" >
									{{$info->info_about}}
								</textarea>
							  </div>
							</div>
							
								<div class="control-group hidden-phone">
							  <label class="control-label" for="ajancy_description">متن ما چه کار میکنیم در صفحه نخست</label>
							  <div class="controls">
								<textarea  id="ajancy_description" name="main_about" class="cleditor" >
									{{$info->info_main_about}}
								</textarea>
							  </div>
							</div>
							
								<div class="control-group hidden-phone">
							  <label class="control-label" for="ajancy_description">متن همکاری با ما</label>
							  <div class="controls">
								<textarea  id="ajancy_description" name="main_cooperation" class="cleditor" >
									{{$info->info_cooperation}}
								</textarea>
							  </div>
							</div>
							@endforeach


							


							<div class="form-actions">
							  <button type="submit" class="btn btn-success">بروزرسانی</button>
							  <a href="{{URL::to('/okAdminShod/ajancy/all')}}" class="btn">انصراف</a>
							</div>
						  </fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->


@endsection