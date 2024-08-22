<?php

namespace App\Services;

use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginOrRegisterService
{
    private $userService;

    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Login or Register a user
     *
 
     * @param  array  $data

     * @return \Illuminate\Http\RedirectResponse
     */

    public function __invoke(array $data): ?RedirectResponse    
    {
        if (!auth()->check()) {
            if ($data['customer_type']  == 'new_customer') {
                $user = $this->userService->createCustomer($data);
                Auth::login($user);
            }
            if ($data['customer_type']  == 'returning_customer') {
                if (!(Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'type' => UserType::CUSTOMER]))) {
                    return back()->withErrors(["email" => __("Not a valid login credential")])->withInput();
                }
                // $request->session()->regenerate();
                request()->session()->regenerate();
            }
        }
        return NULL;
    }
}
