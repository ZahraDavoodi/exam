@extends('layout')

@section('content')
<main>
    {{ Breadcrumbs::render('help') }}
    <section id="ajancy_info" >
        <div class="container">

            <div class="row shadow p-2 ">
                <h4 class="title">راهنمای سایت</h4>
                <div class="container">
                    متن راهنمای سایت
                </div>


            </div>
        </div>
    </section>
</main>

@endsection