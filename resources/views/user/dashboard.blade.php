@extends('user.layout')

@section('admin_area')

			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="{{URL::to('/userHotel')}}">پیشخوان</a>
					<i class="icon-angle-right"></i>
				</li>


			</ul>


			<div class="row-fluid">

				<a class="quick-button metro yellow span2" href="#">
					<i class="fa fa-comment"></i>
					<p>نظرات</p>
					{{--<span class="badge">237</span>--}}
				</a>
				<a class="quick-button metro red span2" href="#">
					<i class="fa fa-question"></i>
					<p>سوالات متداول</p>
					{{--<span class="badge">46</span>--}}
				</a>
				<a class="quick-button metro green span2" href="#">
					<i class="fa fa-file-text"></i>
					<p>دسته بندی</p>
					{{--<span class="badge">13</span>--}}
				</a>
				<a class="quick-button metro blue span2" href="#">
					<i class="fa fa-user"></i>
					<p>مشترکین</p>
				</a>
				<a class="quick-button metro pink span2" href="{{URL::to('/userHotel/order/all')}}">
					<i class="fa fa-shopping-cart"></i>
					<p>سفارشات</p>

				</a>
				<a class="quick-button metro black span2" href="{{URL::to('/userHotel/room/'.Session::get('hotel_id').'/all')}}">
					<i class="fa fa-suitcase"></i>
					<p>اتاق ها</p>
				</a>

				<div class="clearfix"></div>

			</div>


	

@endsection

