<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Website\WebsitePage;
use App\Providers\RouteServiceProvider;
use App\Rules\ValidRecaptcha;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'verifyEmail', 'forGotPassword', 'handleForgotPasswordRequest', 'resetPassword', 'handleResetPassword']);
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

    public function showLoginForm()
    {
        return inertia('Auth/Login', [
            'data' => [
                'title' => __('Login'),
                'page'  => WebsitePage::get(WebsitePage::LOGIN),
            ],
        ]);
    }

    protected function validateLogin(Request $request)
    {
        // Get the user details from database and check if user is inactive
        $user = User::where('email', $request->email)->first();
        if ($user && $user->inactive) {
            throw ValidationException::withMessages([
                $this->username() => __('The account is suspended'),
            ]);
        }

        $recaptcha_enable = filter_var(settings("recaptcha_enable"), FILTER_VALIDATE_BOOLEAN);

        // Then, validate input.
        $request->validate([
            $this->username() => 'required|string',
            'password'        => 'required|string',
            'recaptcha'       => [
                'bail',
                new RequiredIf($recaptcha_enable),
                new ValidRecaptcha(),
            ],
        ]);
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     $request->session()->put('locale', $request->input('language'));
    //     $user->language = $request->input('language');
    //     $user->save();
    // }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }

    // public function verifyEmail(Request $request)
    // {
    //     if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
    //         return redirect($this->redirectTo());
    //     }
    //     return inertia('Auth/VerifyEmail', [
    //         'data' => [
    //             'title'                  => __('Login'),
    //             'verification_link_sent' => $request->session()->get('verification_link_sent'),
    //         ],
    //     ]);
    // }

    // public function forgotPassword(Request $request)
    // {
    //     return inertia('Auth/Passwords/Forgot', [
    //         'data' => [
    //             'title'                    => __('Reset Password'),
    //             'password_reset_link_sent' => $request->session()->get('status'),
    //         ],
    //     ]);
    // }

    // public function handleForgotPasswordRequest(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);
    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return $status === Password::RESET_LINK_SENT
    //     ? back()->with(['status' => __($status)])
    //     : back()->withErrors(['email' => __($status)]);
    // }

    // public function resetPassword(Request $request)
    // {
    //     $token = $request->route()->parameter('token');

    //     $reset = DB::table('password_resets')
    //         ->select(['email'])->where(['email' => $request->input('email')])->first();

    //     $email = null;
    //     $reset_token = null;

    //     if ($reset) {
    //         $email = $request->input('email');
    //         $reset_token = $token;
    //     }

    //     return inertia('Auth/Passwords/Reset', [
    //         'data' => [
    //             'title'            => __('Change Password'),
    //             'token'            => $reset_token,
    //             'email'            => $email,
    //             'password_changed' => $request->session()->get('status'),
    //         ],
    //     ]);
    // }

    // public function handleResetPassword(Request $request)
    // {
    //     $request->validate([
    //         'token'    => 'required',
    //         'email'    => 'required|email',
    //         'password' => ['required', 'string', 'confirmed', 'min:8', PasswordRule::min(8)->letters()->mixedCase()->numbers()->symbols()],
    //     ]);

    //     $status = Password::reset(
    //         $request->only('email', 'password', 'password_confirmation', 'token'),
    //         function ($user, $password) {
    //             $user->forceFill([
    //                 'password' => Hash::make($password),
    //             ])->setRememberToken(Str::random(60));

    //             $user->save();

    //             event(new PasswordReset($user));
    //         }
    //     );

    //     return $status === Password::PASSWORD_RESET
    //     ? redirect()->route('login')->with('status', __($status))
    //     : back()->withErrors(['password' => [__($status)]]);
    // }
}
