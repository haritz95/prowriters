<?php

namespace App\Traits\Settings;

use Illuminate\Http\Request;

trait GoogleRecaptchaTrait
{

    public function recaptcha()
    {
        return inertia('Admin/Settings/GoogleRecaptcha', [
            'records' => $this->getRecords([
                'recaptcha_enable',
                'recaptcha_site_key',
                'recaptcha_secret',
            ]),
            'data'    => [
                'title' => __('Google Recaptcha'),                
            ],
        ]);

    }

    public function updateRecaptcha(Request $request)
    {        
        $request->validate([
            'recaptcha_site_key' => 'required_with:recaptcha_enable',
            'recaptcha_secret'   => 'required_with:recaptcha_enable',
        ], [
            'recaptcha_site_key.required_with' => 'Recaptcha site key field is required',
            'recaptcha_secret.required_with'   => 'Recaptcha secret field is required',
        ]);

        $this->saveRecords($request->only(['recaptcha_site_key', 'recaptcha_secret', 'recaptcha_enable']));

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

}
