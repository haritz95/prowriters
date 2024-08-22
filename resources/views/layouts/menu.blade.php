<nav class="navbar navbar-expand-md shadow-sm navbar-background">
  <div class="container">  

     <a class="navbar-brand" href="{{ url('/') }}">                    
     <img class="logo" src="{{ get_company_logo() }}" alt="{{ config('app.name', 'ProWriter') }}">
     </a>
     
     <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
     <i class="fas fa-bars"></i>
     </button>
     <div class="collapse navbar-collapse" id="navbarSupportedContent">
      @auth
         <ul class="navbar-nav me-auto">      
           @switch(auth()->user()->type)
              @case(UserType::ADMIN)
                  @include('layouts.menu.admin')
                 @break
              @case(UserType::AUTHOR)
                  @include('layouts.menu.author')
                 @break           
              @default
                  @include('layouts.menu.customer')
           @endswitch       
        </ul>
        <ul class="navbar-nav ml-auto">
           <li class="nav-item dropdown language-dropdown">
              <a class="nav-link dropdown-toggle px-2 d-flex align-items-center" id="LanguageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                 <div class="d-inline-flex mr-0 mr-md-3">
                    <div class="flag-icon-holder">
                       <i class="flag-icon flag-icon-{{ countryCode(app()->getLocale()) }}"></i>
                    </div>
                 </div>
                 {{-- <span class="profile-text font-weight-medium d-none d-md-block">{{ (App::getlocale() == 'ar') ? __('Arabic') : __('English') }}</span> --}}
              </a>
              <div class="dropdown-menu dropdown-menu-left navbar-dropdown py-2" aria-labelledby="LanguageDropdown">
                 @foreach (getLanguages() as $language)
                 <a class="dropdown-item language-item" href="{{ switchLang($language->iso_code) }}" >
                  <div class="d-flex justify-content-around">                  
                       <i class="flag-icon flag-icon-{{ $language->country_code }}"></i>                    
                     <span>{{ $language->name }}</span>
                  </div>
                 </a>
                 @endforeach                     
              </div>
           </li>
           <li class="nav-item dropdown" style="z-index: 2000 !important;">                                
              @include('layouts.notification_bell')
           </li>

           <li class="nav-item dropdown" style="z-index: 2000 !important;">
               <a class="nav-link dropdown-toggle" href="#" id="account" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ Auth::user()->first_name }}
               </a>
          
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="account">
               <a class="dropdown-item" href="{{ route('my_account') }}">                   
                  {{ __('My Account') }}
                  </a>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                     @csrf
                  </form>
            </div>
          </li>
        </ul>
        @endauth
     </div>
  </div>
</nav>