<?php

namespace App\Http\View\Composers;

use App\Models\Website\HomePageSection;
use App\Models\Website\WebsiteMenu;
use Illuminate\View\View;

class MenuComposer
{

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {        
        $view->with('top_menu', WebsiteMenu::getMenu(WebsiteMenu::POSITION_TOP));
        $view->with('footer_menu', WebsiteMenu::getMenu(WebsiteMenu::POSITION_FOOTER));
        $view->with('footer', HomePageSection::get(HomePageSection::FOOTER));

    }

}
