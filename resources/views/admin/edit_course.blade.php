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
					<a href="{{URL::to('/okAdminShod/course/'.$course_infos->course_id.'/edit')}}">ویرایش درس</a>
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
						<form class="form-horizontal" action="{{URL::to('/okAdminShod/course/'.$course_infos->course_id.'/edit/done')}}" method="POST"  enctype="multipart/form-data">
							{{csrf_field()}}
						  <fieldset>

						  	<div class="control-group">
								<label class="control-label" for="course_name">عنوان </label>
								<div class="controls">
								  <input type="text" name="course_name" id="course_name" value="{{$course_infos->course_name}}">
								</div>
							</div>

							  <div class="control-group">
								  <label class="control-label" for="course_name">واحد</label>
								  <div class="controls">
									  <input type="number" name="course_unit" id="course_unit" value="{{$course_infos->course_unit}}">
								  </div>
							  </div>
							<div class="control-group">
							  <label class="control-label" for="publication_status">وضعیت انتشار</label>
							  <div class="controls">
								<input type="checkbox" class="cleditor" id="publication_status" name="publication_status" readonly
								  @if($course_infos->publication_status==1) checked   @endif
								/>
							  </div>
							</div>

							
							<div class="form-actions">
							  <button type="submit" class="btn btn-primary">ویرایش</button>
							<a class="btn btn-primary" href="{{URL::to('/okAdminShod/course/all')}}">انصراف</a>
							</div>
						  </fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->

			
    
@endsection