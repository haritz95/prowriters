<?php

namespace App\Services;

use Artesaos\SEOTools\Facades\SEOMeta;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;

class SeoService
{

    public function load($page)
    {
        $fields = Setting::getSeoFieldsByPage($page);

        if ($fields) {

            $meta = Setting::whereIn('option_key', $fields)->pluck('option_value', 'option_key');

            if ($meta->count() > 0) {

                $title = $meta['seo_title_' . $page] . ' - ' . get_company_name();

                SEOTools::setTitle($title, false);
                SEOMeta::setDescription($meta['seo_description_' . $page], false);
                SEOTools::opengraph()->addProperty('type', 'product');
                SEOTools::opengraph()->setUrl(url()->current());
                SEOTools::setDescription($meta['seo_description_' . $page], false);
                $keyword = $meta['seo_keywords_' . $page];

                if ($keyword) {
                    SEOMeta::addKeyword(explode(',', $keyword));
                }

                request()->session()->flash('seo_was_set', TRUE);
            }
        }
    }

    public function loadFromRecord($title, $description, $kewords, $propertyType, $thumbnail = null, $is_home = null)
    {
        $title = $title . ' - ' . get_company_name();

        SEOTools::setTitle($title, false);
        SEOMeta::setDescription($description, false);
        SEOTools::opengraph()->addProperty('type', $propertyType);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setDescription($description, false);

        $thumbnail = ($thumbnail) ? $thumbnail : get_company_website_logo();

        SEOTools::opengraph()->addImage($thumbnail);
        SEOTools::twitter()->setImage($thumbnail);

        SEOMeta::addKeyword($kewords);
        if ($thumbnail) {
            if ($is_home) {
                SEOTools::jsonLd()->addImage($thumbnail);
                SEOTools::opengraph()->addProperty('image:secure_url', $thumbnail);
                SEOTools::opengraph()->addProperty('image:width', 1785);
                SEOTools::opengraph()->addProperty('image:height', 607);
            } else {
                SEOTools::jsonLd()->addImage($thumbnail);
            }
        }

        request()->session()->flash('seo_was_set', TRUE);
    }

    public function loadFromWebsiteSection($section, $isHome = false, $propertyType = 'page')
    {
        if (isset($section->meta_title)) {
            $this->loadFromRecord(
                $section->meta_title,
                $section->meta_description,
                $section->key_words,
                $propertyType,
                asset($section->image),
                $isHome
            );
        }
    }
}
