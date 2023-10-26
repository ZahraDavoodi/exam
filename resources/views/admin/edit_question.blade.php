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
					<a href="{{URL::to('/okAdminShod/question/add')}}">بروزرسانی سوال </a>
				</li>
			</ul>

				<?php 
						// Alert for success add new question
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
						<h2><i class="halflings-icon edit"></i><span class="break"></span>به روز رسانی نظرات</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					
					<div class="box-content">
						<form class="form-horizontal" action="{{URL::to('okAdminShod/question/'.$question_infos->q_id.'/edit/done')}}" method="POST" enctype="multipart/form-data" >
							{{csrf_field()}}
							 <fieldset>
                             <input type="hidden" name="question_id" value={{$question_infos->q_id}} />
						  	<div class="control-group">
								<label class="control-label" for="question_code">  عنوان پیام <span>*</span> </label>
								<div class="controls">
									<input type="text" name="q_title" id="q_title" required value="{{$question_infos->q_title}}">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="q_answer">متن پیام <span>*</span> </label>
								<div class="controls">
								  <textarea class="form-control"  name="q_answer" id="q_answer" required> {{$question_infos->q_answer}}</textarea>
								</div>
							</div>

							<div class="control-group">
							  <label class="control-label" for="publication_status">وضعیت انتشار</label>
							  <div class="controls">
								<input type="checkbox" class="cleditor" id="publication_status" name="publication_status" checked />
							  </div>
							</div>

							
							<div class="form-actions">
							  <button type="submit" class="btn btn-primary">ویرایش</button>
							   <a class="btn" href={{URL::to('/okAdminShod/question/all')}}>انصراف</a>
							</div>
						  </fieldset>
						 
						</form>   
						


					</div>
				</div><!--/span-->

			</div><!--/row-->

<div class="modal" id="questionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                

            </div>
            <div class="modal-body">
              <form  action="{{URL::to('/question/save')}}" method="POST" id="product_question">
                  	{{csrf_field()}}

                  <textarea class="form-control" name="question_message" placeholder="نظر خود را وارد کنید "></textarea>
                  <button type="submit" class="btn btn-primary btn-block"> ارسال</button>
              </form>
            </div>

        </div>

    </div>
</div>

@endsection