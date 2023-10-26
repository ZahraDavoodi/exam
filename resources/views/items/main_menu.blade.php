<header>
    <div class="top-site row">
        <div class="top-menu col-12">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6 col-12 text-right">

                        <?php if (Session::get('customer_name')){
                        ?>
                        <div class="dropdown text-right">


                            <a class=" dropdown-toggle  login-link px-3" data-toggle="dropdown">{{Session::get('customer_name')}} عزیز </a>
                            <ul class="dropdown-menu text-center">
                                <li class="border-bottom p-1"><a href="/customer/panel">پنل کاربری</a></li>
                                <li class="p-1"><a href="{{URL::to('/customer/logout')}}">خروج</a></li>
                            </ul>


                        </div>

                        <?php
                        }else{ ?>

                        <a data-toggle="modal" data-target="#myModal" class="login-link"><i class="fa fa-user"></i>ورود/ عضویت</a>
                        <?php } ?>

                    </div>
                    <div class="col-sm-6 col-12 text-left">
                        <span>09193367021<i class="fa fa-phone"></i></span>
                        <span>a.meskoob@gmail.com<i class="fa fa-globe"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mega-menu top-menu2  mega-menu top-menu2">
            <div class="container">

                <div class="row">
                    <div class="col-10 ">
                        <span class="open-menu fa fa-bars fa-2x mt-3"></span>
                        <nav>
                            <ul>
                                <li><a href="{{URL::to('/')}}" >صفحه نخست </a></li>
                                <li><a href="{{URL::to('/about')}}">درباره ما</a></li>
                                <li><a href="{{URL::to('/contact')}}">تماس با ما</a></li>
                                <li>

                                    <?php if (Session::get('customer_name')){
                                    ?>



                                            <li class="border-bottom p-1"><a href="/customer/panel">پنل کاربری</a></li>

                                    <?php
                                    }else{ ?>

                                    <a data-toggle="modal" data-target="#myModal" class="login-link"><i class="fa fa-user"></i>ورود/ عضویت</a>
                                    <?php } ?>

                                </li>


                            </ul>
                        </nav>
                    </div>
                    <div class="col-2">
                        <a href="index.html" class="logo hidden-sm hidden-xs px-2">
                            <img src="{{url('/frontend/images/logo1.png')}}" >

                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- Mega Menu Responsive -->
        <div class="responsive-menu-layer"></div>
        <div class="mega-menu-responsive">
            <div class="inner">
                <span class="close-menu"><i class="fa fa-remove fa-1x rounded bg-secondary text-white-50 p-1 m-2"></i></span>
                <a href="#" class="res-logo"><img src="{{url('/frontend/images/logo1.png')}}">

                    <nav>
                        <ul>

                        </ul>
                    </nav>

                    <div class="footer">
                        <ul>

                            <li><a href="#"><i class="fa fa-phone"></i> تماس باما</a></li>
                            <li><a href="#"><i class="fa fa-book"></i> درباره ما</a></li>

                        </ul>
                    </div>

            </div>
        </div>
        <!--/ Mega Menu Responsive -->
    </div>
</header>
