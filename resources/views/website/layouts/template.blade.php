<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ get_layout_direction() }}">
<head>
    @include('website.layouts.google_tag_manager')
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ get_company_name() }}</title>
    @hasSection('seo')
        @yield('seo')
    @endif
    @if($favicon = get_website_favicon())
        <link rel="icon" rel="shortcut icon" href="{{ $favicon }}" type="image/x-icon" />
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,regular,500,600,700,800,900,100italic,200italic,300italic,italic,500italic,600italic,700italic,800italic,900italic&#038;subset=latin,latin-ext&#038;display=swap"
        rel="stylesheet">
    @vite(['resources/sass/website/theme.scss'])
        <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v6.2.1/css/all.css">
<?php 
    $css = new \App\Services\CSSGeneratorService();   
?>
  <style>
    <?php echo $css->forWebsite(); ?>    
</style>      
</head>
<body>
    @include('website.layouts.header')
    <div>
        @yield('content')
    </div>
    @include('website.layouts.footer')
    @vite(['resources/js/website/main.js'])
    @stack('scripts')

    <script>
        
        document.addEventListener("DOMContentLoaded", function(){
           

  window.addEventListener('scroll', function() {
      if (window.scrollY > 50) {
        document.getElementById('sticky-header').classList.add('fixed-top');
        // add padding top to show content behind navbar
        navbar_height = document.querySelector('.navbar').offsetHeight;
        document.body.style.paddingTop = navbar_height + 'px';
      } else {
        document.getElementById('sticky-header').classList.remove('fixed-top');
         // remove padding top from body
        document.body.style.paddingTop = '0';
      } 
  });
}); 
// DOMContentLoaded  end
    </script>
    @include('cookie-consent::index')
</body>
</html>