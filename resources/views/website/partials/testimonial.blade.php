@if($testimonials && $testimonials->count() > 0)
<div class="section-container">
   <div class="container">
      <div class="row text-center">
         <div class="col-md-12">
            <h2>{{ __('Customers testimonials') }}</h2>
         </div>
      </div>
      <div class="row justify-content-center mt-5 text-center">
         @foreach ($testimonials as $testimonial)                   
         <div class="col-md-4 p-4">
            <div>
               <img src="{{ asset($testimonial->avatar) }}"
                  alt="{{ $testimonial->name }}" class="avatar" loading="lazy">
               <div class="mt-1"><?php echo website_star_rating($testimonial->rating); ?></div>
               <div><strong>{{ $testimonial->name }}</strong></div>
               <div><em>- {{ $testimonial->position }}</em></div>
               <p class="mt-4">{{ $testimonial->comment }}</p>
            </div>
         </div>
         @endforeach
      </div>
   </div>
</div>
@endif