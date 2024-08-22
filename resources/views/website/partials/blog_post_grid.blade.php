<div class="col-12 col-lg-4 mb-5">
    <article class="blog-grid d-flex flex-column">
        <div class="image-holder">
            <a href="{{ route('blog.content', $post->slug) }}" title="{{ $post->title }}">
                <img class="cover-image" src="{{ get_asset_from_storage($post->thumbnail_image) }}" alt="{{ $post->title }}">
            </a>
        </div>
        <div class="content-holder">
            <div class="category">
                @foreach($post->categories as $category)
                    <a class="mr-2"
                        href="{{ route('blog.content', $category->slug) }}">{{ $category->name }}</a>
                @endforeach
            </div>

            <h4 class="title"><a class="text-dark" href="{{ route('blog.content', $post->slug) }}">
                    {{ Illuminate\Support\Str::limit(strip_tags($post->title), 60) }}
                </a>
            </h4>

            <div class="d-flex justify-content-between post-meta">
                <div>{{ $post->author_name }} </div>
                <div>{{ convert_to_local_time($post->created_at, "d F Y") }}</div>
            </div>

            <div class="excerpt"><?php echo $post->excerpt(); ?></div>


        </div>
        
        <a class="mt-auto bg-light text-dark read-article-link" href="{{ route('blog.content', $post->slug) }}" style="">{{ __('Read Article') }}  <i class="fa-solid fa-arrow-right-long"></i> </a>
        
    </article>
</div>
