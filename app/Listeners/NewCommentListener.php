<?php

namespace App\Listeners;

use App\Events\NewCommentEvent;
use App\Notifications\NewComment;
use App\Services\LogActivity;
use Illuminate\Support\Facades\Notification;

class NewCommentListener
{
    /**
     * Handle the event.
     *
     * @param NewCommentEvent $event
     * @return void
     */
    public function handle(NewCommentEvent $event)
    {
        $comment   = $event->message;
        $task      = $comment->task;
        $commenter = $comment->user;

        // Log user's activity
        LogActivity::userCommentedOnTaskDiscussion($comment, $task);

        $followers = null;

        if ($commenter->id == $task->customer->id) {
            /*
             * If the comment is a customer,
             * and if an author exists include him in the email list,
             */
            $followers = $task->followers;
            if (!is_null($task->author)) {
                // If author exists
                $followers = $task->followers->push($task->author);
            }
        } elseif ($commenter->id == $task->author_id) {
            /*
             * If the commenter is a task author,
             * include the customer in the email list,
             */
            $followers = $task->followers->push($task->customer);
        } else {
            /*
             * Otherwise it means the message is posted by
             * admin who is not assigned to the task. Therefore
             * include both the client and task author in the email
             * list
             */
            $followers = $task->followers()->where('user_id', '<>', $comment->user_id)->get();

            $task->followers()->sync($comment->user_id);
            $followers = $followers->push($task->customer);

            if (!is_null($task->author)) {
                // If author exists
                $followers = $followers->push($task->author);
            }
        }
        if ($followers) {
            Notification::send($followers, new NewComment($comment, $task));
        }

        send_notification_to_company((new NewComment($comment, $task)));
    }

}
