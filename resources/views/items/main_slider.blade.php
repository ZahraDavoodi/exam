
@if(!empty($all_sliders))

 <div id="header_carousel" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ul class="carousel-indicators">

            @php
                $isFirstBtn = true;
                $slideNumber = 0 ;
            @endphp

            @foreach($all_sliders as $slider)
                <li data-target="#header_carousel" data-slide-to="{{$slideNumber}}" class="
                        @if($isFirstBtn)
                        active
                        @endif
                @php
                    $isFirstBtn = false;
                    $slideNumber++;
                @endphp
                        "></li>
            @endforeach

        </ul>

        <!-- The slideshow -->
        <div class="carousel-inner">

            @php
                $isfirst = true;
            @endphp

            @foreach($all_sliders as $slider)

                <div class="carousel-item item
                        @if($isfirst)
                        active
                        @php
                    $isfirst = false;
                @endphp
                @endif
                ">
                    <a href="{{$slider->slider_link}}" class="more">

                    <img style="height: 400px;width: 100%" src="{{URL::to($slider->slider_image)}}" class="girl img-responsive" alt="{{$slider->slider_title}}" />

                    <div class="carousel-caption">

                        <h3> {{$slider->slider_title}} </h3>
                        <p>{{html_entity_decode(strip_tags($slider->slider_description))}}</p>

                    </div>
</a>
                </div>

            @endforeach
        </div>

        <!-- Left and right controls -->
     <a class="carousel-control-prev" href="#header_carousel" data-slide="prev">
         <span class="carousel-control-prev-icon"></span>
     </a>
     <a class="carousel-control-next" href="#header_carousel" data-slide="next">
         <span class="carousel-control-next-icon"></span>
     </a>

    </div>
@endif