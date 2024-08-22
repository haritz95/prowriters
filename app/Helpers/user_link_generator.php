<?php

use App\Models\ProjectManagement\TaskMessage;

function get_default_route_by_user($user)
{
    if (empty($user)) {
        return 'login';
    }

    switch ($user->type) {
        case \App\Enums\UserType::ADMIN:
            $panel = 'admin';
            break;
        case \App\Enums\UserType::AUTHOR:
            $panel = 'author';
            break;
        default:
            $panel = 'customer';
            break;
    }
    return $panel . '.dashboard';
}

function get_task_page_link_by_user_type($user_type_id, $task_uuid)
{
    switch ($user_type_id) {
        case \App\Enums\UserType::CUSTOMER:
            return route('customer.tasks.show', $task_uuid);
            break;
        case \App\Enums\UserType::AUTHOR:
            return route('author.tasks.show', $task_uuid);
            break;
        default:
            return route('admin.tasks.show', $task_uuid);

            break;
    }
}

function get_task_comment_page_link_by_user_type($user_type_id, TaskMessage $comment, $task_uuid)
{
    switch ($user_type_id) {
        case \App\Enums\UserType::CUSTOMER:
            return route('customer.tasks.discussions.index', $task_uuid);
            break;
        case \App\Enums\UserType::AUTHOR:
            $route = ($comment->is_public) ? 'author.tasks.discussions.index' : 'author.tasks.internal-discussions.index';
            return route($route, $task_uuid);
            break;
        default:
            $route = ($comment->is_public) ? 'admin.tasks.discussions.index' : 'admin.tasks.internal-discussions.index';
            return route($route, $task_uuid);

            break;
    }
}

function get_bid_request_page_link_by_user_type($user_type_id, $bid_request_uuid)
{
    switch ($user_type_id) {
        case \App\Enums\UserType::ADMIN:
            return route('admin.bidRequests.show', $bid_request_uuid);
            break;
        case \App\Enums\UserType::AUTHOR:
            return route('author.bidRequests.show', $bid_request_uuid);
            break;
        default:
            return route('customer.bidRequests.show', $bid_request_uuid);
            break;
    }
}

function get_user_profile_link($user)
{
    switch ($user->type) {
        case \App\Enums\UserType::ADMIN:
            return route('admin.users.show', $user->uuid);
            break;
        case \App\Enums\UserType::AUTHOR:
            return route('admin.authors.show', $user->uuid);
            break;
        default:
            return route('admin.customers.show', $user->uuid);
            break;
    }
}

function task_page_link_by_notifiable($notifiable, $task)
{
    $user_type = (isset($notifiable->type)) ? $notifiable->type : null;
    return get_task_page_link_by_user_type($user_type, $task->uuid);
}