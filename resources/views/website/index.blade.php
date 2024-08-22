@extends('website.layouts.template')
@section('title', optional($page)->title)
@if(isset($page->meta_tags) && $page->meta_tags)
    @section('seo')
    <?php echo $page->meta_tags; ?>
    @endsection
@endif
@section('content')
@include('website.partials.hero')
@include('website.partials.about')
@include('website.partials.why_choose_us')
@include('website.partials.how_it_works')
@include('website.partials.testimonial')

@if(isset($blog_posts) && $blog_posts && $blog_posts->count() > 0)
<div class="container">
    <div class="row mt-5 mb-5">
        <div class="col-md-12 text-center mb-4">
           <h2>{{ __('Check out some of our latest blogs') }}</h2>
        </div>
        @foreach($blog_posts as $post)
            <!-- Grid -->
            @include('website.partials.blog_post_grid', ['post' => $post])
            <!-- End of Grid  -->
        @endforeach
    </div>
</div>
@endif
@endsection
