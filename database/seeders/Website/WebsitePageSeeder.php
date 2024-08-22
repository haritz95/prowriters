<?php

namespace Database\Seeders\Website;

use App\Models\Website\WebsitePage;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\URL;

class WebsitePageSeeder extends Seeder
{
    private $path      = 'storage/photos/1/pages/';
    private $locale    = 'en';
    private $page_type = 'system';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->home();
        $this->blog();
        $this->faq();
        $this->contact();
        $this->authorApplication();
        $this->forgotPassword();
        $this->registration();
        $this->login();

    }

    private function home()
    {
        $url         = URL::to('home');
        $title       = 'Home - ProWriters';
        $description = 'Content writers for hire in all industries. We personally manage thousands of freelance writers that create blogs, articles, product descriptions, eCommerce content, white papers, and more.';
        WebsitePage::create([
            'locale'    => $this->locale,
            'type'      => $this->page_type,
            'name'      => WebsitePage::HOME,
            'title'     => 'Home',
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

    private function blog()
    {
        $url   = URL::to('blog');
        $title = 'Blog - ProWriters';
        WebsitePage::create([
            'locale'    => $this->locale,
            'type'      => $this->page_type,
            'name'      => WebsitePage::BLOG,
            'slug'      => 'blog',
            'title'     => 'Blog',
            'sub_title' => 'Latest from Our Blog',
            'meta_tags' => '<meta name="title" content="' . $title . '">
            <meta name="description" content="Latest from Our Blog">
            <meta name="keywords" content="Blog, Article, Content Writing, Author Platform, Writing">
            <meta property="og:title" content="' . $title . '" />
            <meta property="og:type" content="page" />
            <meta property="og:description" content="Latest from Our Blog" />
            <meta property="og:url" content="' . $url . '" />
            <meta name="twitter:title" content="' . $title . '" />
            <meta name="twitter:description" content="Latest from Our Blog" />',

        ]);
    }

    private function faq()
    {
        $url   = URL::to('faq');
        $title = 'FAQ  - ProWriters';
        WebsitePage::create([
            'locale'    => $this->locale,
            'type'      => $this->page_type,
            'name'      => WebsitePage::FAQ,
            'slug'      => 'faq',
            'title'     => 'FAQ',
            'sub_title' => 'Answers to all frequently asked questions may be found here.',
            'meta_tags' => '<meta name="title" content="' . $title . '">
            <meta name="description" content="Find all the answers to frequently asked questions">
            <meta name="keywords" content="frequently, asked, questions, Content Writing, Author Platform, Writing">
            <meta property="og:title" content="' . $title . '" />
            <meta property="og:type" content="page" />
            <meta property="og:description" content="Find all the answers to frequently asked questions" />
            <meta property="og:url" content="' . $url . '" />
            <meta name="twitter:title" content="' . $title . '" />
            <meta name="twitter:description" content="Find all the answers to frequently asked questions" />',

        ]);
    }
    private function contact()
    {
        $url   = URL::to('contact');
        $title = 'Contact us  - ProWriters';

        WebsitePage::create([
            'locale'          => $this->locale,
            'type'            => $this->page_type,
            'name'            => WebsitePage::CONTACT,
            'slug'            => 'contact',
            'title'           => 'Contact us',
            'sub_title'       => 'Have a question or request? We are all ears! Share your order instructions to let us kickstart your paper now.',
            'content'         => null,
            'meta_tags'       => '<meta name="title" content="' . $title . '">
            <meta name="description" content="Find all the answers to frequently asked questions">
            <meta name="keywords" content="frequently, asked, questions, Content Writing, Author Platform, Writing">
            <meta property="og:title" content="' . $title . '" />
            <meta property="og:type" content="page" />
            <meta property="og:description" content="Find all the answers to frequently asked questions" />
            <meta property="og:url" content="' . $url . '" />
            <meta name="twitter:title" content="' . $title . '" />
            <meta name="twitter:description" content="Find all the answers to frequently asked questions" />',
            'additional_data' => [
                'form_title'              => 'Get in Touch',
                'form_sub_title'          => '',
                'form_submission_message' => 'Thank you for your message',
            ],

        ]);
    }

    private function authorApplication()
    {
        WebsitePage::create([
            'locale'          => $this->locale,
            'type'            => $this->page_type,
            'name'            => WebsitePage::AUTHOR_APPLICATION,
            'slug'            => 'author-register',
            'title'           => 'Become a writer - Join us',
            'sub_title'       => null,
            'content'         => $this->getContentForApplicationPage(),
            'additional_data' => [
                'form_title'              => 'Join our team',
                'form_sub_title'          => 'Please thoroughly review our list of qualifications before applying.',
                'form_submission_message' => 'Thank you for your application. We will review it and let you know',
                'meta_title'              => 'Become a writer',
                'meta_description'        => 'Become a writer with us',
                'meta_keywords'           => 'Writer, Job, Author, Content Writing',
                'meta_image'              => null,
            ],

        ]);
    }

    private function login()
    {
        WebsitePage::create([
            'locale'          => $this->locale,
            'type'            => $this->page_type,
            'name'            => WebsitePage::LOGIN,
            'slug'            => 'login',
            'title'           => 'Login to your account',
            'sub_title'       => null,
            'additional_data' => [
                // Welcome section
                'welcome_title'            => 'Hello, Welcome!',
                'welcome_sub_title'        => 'We are glad you are here',
                'welcome_image'            => $this->path . 'login.svg',
                'welcome_image_alt_text'   => 'Welcome to our site',
                // SEO
                'meta_title'               => 'Login',
                'meta_description'         => 'Sign in to your Prowriters account and get the most out of our platform.',
                'meta_keywords'            => 'Login, Sign in, Prowriters',
                'meta_image'               => $this->path . 'login.svg',
                'welcome_background_color' => '#fdfdfd',
            ],

        ]);

    }

    private function registration()
    {
        WebsitePage::create([
            'locale'          => $this->locale,
            'type'            => $this->page_type,
            'name'            => WebsitePage::REGISTRATION,
            'slug'            => 'register',
            'title'           => 'Create an account',
            'sub_title'       => 'Tell us a bit about yourself.',
            'additional_data' => [
                // Welcome section
                'welcome_title'            => 'Hello, Welcome!',
                'welcome_sub_title'        => 'We are glad you are here',
                'welcome_image'            => $this->path . 'login.svg',
                'welcome_image_alt_text'   => 'Create an account',
                // SEO
                'meta_title'               => 'Register',
                'meta_description'         => 'Sign up today to start your content marketing journey',
                'meta_keywords'            => 'Register, Sign up, Prowriters',
                'meta_image'               => $this->path . 'login.svg',
                'welcome_background_color' => '#fdfdfd',
            ],

        ]);
    }

    private function forgotPassword()
    {
        WebsitePage::create([
            'locale'          => $this->locale,
            'type'            => $this->page_type,
            'name'            => WebsitePage::FORGOT_PASSWORD,
            'title'           => 'Reset Your Password',
            'sub_title'       => null,
            'additional_data' => [
                // Welcome section
                'welcome_title'            => 'Hello, Welcome!',
                'welcome_sub_title'        => 'We are glad you are here',
                'welcome_image'            => $this->path . 'login.svg',
                'welcome_image_alt_text'   => 'Reset Your Password',
                // SEO
                'meta_title'               => 'Reset Your Password',
                'meta_description'         => 'Trouble logging in? Enter your email and will send you a link to get back into your account on our platform.',
                'meta_keywords'            => 'Forgot, Password, Prowriters',
                'meta_image'               => $this->path . 'login.svg',
                'welcome_background_color' => '#fdfdfd',
            ],

        ]);
    }

    private function getContentForApplicationPage()
    {
        return '<h4><strong>About </strong></h4><p><span style="color: rgb(58, 58, 58); background-color: rgb(255, 255, 255);">We ensure our customers receive work of the highest quality by supporting our essay writers throughout every stage of the process, from assignment to delivery. We choose to work exclusively with individuals committed to clarity and transparency, who are passionate about helping others learn, grow and better understand the world around them.</span></p><p><br></p><h4><strong>Qualified candidates must have:</strong></h4><ul><li>a flawless grasp of MLA, APA and CPS formatting.</li><li>a bachelor\'s degree, or be in active pursuit of one.</li><li>a 3.3 GPA or better.</li><li>a clear understanding of how to conduct online research.</li><li>the ability to cheerfully accept constructive criticism.</li><li>a consistent commitment to being responsive and reliable.</li><li>Microsoft Office.</li></ul><h4><br></h4><h4><strong>Perks of the job:</strong></h4><ul><li>Choose your own assignments and work when you want, where you want.</li><li>Enjoy working as part of an elite team of skilled and supportive colleagues.</li><li>Write what you know and learn what you don’t on topics ranging from marketing and economics to philosophy and politics.</li><li>We offer among the highest rates in the industry, averaging $25 an hour.</li><li>Payment is delivered conveniently through direct deposit or PayPal.</li></ul><p><br></p><p><br></p>';
    }
}
