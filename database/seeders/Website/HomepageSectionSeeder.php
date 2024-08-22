<?php

namespace Database\Seeders\Website;

use App\Models\Website\HomePageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    private $path = 'storage/photos/1/homepage/';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->heroSection();
        $this->aboutSection();
        $this->howItWorksSection();
        $this->whyChooseUsSection();
        $this->footerSection();
    }

    private function footerSection()
    {
        HomePageSection::create([
            'locale'          => 'en',
            'name'            => HomePageSection::FOOTER,
            'additional_data' => [
                'footer_text'         => 'You can add your text here',
                'company_information' => 'Firmament morning sixth subdue darkness creeping gathered divide our let god moving. Moving in fourth air night bring upon it beast let you dominion likeness open place day great.',
            ],

        ]);

    }

    private function heroSection()
    {
        HomePageSection::create([
            'locale'         => 'en',
            'name'           => HomePageSection::HERO,
            'title'          => 'Your content writing solution',
            'sub_title'      => 'From personal branding to SEO optimized content',
            'content'        => 'Writing is a tough chore, and that is why you need expert writers who can provide you with help. Hire the Web\'s Best Content Writers With Our Professional Content Writing Services',
            'image_alt_text' => 'Your content writing solution',

            'image' => $this->path . 'hero.png',
            // 'image_position' => 'center',
            // 'appearance'     => [
            //     'bg_color'   => 'linear-gradient(70deg, #5446bc 10%, #7673eb 50%, #1c45ef) 50%',
            //     //'bg_color'   => '#2d97c8',
            //     'text_color' => '#fff',
            // ],

        ]);

    }

    private function aboutSection()
    {
        HomePageSection::create([
            'locale'          => 'en',
            'name'            => HomePageSection::ABOUT_US,
            'title'           => 'Built for Your Business',
            'sub_title'       => 'Whatever industry you’re in, you need expert writers who can engage your audience. We’ll find those writers for you.',
            'additional_data' => [
                [
                    'title'          => 'Agencies',
                    'content'        => 'We partner with agencies to support the content and copywriting needs of their clients.',
                    'image'          => $this->path . 'icon_agencies.svg',
                    'image_alt_text' => 'Agencies',
                ],
                [
                    'title'          => 'Publishers',
                    'content'        => 'We support digital publishers by writing targeted content for their different sites at scale.',
                    'image'          => $this->path . 'icon_publishers.svg',
                    'image_alt_text' => 'Publishers',
                ],
                [
                    'title'          => 'eCommerce',
                    'content'        => 'We write product descriptions, web pages, and category pages with an emphasis on SEO.',
                    'image'          => $this->path . 'icon_ecommerce.svg',
                    'image_alt_text' => 'eCommerce',
                ],
                [
                    'title'          => 'Brands',
                    'content'        => 'We develop your voice and tone and write custom content for brands big and small.',
                    'image'          => $this->path . 'icon_brands.svg',
                    'image_alt_text' => 'Brands',
                ],
            ],
        ]);

    }

    private function howItWorksSection()
    {
        HomePageSection::create([
            'locale'          => 'en',
            'name'            => HomePageSection::HOW_IT_WORKS,
            'title'           => 'How it works',
            'sub_title'       => null,
            'appearance'      => [
                'bg_color'   => '#e9eff3',
                'text_color' => null,
            ],
            'additional_data' => [
                [
                    'title'          => 'Client orders content',
                    'content'        => '<ul>
                    <li>Client fills up and sends the order form.</li>
                    <li>Writer(s) matching expertise and level are picked by the content manager.</li>
                    <li>Writer(s) are assigned and tracked for reassigning if needed.</li>
                    </ul>',
                    'image'          => $this->path . 'how_it_works_1.png',
                    'image_alt_text' => 'Client orders content',
                ],
                [
                    'title'          => 'Content Creation',
                    'content'        => '<ul>
                    <li>Writer writes original content to match the project brief.</li>
                    <li>Writer corresponds with the client for questions.</li>
                    <li>Writer sends in a completed original draft.</li>
                    </ul>',
                    'image'          => $this->path . 'how_it_works_2.png',
                    'image_alt_text' => 'Content Creation',
                ],
                [
                    'title'          => 'Editing Review',
                    'content'        => '<ul>
                    <li>A Human Quality Assistant reviews the content.</li>
                    <li>Content is checked in paid tools (Copyscape, Grammarly).</li>
                    <li>Content is sent back to the writer if it does not meet expectations; otherwise, it is sent to the client.</li>
                    </ul>',
                    'image'          => $this->path . 'how_it_works_3.svg',
                    'image_alt_text' => 'Editing Review',
                ],
                [
                    'title'          => 'Client Happiness',
                    'content'        => '<ul>
                    <li>Client reviews</li>
                    <li>Client approves,  asks for revisions, or sends feedback.</li>
                    <li>Client receives up to 2 free revisions.</li>
                    </ul>',
                    'image'          => $this->path . 'how_it_works_4.svg',
                    'image_alt_text' => 'Client Happiness',
                ],
            ],
        ]);

    }

    private function whyChooseUsSection()
    {
        HomePageSection::create([
            'locale'          => 'en',
            'name'            => HomePageSection::WHY_CHOOSE_US,
            'title'           => 'Your one-stop shop for content development.',
            'sub_title'       => 'We’re a unique, creative, human writing team that goes above and beyond.',
            'image'           => $this->path . 'why_choose_us_banner.svg',
            'image_position'  => 'center',
            'image_alt_text'  => 'Your one-stop shop for content development',
            'appearance'      => [
                'bg_color'   => '#e9eff3',
                'text_color' => null,
            ],
            'additional_data' => [
                [
                    'title'          => 'Expert Writers',
                    'content'        => 'Amazing articles from a hand-picked group of authors in the sector. Our own team is assembled and hand-selected.',
                    'image'          => $this->path . 'why_choose_us_1.svg',
                    'image_alt_text' => 'Expert Writers',
                ],
                [
                    'title'          => 'Quick Turnarounds',
                    'content'        => 'On time delivery of content produced according to your timetable. Never again should you play around with uncommitted partners.',
                    'image'          => $this->path . 'why_choose_us_2.svg',
                    'image_alt_text' => 'Quick Turnarounds',
                ],
                [
                    'title'          => 'Strategic Content',
                    'content'        => 'We have on-staff Content Strategists and specialized, highly-trained SEO writers.',
                    'image'          => $this->path . 'why_choose_us_3.svg',
                    'image_alt_text' => 'Strategic Content',
                ],
                [
                    'title'          => 'Great Content at Scale',
                    'content'        => 'Every sort of content you require, at scale. No extra charges. 0 monthly fees. Order what you require',
                    'image'          => $this->path . 'why_choose_us_4.svg',
                    'image_alt_text' => 'Great Content at Scale',
                ],
            ],
        ]);

    }
}
