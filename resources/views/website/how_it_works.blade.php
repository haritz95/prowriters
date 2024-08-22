@extends('website.layouts.template')
@section('content')
<div class="container page-container">
    <div class="row mt-5">
        <div class="col-md-6 my-auto">
            <h1 class="article-heading mt-5">{{ $page->title }}</h1>
            <p>{{ $page->sub_title }}</p>
        </div>
        <div class="col-md-6">
            <img class="img-fluid" src="{{ URL::to($page->image) }}" alt="{{ $page->title }}" />
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <ul class="steps-list">
                @foreach ($page->additional_data as $step)
                <li class="steps-list__item">
                    <div class="steps-list__inner">
                        <h3 class="steps-list__title">{{ $step['title'] }}</h3>
                        <p class="steps-list__text">{!! $step['description'] !!}</p>
                    </div>
                    <div class="steps-list__mark"></div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
