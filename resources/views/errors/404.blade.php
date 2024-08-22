@extends('website.layouts.template')
@section('title', 404) 
@section('content')
<div class="container page-container">
 
    <div class="row mt-5 mb-5">
        <div class="col-md-12 text-center">
            <h1 class="" style="font-size: 10em">404</h1>  
            <h1 class="">{{ __('Page Not Found') }}</h1>  
            <h2>{{ __('We can\'t seem to find the page you\'re looking for') }}</h2>
            <a href="{{ URL::to('/') }}" class="btn btn-success mt-4">{{ __('Go to Homepage') }}</a>
        </div>
    </div>
</div>

@endsection
