<?php

namespace App\Http\Controllers\Auth\SocialLogin;

use App\Models\User;
use Exception;
use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\One\MissingTemporaryCredentialsException;
use Laravel\Socialite\One\TwitterProvider;

class TwitterController extends Controller
{
    private $provider;

    function __construct()
    {
        if (settings('twitter_client_id') && settings('twitter_client_secret')) {
            $this->provider = Socialite::buildProvider(TwitterProvider::class, [
                'client_id' => settings('twitter_client_id'),
                'client_secret' => settings('twitter_client_secret'),
                'redirect' => Setting::socialLoginCallbackUrls('twitter'),
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
    public function redirectToTwitter()
    {
        return $this->provider->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function handleTwitterCallback()
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
                $user = User::where('twitter_id', $token->id)->orWhere('email', $token->email)->first();
                if (!$user) {
                    $name = explode(" ", $token->name);
                    $user = User::create([
                        'first_name' => (isset($name[0])) ? $name[0] : null,
                        'last_name' => (isset($name[1])) ? $name[1] : null,
                        'email' => $token->email,
                        'twitter_id' => $token->id,
                        'email_verified_at' => now(),
                        'password' => encrypt('123456')
                    ]);
                }
                if (empty($user->twitter_id)) {
                    User::where('email', $token->email)->update(['twitter_id' => $token->id]);
                }
                if (empty($user->email_verified_at)) {
                    User::where('email', $token->email)->update(['email_verified_at' => now()]);
                }
                Auth::login($user);
                return redirect(RouteServiceProvider::redirectTo());
            } else {
                return redirect()->route('login')->withErrors(['email' => $failedMessage]);
            }
        } catch (MissingTemporaryCredentialsException $e) {
            return redirect()->route('login')->withErrors(['email' => $failedMessage]);
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['email' => $failedMessage]);
        }
    }
}
