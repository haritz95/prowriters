<?php

namespace Database\Seeders\Dummy\TaskSeederHelper;

use App\Enums\CartType;
use App\Models\ProjectManagement\QualityAssurance;
use App\Models\ProjectManagement\Rating;
use App\Models\ProjectManagement\RevisionRequest;
use App\Models\ProjectManagement\SubmittedWork;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskMessage;
use App\Models\ProjectManagement\TaskStatus;
use App\Models\User;
use App\Services\AttachmentService;
use App\Services\CartService;
use App\Services\PaymentRecordService;
use App\Services\SavedCartProcessingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait StatusTrait
{

    private $task = null;

    public function getTask()
    {
        return $this->task;
    }

    public function setTask(Task $task)
    {
        $this->task = $task;
        return $this;
    }

    private function assignAuthor()
    {
        $task                = $this->getTask();
        $task->author_id = $this->author_id;
        $task->save();
        return $this;
    }

    private function statusInProgress()
    {
        $task                 = $this->getTask();
        $task->task_status_id = TaskStatus::IN_PROGRESS;
        $task->save();
        return $this;
    }

    private function statusSubmittedForApproval()
    {
        $task          = $this->getTask();
        $submittedWork = $task->submittedWorks()->latest('id')->get()->first();

        // Record the QA Report
        $qa                    = new QualityAssurance();
        $qa->message           = $this->faker->sentence;
        $qa->user_id           = $this->admin_id;
        $qa->submitted_work_id = $submittedWork->id;
        $qa->save();

        // Update the task status
        $task->task_status_id = TaskStatus::SUBMITTED_FOR_APPROVAL;
        $task->save();

        // Save the attachment
        (new AttachmentService($qa, $this->getAttachments(), $this->admin_id))->save();

        return $this;
    }

    private function statusComplete()
    {
        $task                 = $this->getTask();
        $task->task_status_id = TaskStatus::COMPLETE;
        $task->accepted_at    = now();
        $task->save();

        $rating          = new Rating();
        $rating->uuid    = Str::uuid();
        $rating->task_id = $task->id;
        $rating->number  = $this->faker->randomElement([3, 4, 5]);
        $rating->comment = $this->faker->sentence;
        $rating->user_id = $this->customer_id;
        $rating->save();

        return $this;
    }

    private function statusQARejected()
    {
        $task          = $this->getTask();
        $submittedWork = $task->submittedWorks()->latest('id')->get()->first();

        // Record the QA Report
        $qa                    = new QualityAssurance();
        $qa->message           = $this->faker->sentence;
        $qa->user_id           = $this->admin_id;
        $qa->submitted_work_id = $submittedWork->id;
        $qa->save();

        // Update the task status
        $task->task_status_id = TaskStatus::QA_REJECTED;
        $task->save();

        // Save the attachment
        (new AttachmentService($qa, $this->getAttachments(), $this->admin_id))->save();
    }

    private function statusSubmittedForQA()
    {
        $task          = $this->getTask();
        $work          = new SubmittedWork();
        $work->message = $this->faker->sentence;
        $work->user_id = $this->author_id;
        $work->task_id = $task->id;
        $work->save();

        $task->task_status_id = TaskStatus::SUBMITTED_FOR_QA;
        $task->save();

        (new AttachmentService($work, $this->getAttachments(), $this->author_id))->save();

        return $this;
    }

    private function statusRequestForRevision()
    {
        $task          = $this->getTask();
        $submittedWork = $task->submittedWorks()->latest('created_at')->first();

        // Update Task Status
        $task->task_status_id           = TaskStatus::REQUESTED_FOR_REVISION;
        $task->revisions_taken          = ($task->revisions_taken) ? $task->revisions_taken + 1 : 1;
        $task->dead_line                = ($task->dead_line) ? $task->dead_line->addDays(5) : $task->dead_line;
        $task->dead_line_for_author = ($task->dead_line_for_author) ? $task->dead_line_for_author->addDays(5) : $task->dead_line_for_author;
        $task->save();

        // Create Revision Request
        $revisionRequest = RevisionRequest::create([
            'submitted_work_id' => $submittedWork->id,
            'user_id'           => $this->customer_id,
            'message'           => $this->faker->sentence,
        ]);

        // Save the attachments
        (new AttachmentService($revisionRequest, $this->getAttachments(), $this->customer_id))->save();
    }

    private function getAttachments()
    {
        $extension   = '.txt';
        $file_1      = Str::random() . $extension;
        $file_2      = Str::random() . $extension;
        $attachments = [
            [
                'name'         => $this->attachment_target_path . $file_1,
                'display_name' => $file_1,

            ]
            , [
                'name'         => $this->attachment_target_path . $file_2,
                'display_name' => $file_2,
            ]];

        foreach ($attachments as $attachment) {
            Storage::put($attachment['name'], 'Dummy Content');
        }

        return $attachments;
    }

    public function execute(CartService $cartService): int
    {
        Model::reguard();

        $token = $cartService->saveCart(CartType::ORDER, $this->customer_id);

        $paymentRecordService = app()->make(PaymentRecordService::class);

        $userCart = $cartService->getSavedCart($token, $this->customer_id);

        // Record the Payment Information
        $payment = $paymentRecordService->store($this->customer_id, 'Stripe', $userCart->total, $this->faker->ean13);

        // // Mark in the cart that payment has been made
        // $token = bin2hex(random_bytes(5));
        $cartService->markPaymentAsComplete($token, $payment->id);

        (new SavedCartProcessingService)->onlinePayment($userCart, User::find($this->customer_id));

        $userCart = $cartService->getSavedCart($token, $this->customer_id);
        // Delete cart
        $cartService->destroySavedCart($token);

        return $userCart->invoice_id;

    }

    public function getCallableMethods()
    {
        // New
        // In Progress
        // Submitted for QA
        // QA Rejected
        // Submitted for approval
        // Requested for revision
        // Completed with customer ratings

        return [
            // 0 In Progress
            function ($task) {
                $this->setTask($task)
                    ->assignAuthor()
                    ->statusInProgress()
                    ->storeDiscussionMessage();
            },
            // 1 Submitted for QA
            function ($task) {
                $this->setTask($task)
                    ->assignAuthor()
                    ->statusInProgress()
                    ->storeDiscussionMessage()
                    ->statusSubmittedForQA();
            },
            // 2 QA Rejected
            function ($task) {
                $this->setTask($task)
                    ->assignAuthor()
                    ->statusInProgress()
                    ->storeDiscussionMessage()
                    ->statusSubmittedForQA()
                    ->statusQARejected();
            },
            // 3 Requested for Approval
            function ($task) {
                $this->setTask($task)
                    ->assignAuthor()
                    ->statusInProgress()
                    ->storeDiscussionMessage()
                    ->statusSubmittedForQA()
                    ->statusSubmittedForApproval();
            },
            // 4 Requested for revision
            function ($task) {
                $this->setTask($task)
                    ->assignAuthor()
                    ->statusInProgress()
                    ->storeDiscussionMessage()
                    ->statusSubmittedForQA()
                    ->statusSubmittedForApproval()
                    ->statusRequestForRevision();
            },
            // 5 Completed with customer ratings
            function ($task) {
                $this->setTask($task)
                    ->assignAuthor()
                    ->statusInProgress()
                    ->storeDiscussionMessage()
                    ->statusSubmittedForQA()
                    ->statusSubmittedForApproval()
                    ->statusComplete();
            },
            // 6 Completed with customer ratings
            function ($task) {
                $this->setTask($task)
                    ->assignAuthor()
                    ->statusInProgress()
                    ->storeDiscussionMessage()
                    ->statusSubmittedForQA()
                    ->statusSubmittedForApproval()
                    ->statusComplete();
            },
        ];
    }

    public function storeDiscussionMessage()
    {
        $messages = [
            // ***** External / Public Discussion
            // Client to Author
            [
                'user_id'        => $this->customer_id,
                'is_public'      => true,
                'has_attachment' => true,
            ],
            // Author to Client
            [
                'user_id'        => $this->author_id,
                'is_public'      => true,
                'has_attachment' => false,
            ],
            // Admin to Client / Author
            [
                'user_id'        => $this->admin_id,
                'is_public'      => true,
                'has_attachment' => false,
            ],

            // ***** Internal / Private Discussion
            // Admin to client
            [
                'user_id'        => $this->admin_id,
                'is_public'      => null,
                'has_attachment' => true,
            ],
            // Author to client
            [
                'user_id'        => $this->author_id,
                'is_public'      => null,
                'has_attachment' => false,
            ],
            [
                'user_id'        => $this->admin_id,
                'is_public'      => null,
                'has_attachment' => false,
            ],
        ];

        $task = $this->getTask();

        foreach ($messages as $row) {
            $message            = new TaskMessage();
            $message->body      = $this->faker->paragraph;
            $message->user_id   = $row['user_id'];
            $message->task_id   = $task->id;
            $message->is_public = $row['is_public'];
            $message->save();

            if ($row['has_attachment']) {
                (new AttachmentService($message, $this->getAttachments(), $row['user_id']))->save();
            }

        }

        return $this;
    }
}
