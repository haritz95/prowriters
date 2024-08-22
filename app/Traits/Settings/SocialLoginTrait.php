<?php

namespace App\Traits\Settings;

use App\Models\Setting;
use Illuminate\Http\Request;

trait SocialLoginTrait
{
    public function socialLogin()
    {
        return inertia('Admin/Settings/SocialLogin', [
            'records' => $this->getRecords([
                'is_google_enable',
                'google_client_id',
                'google_client_secret',
            ]),
            'data'    => [
                'title'          => __('Social Login Settings'),
                'call_back_urls' => Setting::socialLoginCallbackUrls(),

            ],
        ]);
    }

    public function updateSocialLogin(Request $request)
    {
        $this->saveRecords($request->validate([
            'is_google_enable'     => 'nullable|boolean',
            'google_client_id'     => 'required_if:is_google_enable,1|string|max:500',
            'google_client_secret' => 'required_if:is_google_enable,1|string|max:500',
        ]));

        return redirect()->back()->withSuccess(__('Successfully updated'));

    }

}
