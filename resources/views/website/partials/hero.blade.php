@if($hero)
<?php 
$image_position = settings('website_hero_image_position');
$button_text = __('Order Now');
$button_link = route('customer.tasks.create');
?>
    <div class="min-vh-100" id="hero">
        <div class="container pb-5">
            @if($image_position == 'center')
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="{{ $hero->image }}" alt="{{ $hero->image_alt_text }}" class="img-fluid w-50" />
                        <h1 class="title display-4 fw-bold lh-1 mb-3 mt-5">{!! $hero->title !!}</h1>
                        <h5 class="mt-4 sub-title">{{ $hero->sub_title }}</h5>
                        <p class="mt-4 lh-lg content">{!! display_html_content($hero->content) !!}</p>
                        <div class="mt-4">
                            <a class="btn-1" href="{{ $button_link }}">{{ $button_text }}</a>
                        </div>
                    </div>
                </div>
            @elseif($image_position == 'left')
                <div class="row">
                    <div class="col-md-5">
                        <img src="{{ $hero->image }}" alt="{{ $hero->image_alt_text }}" class="img-fluid" />
                    </div>
                    <div class="col-md-7">
                        <h1 class="title text-center text-md-start display-4 fw-bold lh-1 mb-3 mt-5">{!! $hero->title !!}</h1>
                        <h5 class="mt-4 text-center text-md-start sub-title">{{ $hero->sub_title }}</h5>
                        <p class="mt-4 text-center text-md-start lh-lg content">{!! display_html_content($hero->content) !!}</p>
                        <div class="text-center text-md-start mt-4">
                            <a class="btn-1" href="{{ $button_link }}">{{ $button_text }}</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-7 mt-5">
                        <h1 class="title text-center text-md-start display-4 fw-bold lh-1 mb-3">{!! $hero->title !!}</h1>
                        <h5 class="mt-4 text-center text-md-start sub-title">{{ $hero->sub_title }}</h5>
                        <p class="mt-4 text-center text-md-start lh-lg content">{!! display_html_content($hero->content) !!}</p>                        
                        <div class="text-center text-md-start mt-4">
                            <a class="btn-1" href="{{ $button_link }}">{{ $button_text }}</a>
                        </div>
                    </div>
                    <div class="col-md-5 text-end my-auto">
                        <img src="{{ $hero->image }}" alt="{{ $hero->image_alt_text }}" class="img-fluid" />
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
