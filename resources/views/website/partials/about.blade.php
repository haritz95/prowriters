@if($about)
    <div class="section-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="display-5 fw-bold lh-1 mb-3 mt-5">{!! $about->title !!}</h2>
                    <p>{{ $about->sub_title }}</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center mt-4">
                @foreach($about->additional_data as $row)
                    <div class="col-md-3 mb-4">
                        <div class="benefits__item h-100 text-center">
                            <div class="row">
                                <div class="col-md-12">
                                    <div><img src="{{ $row['image'] }}" class="icon-img"></div>
                                    <div class="benefits__heading mt-2">{{ $row['title'] }}
                                    </div>
                                    <div class="benefits__description mt-2">{!! display_html_content($row['content'])
                                        !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
