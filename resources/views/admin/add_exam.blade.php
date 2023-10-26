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
			<a href="{{URL::to('/okAdminShod/exam/add')}}"> افزودن ازمون</a>
		</li>
	</ul>

	<?php
	// Alert for success add new exam
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
				<h2><i class="halflings-icon edit"></i><span class="break"></span>افزودن ازمون جدید</h2>
				<div class="box-icon">
					<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
					<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
					<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
				</div>
			</div>

			<div class="box-content">
				<form class="form-horizontal" action="{{URL::to('/okAdminShod/exam/save')}}" method="POST" enctype="multipart/form-data" id="myform">
					{{csrf_field()}}
					<fieldset>

						<div class="control-group">
							<label class="control-label" for="exam_name">نام ازمون <span>*</span> </label>
							<div class="controls">
								<input type="text" class="input-xlarge" id="exam_name" name="exam_name" required>
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<label class="control-label" for="prof_id">استاد<span>*</span></label>

								<select id="prof_id" name="prof_id" data-rel="chosen" required>
									<option value="0">لطفا استاد را انتخاب کنید</option>
									@foreach($all_profs as $prof)
										@if($prof->publication_status)

											<option value="{{$prof->prof_id}}" >{{$prof->prof_name}}</option>
										@endif
									@endforeach
								</select>
							</div>


						</div>


						<div class="control-group">
							<label class="control-label" for="course_id">درس<span>*</span> </label>
							<div class="controls">
								<select id="course_id" name="course_id" data-rel="chosen" >
									<option value="0">یک مورد را انتخاب کنید</option>
									@foreach( $all_courses as $course)
										@if($course->publication_status)

											<option value="{{$course->course_id}}" >{{$course->course_name}}</option>

										@endif
									@endforeach
								</select>
							</div>
						</div>




						<div class="control-group">
							<label class="control-label" for="class_id">
								مقطع تحصیلی
							</label>


							<div class="controls">
								<select id="class_id" name="class_id" data-rel="chosen" >
									<option value="0">یک مورد را انتخاب کنید</option>
									@foreach( $all_classes as $class)
										@if($class->publication_status)

											<option value="{{$class->class_id}}" >{{$class->class_name}}</option>

										@endif
									@endforeach
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="qt_id">نوع ازمون<span>*</span> </label>
							<div class="controls">
								<select id="qt_id" name="qt_id" data-rel="chosen" >
									<option value="0">یک مورد را انتخاب کنید</option>
									@foreach( $all_qts as $qt)
										@if($qt->publication_status)

											<option value="{{$qt->qt_id}}" >{{$qt->qt_name}}</option>

										@endif
									@endforeach
								</select>
							</div>
						</div>

						<div class="control-group hidden-phone">
							<label class="control-label" for="exam_date">

								تاریخ آزمون
								<span>*</span>
							</label>
							<div class="controls">
								<input  class="form-control date" id="exam_date" name="exam_date" value="" required />
							</div>
						</div>

						<div class="control-group hidden-phone">
							<label class="control-label" for="exam_time">

								ساعت آزمون
								<span>*</span>
							</label>
							<div class="controls">
								<input  class="form-control " id="exam_time" name="exam_time" value="" placeholder=" مثال 13:15 " required />
							</div>
						</div>
						<div class="control-group hidden-phone">
							<label class="control-label" for="exam_qNum">تعداد سوال
								<span>*</span>
							</label>
							<div class="controls">
								<input  type="number" min="0" max="100"  class="form-control "   id="exam_qNum" name="exam_qNum" value="" required />
							</div>
						</div>
						<div class="control-group hidden-phone">
							<label class="control-label" for="exam_maxScore">نمره امتحان
								<span>*</span>
							</label>
							<div class="controls">
								<input type="number" min="0" max="100"  class="form-control " id="exam_maxScore" name="exam_maxScore" value=""  required/>
							</div>
						</div>
						<div class="control-group hidden-phone">
							<label class="control-label" for="exam_duration">مدت زمان امتحان به دقیقه
								<span>*</span>
							</label>
							<div class="controls">
								<input type="number" min="0" max="1000"  class="form-control " id="exam_duration" name="exam_duration" value="" required />
							</div>
						</div>
						<div class="control-group hidden-phone">
							<label class="control-label" for="property_publish">وضعیت انتشار </label>
							<div class="controls">
								<input type="checkbox" class="cleditor" id="publication_status" name="publication_status" checked />
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-primary" >افزودن</button>
							<button type="reset" class="btn">انصراف</button>
						</div>
					</fieldset>
				</form>

			</div>
		</div><!--/span-->

	</div><!--/row-->



@endsection