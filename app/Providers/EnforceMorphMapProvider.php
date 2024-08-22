<?php

namespace App\Providers;

use App\Models\Author\Applicant;
use App\Models\Messages\Message;
use App\Models\Payments\Payment;
use App\Models\Payments\PendingForApprovalPayment;
use App\Models\ProjectManagement\AcademicWriting;
use App\Models\ProjectManagement\ContentWriting;
use App\Models\ProjectManagement\FixedPriceWriting;
use App\Models\ProjectManagement\QualityAssurance;
use App\Models\ProjectManagement\RevisionRequest;
use App\Models\ProjectManagement\SubmittedWork;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class EnforceMorphMapProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            // 'work_level'                   => 'App\Models\Business\WorkLevel',
            // 'subject'                      => 'App\Models\Business\Subject',
            // 'urgency'                      => 'App\Models\Business\Urgency',
            // 'paper_format'                 => 'App\Models\Business\PaperFormat',
            // 'additional_service'           => 'App\Models\Business\AdditionalService',
            'task'                         => Task::class,
            'task_message'                 => TaskMessage::class,
            'submitted_work'               => SubmittedWork::class,
            'quality_assurance'            => QualityAssurance::class,
            'revision_request'             => RevisionRequest::class,
            'payment'                      => Payment::class,
            'payment_pending_for_approval' => PendingForApprovalPayment::class,
            'user'                         => User::class,
            'academic_writing'             => AcademicWriting::class,
            'content_writing'              => ContentWriting::class,
            'fixed_price_writing'          => FixedPriceWriting::class,
            'applicant'                    => Applicant::class,
            'message'                      => Message::class,

        ]);
    }
}
