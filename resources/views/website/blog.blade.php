@extends('website.layouts.template')
@section('title', $title)
@if(isset($meta_tags) && $meta_tags)
    @section('seo')
    <?php echo $meta_tags; ?>
    @endsection
@endif
@section('content')
<div class="container page-container">
    <div class="row mt-5">
        <div class="col-md-8 my-auto">
            <h1 class="article-heading mt-5">
                {{ $title }}
            </h1>
            <p>
                {{ $sub_title }}
            </p>
        </div>
        <div class="col-md-4 text-end">
           <div class="form-group mt-md-5">
                <?php echo form_dropdown("category", $post_categories, request()->segment(3), "class='form-control form-control-sm blog-category'") ?>
            </div> 
        </div>
    </div>
    @if($posts->count() > 0)
        <div class="row mt-5">
            @foreach($posts as $post)
                <!-- Grid -->
                @include('website.partials.blog_post_grid', ['post' => $post])
                <!-- End of Grid  -->
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-12">
                <div>{!! $posts->links() !!}</div>
            </div>
        </div>
    @endif
</div>

@endsection
@push('scripts')
    <script>
        const selectElement = document.querySelector('.blog-category');

        selectElement.addEventListener('change', (event) => {
            var category = event.target.value;
            if (category) {
                var url = "{{ route('blog.content', ':id') }}";
                window.location.href = url.replace(":id", category);
            } else {
                window.location.href = "{{ route('blog') }}";
            }
        });

    </script>
@endpush
