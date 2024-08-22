<?php

namespace App\Services;

use App\Models\Payments\Payment;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskMessage;

class LogActivity
{
    public static function authorStartedWorking(Task $task)
    {
        $log = __('app.activity_log.author_started_working');
        $properties = self::prepareProperties(route('admin.tasks.show', $task->uuid));
        self::log_activity($log, $task, $properties);
    }

    public static function AdminAssignedTaskToAuthor(Task $task)
    {
        // $log = __('app.activity_log.admin_assigned_task');
        // $properties = self::prepareProperties(route('admin.tasks.show', $task->uuid));
        // self::log_activity($log, $task, $properties);

        // $log = self::anchor($task->number, route('admin.tasks.show', $task->uuid));
        // self::log_activity($log, $task, $task->author);
    }
    
    public static function AdminUpdatedTaskPaymentAmount(Task $task)
    {
        // $log = __('app.activity_log.admin_assigned_task');
        // $properties = self::prepareProperties(route('admin.tasks.show', $task->uuid));
        // self::log_activity($log, $task, $properties);

        // $log = self::anchor($task->number, route('admin.tasks.show', $task->uuid));
        // self::log_activity($log, $task, $task->author);
    }

    public static function userCommentedOnTaskDiscussion(TaskMessage $message, Task $task)
    {
        // $subject = anchor($task->number, route('orders.show', $task->uuid));
        // logActivity('posted comment' . ' ' . $subject, $task, $commenter);
    }
    public static function authorSubmittedWorkForQA(Task $task)
    {
        // $subject = anchor($task->number, route('orders.show', $task->uuid));
        // logActivity('posted comment' . ' ' . $subject, $task, $commenter);
    }
    
    public static function authorSubmittedWorkForCustomerReview(Task $task)
    {
        // $subject = anchor($task->number, route('orders.show', $task->uuid));
        // logActivity('posted comment' . ' ' . $subject, $task, $commenter);
    }
    
    public static function editorApprovedSubmittedWork(Task $task)
    {
        // $subject = anchor($task->number, route('orders.show', $task->uuid));
        // logActivity('posted comment' . ' ' . $subject, $task, $commenter);
    }
    
    public static function editorRejectedSubmittedWork(Task $task)
    {
        // $subject = anchor($task->number, route('orders.show', $task->uuid));
        // logActivity('posted comment' . ' ' . $subject, $task, $commenter);
    }
    
    public static function customerRequestedForRevision(Task $task)
    {
        // $subject = anchor($task->number, route('orders.show', $task->uuid));
        // logActivity('posted comment' . ' ' . $subject, $task, $commenter);
    }
    
    public static function customerAcceptedWork(Task $task)
    {
        // $subject = anchor($task->number, route('orders.show', $task->uuid));
        // logActivity('posted comment' . ' ' . $subject, $task, $commenter);
    }
    
    public static function offlinePaymentApproved(Payment $payment)
    {
        // $subject = anchor($task->number, route('orders.show', $task->uuid));
        // logActivity('posted comment' . ' ' . $subject, $task, $commenter);
    }
    
    public static function offlinePaymentDisapproved(array $data)
    {


        // $data = $event->data;   
        // // Log user's activity    
        // //logActivity(null, 'disapproved an offline payment', null, $data);
    }


    private static function prepareProperties($url)
    {
        return [
            'url'  => $url,
            // 'text' => $text,
        ];
    }

    private static function log_activity($log, $performedOn = null, $properties = null, $user = null)
    {
        $user     = (empty($user)) ? auth()->user() : $user;
        $activity = activity()->causedBy($user);

        if ($performedOn) {
            $activity->performedOn($performedOn);
        }

        if ($properties) {
            $activity->withProperties($properties);
        }
        return $activity->log($log);
    }
}
