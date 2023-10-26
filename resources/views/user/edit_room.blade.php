@extends('user.layout')
@section('admin_area')

			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="{{URL::to('/userHotel/dashboard')}}">پیشخوان</a>
					<i class="icon-angle-right"></i> 
				</li>
				<li>
					<i class="icon-edit"></i>
					<a href="#">ویرایش اتاق </a>
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
						<h2><i class="halflings-icon edit"></i><span class="break"></span>ویرایش</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>
					
					<div class="box-content">
						<form class="form-horizontal" action="{{URL::to('/userHotel/room/'.$hotel->hotel_id.'/edit/done')}}" method="POST" enctype="multipart/form-data" id="myform">
							{{csrf_field()}}
						  <fieldset>
							  <div class="control-group">
								  <h3 class="text-center">لیست اتاقهای  {{ $hotel->hotel_pName }}</h3>
								  <div class="controls">
									  <input type="hidden" name="hotel_id" value="{{$hotel->hotel_id}}" />
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
											<th>وضعیت انتشار</th>
										</tr>
									  </thead>
									  <tbody>
									     @php($i=0)
									     @foreach($rooms as $room)
											 @php($i=$i+1)
										     <tr>

                                             <td><input type="text" id="room_name" name="room_name[{{$i}}]" value="{{$room->room_name}}" required></td>
											 <td>
												 <select name="room_condition[{{$i}}]" required data-rel="chosen">
													 <option value="0">لطفا انتخاب کنید.</option>
													 @foreach($all_conditions as $condition)
														 <option value="{{$condition->condition_id}}" @if($condition->condition_id == $room->room_condition) selected @endif>{{$condition->condition_name}}</option>
													 @endforeach
												 </select>
											 </td>
											 <td><input type="number" id="adult_capacity" name="adult_capacity[{{$i}}]" required min="1" max="10" step="1" value="{{$room->adult_capacity}}"></td>
											 <td><input type="number" id="child_capacity" name="child_capacity[{{$i}}]" required min="1" max="10" step="1" value="{{$room->child_capacity}}"></td>
											 <td><input type="number" class="input-xlarge" id="room_number" name="room_number[{{$i}}]" required value="{{$room->room_number}}"></td>
											 <td>

											{{--<input type="number" name="room_price" value="{{$room->room_price}}" />--}}
                                                 <a  href="#demo{{$room->room_id}}" data-toggle="collapse">قیمتهای اتاق</a>


											 </td>
											 <td> <textarea class="input-xlarge" id="room_cancel" name="room_cancel[{{$i}}]" required>{{$room->room_cancel}}</textarea></td>


											 <td><input type="checkbox" class="cleditor" id="publication_status" name="publication_status[{{$i}}]" checked /></td>
										 </tr>
                                             <tr>
                                                 <td colspan="8">
                                                     <div id="demo{{$room->room_id}}" class="col-xs-12 collapse show" style="margin-right: 20px">
                                                        <div class="col-xs-12">
                                                            <img  onclick="add_price({{$room->room_id}})" src="{{asset('backend/img/add.png')}}" class="img_add" title="افزودن قیمت">
                                                            <img  onclick="remove_price({{$room->room_id}})" src="{{asset('backend/img/minus.png')}}" class="img_add" title="حذف قیمت">
                                                        </div>
                                                         @php($j=0)

                                                         @foreach($all_prices as $price)
                                                             @if($price->room_id == $room->room_id)
                                                                 @php($j=$j+1)

                                                                    <div class="row row_price" >
                                                                        <div class="col-xs-4"><label>از تاریخ</label><input type="text" class="date"  name="room_fromDate[{{$i}}][{{$j}}]" value="{{$price->room_fromDate}}"/></div>
                                                                        <div class="col-xs-4"><label>تا تاریخ</label><input type="text" class="date"  name="room_toDate[{{$i}}][{{$j}}]" value="{{$price->room_toDate}}" /></div>
                                                                        <div class="col-xs-4"><label>قیمت</label><input type="number"  name="room_price[{{$i}}][{{$j}}]" value="{{$price->room_price}}"/></div>
                                                                    </div>
                                                             @endif
                                                         @endforeach
                                                     </div>
                                                 </td>

                                             </tr>

										 @endforeach
									  </tbody>
								  </table>
							  </div>

							<div class="form-actions">
							  <button type="button" class="btn btn-primary" onclick="validate_hotel()">ویرایش</button>
							  <button type="reset" class="btn">انصراف</button>
							</div>
						  </fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->


    
@endsection