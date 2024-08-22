@extends('website.layouts.template')
@section('content')
<div class="container page-container is-faq">

   <div class="row mt-5">
       <div class="offset-md-2 col-md-8">
          @include('website.faq_search_box')
          <h1 class="h5 mb-3 mt-5">{{ __('Search Result') }}</h1>
          @if($questions->count() > 0)
          <ul class="list-unstyled">
                @foreach ($questions as $question)
                <li class="pb-3"><a href="{{ route('faq_details', $question->slug) }}">{{ $question->title}}</a></li>
                @endforeach
            </ul>
          @else
            <div>{{ __('We could not find any result that match your search query') }}</div>
          @endif
       </div>
   </div>
</div>
@endsection
