@extends('layout')

@section('content')
<main>
    {{ Breadcrumbs::render('rules') }}
    <section >
        <div class="container">

            <div class="row shadow p-2 ">

                <div class="container">
                    <div class="col-12">
                        @foreach($info as $info)
                            @php(print($info->info_rule))
                        @endforeach
                    </div>
                </div>
    </section>
</main>

@endsection