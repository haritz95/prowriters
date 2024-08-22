<?php

namespace App\Http\Controllers\Auth\SocialLogin;

use App\Models\User;
use Exception;
use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\LinkedInProvider;
use Laravel\Socialite\Two\InvalidStateException;

class LinkedinController extends Controller
{
    private $provider;

    function __construct()
    {
        if (settings('linkedin_client_id') && settings('linkedin_client_secret')) {
            $this->provider = Socialite::buildProvider(LinkedInProvider::class, [
                'client_id' => settings('linkedin_client_id'),
                'client_secret' => settings('linkedin_client_secret'),
                'redirect' => Setting::socialLoginCallbackUrls('linkedin'),
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
    public function redirectToLinkedin()
    {
        return $this->provider->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function handleLinkedinCallback()
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
                $user = User::where('linkedin_id', $token->id)->orWhere('email', $token->email)->first();
                if (!$user) {
                    $user = User::create([
                        'first_name' => $token->first_name,
                        'last_name' => $token->last_name,
                        'email' => $token->email,
                        'linkedin_id' => $token->id,
                        'email_verified_at' => now(),
                        'password' => encrypt('123456')
                    ]);
                }
                if (empty($user->linkedin_id)) {
                    User::where('email', $token->email)->update(['linkedin_id' => $token->id]);
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
