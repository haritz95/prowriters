@component('mail::message')

{{ __('Hi') }}, 

{{ __('Congratulation') }}! {{ __('Your email is configured correctly') }}.


{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
