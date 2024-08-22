@isset($howItWorks)
<div class="section-container"
   style="{{ get_website_css($howItWorks->appearance, ['bg_color']) }}">
   <div class="container">
      <div class="row text-center">
         <div class="col-md-12"
            style="{{ get_website_css($howItWorks->appearance, ['text_color']) }}">
            <h2>{{ $howItWorks->title }}</h2>
            <p>{{ $howItWorks->sub_title }}</p>
         </div>
      </div>
      <div class="row justify-content-center mt-5">
         @foreach($howItWorks->additional_data as $key=>$row)
         <div class="col-md-6 mb-4">
            <div class="card h-100">
               <div class="card-body">
                  <div class="d-flex align-items-center ps-4 pt-2">
                     <div class="flex-shrink-0">
                        <img class="avatar" src="{{ asset($row['image']) }}"
                           alt="{{ $row['image_alt_text'] }}" />
                     </div>
                     <div class="flex-grow-1 ms-3">
                        <h5>{{ __('Step') }} {{ $key + 1 }} :
                           {{ $row['title'] }}
                        </h5>
                     </div>
                  </div>
                  {!! display_html_content($row['content']) !!}
               </div>
            </div>
         </div>
         @endforeach
      </div>
   </div>
</div>
</div>
@endisset