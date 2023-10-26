@extends('admin.layout')
@section('admin_area')

		<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="{{URL::to('/okAdminShod/dashboard')}}">پیشخوان</a> 
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="{{URL::to('/okAdminShod/questions/all')}}">همه ی سوال ها</a></li>
			</ul>
						 <?php 
						// Alert for success add new question
							if (Session::get('msg')) {
								echo '<p class="alert alert-success text-right">';
								echo Session::get('msg');
								echo '</p>';

								Session::put('msg',null);
							}
							?>
			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon user"></i><span class="break"></span>همه ی سوال ها</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bcommented bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>ردیف</th>
								  <th>سوال</th>
								  <th>وضعیت</th>
								  <th>عملیات</th>
							  </tr>
						  </thead>   
						  <tbody>
						  <?php $i=0;
						  	foreach($all_questions as $question){
						  	 
						?>		
								<tr>
								<td>{{ $i}}</td>
							
								<td>{{$question->q_title}}</td>

							
								<td class="center">
									@if($question->publication_status)
									<span class="label label-success">فعال</span>

									@else
									<span class="label label-unsuccess">غیرفعال</span>
									@endif 


								</td>
								<td class="center">
									
                            	@if($question->publication_status)
									<a class="btn btn-unsuccess" title="  تغییر وضعیت به حالت  غیر فعال" href="{{URL::to('/okAdminShod/question/'.$question->q_id.'/unactive')}}">
										<i class="halflings-icon white remove"></i>  
									</a>
									@else
									<a class="btn btn-success" title="تغییر وضعیت به حالت  فعال" href="{{URL::to('/okAdminShod/question/'.$question->q_id.'/active')}}">
										<i class="halflings-icon white ok"></i>  
									</a>
									@endif

									<a class="btn btn-info" title="ویرایش" href="{{URL::to('/okAdminShod/question/'.$question->q_id.'/edit')}}">
										<i class="halflings-icon white edit"></i>  
									</a>

									<a class="btn btn-danger" title="حذف" href="{{URL::to('/okAdminShod/question/'.$question->q_id.'/delete')}}" onclick="return confirm('آیا مطمئن هستید ؟  ')">
										<i class="halflings-icon white trash"></i> 
									</a>
								</td>
							</tr>
							<?php  $i++; } ?>
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
@endsection