<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\CustomerQuery;
use App\Models\Website\Blog\Post;
use App\Models\Website\Blog\PostCategory;
use App\Models\Website\Faq\FaqCategory;
use App\Models\Website\HomePageSection;
use App\Models\Website\WebsitePage;
use App\Models\Website\WebsiteTestimonial;
use App\Rules\ValidRecaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\RequiredIf;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('website');
    }

    public function index()
    {
        if (env('ENABLE_APP_SETUP_CONFIG') != true) {
            return redirect()->route('installer_page');
        }

        $blog_posts = null;

        if (!settings('hide_blog')) {
            $blog_posts = Post::with('categories')
                ->where('locale', app()->getLocale())
                ->orderBy('created_at', 'DESC')
                ->where('published', true)->limit(3)->get();
        }

        return view('website.index', [
            'page'         => WebsitePage::get(WebsitePage::HOME),
            'hero'         => HomePageSection::get(HomePageSection::HERO),
            'about'        => HomePageSection::get(HomePageSection::ABOUT_US),
            'howItWorks'   => HomePageSection::get(HomePageSection::HOW_IT_WORKS),
            'whyChooseUs'  => HomePageSection::get(HomePageSection::WHY_CHOOSE_US),
            'testimonials' => WebsiteTestimonial::get(),
            'blog_posts'   => $blog_posts,
        ]);
    }

    public function faq(Request $request)
    {
        $page = WebsitePage::get(WebsitePage::FAQ);

        $faqCategories = Cache::rememberForever(FaqCategory::getCacheName(app()->getLocale()), function () {
            return FaqCategory::where('locale', app()->getLocale())->with('questions')->get();
        });

        // $breadcrumbs = $this->getBreadcrumbs([
        //     ['label' => $page->title, 'url' => url()->full()],
        // ]);

        return view('website.faq', compact('page', 'faqCategories'));
    }

    private function getBreadcrumbs(array $items)
    {
        $breadcrumbs = [['label' => __('Home'), 'url' => URL::to('/')]];

        foreach ($items as $row) {
            array_push($breadcrumbs, $row);
        }

        return $breadcrumbs;
    }

    public function page(Request $request)
    {
        $slug = (is_single_language()) ? $request->segment(1) : $request->segment(2);

        if (empty($slug)) {
            abort(404);
        }
        $page = WebsitePage::getCustomPage($slug);

        if (!$page) {
            abort(404);
        }

        $breadcrumbs = $this->getBreadcrumbs([['label' => $page->title, 'url' => url()->full()]]);
        return view('website.page', compact('page', 'breadcrumbs'));

    }

    public function contact()
    {
        $page = WebsitePage::get(WebsitePage::CONTACT);

        // $breadcrumbs = $this->getBreadcrumbs([
        //     ['label' => $page->title, 'url' => url()->full()],
        // ]);

        return view('website.contact', compact('page'));
    }

    public function handleContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message'   => 'required',
            'name'      => 'required',
            'email'     => 'required|email',
            'subject'   => 'required',
            'recaptcha' => [
                'bail',
                new RequiredIf(settings("recaptcha_enable")),
                new ValidRecaptcha(),
            ],
        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Mail::to(settings('company_email'))->send(new CustomerQuery($request->all()));

        $request->session()->flash('alert-class', 'alert-success');
        $request->session()->flash('message', __('Thank you for your query. We will get back to you as soon as possible'));

        return redirect()->route('contact');
    }

    public function blog()
    {
        $page = WebsitePage::get(WebsitePage::BLOG);

        return view('website.blog', [
            'post_categories'  => PostCategory::dropdown(),
            'page'             => $page,
            'posts'            => Post::get(),
            'default_category' => null,
            'title'            => $page->title,
            'sub_title'        => $page->sub_title,
            'meta_tags'        => $page->meta_tags,
            // 'breadcrumbs'     => $this->getBreadcrumbs([
            //     ['label' => $page->title, 'url' => url()->full()],
            // ]),
        ]);
    }

    public function blogPostsByCategory(Request $request)
    {
        $slug = (is_single_language()) ? $request->segment(2) : $request->segment(3);

        $category = PostCategory::getBySlug($slug);

        if ($category) {

            return view('website.blog', [
                'post_categories'  => PostCategory::dropdown(),
                'posts'            => $category->posts()->orderBy('id', 'DESC')->where('published', true)->paginate(12),
                'default_category' => $slug,
                'title'            => $category->name,
                'sub_title'        => null,
                'meta_tags'        => $category->meta_tags,
                // 'breadcrumbs'     => $this->getBreadcrumbs([
                //     ['label' => $page->title, 'url' => url()->full()],
                // ]),
            ]);
        } else {

            $post = Post::getBySlug($slug);

            $categories   = $post->categories->modelKeys();
            $relatedPosts = Post::whereHas('categories', function ($q) use ($categories, $post) {
                $q->whereIn('post_category_id', $categories)
                    ->where('post_id', '<>', $post->id);
            })->limit(3)->get();

            // $breadcrumbs = $this->getBreadcrumbs([
            //     ['label' => __('Blog'), 'url' => route('blog')],
            //     ['label' => $post->title, 'url' => URL::to($post->slug)],
            // ]);

            return view('website.blog_post', [
                'post'         => $post,
                'relatedPosts' => $relatedPosts,

                'title'       => $post->title,
                'sub_title'   => null,
                'meta_tags'   => $post->meta_tags,
                'breadcrumbs' => null,
            ]);
        }
    }

    public function switchLanguage(Request $request)
    {
        if (in_array($request->switchTo, allowed_languages())) {
            return redirect()->route('homepage', ['lc' => $request->switchTo]);
        } else {
            return redirect()->route('homepage');
        }

        // if (Setting::switchLanguage($request, $request->switchTo)) {
        //     return redirect()->route('homepage', ['lc' => $request->switchTo]);

        // } else {
        //     return redirect()->route('homepage');
        // }

    }
}
