<?php

namespace App\Http\Controllers\Auth\SocialLogin;

use App\Models\User;
use Exception;
use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\InvalidStateException;

class FacebookController extends Controller
{
    private $provider;

    function __construct()
    {
        if (settings('facebook_client_id') && settings('facebook_client_secret')) {
            $this->provider = Socialite::buildProvider(FacebookProvider::class, [
                'client_id' => settings('facebook_client_id'),
                'client_secret' => settings('facebook_client_secret'),
                'redirect' => Setting::socialLoginCallbackUrls('facebook'),
            ]);
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return $this->provider->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function handleFacebookCallback()
    {
        $failedMessage = __('Please login with your email and password');

        try {
            $token = $this->provider->user();
        } catch (InvalidStateException $e) {
            $token = $this->provider->stateless()->user();
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['email' => $failedMessage]);
        }

        try {
            if (isset($token->id)) {
                $user = User::where('facebook_id', $token->id)->orWhere('email', $token->email)->first();
                if (!$user) {
                    $name = explode(" ", $token->user['name']);
                    $user = User::create([
                        'first_name' => (isset($name[0])) ? $name[0] : null,
                        'last_name' => (isset($name[1])) ? $name[1] : null,
                        'email' => $token->email,
                        'facebook_id' => $token->id,
                        'email_verified_at' => now(),
                        'password' => encrypt('123456')
                    ]);
                }
                if (empty($user->facebook_id)) {
                    User::where('email', $token->email)->update(['facebook_id' => $token->id]);
                }
                if (empty($user->email_verified_at)) {
                    User::where('email', $token->email)->update(['email_verified_at' => now()]);
                }
                Auth::login($user);
                return redirect(RouteServiceProvider::redirectTo());
            } else {
                return redirect()->route('login')->withErrors(['email' => $failedMessage]);
            }
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['email' => $failedMessage]);
        }
    }
}
