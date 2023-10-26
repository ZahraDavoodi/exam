@extends('layout')

@section('content')
<main>
    {{ Breadcrumbs::render('cooperation') }}
    <section >
        <div class="container">

            <div class="row shadow p-2">
                <h4 class="title">همکاری با  ما</h4>
                <div class="container">
                    <div class="col-12">
                        @foreach($info as $info)
                            @php(print($info->info_cooperation))
                        @endforeach
                    </div>
                </div>
    </section>
</main>

@endsection