<?php

namespace App\Traits\Settings;

use App\Models\Setting;
use Illuminate\Http\Request;

trait GoogleTagManagerTrait
{

    public function googleTagManager()
    {        
        return inertia('Admin/Settings/GoogleTagManager', [
            'records' => $this->getRecords(array_keys(Setting::googleTagManagerFields())),
            'data'    => [
                'title' => __('Google Tag Manager'),
            ],
        ]);

    }

    public function updateGoogleTagManager(Request $request)
    {        
        $this->saveRecords($request->validate(Setting::googleTagManagerFields()));
        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

}
