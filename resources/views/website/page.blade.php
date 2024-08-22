@extends('website.layouts.template')
@section('title', $page->title)
@if(isset($page->meta_tags) && $page->meta_tags)
    @section('seo')
    <?php echo $page->meta_tags; ?>
    @endsection
@endif
@section('content')
<div class="container-fluid">  
    @if($page->image_position == 'center')  
    <div class="row p-0" style="{{ get_website_css($page->appearance, ['bg_color', 'text_color', 'header_minimum_height']) }}">
        <div class="col-md-12 my-auto text-center {{ get_website_text_alignment_class($page->appearance, 'image_alignment') }}">
            @if(isset($page->image) && $page->image)
            <img src="{{ asset($page->image) }}" alt="{{ $page->image_alt_text }}" class="img-fluid w-25" />          
            @endif
        </div> 
        <div class="col-md-12 my-auto text-center {{ get_website_text_alignment_class($page->appearance, 'title_alignment') }}">
            <h1 class="">{!! $page->title !!}</h1>   
            <p>{{ $page->sub_title }}</p>       
        </div>        
    </div>
    @endif
    @if($page->image_position == 'left')  
    <div class="row p-0" style="{{ get_website_css($page->appearance, ['bg_color', 'text_color', 'header_minimum_height']) }}">
        <div class="col-md-6 text-center {{ get_website_text_alignment_class($page->appearance, 'image_alignment') }}">
            @if(isset($page->image) && $page->image)
            <img src="{{ asset($page->image) }}" alt="{{ $page->image_alt_text }}" class="img-fluid w-50" />          
            @endif            
        </div>
        <div class="col-md-6 my-auto text-center {{ get_website_text_alignment_class($page->appearance, 'title_alignment') }}">
            <h1 class="">{!! $page->title !!}</h1>   
            <p>{{ $page->sub_title }}</p>        
        </div>       
        
    </div>
    @endif 
    @if($page->image_position == 'right')  
    <div class="row p-0" style="{{ get_website_css($page->appearance, ['bg_color', 'text_color', 'header_minimum_height']) }}">
        <div class="col-md-6 my-auto text-center {{ get_website_text_alignment_class($page->appearance, 'title_alignment') }}">
            <h1 class="">{!! $page->title !!}</h1>   
            <p>{{ $page->sub_title }}</p>         
        </div>
        <div class="col-md-6 text-center {{ get_website_text_alignment_class($page->appearance, 'image_alignment') }}">
            @if(isset($page->image) && $page->image)
            <img src="{{ asset($page->image) }}" alt="{{ $page->image_alt_text }}" class="img-fluid w-50" />          
            @endif  
        </div>   
    </div>
    @endif 
</div>

<div class="container page-container">
    @include('website.layouts.breadcrumb')
    <div class="row">
        <div class="col-md-12">{!! display_html_content($page->content) !!}</div>        
    </div>   
</div>

@endsection
