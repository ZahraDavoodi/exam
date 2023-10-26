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
					<a href="#">بروزرسانی آزمون </a>
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
						
			
			<div class="row-fluid sortable" dir="rtl">
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon edit"></i><span class="break"></span> بروزرسانی آزمون</h2>
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
								'/okAdminShod/exam/'.$exam_infos->exam_id.'/edit/done'
							)
						}}
							" method="POST" enctype="multipart/form-data" id="myform">
							{{csrf_field()}}
							<fieldset>

								<div class="control-group">
									<label class="control-label" for="exam_name">نام ازمون <span>*</span> </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="exam_name" name="exam_name" value="{{$exam_infos->exam_name}}" required>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label" for="course_id">درس<span>*</span> </label>
									<div class="controls">
										<select id="course_id" name="course_id" data-rel="chosen" >
											<option value="0">یک مورد را انتخاب کنید</option>
											@foreach( $all_courses as $course)
												@if($course->publication_status)

													<option value="{{$course->course_id}}" @if($exam_infos->course_id ==$course->course_id )  selected @endif >{{$course->course_name}}</option>

												@endif
											@endforeach
										</select>
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<label class="control-label" for="prof_id">استاد<span>*</span></label>
										<select id="prof_id" name="prof_id" data-rel="chosen" required>
											<option value="0">لطفا استاد را انتخاب کنید</option>
											@foreach($all_profs as $prof)
												@if($prof->publication_status)

													<option value="{{$prof->prof_id}}" @if($exam_infos->prof_id ==$prof->prof_id )  selected @endif   >{{$prof->prof_name}}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>


								<div class="control-group">
									<label class="control-label" for="class_id">مخصوص دانش آموزان پایه<span>*</span> </label>
									<div class="controls">
										<select id="class_id" name="class_id" data-rel="chosen" >
											<option value="0">یک مورد را انتخاب کنید</option>
											@foreach( $all_classes as $class)
												@if($class->publication_status)

													<option value="{{$class->class_id}}" @if($exam_infos->class_id ==$class->class_id )  selected @endif >{{$class->class_name}}</option>

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

													<option value="{{$qt->qt_id}}" @if($exam_infos->qt_id ==$qt->qt_id )  selected @endif >{{$qt->qt_name}}</option>

												@endif
											@endforeach
										</select>
									</div>
								</div>

								<div class="control-group hidden-phone">
									<label class="control-label" for="exam_date">تاریخ آزمون
									<span>*</span>
									</label>
									<div class="controls">
										<input  class="form-control date" id="exam_date" name="exam_date" value="{{$exam_infos->exam_date}}" required />
									</div>
								</div>

								<div class="control-group hidden-phone">
									<label class="control-label" for="exam_time">ساعت آزمون
										<span>*</span>
									</label>
									<div class="controls">
										<input  class="form-control " id="exam_time" name="exam_time" value="{{$exam_infos->exam_time}}" placeholder=" مثال 13:15 " required  />
									</div>
								</div>
								<div class="control-group hidden-phone">
									<label class="control-label" for="exam_qNum">تعداد سوال
										<span>*</span>
									</label>
									<div class="controls">
										<input  type="number" min="0" max="100"  class="form-control "   id="exam_qNum" name="exam_qNum" value="{{$exam_infos->exam_qNum}}" />
									</div>
								</div>
								<div class="control-group hidden-phone">
									<label class="control-label" for="exam_maxScore">نمره امتحان
										<span>*</span>
									</label>
									<div class="controls">
										<input type="number" min="0" max="100"  class="form-control " id="exam_maxScore" name="exam_maxScore" value="{{$exam_infos->exam_maxScore}}" required />
									</div>
								</div>
								<div class="control-group hidden-phone">
									<label class="control-label" for="exam_duration">مدت زمان امتحان به دقیقه
										<span>*</span>
									</label>
									<div class="controls">
										<input type="number" min="0" max="1000"  class="form-control " id="exam_duration" name="exam_duration" value="{{$exam_infos->exam_duration}}" required />
									</div>
								</div>
								<div class="control-group hidden-phone">
									<label class="control-label" for="exam_publish">وضعیت انتشار </label>
									<div class="controls">
										<input type="checkbox" class="cleditor" id="publication_status" name="publication_status"
											   @if($exam_infos->publication_status)
											   checked
												@endif
										/>
									</div>
								</div>
									<div class="form-actions">
										<button type="submit" class="btn btn-primary" >ویرایش</button>
										<button type="reset" class="btn">انصراف</button>
									</div>
							</fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->


@endsection