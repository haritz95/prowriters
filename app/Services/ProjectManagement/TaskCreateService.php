<?php

namespace App\Services\ProjectManagement;

use App\Enums\ServiceType;
use App\Models\Business\Service;
use App\Models\ProjectManagement\AcademicWriting;
use App\Models\ProjectManagement\ContentWriting;
use App\Models\ProjectManagement\FixedPriceWriting;
use App\Models\ProjectManagement\Task;

class TaskCreateService
{

    private $data;

    public function __invoke(array $data)
    {
        $this->data = $data;

        $service = Service::find($data['service_id']);

        if (isset($data['is_bid_request']) && $data['is_bid_request']) {

            $data = array_merge($data, [
                'task_status_id'        => NULL,
                'dead_line'             => null,
                'dead_line_for_author'  => null,
                'total'                 => 0,
                'author_payment_amount' => 0,
            ]);

        }

        switch ($service->service_type_id) {
            case ServiceType::CONTENT_WRITING:

                $detail = ContentWriting::create($data);
                break;
            case ServiceType::ACADEMIC_WRITING:
                $detail = AcademicWriting::create($data);
                break;
            case ServiceType::FIXED_PRICE:
                $detail = FixedPriceWriting::create($data);
                break;
            default:
                # code...
                break;
        }

        $task = $detail->task()->create($data);
        $this->recordAttachments($task, $this->data['attachments'], $data['customer_id']);
        $task->followers()->attach($task->customer_id);

        if (isset($this->data['added_additional_services'])) {
            $this->recordAddedServices($task, $this->data['added_additional_services']);
        }

        return $task;
    }

    public function recordAddedServices(Task $order, $addedServices)
    {
        if ($addedServices && is_array($addedServices) && count($addedServices) > 0) {
            foreach ($addedServices as $row) {
                $order->additionalServices()->attach($row['id'], [
                    'quantity' => $row['quantity'],
                    'price'    => $row['price'],
                ]);
            }
        }
    }

    public function recordAttachments(Task $task, $attachments, $customer_id)
    {
        if ($attachments && is_array($attachments) && count($attachments) > 0) {

            (app()->makeWith('App\Services\AttachmentService', [
                'model'       => $task,
                'attachments' => $attachments,
                'userId'      => $customer_id,
            ]))->save();
        }
    }

}
