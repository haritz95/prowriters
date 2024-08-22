<?php

namespace App\Traits\Settings;

use Illuminate\Http\Request;

trait SocialLinkTrait
{
    private function getSocialLinkFields()
    {
        return [
            'facebook'  => 'nullable|max:1000',
            'twitter'   => 'nullable|max:1000',
            'instagram' => 'nullable|max:1000',
            'linkedin'  => 'nullable|max:1000',
            'youtube'   => 'nullable|max:1000',
        ];
    }
    public function socialLinks()
    {
        return inertia('Admin/Settings/SocialLinks', [
            'records' => $this->getRecords(array_keys($this->getSocialLinkFields())),
            'data'    => [
                'title' => __('Social Media Links'),
            ],
        ]);
    }

    public function updateSocialLinks(Request $request)
    {
        $this->saveRecords($request->validate($this->getSocialLinkFields()));

        return redirect()->back()->withSuccess(__('Successfully updated'));

    }

}
