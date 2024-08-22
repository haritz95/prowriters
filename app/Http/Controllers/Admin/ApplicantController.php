<?php

namespace App\Http\Controllers\Admin;

use App\Events\AuthorApplicationApprovedEvent;
use App\Http\Controllers\Controller;
use App\Models\Author\Applicant;
use App\Models\Author\ApplicantStatus;
use App\Models\Business\AuthorLevel;
use App\Models\Business\Subject;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApplicantController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Applicants/Index', [
            'data' => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'      => __('Applicants'),
                    'dropdowns'  => Applicant::adminSearchDropdown(),

                ];
            },
            'filters'    => $request->only('filters'),
            'applicants' => Applicant::with(['status', 'service'])

                ->when($request->filled('filters.applicant_status_id'), function ($query) use ($request) {
                    return $query->where('applicant_status_id', $request->input('filters.applicant_status_id'));
                })
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery->where('first_name', 'like', '%' . $request->filters['search'] . '%')
                            ->orWhere('last_name', 'like', '%' . $request->filters['search'] . '%')
                            ->orWhere('email', 'like', '%' . $request->filters['search'] . '%')
                            ->orWhere('number', 'like', '%' . $request->filters['search'] . '%');
                    });
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function show(Applicant $applicant)
    {

        $applicant->load(['status', 'service', 'educationLevel', 'country', 'attachments']);

        $attachment = $applicant->attachments->first();

        return inertia('Admin/Applicants/Show', [
            'data'      => [
                'title'           => __('Applicant Profile') . ' ' . $applicant->number,
                'dropdowns'       => [
                    'statuses'      => ApplicantStatus::orderBy('id', 'ASC')->get(),
                    'author_levels' => AuthorLevel::orderBy('id', 'ASC')->get(),
                ],
                'subjects'        => Subject::whereIn('id', array_filter([
                    $applicant->subject_id_1,
                    $applicant->subject_id_2,
                    $applicant->subject_id_3,
                    $applicant->subject_id_4,
                    $applicant->subject_id_5,
                ]))->get(),
                'attachment_uuid' => ($attachment) ? $attachment->uuid : null,
            ],
            'applicant' => $applicant,

        ]);
    }

    /**
     * Change the status of the specified resource.
     *
     * @param  \App\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Applicant $applicant)
    {
        $applicant->applicant_status_id = $request->applicant_status_id;
        $applicant->note                = $request->note;
        $applicant->save();
        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Applicant $applicant)
    {
        $applicant->attachments()->delete();
        $applicant->delete();
        return redirect()->route('admin.applicants.index')->withSuccess(__('Successfully Deleted'));
    }

    /**
     * Hire  applicant and enroll as author
     *
     * @param  \App\Applicant  $applicant
     * @return \Illuminate\Http\Response
     */
    public function enroll(Request $request, Applicant $applicant, UserService $userService)
    {
        $validator = Validator::make($request->all(), [
            'author_level_id' => 'required',
        ], [], [
            'author_level_id' => __('Author Level'),
        ]);

        //hook to add additional rules by calling the ->after method
        $validator->after(function ($validator) use ($applicant) {

            if (User::where('email', $applicant->email)->get()->count()) {

                $validator->errors()->add('author_level_id', __('The applicant is already enrolled'));
            }

        });

        //run validation which will redirect on failure
        $validator->validate();

        $password                = Str::random(10);
        $data                    = $applicant->toArray();
        $data['password']        = $password;
        $data['author_level_id'] = $request->author_level_id;
        $user                    = $userService->createAuthor($data);

        // Update Resume
        $attachment                  = $applicant->attachments->first();
        $attachment->attachable_id   = $user->id;
        $attachment->attachable_type = 'user';
        $attachment->user_id         = $user->id;
        $attachment->save();

        // Destroy the applicant record
        $applicant->delete();

        // Dispatch event
        event(new AuthorApplicationApprovedEvent($user, $password));

        return redirect()->route('admin.authors.show', $user->uuid)->withSuccess(__('Enrolled'));
    }
}
