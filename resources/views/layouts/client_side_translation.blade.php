@php $languageFilepath = lang_path(app()->getLocale() . '.json') ; @endphp
@if(app()->getLocale() != 'en' && file_exists($languageFilepath))    
<script type="text/javascript"> 
    window.js_translations = {!! file_get_contents($languageFilepath) !!};
    window.translate = function($key){
        return (js_translations[$key]) ? js_translations[$key] : $key;
    };
</script>
@else 
<script type="text/javascript"> 
    window.translate = function($key){
        return $key;
    };
</script>
@endif