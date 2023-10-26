@extends('layout')

@section('content')
<main>
    {{ Breadcrumbs::render('about') }}
    <section >

        <div class="container">

            <div class="row shadow p-2">
                <h4 class="title">درباره ما</h4>
                <div class="container">
                    <div class="col-12">

                        <?php

                        $now=jdate('H:i:s','','','','en');
                        echo $now;
                        echo '<br/>';
                        $exp='23:25:00';
                        echo $exp;
                        echo'<br/>';
                        $date1 = new DateTime($now);
                        $date2 = new DateTime($exp);
                        $interval = $date1->diff($date2);

                        //print_r($interval->h.':'.$interval->i.':'.$interval->s) ;
                        //print_r( $interval);
                        ?>
                        @foreach($info as $info)
                            @php(print($info->info_about))
                        @endforeach
                    </div>
                </div>

    </section>
</main>

@endsection