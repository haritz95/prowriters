<?php

namespace App\Services\ProjectManagement;

use App\Events\NewCommentEvent;
use App\Events\NewPrivateCommentEvent;
use App\Models\Attachment;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskMessage;
use App\Services\AttachmentService;
use Illuminate\Support\Str;

class TaskMessageService
{

    private function query(Task $task, $request, $isInternal = null)
    {
        $query = ($isInternal) ? $task->internalDiscussions() : $task->externalDiscussions();

        return $query->with([
            'attachments' => function ($q) {
                $q->select(['id', 'uuid', 'name', 'display_name', 'attachable_id', 'attachable_type']);
            },
            'user'        => function ($q) {
                $q->select(['id', 'uuid', 'type', 'code', 'photo']);
            },
        ])->orderBy('id', 'DESC')->paginate(config('app.pagination.per_page'));
    }

    public function getInternalDiscussions(Task $task, $request)
    {
        return $this->query($task, $request, true);
    }

    public function getExternalDiscussions($task, $request)
    {
        return $this->query($task, $request);
    }

    public function getConfigForCreateMessage($form_submit_url)
    {
        $config                        = Attachment::configForFrontEnd();
        $config['urls']['submit_form'] = $form_submit_url;
        return $config;
    }

    public function store(Task $task, $request, $isPublic = null)
    {
        $message_body = $request->input('message');

        if (($request->has('quote'))) {
            $message_body = '<blockquote>' . $request->input('quote') . '</blockquote>' . $message_body;
        }

        $message            = new TaskMessage();
        $message->body      = $this->removeContactInformation($message_body);
        $message->uuid      = Str::orderedUuid();
        $message->user_id   = auth()->user()->id;
        $message->task_id   = $task->id;
        $message->is_public = ($isPublic) ? true : null;
        $message->save();

        if ($request->has('files')) {
            (new AttachmentService($message, $request->input('files'), auth()->user()->id))->save();
        }

        //Dispatching Event
        if ($message->is_public) {
            event(new NewCommentEvent($message));
        } else {
            event(new NewPrivateCommentEvent($message));
        }

    }

    // Being also used from Author/TaskController
    public function removeContactInformation($message)
    {
        if (!is_filter_contact_info_from_message_enabled()) {
            return $message;
        }

        $regex_email = '/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/';
        $regex_phone = "/[0-9]{5,}|\d[ 0-9 ]{1,}\d|\sone|\stwo|\sthree|\sfour|\sfive|\ssix|\sseven|\seight|\snine|\sten/i";

        //$str = " Hello My Email whatever@gmail.com AND Phone No. is +919992799999 and +21 9992799999, or 9 9 9 2 7 9 9 9 9 9 and Nine Nine";;

        $message = preg_replace($regex_email, '(hidden)', $message); // extract email
        $message = preg_replace($regex_phone, '(hidden)', $message); // extract phone

        return $message;
    }
}
