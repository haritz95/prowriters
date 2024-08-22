<?php

namespace App\Traits\Settings;

use App\Models\Setting;
use Illuminate\Http\Request;

trait AppSettingTrait
{

    public function appSettings()
    {
        return inertia('Admin/Settings/AppSetting', [
            'records' => $this->getRecords(array_keys(Setting::appSettingsFields())),
            'data'    => [
                'title' => __('Application'),
            ],
        ]);

    }

    public function updateAppSettings(Request $request)
    {
        $this->saveRecords($request->validate(Setting::appSettingsFields()));
        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

}
