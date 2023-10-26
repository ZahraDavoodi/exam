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
					<a href="{{URL::to('/okAdminShod/room/add')}}">افزودن اتاق </a>
				</li>
			</ul>

				<?php 
						// Alert for success add new hotel
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
						<h2><i class="halflings-icon edit"></i><span class="break"></span>افزودن اتاق جدید</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					
					<div class="box-content">
						<form class="form-horizontal" action="{{URL::to('/okAdminShod/room/save')}}" method="POST" enctype="multipart/form-data" id="myform">
							{{csrf_field()}}
						  <fieldset>
							  <div class="control-group">
								  <label class="control-label" for="hotel_type">نام هتل<span>*</span> </label>
								  <div class="controls">
									  <select name="hotel_id" required data-rel="chosen">
										  <option value="0">لطفا انتخاب کنید.</option>
										  @foreach($all_hotels as $hotel)
											  <option value="{{$hotel->hotel_id}}">{{$hotel->hotel_pName}}</option>
										  @endforeach
									  </select>
								  </div>
							  </div>
							  <?php
							  $option='';
							  foreach($all_conditions as $condition)
							  {
								  $option.='<option value='.$condition->condition_id.'>'.$condition->condition_name.'</option>';
							  }
							  ?>
							  <img id="add_row"  onclick="add_hotel('{{$option}}')" src="{{asset('backend/img/add.png')}}">
							  <img id="add_row"  onclick="remove_hotel()" src="{{asset('backend/img/minus.png')}}">
							  <div class="table-responsive">
								  <table class="table table-hovered table-bordered" id="add_room">
									  <thead>
									    <tr>
											<th> نام اتاق<span class="text-danger">*</span></th>
											<th>امکانات<span class="text-danger">*</span></th>
											<th>ظرفیت اتاق (تعداد بزرگسال)  <span class="text-danger">*</span></th>
											<th>ظرفیت اتاق (تعداد کودک) <span class="text-danger">*</span></th>
											<th>تعداد اتاق <span class="text-danger">*</span></th>
											<th>قیمت اتاق- تومان<span class="text-danger">*</span></th>
											<th>شرایط کنسلی<span class="text-danger">*</span></th>
											<th>تاریخ</th>
											<th>وضعیت انتشار</th>
										</tr>
									  </thead>
									  <tbody>
									     <tr>
											 <td><input type="text" id="room_name" name="room_name[1]" required></td>
											 <td>
												 <select name="room_condition[1]" required data-rel="chosen">
													 <option value="0">لطفا انتخاب کنید.</option>
													 @foreach($all_conditions as $condition)
														 <option value="{{$condition->condition_id}}">{{$condition->condition_name}}</option>
													 @endforeach
												 </select>
											 </td>
											 <td><input type="number" id="adult_capacity" name="adult_capacity[1]" required min="1" max="10" step="1"></td>
											 <td><input type="number" id="child_capacity" name="child_capacity[1]" required min="1" max="10" step="1"></td>
											 <td><input type="number" class="input-xlarge" id="room_number" name="room_number[1]" required></td>
											 <td><a  href="#demo1" data-toggle="collapse">قیمتهای اتاق</a></td>
											 <td><textarea class="input-xlarge" id="room_cancel" name="room_cancel[1]" required></textarea></td>
											 <td></td>
											 <td><input type="checkbox" class="cleditor" id="publication_status" name="publication_status[1]" checked /></td>

										 </tr>

										 <tr>
											 <td colspan="8">
												 <div id="demo1" class="col-xs-12 collapse show">
													 <div class="col-xs-12">
														 <img  onclick="add_price(1)" src="{{asset('backend/img/add.png')}}" class="img_add" title="افزودن قیمت">
														 <img  onclick="remove_price(1)" src="{{asset('backend/img/minus.png')}}" class="img_add" title="حذف قیمت">
													 </div>
															 <div class="row row_price">
																 <div class="col-xs-4"><label>از تاریخ</label><input type="text" class="date"  name="room_fromDate[1][1]" value=""/></div>
																 <div class="col-xs-4"><label>تا تاریخ</label><input type="text" class="date"  name="room_toDate[1][1]"  /></div>
																 <div class="col-xs-4"><label>قیمت</label><input type="number"  name="room_price[1][1]" /></div>
															 </div>

												 </div>
											 </td>

										 </tr>



									  </tbody>
								  </table>
							  </div>


							<div class="form-actions">
							  <button type="button" class="btn btn-primary" onclick="validate_hotel()">افزودن</button>
							  <button type="reset" class="btn">انصراف</button>
							</div>
						  </fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->


    
@endsection