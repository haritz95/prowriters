 <nav id="sticky-header" class="navbar navbar-expand-lg animate__animated animate__fadeIn">
    <div class="container">
       <a class="navbar-brand ml-2 ml-md-0" href="{{ route('homepage') }}"><img
          style="width: 100%; height:auto;" loading=lazy src="{{ get_website_logo() }}"
          alt="{{ settings('company_name') }}"></a>
       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07"
          aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarsExample07">
          <ul class="navbar-nav me-auto"></ul>
          <ul class="navbar-nav">
            
            @if(!is_single_language())            
            <li class="nav-item dropdown">
                <button class="btn btn-link dropdown-toggle caret-off" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="fi fi-{{ get_country_code(app()->getLocale()) }}"></span>
                </button>
                <ul class="dropdown-menu">
                   @foreach (get_languages() as $language)
                   <li><a class="dropdown-item" href="{{ route('switchLanguage', ['switchTo' => $language->iso_code])}}"><span class="fi fi-{{ $language->country_code }}"></span> {{ $language->name }}</a></li>
                   @endforeach
                </ul>
             </li>
             @endif

             <li class="nav-item"><a class="nav-link"
                href="{{ route('homepage') }}">{{ __('Home') }}</a></li>
             @if(isset($top_menu) && $top_menu)
             @foreach($top_menu as $key => $parentMenu)
             @if($parentMenu->children->count() > 0)
             <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                   aria-expanded="false">{{ $parentMenu->name }}</a>
                <ul class="dropdown-menu">
                   @foreach($parentMenu->children as $childMenu)
                   <li><a class="dropdown-item" href="{{ route('page', $childMenu->page->slug)  }}">{{ $childMenu->name }}</a></li>
                   @endforeach
                </ul>
             </li>
             @else
             <li class="nav-item"><a class="nav-link"
                href="{{ route('page', $parentMenu->page->slug) }}">{{ $parentMenu->name }}</a></li>
             @endif
             @endforeach
             @endif
             @if(!settings('hide_blog'))
             <li class="nav-item"><a class="nav-link"
                href="{{ route('blog') }}">{{ __('Blog') }}</a></li>
             @endif
             @if(!hide_author_application_link())
             <li class="nav-item"><a class="nav-link login"
               href="{{ route('public.author.application.create') }}">{{ __('Become an author') }}</a>
            </li>
            @endif
             <li class="nav-item"><a class="nav-link"
                href="{{ route('faq') }}">{{ __('FAQ') }}</a></li>
             <li class="nav-item"><a class="nav-link"
                href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
             <li class="nav-item link-order"><a class="nav-link {{ is_active_menu('order_page') }}"
                href="{{ route('customer.tasks.create') }}">{{ __('Order Now') }}</a></li>
               @auth
               <li class="nav-item"><a class="nav-link login"
                  href="{{ route('login') }}">{{ __('Your Dashboard') }}</a>
               </li>
               @endauth
             @guest
             <li class="nav-item"><a class="nav-link login"
                href="{{ route('login') }}">{{ __('Sign In') }}</a>
             </li>
             @endguest
          </ul>
       </div>
    </div>
 </nav>
