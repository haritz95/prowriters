<?php

namespace App\Services;

class CSSGeneratorService
{
    public function forApplication()
    {
        return $this->array_to_css([
            '.navbar' => [
                'background' => settings('application_top_nav_bg_color') . '!important',
            ],

            'a'       => [
                'color' => settings('application_link_color') . '!important',
            ],
            'a:hover' => [
                'color' => settings('application_link_hover_color') . '!important',
            ],
        ]);
    }

    public function forWebsite()
    {
        return $this->array_to_css([
            '.link-order > .nav-link'       => [
                'background' => settings('website_order_now_button_bg_color'),
                'color'      => settings('website_order_now_button_text_color') . '!important',
            ],
            '.link-order > .nav-link:hover' => [
                'background' => settings('website_order_now_button_hover_bg_color'),
            ],
            '.navbar'                       => [
                'background' => settings('website_top_nav_bg_color'),
                // 'color' => settings('website_footer_text_color'),
            ],
            '.navbar .nav-link'             => [
                'color' => settings('website_top_nav_link_color'),
            ],
            '.navbar .nav-link:hover'       => [
                'color' => settings('website_top_nav_link_hover_color'),
            ],
            '.site-footer'                  => [
                'background' => settings('website_footer_bg_color'),
                'color'      => settings('website_footer_text_color'),
            ],
            '.site-footer a'                => [
                'color' => settings('website_footer_link_color'),
            ],
            '.site-footer a:hover'          => [
                'color' => settings('website_footer_link_hover_color'),
            ],

            '#hero' => [
                'background' => settings('website_hero_bg_color'),
                'color'      => settings('website_hero_text_color'),
            ],

            'a'       => [
                'color' => settings('website_link_color'),
            ],
            'a:hover' => [
                'color' => settings('website_link_hover_color'),
            ],
        ]);
    }

/**
 * Recursive function that generates from a a multidimensional array of CSS rules, a valid CSS string.
 *
 * @param array $rules
 *   An array of CSS rules in the form of:
 *   array('selector'=>array('property' => 'value')). Also supports selector
 *   nesting, e.g.,
 *   array('selector' => array('selector'=>array('property' => 'value'))).
 *
 * @return string A CSS string of rules. This is not wrapped in <style> tags.
 * @source http://matthewgrasmick.com/article/convert-nested-php-array-css-string
 */
    private function array_to_css($rules, $indent = 0)
    {
        $css    = '';
        $prefix = str_repeat('  ', $indent);

        foreach ($rules as $key => $value) {
            if (is_array($value)) {
                $selector   = $key;
                $properties = $value;

                $css .= $prefix . "$selector {\n";
                $css .= $prefix . $this->array_to_css($properties, $indent + 1);
                $css .= $prefix . "}\n";
            } else {
                if($value && $value != '!important')
                {
                    $property = $key;
                    $css .= $prefix . "$property: $value;\n";
                }
                
            }
        }

        return $css;
    }

/*
$stylesheet = array(
"body" => array(
"margin" => "0",
"font-size" => "1rem",
"font-weight" => 400,
"line-height" => 1.5,
"color" => "#212529",
"text-align" => "left",
"background-color" => "#fff"
),
".form-control" => array(
"display" => "block",
"width" => "100%!important",
"font-size" => "1em",
"background-color" => "#fff",
"border-radius" => ".25rem"
)
);

echo array_to_css($stylesheet);
 */
}
