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
					<a href="#">نمایش پاسخ های ارسال شده  </a>
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
						{{URL::to('/okAdminShod/exam/'.$exam_infos->exam_id.'/save_grade')}}
							" method="POST" enctype="multipart/form-data" id="myform">
							{{csrf_field()}}
							<fieldset>

								<div class="control-group">
									<label class="control-label" for="exam_name">نام ازمون <span>*</span> </label>
									<div class="controls">
										<input type="text" class="input-xlarge" id="exam_name" name="exam_name" value="{{$exam_infos->exam_name}}" required >
									</div>
								</div>
								<div class="control-group">
									<table class="table table-bordered table-striped">
										<tr>
											<td>نام و نام خانوادگی</td>
											<td>ایمیل</td>
											<td>دانلود فایل آپلود شده</td>
											<td>مشاهده پاسخ ها </td>
											<td>نمره </td>
										</tr>
                                        @php($i=0)
										<input type="hidden" name="exam_id" value="{{$exam_infos->exam_id}}" />
										@foreach($all_customers as $customer)
											<tr>
												@php($i=$i+1)
												<input type="hidden" name="customer_id" value="{{$customer->customer_id}}" />
												<td>{{$customer->customer_name}}</td>
												<td>{{$customer->customer_email}}</td>
												<td>
													@if(file_exists(public_path().'/exam_files/' . $customer->customer_id .'-'.$exam_infos->exam_id.'.pdf'))
														<a href="{{'/exam_files/' . $customer->customer_id .'-'.$exam_infos->exam_id.'.pdf'}}">دانلود فایل </a>
													@elseif(file_exists(public_path().'/exam_files/' . $customer->customer_id .'-'.$exam_infos->exam_id.'.docx'))
															<a href="{{'/exam_files/' . $customer->customer_id .'-'.$exam_infos->exam_id.'.docx'}}">دانلود فایل </a>
													@elseif(file_exists(public_path().'/exam_files/' . $customer->customer_id .'-'.$exam_infos->exam_id.'.jpg'))
															<a href="{{'/exam_files/' . $customer->customer_id .'-'.$exam_infos->exam_id.'.jpg'}}">دانلود فایل </a>
													@elseif(file_exists(public_path().'/exam_files/' . $customer->customer_id .'-'.$exam_infos->exam_id.'.png'))
															<a href="{{'/exam_files/' . $customer->customer_id .'-'.$exam_infos->exam_id.'.png'}}">دانلود فایل </a>
													@else
																	پاسخی به صورت فایل اپلود نشده است
													@endif
												</td>
												<td>
													<a href="#answers{{$customer->customer_id}}" class="btn btn-primary" data-toggle="collapse" >نمایش پاسخ ها</a>
												</td>
												@php($mygrade='');
												@foreach($all_grades as $grade)
													@if($grade->customer_id== $customer->customer_id && $grade->exam_id ==$exam_infos->exam_id )
														@php($mygrade=$grade->grade_grade)
													@endif
												@endforeach
												<td><input  name="grade[{{$i}}]" type="number" class="form-control" min="0" max="20" value="{{$mygrade}}" /> </td>
											</tr>
											<tr>
												<td colspan="5">
													<div id="answers{{$customer->customer_id}}" class="col-xs-12 collapse show" style="margin-right: 20px">
														<table class="table table-sm table-striped">
															<tr>
																<td>شماره </td>
																<td>بارم</td>
																<td>سوال</td>
																<td>پاسخ</td>
															</tr>
                                                                  @php($j=0)
																  @foreach($all_questions as$question)
																  @php($j=$j+1)
																<tr>
																	<td>{{$j}} </td>
																	<td>{{$question->q_grade}}</td>
																	<td>{{$question->q_title}}</td>
																	<td>
																		@foreach($all_answers as $answer)
																			@if($customer->customer_id==$answer->customer_id && $question->q_id == $answer->q_id )
																			  {{$answer->answer_answer}}
																			@endif
																		@endforeach

																	</td>
																</tr>
																@endforeach

														</table>
													</div>
												</td>

											</tr>
										@endforeach
									</table>


									echo "<script type='text/javascript'> var elem=$('#offer .col-sm-3').eq(".$i.");  expTime(elem,'". $ExpDate."');</script>";



								</div>
									<div class="form-actions">
										<button type="submit" class="btn btn-primary" >تایید</button>
										<button type="reset" class="btn">انصراف</button>
									</div>
							</fieldset>
						</form>
					</div>
				</div><!--/span-->
			</div><!--/row-->
@endsection