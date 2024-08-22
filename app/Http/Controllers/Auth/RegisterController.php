<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\Website\WebsitePage;
use App\Providers\RouteServiceProvider;
use App\Rules\ValidRecaptcha;
use App\Services\UserService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\RequiredIf;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    public function redirectTo()
    {
        return RouteServiceProvider::redirectTo();
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        return inertia('Auth/Register', [
            'data' => [
                'title'     => __('Register'),
                'page'      => WebsitePage::get(WebsitePage::REGISTRATION),
                'dropdowns' => [
                    'countries' => Country::all(),
                    'timezones' => User::getTimeZones(),
                ],
                'tooltips'  => [
                    'password' => __('Must contain 8 or more characters that are of at least one number, and one uppercase and lowercase letter, and special character'),
                ],
            ],
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $recaptcha_enable = filter_var(settings("recaptcha_enable"), FILTER_VALIDATE_BOOLEAN);

        $rules = [
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'string', 'min:8', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'country_code' => 'required',
            'timezone'     => 'required',
            'phone'        => 'nullable',
            'recaptcha'    => [
                'bail',
                new RequiredIf($recaptcha_enable),
                new ValidRecaptcha(),
            ],

        ];

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    protected function create(array $data)
    {
        return (new UserService())->customerRegistration($data);
    }
}
