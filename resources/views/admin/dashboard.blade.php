@extends('admin.layout')

@section('admin_area')

			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="{{URL::to('/okAdminshod')}}">پیشخوان</a>
					<i class="icon-angle-right"></i>
				</li>
			</ul>


			<div class="row-fluid">

				<a class="quick-button metro yellow span2" href="">
					<i class="fa fa-comment"></i>
					<p>اسلایدر</p>
					{{--<span class="badge">237</span>--}}
				</a>
				<a class="quick-button metro red span2" href="">
					<i class="fa fa-question"></i>
					<p>اطلاعات سایت</p>
					{{--<span class="badge">46</span>--}}
				</a>
				<a class="quick-button metro green span2" href="">
					<i class="fa fa-file-text"></i>
					<p>دروس</p>
					{{--<span class="badge">13</span>--}}
				</a>
				<a class="quick-button metro blue span2" href="">
					<i class="fa fa-user"></i>
					<p>دانشجویان</p>
				</a>
				<a class="quick-button metro pink span2" href="">
					<i class="fa fa-shopping-cart"></i>
					<p>اساتید</p>

				</a>
				<a class="quick-button metro black span2" href="">
					<i class="fa fa-suitcase"></i>
					<p>آزمون ها</p>
				</a>

				<div class="clearfix"></div>

			</div>

			
			<!--/row-->

@endsection

