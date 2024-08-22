@component('mail::message')

{{ __('Hi') }}, 

{{ __('Congratulation') }}! {{ __('You have been invited to join') }} {{ settings('company_name') }} {{ __('as') }} {{ $role_name }} ! {{ __('Please click the button below to confirm your joining') }}.

@component('mail::button', ['url' => route('register', ['c' => $ref_code ])])
{{ __('Join Now') }}
@endcomponent

{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
