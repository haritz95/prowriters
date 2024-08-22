<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Profile\AccountProfileTrait;

class AccountController extends Controller
{
    use AccountProfileTrait;

    private function getFileLocation($file)
    {
        return 'Customer/Account/' . $file;
    }

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
