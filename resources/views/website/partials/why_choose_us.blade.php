@if($whyChooseUs)
    <div style="{{ get_website_css($whyChooseUs->appearance, ['bg_color', 'text_color']) }}height: 100%;">
        <div class="container pt-5 pb-5">
            @if($whyChooseUs->image_position == 'center')
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="{{ asset($whyChooseUs->image) }}" alt="{{ $whyChooseUs->image_alt_text }}" class="img-fluid w-25" />
                        <h1>{!! $whyChooseUs->title !!}</h1>
                        <h5 class="mt-4">{{ $whyChooseUs->sub_title }}</h5>
                        <p class="mt-4">{!! display_html_content($whyChooseUs->content) !!}</p>
                   
                    </div>
                </div>
            @elseif($whyChooseUs->image_position == 'left')
                <div class="row">
                    <div class="col-md-5">
                        <img src="{{ asset($whyChooseUs->image) }}" alt="{{ $whyChooseUs->image_alt_text }}" class="img-fluid" />
                    </div>
                    <div class="col-md-7" style="margin-top: 5%;">
                        <h1>{!! $whyChooseUs->title !!}</h1>
                        <h4 class="mt-4">{{ $whyChooseUs->sub_title }}</h4>
                        <p class="mt-4">{!! display_html_content($whyChooseUs->content) !!}</p>
                      
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-7" style="margin-top: 5%;">
                        <h1>{!! $whyChooseUs->title !!}</h1>
                        <h4 class="mt-4">{{ $whyChooseUs->sub_title }}</h4>
                        <p class="mt-4">{!! display_html_content($whyChooseUs->content) !!}</p>
                   
                    </div>
                    <div class="col-md-5 text-end">
                        <img src="{{ asset($whyChooseUs->image) }}" alt="{{ $whyChooseUs->image_alt_text }}" class="img-fluid" />
                    </div>
                </div>
            @endif

            <div class="row justify-content-center mt-5">
                @foreach($whyChooseUs->additional_data as $key=>$row)
                    <div class="col-md-6 mb-5 text-center">
                        <div class="h-100 p-3">                        
                            <div class="text-center">
                                <img class="img-fluid w-25" src="{{ asset($row['image']) }}"
                                    alt="{{ $row['image_alt_text'] }}" />
                            </div>
                            <div class="mt-4">
                                <h5>{{ $row['title'] }}</h5>                                    
                                {!! display_html_content($row['content']) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endif
