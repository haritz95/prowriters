<?php

namespace App\Http\Controllers;

use App\Events\AuthorApplicationReceivedEvent;
use App\Models\Attachment;
use App\Models\Author\Applicant;
use App\Models\Author\ApplicantStatus;
use App\Models\Author\AuthorProfile;
use App\Models\Author\EducationLevel;
use App\Models\Business\Language;
use App\Models\Business\Service;
use App\Models\Country;
use App\Models\User;
use App\Models\Website\WebsitePage;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ApplicantController extends Controller
{

    public function __construct()
    {
        // if the Author application is disabled show 404 page
        if (disable_author_application()) {
            abort(404);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $subjects = [];
        $services = Service::active()->with(['subjects' => function ($q) {
            $q->select(['subjects.id', 'name']);
        }])->get();

        foreach ($services as $service) {
            $subjects[$service->id] = $service->subjects;
        }

        return inertia('Public/Applicants/Create', [
            'data' => [
                'title'           => __('Become a author'),
                'page'            => WebsitePage::get(WebsitePage::AUTHOR_APPLICATION),
                'dropdowns'       => [
                    'countries'        => Country::all(),
                    'timezones'        => User::getTimeZones(),
                    'education_levels' => EducationLevel::all(),
                    'subjects'         => $subjects,
                    'services'         => Service::active()->get(),
                    'experiences'      => AuthorProfile::experience(),
                    'languages'        => Language::all(),
                ],
                'success_message' => $request->session()->get('success_message'),
                'config'          => Attachment::configForAuthorApplication(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'           => 'required|string|max:255',
            'last_name'            => 'required|string|max:255',
            'email'                => "required|string|email|max:255|unique:applicants,email|unique:users,email",
            'phone'                => ['required', new PhoneNumber],
            'country_code'         => 'required',
            'timezone'             => 'required',
            'education_level_id'   => 'required',
            'bio'                  => 'required|string|min:200|max:500',
            'address'              => 'nullable|string|max:400',
            'state'                => 'nullable|string|max:192',
            'city'                 => 'nullable|string|max:192',
            'blog_url'             => 'nullable',
            'online_portfolio_url' => 'nullable',
            'linked_in_url'        => 'nullable',
            'years_of_experience'  => 'required|integer',
            'service_id_1'         => 'required',
            'subject_id_1'         => 'nullable',
            'subject_id_2'         => 'nullable',
            'subject_id_3'         => 'nullable',
            'subject_id_4'         => 'nullable',
            'subject_id_5'         => 'nullable',
            'attachment'           => 'required',

        ], [
            'email.unique' => __('Looks like we already have your application'),
        ]);

        $data = $request->all();
       
        $data['applicant_status_id'] = ApplicantStatus::APPLIED;
        $data['number']              = mt_rand(100000, 999999);
        $data['uuid']                = Str::orderedUuid();

        $applicant = Applicant::create($data);


        (app()->makeWith('App\Services\AttachmentService', [
            'model'       => $applicant,
            'attachments' => $data['attachment'],           
        ]))->save();

        // Dispatching Event
        event(new AuthorApplicationReceivedEvent($applicant));

        $page = WebsitePage::get(WebsitePage::AUTHOR_APPLICATION);

        if (isset($page['additional_data']['form_submission_message']) && !empty($page['additional_data']['form_submission_message'])) {
            $message = $page['additional_data']['form_submission_message'];
        } else {
            $message = __('Thank you! We will review your application and get in touch with you');
        }
        Session::flash('success_message', $message);

        return redirect()->back();
    }

}
