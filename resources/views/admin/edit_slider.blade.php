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
					<a href="{{URL::to('/okAdminShod/slider/'.$slider_infos->slider_id.'/edit')}}">ویرایش اسلایدر</a>
				</li>
			</ul>

				<?php 
						// Alert for success add new Category
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
						<h2><i class="halflings-icon edit"></i><span class="break"></span>ویرایش </h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					
					<div class="box-content">
						<form class="form-horizontal" action="{{URL::to('/okAdminShod/slider/'.$slider_infos->slider_id.'/edit/done')}}" method="POST"  enctype="multipart/form-data">
							{{csrf_field()}}
						  <fieldset>

						  	<div class="control-group">
								<label class="control-label" for="slider_title">عنوان </label>
								<div class="controls">
								  <input type="text" name="slider_title" id="slider_title" value="{{$slider_infos->slider_title}}">
								</div>
							</div>

						  	<div class="control-group">
								<label class="control-label" for="slider_link">لینک دکمه  </label>
								<div class="controls">
								  <input type="text" name="slider_link" id="slider_link" value="{{$slider_infos->slider_link}}">
								</div>
							</div>

					  		<div class="control-group">
							  <label class="control-label" for="slider_description">توضیحات  </label>
							  <div class="controls">
								<textarea class="cleditor" id="slider_description" name="slider_description" rows="3">{{$slider_infos->slider_description}}</textarea>
							  </div>
							</div>

							<div class="control-group">
								<label class="control-label" for="slider_image">تصویر اسلایدر </label>
								<div class="controls">
								  <input type="file" name="slider_image" id="slider_image">
								<img src="https://okshod.com/{{$slider_infos->slider_image}}" class="hotel_img">
								</div>
								
							</div>

						

							<div class="control-group">
							  <label class="control-label" for="publication_status">وضعیت انتشار</label>
							  <div class="controls">
							      
								<input type="checkbox" class="cleditor" id="publication_status" name="publication_status" />
							  </div>
							</div>

							
							<div class="form-actions">
							  <button type="submit" class="btn btn-primary">ویرایش</button>
							<a class="btn btn-primary" href="{{URL::to('/okAdminShod/slider/all')}}">انصراف</a>
							</div>
						  </fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->

			
    
@endsection