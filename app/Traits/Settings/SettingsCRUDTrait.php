<?php

namespace App\Traits\Settings;

use App\Models\Setting;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Mews\Purifier\Facades\Purifier;

trait SettingsCRUDTrait
{
    private function getRecords(array $keys)
    {
        $records = Setting::whereIn('option_key', $keys)->get();

        foreach ($records as $row) {
            $values_in_db[$row->option_key] = $row->option_value;
        }

        foreach ($keys as $key) {
            $settings[$key] = (isset($values_in_db[$key])) ? $values_in_db[$key] : '';
        }
        return $settings;
    }

    private function saveRecords($data, $auto_load_disabled = null, $sanitize = null)
    {        
        foreach ($data as $key => $value) {

            Setting::updateOrCreate([
                'option_key' => $key,
            ], ['option_value' => trim($value), 'auto_load_disabled' => null]);            
        }
    }

    private function updateEnvKeys($keys)
    {
        DotenvEditor::setKeys($keys);
        DotenvEditor::save();

        \Artisan::call("cache:clear");
        \Artisan::call("config:clear");
    }

    private function getEnv($key)
    {
        if (DotenvEditor::keyExists($key)) {
            return DotenvEditor::getValue($key);
        }
        return null;
    }

}
