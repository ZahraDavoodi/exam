@extends('layout')

@section('content')
<main>
    {{ Breadcrumbs::render('contact')}}
    <section id="ajancy_info" >
        <div class="container">

            <div class="row shadow p-2 ">
                <h4 class="title">ارتباط با ما</h4>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="contact">
                                <li><span class="fa fa-phone"></span>09193367021</li>
                                <li><span class="fa fa-globe"></span>a.meskoob@gmail.com</li>
                                <li><span class="fa fa-home"></span>
                                    تهران- میدان قزوین- بریانک - هنرستان شهید قدمی
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1352.0219407928812!2d51.476604871416676!3d35.72694996806618!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f8e0335eaf62983%3A0x1d7ac2a68a21cf16!2z2KfZiNqp24wg2LTYrw!5e0!3m2!1sen!2s!4v1568530140869!5m2!1sen!2s" class="map" allowfullscreen=""></iframe>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
</main>

@endsection