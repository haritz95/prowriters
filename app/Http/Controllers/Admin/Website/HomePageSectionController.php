<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Models\Website\HomePageSection;
use Illuminate\Http\Request;

class HomePageSectionController extends Controller
{

    public function heroEdit($language)
    {
        $section = HomePageSection::HERO;

        return inertia($this->getViewFileBySection($section), [
            'data'             => [
                'title' => $this->getPageTitle(__('Hero')),
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $section),
        ]);
    }

    public function heroUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, HomePageSection::HERO, $request->validate([
            'title'          => 'required',
            'sub_title'      => 'nullable',
            'content'        => 'nullable',
            'image'          => 'required',
            'image_alt_text' => 'required',

        ]));

    }

    public function aboutEdit($language)
    {
        $section = HomePageSection::ABOUT_US;

        return inertia($this->getViewFileBySection($section), [
            'data'             => [
                'title' => $this->getPageTitle(__('About')),
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $section),
        ]);
    }

    public function aboutUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, HomePageSection::ABOUT_US, $request->validate([
            'title'                            => 'required|max:192',
            'sub_title'                        => 'nullable|max:192',
            'additional_data'                  => 'nullable|array',
            'additional_data.*.title'          => 'required|max:192',
            'additional_data.*.content'        => 'required',
            'additional_data.*.image'          => 'required',
            'additional_data.*.image_alt_text' => 'required',
        ], [], [
            'additional_data.*.title'          => __('Title'),
            'additional_data.*.content'        => __('Content'),
            'additional_data.*.image'          => __('Image'),
            'additional_data.*.image_alt_text' => __('Alt Text'),
        ]));

    }

    public function howItWorksEdit($language)
    {
        $section = HomePageSection::HOW_IT_WORKS;

        return inertia($this->getViewFileBySection($section), [
            'data'             => [
                'title' => $this->getPageTitle(__('About')), __('How it works'),
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $section),
        ]);
    }

    public function howItWorksUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, HomePageSection::HOW_IT_WORKS, $request->validate([
            'title'                            => 'required|max:192',
            'sub_title'                        => 'required|max:192',
            'additional_data'                  => 'nullable|array',
            'additional_data.*.title'          => 'required|max:192',
            'additional_data.*.content'        => 'required',
            'additional_data.*.image'          => 'required',
            'additional_data.*.image_alt_text' => 'required',
        ], [], [
            'additional_data.*.title'          => __('Title'),
            'additional_data.*.content'        => __('Content'),
            'additional_data.*.image'          => __('Image'),
            'additional_data.*.image_alt_text' => __('Alt Text'),
        ]));

    }

    public function whyChooseUsEdit($language)
    {
        $section = HomePageSection::WHY_CHOOSE_US;

        return inertia($this->getViewFileBySection($section), [
            'data'             => [
                'title'     => $this->getPageTitle(__('About')), __('Why choose us'),
                'dropdowns' => [
                    'image_positions' => HomePageSection::getImagePositions(),
                ],
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $section),
        ]);
    }

    public function whyChooseUsUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, HomePageSection::WHY_CHOOSE_US, $request->validate([
            'title'                            => 'required|max:192',
            'sub_title'                        => 'required|max:192',
            'appearance'                       => 'nullable|array',
            'appearance.bg_color'              => 'nullable',
            'appearance.text_color'            => 'nullable',
            'additional_data'                  => 'nullable|array',
            'additional_data.*.title'          => 'required|max:192',
            'additional_data.*.content'        => 'required',
            'additional_data.*.image'          => 'required',
            'additional_data.*.image_alt_text' => 'required',
        ], [], [
            'additional_data.*.title'          => __('Title'),
            'additional_data.*.content'        => __('Content'),
            'additional_data.*.image'          => __('Image'),
            'additional_data.*.image_alt_text' => __('Alt Text'),
            'appearance.bg_color'              => __('Background Color'),
            'appearance.text_color'            => __('Text Color'),
        ]));

    }

    public function footerEdit($language)
    {
        $section = HomePageSection::FOOTER;

        return inertia($this->getViewFileBySection($section), [
            'data'             => [
                'title' => $this->getPageTitle(__('Footer')),
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $section),
        ]);
    }

    public function footerUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, HomePageSection::FOOTER, $request->validate([
            'additional_data'                     => 'required|array',
            'additional_data.footer_text'         => 'nullable',
            'additional_data.company_information' => 'required',
        ], [], [
            'additional_data.footer_text'         => __('Footer Text'),
            'additional_data.company_information' => __('Company Information'),
        ]));

    }

    private function getViewFileBySection($section)
    {
        $files = [
            HomePageSection::HERO          => 'Hero',
            HomePageSection::ABOUT_US      => 'About',
            HomePageSection::HOW_IT_WORKS  => 'HowItWorks',
            HomePageSection::WHY_CHOOSE_US => 'WhyChooseUs',
            HomePageSection::FOOTER        => 'Footer',
        ];

        return 'Admin/Manage/Homepage/' . $files[$section];
    }

    private function getExistingRecord($language, $section)
    {
        return HomePageSection::where('locale', $language)->where('name', $section)
            ->where('locale', $language)->get()->first();
    }

    private function updateRecord($language, $section, $data)
    {
        HomePageSection::updateOrCreate(['locale' => $language, 'name' => $section], $data);

        HomePageSection::clearCache($language);

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    private function getPageTitle($title)
    {
        return __('Homepage') . ' ' . $title;
    }
}
