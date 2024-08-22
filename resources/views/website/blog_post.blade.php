@extends('website.layouts.template')
@section('title', $title)
@if(isset($meta_tags) && $meta_tags)
    @section('seo')
    <?php echo $meta_tags; ?>
    @endsection
@endif
@section('content')
<style type="text/css">
    iframe {
        vertical-align: bottom;
    }

</style>
<div class="container page-container blog-single-post">

    <div class="row mt-5 mb-4">
        <div class="offset-md-2 col-md-8">
            <div class="content-holder">
                <img class="img-fluid mb-4" src="{{ get_asset_from_storage($post->cover_image) }}"
                    alt="{{ $post->cover_image_alt_title }}" />

                <div class="category">
                    @foreach($post->categories as $category)
                        <a class="mr-2"
                            href="{{ route('blog.content', $category->slug) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
                <h1 class="title">{{ $post->title }}</h1>

                <div class="d-flex justify-content-between post-meta">
                    <div>{{ $post->author_name }} </div>
                    <div>{{ convert_to_local_time($post->created_at, "d F Y") }}</div>
                </div>

                <div class="mt-4"><?php echo $post->content; ?></div>

                <hr>
                <div class="social-share">
                    <div class="h4">{{ __('Share Now') }}</div>
                    <!-- Facebook share button code -->
                    <div class="fb-share-button" data-href="{{ url()->full() }}" data-layout="button_count">
                    </div>

                    <!-- twitter share button code -->
                    <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button"
                        data-show-count="false">{{ __('Tweet') }}</a>
                </div>

            </div>
            <div id="disqus_thread" class="mt-5 pt-5"></div>
        </div>


    </div>

    @if($relatedPosts->count() > 0)
        <div class="row mb-5">
            <div class="col-md-12 text-center">
                <h2>{{ __('Catch up on the related articles') }}</h2>
            </div>
        </div>

        <div class="row">
            @foreach($relatedPosts as $post)
                @include('website.partials.blog_post_grid')
            @endforeach
        </div>
    @endif
</div>


@endsection

@push('scripts')
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

    </script>

    <!-- Load twitter JavaScript -->
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

    @if($disqus_site_name = settings('disqus_site_name'))
        <script>
            var disqus_config = function () {
                this.page.url = '<?php echo URL::to($post->slug); ?>';
                this.page.identifier = '<?php echo $post->slug; ?>';
            };
            (function () { // DON'T EDIT BELOW THIS LINE
                var d = document,
                    s = d.createElement('script');
                s.src = 'https://{{ $disqus_site_name }}.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();

        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by
                Disqus.</a></noscript>
    @endif
@endpush
