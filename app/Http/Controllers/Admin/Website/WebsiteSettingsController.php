<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Traits\Settings\SettingsCRUDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebsiteSettingsController extends Controller
{
    use SettingsCRUDTrait;

    // public function index($language)
    // {
    //     return inertia('Admin/Manage/DefaultPage', [
    //         'records' => $this->getRecords(array_keys($this->getGeneralFields())),
    //         'data'    => [
    //             'title'            => __('General Settings'),

    //         ],
    //         'content_language' => $language,
    //     ]);
    // }

    // public function updateGeneral(Request $request)
    // {
    //     $data = $request->validate($this->getGeneralFields());

    //     $this->saveRecords($data);

    //     return redirect()->back()->withSuccess(__('Successfully updated'));
    // }

    // private function getGeneralFields()
    // {
    //     return [
    //         'website_top_nav_bg_color'              => 'nullable',
    //         'website_top_nav_link_color'            => 'nullable',
    //         'website_top_nav_link_hover_color'      => 'nullable',
    //         'website_footer_bg_color'               => 'nullable',
    //         'website_footer_text_color'             => 'nullable',
    //         'website_footer_link_color'             => 'nullable',
    //         'website_footer_link_hover_color'       => 'nullable',           
    //         'website_order_now_button_color'        => 'nullable',
    //         'website_order_now_button__hover_color' => 'nullable',

    //         'website_logo'                          => 'nullable',
    //         'website_accept_payment_image'          => 'nullable',
    //         'website_company_address'               => 'nullable',
            
    //     ];
    // }

    // public function cache()
    // {
    //     return inertia('Admin/Settings/Cache', [
    //         'data' => [
    //             'title' => __('Clear Cache'),
    //             'urls'  => [
    //                 'submit_form' => route('admin.settings.clear.cache'),
    //             ],

    //         ],
    //     ]);
    // }

    public function translateTexts()
    {
        $jsonStrings = file_get_contents(resource_path('lang/fr_app.json'));
        $records     = json_decode($jsonStrings, true, 512, JSON_UNESCAPED_UNICODE);
        return view('setup.translate', compact('records'));
    }

    public function saveTranslatedTexts(Request $request)
    {
        $jsonStrings = file_get_contents(resource_path('lang/fr_app.json'));
        $records     = json_decode($jsonStrings, true, 512, JSON_UNESCAPED_UNICODE);

        $i     = 0;
        $texts = $request->texts;
        foreach ($records as $key => $value) {
            $records[$key] = $texts[$i];
            $i++;
        }

        $newJsonString = json_encode($records, JSON_UNESCAPED_UNICODE);
        file_put_contents(resource_path('lang/fr_app.json'), $newJsonString);

        $this->saveTranslationToOriginalFile($records);

        return redirect()->back()->withSuccess(__('Successfully Updated'));
    }

    private function saveTranslationToOriginalFile($data)
    {
        $jsonStrings = file_get_contents(resource_path('lang/fr.json'));
        $records     = json_decode($jsonStrings, true, 512, JSON_UNESCAPED_UNICODE);

        $records = array_merge($records, $data);

        $newJsonString = json_encode($records, JSON_UNESCAPED_UNICODE);
        file_put_contents(resource_path('lang/fr.json'), $newJsonString);

        return redirect()->back()->withSuccess(__('Successfully Updated'));
    }

}
