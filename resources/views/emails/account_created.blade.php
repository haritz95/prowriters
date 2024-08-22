@component('mail::message')

Dear {{ $user->full_name}}, 

Your account has been created. Please use the following credentials to login:

@component('mail::table')
    | Email | Password |
    | -- |:----:|
    | {{ $user->email }} | {{ $password }} |
@endcomponent


@component('mail::button', ['url' => route('login')])
{{ __('Login') }}
@endcomponent

{{ __('Thank you') }},<br>
{{ get_company_name() }}

@endcomponent
