<!-- footer -->
<footer class="footer site-footer">
    <div class="footer_top">
       <div class="container">
          <div class="row mt-4">
             <div class="col-md-4">            
                <img class="img-fluid" loading=lazy src="{{ get_website_logo() }}"  alt="{{ settings('company_name') }}">
                
                <div class="contact mt-3">            
             
                    <div class="d-flex mb-4">
                      <div class="flex-shrink-0">
                          <i class="fa-solid fa-phone"></i>
                      </div>
                      <div class="flex-grow-1 ms-3">
                          {{ get_company_phone() }}
                      </div>
                    </div>                   
                
                    <div class="d-flex mb-4">
                      <div class="flex-shrink-0">
                          <i class="fa-solid fa-envelope"></i>
                      </div>
                      <div class="flex-grow-1 ms-3">
                        {{ get_company_email() }}
                      </div>
                    </div>
              </div>

             </div>
             <div class="col-md-4">
                <div class="infos__heading">
                   {{ __('We accept payments via')}}
                </div>
                <div class="infos__icons"> 
                  <img src="{{ get_payment_gateway_image() }}" class="img-fluid" title="{{ __('Payment Gateways') }}">                   
                </div>
             </div>
             <div class="col-md-4">
                <div class="infos__heading">
                   {{ __('Social media')}}
                </div>
               

                <div class="infos__icons">

                  <div class="socail_links">
                     <ul>
                        @if($link = settings('facebook'))
                        <li>
                           <a href="{{ $link }}" target="_blank">
                           <i class="fab fa-facebook-square"></i>
                           </a>
                        </li>
                        @endif
                        @if($link = settings('twitter'))
                        <li>
                           <a href="{{ $link }}" target="_blank">
                           <i class="fab fa-twitter-square"></i>
                           </a>
                        </li>
                        @endif
                        @if($link = settings('instagram'))
                        <li>
                           <a href="{{ $link }}" target="_blank">
                           <i class="fab fa-instagram"></i>
                           </a>
                        </li>
                        @endif
                        @if($link = settings('linkedin'))
                        <li>
                           <a href="{{ $link }}" target="_blank">
                           <i class="fab fa-linkedin"></i>
                           </a>
                        </li>
                        @endif
                        @if($link = settings('youtube'))
                        <li>
                           <a href="{{ $link }}" target="_blank">
                           <i class="fab fa-youtube"></i>
                           </a>
                        </li>
                        @endif
                     </ul>
                  </div>
                </div>
             </div>
          </div>
          <div class="row mt-5 justify-content-center">

            

             @if(isset($footer_menu))    
             <div class="col-md-12">
               <hr>
            </div>
                       
             @foreach ($footer_menu as $parent)
             <div class="col-md-3 mb-3">
                <h5>{{ $parent->name }}</h5>
                <ul class="list-unstyled">
                   @foreach ($parent->children as $child)
                   <li class="mb-2"><a href="{{ $child->page->slug }}">{{ $child->name }}</a></li>
                   @endforeach
                </ul>
             </div>
             @endforeach
             @endif
          </div>
       </div>
    </div>
    <div class="site-footer__copyrights copyrights mt-5">
       <div class="container text-center">
          <p>
             {{ __('Copyright')}} &copy; {{ date("Y") }}
             <a role="button" href="{{ URL::to('/') }}">
             {{ get_company_name() }}
             </a>
             @isset($footer->additional_data['footer_text'])                     
          <p>{!! display_html_content($footer->additional_data['footer_text']) !!}</p>
          @endisset     
          </p>
       </div>
    </div>
 </footer>
 {{-- @include('cookie-consent::index') --}}