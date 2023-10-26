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
					<a href="#">لیست سوالات آزمون</a>
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
						<h2><i class="halflings-icon edit"></i><span class="break"></span>سوالات آزمون</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					
					<div class="box-content">
						<form class="form-horizontal" action="{{URL::to('/okAdminShod/question/save')}}" method="POST" enctype="multipart/form-data" id="myform">
							{{csrf_field()}}
						  <fieldset>

							<div class="control-group">
							  <label class="control-label" for="exam_name">نام ازمون <span>*</span> </label>
							  <div class="controls">
								  <input type="hidden" name="exam_id"  value="{{$exam_infos->exam_id}}">
								  <input type="text" class="input-xlarge" id="exam_name" name="exam_name" required value="{{$exam_infos->exam_name}}" readonly>
							  </div>
							</div>


							  @for($i=0;$i<$exam_infos->exam_qNum;$i++)

								  <div class="control-group">
									  <label class="control-label" for="question{{$i}}"> سوال   {{ $i+1 }} </label>
									  <div class="controls">
										  <input type="hidden" name="q_num[{{$i}}]" value="{{$i+1}}" />
										  <input placeholder="بارم" name="q_grade[{{$i}}]" value="@if(isset($questions[$i]->q_grade)){{$questions[$i]->q_grade}}@endif"/>
										  <textarea  id="question{{$i}}" name="question[{{$i}}]" class="cleditor" >
											  @if(isset($questions[$i]->q_title))
											     {{$questions[$i]->q_title}}
											  @endif
										  </textarea>
									  </div>
								  </div>
							   @endfor


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