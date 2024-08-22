<?php

namespace App\Http\Controllers\Auth\SocialLogin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\AvatarUploadService;
use App\Services\UserService;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use \Laravel\Socialite\Two\GoogleProvider;

class GoogleController extends Controller
{
    private $provider;

    public function __construct()
    {
        if ($this->isEnabled()) {
            $this->provider = Socialite::buildProvider(GoogleProvider::class, [
                'client_id'     => settings('google_client_id'),
                'client_secret' => settings('google_client_secret'),
                'redirect'      => Setting::socialLoginCallbackUrls('google'),
            ]);
        }
    }

    private function isEnabled()
    {
        return (settings('google_client_id') && settings('google_client_secret')) ?? false;
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle(Request $request)
    {

        if (!$this->isEnabled()) {
            return redirect()->route($this->getRedirectRoute());
        }

        $timezone = null;

        if ($request->has('timezone') && $this->isValidTimezone($request->timezone)) {
            $timezone = $request->timezone;
        }

        if ($this->isRequestFromCheckout()) {
            session(['checkout' => true, 'timezone' => $timezone]);
        } else {
            session(['checkout' => false, 'timezone' => $timezone]);
        }

        return $this->provider->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback(Request $request, UserService $userService, AvatarUploadService $avatarUploadService)
    {
        $failedMessage = __('Please login with your email and password');
        try {
            $token = $this->provider->user();
        } catch (InvalidStateException $e) {
            $token = $this->provider->stateless()->user();
        } catch (Exception $e) {
            return redirect()->route($this->getRedirectRoute())->withErrors(['email' => $failedMessage]);
        }

        $timezone = UserService::DEFAULT_TIME_ZONE;

        if ($request->session()->has('timezone') && $request->session()->get('timezone')) {
            $timezone = $request->session()->get('timezone');
        }

        $this->forgetSession();

        try {
            if (isset($token->id)) {
                $user = User::where('google_id', $token->id)->orWhere('email', $token->email)->first();
                if (!$user) {

                    // User Photo
                    $photo = null;

                    if (isset($token->avatar)) {
                        $photo = $avatarUploadService->storeAvatarFromRemoteURL($token->avatar);
                    }

                    // Create User Record
                    $user = $userService->createCustomer([
                        'first_name' => $token->user['given_name'],
                        'last_name'  => $token->user['family_name'],
                        'email'      => $token->email,
                        'language'   => $token->user['locale'],
                        'password'   => '123456',
                        'google_id'  => $token->id,
                        'photo'      => $photo,
                        'timezone'   => $timezone,
                    ]);
                }

                if (empty($user->google_id)) {
                    User::where('email', $token->email)->update(['google_id' => $token->id]);
                }
                if (empty($user->email_verified_at)) {
                    User::where('email', $token->email)->update(['email_verified_at' => now()]);
                }
                Auth::login($user);

                if ($this->isRequestFromCheckout()) {
                    return redirect()->route('checkout');
                } else {
                    return redirect(RouteServiceProvider::redirectTo());
                }

            } else {
                return redirect()->route($this->getRedirectRoute())->withErrors(['email' => $failedMessage]);
            }
        } catch (Exception $e) {
            return redirect()->route($this->getRedirectRoute())->withErrors(['email' => $failedMessage]);
        }
    }

    private function isRequestFromCheckout()
    {
        if (request()->has('checkout') || request()->session()->get('checkout')) {
            return true;
        }
        return false;
    }

    private function getRedirectRoute()
    {
        return ($this->isRequestFromCheckout()) ? 'checkout' : 'login';
    }

    private function isValidTimezone($timezone)
    {
        return (in_array($timezone, DateTimeZone::listIdentifiers())) ? true : false;
    }

    private function forgetSession()
    {
        request()->session()->forget(['checkout', 'timezone']);
    }
}
