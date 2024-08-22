<?php

namespace Database\Seeders\Dummy;

use App\Models\Website\Blog\Post;
use App\Models\Website\Blog\PostCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    private $path   = 'storage/photos/1/blog/';
    private $locale = 'en';
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

        $categories = $this->getCategories();

        for ($i = 1; $i <= 15; $i++) {
            foreach ($categories as $category) {
                $post = $this->createPost();
                $this->attachCategoryPost($category->id, $post->id);
            }
        }

    }

    private function attachCategoryPost($category_id, $post_id)
    {
        DB::table('post_category_tag')->insert([
            'post_id'          => $post_id,
            'post_category_id' => $category_id,
        ]);
    }

    private function getCategories()
    {
        $categories = [
            'Automotive',
            'Blogging',
            'Content Marketing',
            'ContentWriters NEWS',
            'Digital Marketing',
            'eCommerce',
            'Education & Day Care',
            'Fashion, Music & Entertainment',
            'Finance, Business & Real Estate',
            'Food & Beverage',
            'Freelance Writers',
            'Government & Non-Profits',
            'Grammar & Writing',
            'Law & Legal',
            'Medical & Healthcare',
            'Press Releases',
            'Productivity',
            'SEO',
            'Social Media',
            'Sports, Gaming & Fitness',
            'Tech & Internet',
            'Travel & Lifestyle',
            'White Papers',
        ];

        foreach ($categories as $name) {
            $title       = $name . ' - ProWriters';
            $description = $this->faker->sentence();
            $url         = URL::to('en/blog/' . $name);

            $subject = PostCategory::create([
                'locale'    => $this->locale,
                'slug'      => Str::slug(strtolower($name), '-'),
                'name'      => $name,
                'meta_tags' => '<meta name="title" content="' . $title . '">
            <meta name="description" content="' . $description . '">
            <meta name="keywords" content="Blog, Article, Content Writing, Author Platform, Writing">
            <meta property="og:title" content="' . $title . '" />
            <meta property="og:type" content="website" />
            <meta property="og:description" content="' . $description . '" />
            <meta property="og:url" content="' . $url . '" />
            <meta name="twitter:title" content="' . $title . '" />
            <meta name="twitter:description" content="' . $description . '" />',
            ]);
        }

        return PostCategory::all();
    }

    private function getTitle()
    {
        $nbWords  = $this->faker->randomElement(range(7, 15));
        $sentence = $this->faker->sentence($nbWords);
        return substr($sentence, 0, strlen($sentence) - 1);
    }

    private function generateContent()
    {
        $break = '<br> <br>';
        $text  = $this->faker->paragraph($this->faker->numberBetween(4, 6), true);

        $size_of_content = $this->faker->numberBetween(15, 20);
        for ($i = 0; $i < $size_of_content; $i++) {
            $text .= $break;
            $text .= $this->faker->paragraph($this->faker->numberBetween(4, 6));
            $text .= $break;
            $text .= $this->faker->text;
        }
        return $text;
    }

    private function getMetaTags($title)
    {
        $description = $this->faker->sentence(10);
        $slug        = Str::slug(strtolower($title), '-');
        $keywords    = 'hello';
        $url         = URL::to('en/blog/' . $slug);

        return '<meta name="title" content="' . $title . '">
        <meta name="description" content="' . $description . '">
        <meta name="keywords" content="' . $keywords . '">
        <meta property="og:title" content="' . $title . '" />
        <meta property="og:type" content="website" />
        <meta property="og:description" content="' . $description . '" />
        <meta property="og:url" content="' . $url . '" />
        <meta name="twitter:title" content="' . $title . '" />
        <meta name="twitter:description" content="' . $description . '" />';
    }

    private function createPost()
    {
        $title = $this->getTitle();
        $image = $this->path . $this->faker->randomElement(range(1, 21)) . '.jpg';

        return Post::create([
            'locale'                    => $this->locale,
            'slug'                      => Str::slug(strtolower($title), '-'),
            'title'                     => $title,
            'author_name'               => $this->faker->name($this->faker->randomElement(['male', 'female'])),
            'thumbnail_image'           => $image,
            'thumbnail_image_alt_title' => 'Thumb alt title',
            'cover_image'               => $image,
            'cover_image_alt_title'     => 'Cover image alt title',
            'excerpt'                   => $this->faker->paragraph($this->faker->randomElement([2, 3])),
            'content'                   => $this->generateContent(),
            'meta_tags'                 => $this->getMetaTags($title),
            'published'                 => true,
            'disable_auto_slug_gen'     => null,
            'user_id'                   => 1,
        ]);
    }
}
