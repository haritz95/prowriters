<?php

namespace Database\Seeders\Website;

use App\Models\Website\WebsiteMenu;
use App\Models\Website\WebsitePage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class WebsiteMenuSeeder extends Seeder
{
    private $path      = 'storage/photos/1/pages/';
    private $locale    = 'en';
    private $page_type = 'system';
    private $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // HomePageSection::query()->delete();

        $this->top_menu();
        $this->footerMenu();

    }

    private function footerMenu()
    {
        $menus = [
            [
                'name'     => 'Contents',
                'children' => [
                    'Blog Posts',
                    'Authority Content',
                    'Expert Web Pages',
                    'Content Strategy',
                    'Press Release Writing',
                    'Marketing Copy',
                    'Ebook & Guides',
                    'Email Copy',
                    'Product Descriptions',
                    'Social Media Posts',
                ],
            ],
            [
                'name'     => 'Industries',
                'children' => [
                    'Automotive',
                    'Business',
                    'Education & Day Care',
                    'Finance',
                    'Legal',
                    'Medical & Healthcare',
                    'Real Estate',
                    'Sports, Gaming & Fitness',
                    'Tech & Internet',
                    'Travel & Lifestyle',
                ],
            ],
            [
                'name'     => 'Resources',
                'children' => [
                    'Blog',
                    'FAQ',
                    'How it works',
                ],
            ],
            [
                'name'     => 'Legal',
                'children' => [
                    'Terms of Service',
                    'Privacy Policy',
                    'Writer Service Agreement',
                ],
            ],
        ];

        $i = 0;
        foreach ($menus as $key => $parentMenu) {
            $parent_id = WebsiteMenu::create([
                'parent_id'       => null,
                'locale'          => 'en',
                'position'        => WebsiteMenu::POSITION_FOOTER,
                'name'            => $parentMenu['name'],
                'sequence_number' => $i + 1,
                'website_page_id' => null,
            ])->id;

            $i++;
            foreach ($parentMenu['children'] as $childMenu) {
                $data[$i] = [
                    'parent_id'       => $parent_id,
                    'locale'          => 'en',
                    'position'        => WebsiteMenu::POSITION_FOOTER,
                    'name'            => $childMenu,
                    'sequence_number' => $i + 1,
                    'website_page_id' => $this->generatePage($childMenu),
                ];
                $i++;
            }
        }

        WebsiteMenu::insert($data);
    }

    private function top_menu()
    {

        WebsiteMenu::insert([
            [
                'parent_id'       => null,
                'locale'          => 'en',
                'position'        => WebsiteMenu::POSITION_TOP,
                'name'            => 'Services',
                'sequence_number' => 1,
                'website_page_id' => null,
            ],
            [
                'parent_id'       => 1,
                'locale'          => 'en',
                'position'        => WebsiteMenu::POSITION_TOP,
                'name'            => 'Content Writing',
                'sequence_number' => 2,
                'website_page_id' => $this->generatePage('Content Writing'),
            ],
            [
                'parent_id'       => 1,
                'locale'          => 'en',
                'position'        => WebsiteMenu::POSITION_TOP,
                'name'            => 'Academic Writing',
                'sequence_number' => 3,
                'website_page_id' => $this->generatePage('Content Writing'),
            ],
            [
                'parent_id'       => 1,
                'locale'          => 'en',
                'position'        => WebsiteMenu::POSITION_TOP,
                'name'            => 'Career Writing',
                'sequence_number' => 4,
                'website_page_id' => $this->generatePage('Career Writing'),
            ],
        ]);
    }

    private function generatePage($page_title)
    {
        if ($page_title == 'Blog') {
            return WebsitePage::where('name', 'blog')->get()->first()->id;
        } else if ($page_title == 'FAQ') {
            return WebsitePage::where('name', 'faq')->get()->first()->id;
        }

        $slug        = Str::slug($page_title, '-');
        $url         = URL::to($slug);
        $title       = $page_title . ' - ProWriters';
        $keywords    = str_replace(" ", ',', $this->faker->realText($this->faker->numberBetween(10, 15)));
        $description = $this->faker->realText($this->faker->numberBetween(10, 20));

        return WebsitePage::create([
            'locale'         => $this->locale,
            'slug'           => $slug,
            'type'           => WebsitePage::TYPE_CUSTOM,
            'name'           => $page_title,
            'title'          => $page_title,
            'sub_title'      => $this->faker->sentence,
            'image'          => $this->path . 'page-header.svg',
            'image_position' => 'left',
            'image_alt_text' => $page_title,
            'content'        => $this->faker->paragraphs($this->faker->numberBetween(4, 6), true) . '<br> <br>' . $this->faker->text,
            'appearance'     => [
                "bg_color"              => "#dff0f5ff",
                "text_color"            => null,
                "title_alignment"       => "left",
                "image_alignment"       => "center",
                "header_minimum_height" => "300",
            ],
            'meta_tags'      => '<meta name="title" content="' . $title . '">
            <meta name="description" content="' . $description . '">
            <meta name="keywords" content="' . $keywords . '">
            <meta property="og:title" content="' . $title . '" />
            <meta property="og:type" content="page" />
            <meta property="og:description" content="' . $description . '" />
            <meta property="og:url" content="' . $url . '" />
            <meta name="twitter:title" content="' . $title . '" />
            <meta name="twitter:description" content="' . $description . '" />',

        ])->id;
    }

}
