<?php

namespace App\Traits\Profile;

use App\Http\Requests\ChangeProfilePhotoRequest;
use App\Models\Country;
use App\Models\Locale\SystemLanguage;
use App\Models\User;
use App\Services\AvatarUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

trait AccountProfileTrait
{
    public function edit(Request $request)
    {
        return inertia($this->getFileLocation('Edit'), [
            'data' => [
                'title' => __('Contact Information'),
            ],
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user()->update($request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
            'phone'      => 'required|string|max:255',
        ]));

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    public function language(Request $request)
    {
        return inertia($this->getFileLocation('Language'), [
            'data' => [
                'title'     => __('Language'),
                'dropdowns' => [
                    'languages' => SystemLanguage::all(),
                ],
            ],
            'user' => auth()->user(),
        ]);
    }

    public function updateLanguage(Request $request)
    {
        auth()->user()->update($request->validate([
            'language' => 'required',
        ]));

        $request->session()->put('locale', $request->language);

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    

    public function password(Request $request)
    {
        return inertia($this->getFileLocation('ChangePassword'), [
            'data' => [
                'title' => __('Change Password'),
            ],
            'user' => auth()->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $password = $user->password;

        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($password) {
                    if (!Hash::check($value, $password)) {
                        return $fail(__('Current password is not valid'));
                    }
                },
            ],
            'password'         => ['required', Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(), 'confirmed', 'different:current_password'],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    public function avatar()
    {
        return inertia($this->getFileLocation('Avatar'), [
            'data' => [
                'title' => __('Change Avatar'),
            ],
            'user' => auth()->user(),
        ]);
    }

    public function updateAvatar(ChangeProfilePhotoRequest $request, AvatarUploadService $avatar)
    {
        if ($avatar->upload($request, auth()->user())) {
            return redirect()->back()->withSuccess(__('Successfully updated'));
        } else {
            return redirect()->back()->withError(__('Avatar could not be updated'));
        }
    }

}
