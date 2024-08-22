<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Traits\Profile\AccountProfileTrait;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use AccountProfileTrait;

    private function getFileLocation($file)
    {
        return 'Admin/Account/' . $file;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function location(Request $request)
    {
        return inertia($this->getFileLocation('Location'), [
            'data' => [
                'title'     => __('Location'),
                'dropdowns' => [
                    'countries' => Country::all(),
                    'timezones' => User::getTimeZones(),
                ],
            ],
            'user' => auth()->user(),
        ]);
    }

    public function updateLocation(Request $request)
    {
        auth()->user()->update($request->validate([
            'country_code' => 'required',
            'timezone'     => 'required',
        ]));

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }
}
