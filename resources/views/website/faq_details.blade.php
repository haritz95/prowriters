@extends('website.layouts.template')
@section('content')
<div class="container page-container is-faq">

   <div class="row mt-5">
       <div class="offset-md-2 col-md-8">
          @include('website.faq_search_box')
          <div class="mb-3 mt-5"><a href="{{ route('faq') }}">Explore All Question and Answers</a> </div>
          <h1 class="h5 mb-3 mt-5"> <strong>Question</strong> {{ $faq->title }}</h1>
          <div class="bg-light p-3"><?php echo nl2br($faq->description); ?></div>
          <div class="h5 mt-4"><span  style="border-top: 10px solid orange;">Expert Answer</span> </div>
          <hr>
          <div class="mt-4">{!! nl2br($faq->answer) !!}</div>
          <hr>
          <a href="{{ route('login') }}" class="btn btn-success pl-5 pr-5">Login</a>
          <a href="{{ route('register') }}" class="btn btn-primary pl-5 pr-5">Sign Up</a>
       </div>
   </div>
</div>
@endsection
