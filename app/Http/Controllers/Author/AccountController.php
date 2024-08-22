<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Author\AuthorProfile;
use App\Models\Author\EducationLevel;
use App\Models\Business\Service;
use App\Models\Business\Subject;
use App\Models\Country;
use App\Models\User;
use App\Traits\Profile\AccountProfileTrait;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use AccountProfileTrait;

    private function getFileLocation($file)
    {
        return 'Author/Account/' . $file;
    }

    public function location(Request $request)
    {
        $author          = auth()->user();
        $author->profile = AuthorProfile::where('user_id', auth()->user()->id)->get()->first();

        return inertia('Author/Account/Location', [
            'data'   => [
                'title'     => __('Location'),
                'urls'      => [
                    'submit_form' => route('author.account.location.update'),
                ],
                'dropdowns' => [
                    'countries' => Country::all(),
                    'timezones' => User::getTimeZones(),
                ],
            ],
            'author' => $author,
        ]);
    }

    public function updateLocation(Request $request)
    {
        $data = $request->validate([
            'address'      => 'required|string|max:400',
            'state'        => 'required|string|max:192',
            'city'         => 'required|string|max:192',
            'country_code' => 'required',
            'timezone'     => 'required',
        ]);

        AuthorProfile::where('user_id', auth()->user()->id)->update([
            'address' => $data['address'],
            'state'   => $data['state'],
            'city'    => $data['city'],
        ]);

        auth()->user()->update([
            'country_code' => $data['country_code'],
            'timezone'     => $data['timezone'],
        ]);

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    public function skill(Request $request)
    {
        $author          = auth()->user();
        $author->profile = AuthorProfile::where('user_id', auth()->user()->id)->get()->first();

        return inertia('Author/Account/Skill', [
            'data'   => [
                'title'     => __('Skill & Experience'),
                'urls'      => [
                    'submit_form' => route('author.account.skill.update'),
                ],
                'dropdowns' => [
                    'education_levels' => EducationLevel::all(),
                    'subjects'         => Subject::all(),
                ],
            ],
            'author' => $author,
        ]);
    }

    public function updateSkill(Request $request)
    {
        $data = $request->validate([
            'education_level_id'   => 'required',
            'years_of_experience'  => 'required|integer',
            'subject_id_1'         => 'required',
            'subject_id_2'         => 'required',
            'subject_id_3'         => 'required',
            'blog_url'             => 'nullable',
            'online_portfolio_url' => 'nullable',
            'linked_in_url'        => 'nullable',

        ], [], [
            'education_level_id'   => __('Education Level'),
            'years_of_experience'  => __('Years of Experience'),
            'subject_id_1'         => __('Area of Expertise'),
            'subject_id_2'         => __('Area of Expertise'),
            'subject_id_3'         => __('Area of Expertise'),
            'blog_url'             => __('Blog URL'),
            'online_portfolio_url' => __('Online Portfolio URL'),
            'linked_in_url'        => __('LinkedIn URL'),
        ]);

        AuthorProfile::where('user_id', auth()->user()->id)->update([
            'education_level_id'   => $data['education_level_id'],
            'years_of_experience'  => $data['years_of_experience'],
            'subject_id_1'         => $data['subject_id_1'],
            'subject_id_2'         => $data['subject_id_2'],
            'subject_id_3'         => $data['subject_id_3'],
            'blog_url'             => $data['blog_url'],
            'online_portfolio_url' => $data['online_portfolio_url'],
            'linked_in_url'        => $data['linked_in_url'],
        ]);

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    public function paymentSettings(Request $request)
    {
        $author          = auth()->user();
        $author->profile = AuthorProfile::select(['payment_method', 'payment_method_details'])
            ->where('user_id', auth()->user()->id)->get()->first();

        return inertia('Author/Account/PaymentSettings', [
            'data'   => [
                'title' => __('Payment Settings'),
                'urls'  => [
                    'submit_form' => route('author.account.payment.settings.update'),
                ],
            ],
            'author' => $author,
        ]);
    }

    public function updatePaymentSettings(Request $request)
    {
        AuthorProfile::where('user_id', auth()->user()->id)->update($request->validate([
            'payment_method'         => 'required|string|max:100',
            'payment_method_details' => 'required|string|max:100',

        ], [], [
            'payment_method'         => __('Payment Method'),
            'payment_method_details' => __('Payment Method Details'),
        ]));

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    public function bio(Request $request)
    {
        $author          = auth()->user();
        $author->profile = AuthorProfile::select(['bio'])
            ->where('user_id', auth()->user()->id)->get()->first();

        return inertia('Author/Account/Bio', [
            'data'   => [
                'title' => __('Bio'),
                'urls'  => [
                    'submit_form' => route('author.account.bio.update'),
                ],
            ],
            'author' => $author,
        ]);
    }

    public function updateBio(Request $request)
    {
        AuthorProfile::where('user_id', auth()->user()->id)->update($request->validate([
            'bio' => 'required|string|min:100|max:3000',

        ], [], [
            'bio' => __('Bio'),

        ]));

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    public function profile()
    {
        $user = auth()->user();

        $profile = AuthorProfile::with(['authorLevel'])
            ->where('user_id', $user->id)->get()->first();

        return inertia('Author/Account/Profile', [
            'data'   => [
                'title'        => __('Your Services'),
                'services'     => Service::select(['id', 'name'])->whereIn('id', array_filter([
                    $profile->service_id_1,
                    $profile->service_id_2,
                    $profile->service_id_3,
                ]))->get(),
                'author_level' => $profile->authorLevel,
            ],
            'author' => $user,
        ]);
    }
}
