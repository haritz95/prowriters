@extends('website.layouts.template')
@section('title', $page->title)
@if(isset($page->meta_tags) && $page->meta_tags)
    @section('seo')
    <?php echo $page->meta_tags; ?>
    @endsection
@endif
@section('content')
<div class="container page-container">
   @if($page->image_position == 'center')
   <div class="row">
      <div class="col-md-12 text-center">
          <img src="{{ asset($page->image) }}" alt="{{ $page->image_alt_text }}" class="img-fluid w-50 mt-4" />
          <h1 class="mt-4">{!! $page->title !!}</h1>
          <h5 class="mt-4">{{ $page->sub_title }}</h5>          
      </div>
  </div>
   @elseif($page->image_position == 'left')

   <div class="row mt-5">
      <div class="col-md-6">
         @if($page->image)
             <img class="img-fluid" src="{{ URL::to($page->image) }}" alt="{{ $page->image_alt_text }}" />
         @endif
     </div>
      <div class="col-md-6 my-auto">
          <h1 class="mt-5">{{ $page->title }}</h1>
          <p>{{ $page->sub_title }}</p>
      </div>
      
  </div>

   @else

   <div class="row mt-5">
      <div class="col-md-6 my-auto">
          <h1 class="mt-5">{{ $page->title }}</h1>
          <p>{{ $page->sub_title }}</p>
      </div>
      <div class="col-md-6">
          @if($page->image)
              <img class="img-fluid" src="{{ URL::to($page->image) }}" alt="{{ $page->image_alt_text }}" />
          @endif
      </div>
  </div>

   @endif

 
  <div class="row">
      <div class="col-md-12">
          @if(Session::has('message'))
          <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">
              {!! session('message') !!}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif
      </div>
  </div>
  
      <div class="row mt-5">
         <div class="col-md-12">
            <div class="shadow-lg mb-5 bg-white rounded section-contact-form">
               <div class="row">
                  <div class="col-md-6 text-center">
                     <div class="my-auto" style="background-color: #fff4ea; height:100%;">
                        <div class="p-4">
                           <h2 class="section__heading"><i class="fas fa-home"></i> {{ __('Address') }}</h2>
                         <div>{!! nl2br(Purifier::clean(settings('company_address'))) !!}</div>
                         
                         <h2 class="section__heading mt-5"><i class="fas fa-phone-alt"></i> {{ __('Phone') }}</h2>
                         <div>{!! Purifier::clean(settings('company_phone')) !!}</div>

                         <h2 class="section__heading mt-5"><i class="far fa-envelope-open"></i> {{ __('Email') }}</h2>
                         <div>{!! Purifier::clean(settings('company_email')) !!}</div>
                         @if($page->content)
                           <p class="mt-4">{!! display_html_content(Purifier::clean($page->content)) !!}</p>
                         @endif
                        </div>
                     </div>
                  </div>

                  <div class="col-md-6">
                    
                     <div class="p-4">
                        <h2 class="section__heading">{{ $page->additional_data['form_title'] }}</h2>
                        @if (isset($page->additional_data['form_sub_title']) && $page->additional_data['form_sub_title'])
                           <p>{{ $page->additional_data['form_sub_title'] }}</p>
                        @endif
                        <form class="" action="{{ route('handle_email_query') }}" method="post" id="contactForm" novalidate="novalidate" >
                           {{ csrf_field()  }}
                          <div class="row">
                             <div class="col-12">
                                <div class="form-group contact-form__field">
                                   <textarea class="form-control w-100 {{ showErrorClass($errors,'message') }}" name="message" id="message" cols="30" rows="9" placeholder="{{ __('Enter Message') }}">{{ old('message') }}</textarea>
                                   <div class="invalid-feedback d-block">{{ showError($errors,'message') }}</div>
                                </div>                     
                             </div>
                             <div class="col-sm-6">
                                <div class="form-group contact-form__field">
                                   <input class="form-control {{ showErrorClass($errors,'name') }}" name="name" id="name" type="text" placeholder="{{ __('Enter your name') }}" value="{{ old('name') }}">
                                   <div class="invalid-feedback d-block">{{ showError($errors,'name') }}</div>
                                </div>                     
                             </div>
                             <div class="col-sm-6">
                                <div class="form-group contact-form__field">
                                   <input class="form-control {{ showErrorClass($errors,'email') }}" name="email" id="email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}">
                                   <div class="invalid-feedback d-block">{{ showError($errors,'email') }}</div>
                                </div>                     
                             </div>
                             <div class="col-12">
                                <div class="form-group contact-form__field">
                                   <input class="form-control {{ showErrorClass($errors,'subject') }}" name="subject" id="subject" type="text" placeholder="{{ __('Enter Subject') }}" value="{{ old('subject') }}">
                                   <div class="invalid-feedback d-block">{{ showError($errors,'subject') }}</div>
                                </div>                     
                             </div>
                          </div>
                          <?php recaptchaHtml($errors); ?>
                          <div class="form-group mt-3">
                             <button type="submit" class="btn btn-primary btn-block">
                                <i class="far fa-paper-plane"></i> {{ __('Send') }}</button>
                          </div>
                       </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
</div> 

@endsection


@if(settings("recaptcha_enable"))
@push('scripts')
<?php recaptchaJavascript(); ?>
@endpush
@endif