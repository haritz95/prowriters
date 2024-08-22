<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Website\Blog\Post;
use App\Models\Website\WebsiteMenu;
use Illuminate\Http\Request;

class SitemapController extends Controller
{

    public function index(Request $request)
    {
        return response()->view('website.sitemap.index')->header('Content-Type', 'text/xml');
    }

    public function topMenu(Request $request)
    {
        $menu = [
            ['url' => route('homepage')],
            ['url' => route('faq')],
            ['url' => route('contact')],
            ['url' => route('login')],
        ];
        if (!settings('hide_blog')) {
            array_push($menu, [
                'url' => route('blog'),
            ]);
        }
        if (!hide_author_application_link()) {
            array_push($menu, [
                'url' => route('public.author.application.create'),
            ]);
        }

        $top_menus = WebsiteMenu::getMenu(WebsiteMenu::POSITION_TOP);

        foreach ($top_menus as $key => $parentMenu) {
            if ($parentMenu->children->count() > 0) {
                foreach ($parentMenu->children as $childMenu) {
                    array_push($menu, [
                        'url' => route('page', $childMenu->page->slug),
                    ]);
                }
            } else {
                array_push($menu, [
                    'url' => route('page', $parentMenu->page->slug),
                ]);
            }
        }

        return response()->view('website.sitemap.menu', compact('menu'))->header('Content-Type', 'text/xml');
    }

    public function footerMenu(Request $request)
    {
        $footer_menus = WebsiteMenu::getMenu(WebsiteMenu::POSITION_FOOTER);
        $menu         = [];

        foreach ($footer_menus as $key => $parentMenu) {
            if ($parentMenu->children->count() > 0) {
                foreach ($parentMenu->children as $childMenu) {
                    array_push($menu, [
                        'url' => route('page', $childMenu->page->slug),
                    ]);
                }
            } else {
                array_push($menu, [
                    'url' => route('page', $parentMenu->page->slug),
                ]);
            }
        }

        return response()->view('website.sitemap.menu', compact('menu'))->header('Content-Type', 'text/xml');
    }

    public function blog(Request $request)
    {
        $posts = Post::select(['slug', 'updated_at'])->where('locale', app()->getLocale())->orderBy('id', 'DESC')->get();
        return response()->view('website.sitemap.blog', compact('posts'))->header('Content-Type', 'text/xml');
    }

}
