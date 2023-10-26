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
					<a href="{{URL::to('/userHotel/add_order')}}">بروزرسانی سفارش </a>
				</li>
			</ul>

				<?php 
						// Alert for success add new order
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
						<h2><i class="halflings-icon edit"></i><span class="break"></span> بروزرسانی سفارش</h2>
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
								'/userHotel/order/'.$order_infos->order_id.'/edit/done'
							)
						}}
							" method="POST" >
							{{csrf_field()}}

							<div class="control-group">
								<label class="control-label" for="hotel_id">نام هتل<span>*</span> </label>
								<div class="controls">
									<select id="hotel_id" name="hotel_id" data-rel="chosen" required readonly>
										@foreach($all_hotels as $hotel)
											@if($hotel->publication_status)
												@if($hotel->hotel_id==$order_infos->hotel_id)
												<option value="{{$hotel->hotel_id}}" selected>{{$hotel->hotel_pName}}</option>
												@else
														<option value="{{$hotel->hotel_id}}">{{$hotel->hotel_pName}}</option>
												@endif
											@endif
										@endforeach
									</select>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="customer_id">نام مشترک<span>*</span> </label>
								<div class="controls">
									<select id="customer_id" name="customer_id" data-rel="chosen" required>
										@foreach($all_customers as $customer)
											@if($customer->publication_status)
												@if($customer->customer_id==$order_infos->customer_id)
													<option value="{{$customer->customer_id}}" selected>{{$customer->customer_name}}</option>
												@else
													<option value="{{$customer->customer_id}}">{{$customer->customer_name}}</option>
													@endif

											@endif
										@endforeach
									</select>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="order_price">هزینه سفارش (تومان)<span>*</span> </label>
								<div class="controls">
									<input type="text" class="input-xlarge" id="order_price" name="order_price" required value="{{$order_infos->order_total}}">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="order_price">تاریخ  ورود <span>*</span> </label>
								<div class="controls">
									<input type="text" class="input-xlarge" id="order_inDate" name="order_inDate" required value="{{$order_infos->order_inDate}}">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="order_price">تاریخ  خروج <span>*</span> </label>
								<div class="controls">
									<input type="text" class="input-xlarge" id="order_outDate" name="order_outDate" required value="{{$order_infos->order_outDate}}">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="order_status">وضعیت سفارش<span>*</span> </label>
								<div class="controls">
									<select id="order_status" name="order_status" data-rel="chosen" required>
										@foreach($order_status as $order_status)
											@if($order_status->publication_status)
												@if($order_status->os_id==$order_infos->order_status)
													<option value="{{$order_status->os_id}}" selected>{{$order_status->os_name}}</option>
												@else
													<option value="{{$order_status->os_id}}">{{$order_status->os_name}}</option>
													@endif
											@endif
										@endforeach
									</select>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="order_description">توضیحات سفارش  </label>
								<div class="controls">
									<textarea class="cleditor" id="order_description" name="order_description" >{{$order_infos->order_description}}</textarea>
								</div>
							</div>
							 
								
								<h4>لیست مسافران</h4>
								<div class="control-group">

									<div>

										<img id="add_row"  onclick="add_traveler_row({{$hotel_room.','.$order_infos->hotel_id}})" src="{{asset('backend/img/add.png')}}">
										<img id="add_row"  onclick="remove_traveler_row()" src="{{asset('backend/img/minus.png')}}">
										<table class="table  table-hover table-responsive" id="add_traveler_row">
											<thead>
											<th>نام <span>*</span></th>
											<th> نام خانوادگی<span>*</span></th>

											<th>سن<span>*</span></th>
											<th>تلفن همراه<span>*</span></th>
											<th> نام اتاق</th>
											
											<th>تاییده مدارک</th>	
											</thead>
											<tbody>
											@php ($i=0)
											@foreach($hotel_traveler as $traveler)
												@php($i=$i+1)
												<tr>
													<td>
														<input type="text" name="traveler_name[{{$i}}]" required value="{{$traveler->traveler_name}}">
													</td>

													<td>
														<input type="text" name="traveler_lname[{{$i}}]" required value="{{$traveler->traveler_lname}}">
													</td>
													<td>
														@if($traveler->traveler_age != null)
														<input  name="traveler_age[{{$i}}]" value="{{$traveler->traveler_age}}">
														@else
															بزرگسال
														@endif
													</td>
													<td>

														<input  name="traveler_mobile[{{$i}}]" value="{{$traveler->traveler_mobile}}">
													</td>
													<td>
														<select name="room_id[{{$i}}]">
															@foreach($hotel_room as $room)
																<option value="{{$room->room_id}}" @if($room->room_id==$traveler->room_id) selected @endif>{{$room->room_name}}</option>
															@endforeach
														</select>
													</td>

													<td><input type="checkbox" name="traveler_submit[{{$i}}]" class="form-control" 	@if($traveler->traveler_submit==1){{'checked'}}@endif></td>
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</div>


								<div class="control-group hidden-phone">
									<label class="control-label" for="order_publish">وضعیت انتشار </label>
									<div class="controls">
										<input type="checkbox" class="cleditor" id="publication_status" name="publication_status" checked />
									</div>
								</div>

						




							<div class="form-actions">
							  <button type="submit" class="btn btn-success">بروزرسانی</button>
							  <a href="{{URL::to('/userHotel/order/all')}}" class="btn">انصراف</a>
							</div>
						  </fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->


@endsection