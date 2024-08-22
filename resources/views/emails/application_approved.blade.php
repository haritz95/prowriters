@component('mail::message')
{{ __('Dear') }} {{ $user->first_name }},

{{ __('We are pleased to inform you that your application for joining us as a Writer has been approved') }} 

{{ __('You can use the following credentials to log in to our system') }} . {{ __('Please make sure to change your password after you log in') }}

@component('mail::table')
| Email | Password |
|:-----| -----:| 
|{{ $user->email }}| {{ $password }}|

@endcomponent

@component('mail::button', ['url' => $login_link])
{{ __('Login') }}
@endcomponent

{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
