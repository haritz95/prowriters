<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Website\WebsitePage;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    use ResetsPasswords;

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password'      => ['required', 'string', 'confirmed', 'min:8', PasswordRule::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ];
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

    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');

        $reset = DB::table('password_resets')
            ->select(['email'])->where(['email' => $request->input('email')])->first();

        $email       = null;
        $reset_token = null;

        if ($reset) {
            $email       = $request->input('email');
            $reset_token = $token;
        }

        return inertia('Auth/Passwords/Reset', [
            'data' => [
                'title'            => __('Change Password'),
                'token'            => $reset_token,
                'email'            => $email,
                'password_changed' => $request->session()->get('status'),
                'page'  => WebsitePage::get(WebsitePage::LOGIN),
            ],
        ]);
    }

    // /**
    //  * Reset the given user's password.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    //  */
    // public function reset(Request $request)
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
