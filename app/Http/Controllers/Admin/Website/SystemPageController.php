<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Models\Website\WebsitePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SystemPageController extends Controller
{

    public function homeEdit($language)
    {
        $page = WebsitePage::HOME;

        return inertia($this->getViewFileBySection($page), [
            'data'             => [
                'title' => $this->getPageTitle(__('Home')),
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $page),
        ]);
    }

    public function homeUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, WebsitePage::HOME, $request->validate([
            'meta_tags' => 'required',
        ]));

    }

    public function blogEdit($language)
    {
        $page = WebsitePage::BLOG;

        return inertia($this->getViewFileBySection($page), [
            'data'             => [
                'title'     => $this->getPageTitle(__('Blog')),
                'dropdowns' => [
                    'image_positions' => WebsitePage::getImagePositions(),
                ],
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $page),
        ]);
    }

    public function blogUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, WebsitePage::BLOG, $request->validate([
            'title'     => 'required',
            'sub_title' => 'nullable',
            'meta_tags' => 'required',
        ]));

    }

    public function faqEdit($language)
    {
        $page = WebsitePage::FAQ;

        return inertia($this->getViewFileBySection($page), [
            'data'             => [
                'title'     => $this->getPageTitle(__('FAQ')),
                'dropdowns' => [
                    'image_positions' => WebsitePage::getImagePositions(),
                ],
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $page),
        ]);
    }

    public function faqUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, WebsitePage::FAQ, $request->validate([
            'title'          => 'required',
            'sub_title'      => 'nullable',
            'image'          => 'nullable',
            'image_alt_text' => 'required_with:image',
            'image_position' => 'required_with:image',
            'meta_tags'      => 'nullable',
        ]));

    }

    public function loginEdit($language)
    {
        $page = WebsitePage::LOGIN;

        return inertia($this->getViewFileBySection($page), [
            'data'             => [
                'title' => $this->getPageTitle(__('Login')),
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $page),
        ]);
    }

    public function loginUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, WebsitePage::LOGIN, $this->validateAuthenticatePageForm($request));

    }

    public function registrationEdit($language)
    {
        $page = WebsitePage::REGISTRATION;

        return inertia($this->getViewFileBySection($page), [
            'data'             => [
                'title' => $this->getPageTitle(__('Registration')),
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $page),
        ]);
    }

    public function registrationUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, WebsitePage::REGISTRATION, $this->validateAuthenticatePageForm($request));

    }
    public function forgotPasswordEdit($language)
    {
        $page = WebsitePage::FORGOT_PASSWORD;

        return inertia($this->getViewFileBySection($page), [
            'data'             => [
                'title' => $this->getPageTitle(__('Forgot Password')),
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $page),
        ]);
    }

    public function forgotPasswordUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, WebsitePage::FORGOT_PASSWORD, $this->validateAuthenticatePageForm($request));

    }

    public function authorApplicationEdit($language)
    {
        $page = WebsitePage::AUTHOR_APPLICATION;

        return inertia($this->getViewFileBySection($page), [
            'data'             => [
                'title' => $this->getPageTitle(__('Author Application')),
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $page),
        ]);
    }

    public function authorApplicationUpdate(Request $request, $language)
    {        
        return $this->updateRecord($language, WebsitePage::AUTHOR_APPLICATION, $request->validate([
            'title'                                   => 'required|max:192',
            'sub_title'                               => 'nullable|max:192',
            'content'                                 => 'nullable',
            'additional_data'                         => 'required|array',
            'additional_data.form_title'              => 'required|max:192',
            'additional_data.form_sub_title'          => 'nullable|max:192',
            'additional_data.form_submission_message' => 'required|max:192',
            'additional_data.meta_title'              => 'required|max:192',
            'additional_data.meta_description'        => 'required',
            'additional_data.meta_keywords'           => 'required|max:192',
            'additional_data.meta_image'              => 'required',
        ], [], [
            'additional_data.form_title'              => __('Application Form Title'),
            'additional_data.form_sub_title'          => __('Application Form Sub Title'),
            'additional_data.form_submission_message' => __('Success Message'),
            'additional_data.meta_title'              => __('Meta Title'),
            'additional_data.meta_description'        => __('Meta Description'),
            'additional_data.meta_keywords'           => __('Meta Keywords'),
            'additional_data.meta_image'              => __('Meta Image'),

        ]));

    }
    public function contactEdit($language)
    {
        $page = WebsitePage::CONTACT;

        return inertia($this->getViewFileBySection($page), [
            'data'             => [
                'title'     => $this->getPageTitle(__('Contact us')),
                'dropdowns' => [
                    'image_positions' => WebsitePage::getImagePositions(),
                ],
            ],
            'content_language' => $language,
            'existing_record'  => $this->getExistingRecord($language, $page),
        ]);
    }

    public function contactUpdate(Request $request, $language)
    {
        return $this->updateRecord($language, WebsitePage::CONTACT, $request->validate([
            'title'                                   => 'required|max:192',
            'sub_title'                               => 'nullable|max:192',
            'content'                                 => 'nullable',
            'image'                                   => 'required',
            'image_alt_text'                          => 'required',
            'image_position'                          => 'required',
            'meta_tags'                               => 'nullable',
            'additional_data'                         => 'required|array',
            'additional_data.form_title'              => 'required|max:192',
            'additional_data.form_sub_title'          => 'nullable|max:192',
            'additional_data.form_submission_message' => 'required|max:192',

        ], [], [
            'additional_data.form_title'              => __('Contact Form Title'),
            'additional_data.form_sub_title'          => __('Contact Form Sub Title'),
            'additional_data.form_submission_message' => __('Success Message'),

        ]));

    }

    private function getViewFileBySection($page)
    {
        $files = [
            WebsitePage::HOME                   => 'Home',
            WebsitePage::BLOG                   => 'Blog',
            WebsitePage::FAQ                    => 'Faq',
            WebsitePage::LOGIN                  => 'Login',
            WebsitePage::REGISTRATION           => 'Registration',
            WebsitePage::FORGOT_PASSWORD        => 'ForgotPassword',
            WebsitePage::AUTHOR_APPLICATION => 'AuthorApplication',
            WebsitePage::CONTACT                => 'Contact',
        ];

        return 'Admin/Manage/SystemPages/' . $files[$page];
    }

    private function getExistingRecord($language, $page)
    {
        return WebsitePage::where('locale', $language)->where('name', $page)
            ->where('locale', $language)->get()->first();
    }

    private function updateRecord($language, $page, $data)
    {
        WebsitePage::updateOrCreate(['locale' => $language, 'name' => $page], $data);

        Cache::forget(WebsitePage::getCacheName($language, $page));

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    private function getPageTitle($title)
    {
        return __('System Default Page') . ' ' . $title;
    }

    private function validateAuthenticatePageForm($request)
    {
        return $request->validate([
            'title'                                    => 'required',
            'sub_title'                                => 'nullable',
            // Welcome section
            'additional_data.welcome_title'            => 'required',
            'additional_data.welcome_sub_title'        => 'nullable',
            'additional_data.welcome_image'            => 'required',
            'additional_data.welcome_image_alt_text'   => 'required',
            // SEO
            'additional_data.meta_title'               => 'required|max:192',
            'additional_data.meta_description'         => 'required',
            'additional_data.meta_keywords'            => 'required|max:192',
            'additional_data.meta_image'               => 'required',
            'additional_data.welcome_background_color' => 'required',
        ], [], [
            // Welcome section
            'additional_data.welcome_title'            => __('Welcome Title'),
            'additional_data.welcome_sub_title'        => __('Welcome Sub Title'),
            'additional_data.welcome_image'            => __('Welcome Image'),
            'additional_data.welcome_image_alt_text'   => __('Image Alt Text'),
            // SEO
            'additional_data.meta_title'               => __('Meta Title'),
            'additional_data.meta_description'         => __('Meta Description'),
            'additional_data.meta_keywords'            => __('Meta Keywords'),
            'additional_data.meta_image'               => __('Meta Image'),
            'additional_data.welcome_background_color' => __('Background Color'),
        ]);

    }
}
