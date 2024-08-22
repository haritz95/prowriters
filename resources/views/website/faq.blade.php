@extends('website.layouts.template')
@section('title', $page->title)
@if(isset($page->meta_tags) && $page->meta_tags)
    @section('seo')
    <?php echo $page->meta_tags; ?>
    @endsection
@endif
@section('content')
<div class="container page-container">
    <div class="row">
        <div class="col-md-12 my-auto">
            <h1 class="mt-5">{{ optional($page)->title }}</h1>
            <p>{{ optional($page)->sub_title }}</p>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-3">
            @if(isset($faqCategories) && $faqCategories->count() > 0)
                <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    @foreach($faqCategories as $key=>$faqCategory)
                        <?php $category_slug = Illuminate\Support\Str::slug($faqCategory->name, '-') ; ?>
                        <button
                            class="nav-link {{ ($key == 0) ? 'active' : null }}"
                            id="v-pills-{{ $category_slug }}-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-{{ $category_slug }}" type="button" role="tab"
                            aria-controls="v-pills-{{ $category_slug }}" aria-selected="true">
                            {{ $faqCategory->name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                @foreach($faqCategories as $key=>$faqCategory)
                    <?php 
               $category_slug = Illuminate\Support\Str::slug($faqCategory->name, '-') ;
               $accordion_name = 'accordion_' . $category_slug;
               ?>
                    <div class="tab-pane fade {{ ($key == 0) ? 'active show' : null }}"
                        id="v-pills-{{ $category_slug }}" role="tabpanel"
                        aria-labelledby="v-pills-{{ $category_slug }}-tab" tabindex="{{ $key }}">
                        <h4 class="mb-4">{{ $faqCategory->name }}</h4>
                        <div class="accordion" id="accordionExample">
                            @foreach($faqCategory->questions as $faqKey=>$faq)
                                <?php $collapse =  'collapse_' .$faq->id; ?>
                                <?php $heading =  'heading_' .$faq->id; ?>
                                @if($faqKey == 0)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="{{ $heading }}">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapse }}" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                {{ $faq->title }}
                                            </button>
                                        </h2>
                                        <div id="{{ $collapse }}" class="accordion-collapse collapse show"
                                            aria-labelledby="{{ $heading }}" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                {{ $faq->description }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="{{ $heading }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#{{ $collapse }}"
                                                aria-expanded="false" aria-controls="collapseTwo">
                                                {{ $faq->title }}
                                            </button>
                                        </h2>
                                        <div id="{{ $collapse }}" class="accordion-collapse collapse"
                                            aria-labelledby="{{ $heading }}" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                {{ $faq->description }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
