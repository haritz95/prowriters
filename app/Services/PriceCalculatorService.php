<?php

namespace App\Services;

use App\Enums\InvoiceItemType;
use App\Enums\ServiceType;
use App\Enums\UserType;
use App\Models\Business\Assignment;
use App\Models\Business\Service;
use App\Models\ProjectManagement\TaskStatus;
use App\Services\Calculator\AcademicWritingPriceCalculator;
use App\Services\Calculator\AdditionalServicePriceCalculator;
use App\Services\Calculator\ContentWritingPriceCalculator;
use App\Services\Calculator\FixedPriceServicePriceCalculator;
use Illuminate\Support\Collection;

class PriceCalculatorService
{

    public function calculate(Service $service, $request): Collection
    {

        if (ServiceType::CONTENT_WRITING == $service->service_type_id) {
            $fields_to_merge = (new ContentWritingPriceCalculator($request))->get();
        } else if (ServiceType::ACADEMIC_WRITING == $service->service_type_id) {
            $fields_to_merge = (new AcademicWritingPriceCalculator($request))->get();
        } else if (ServiceType::FIXED_PRICE == $service->service_type_id) {
            $fields_to_merge = (new FixedPriceServicePriceCalculator($request))->get();
        } else {
            throw new \Exception('Service not found');
        }

        $base_price = $this->roundPrice($fields_to_merge['amount']);

        $additional_services = (new AdditionalServicePriceCalculator([
            'service_id'                => $request->service_id,
            'basic_price'               => $base_price,
            'added_additional_services' => $request->added_additional_services,
        ]))->get();

        $total      = $base_price + $additional_services['amount'];
        $assignment = Assignment::find($request->assignment_id);

        if (auth()->check() && auth()->user()->type == UserType::ADMIN) {

            if ($request->is_total_overridden) {
                $fields_to_merge['is_total_overridden'] = true;
                $fields_to_merge['original_total']      = $total;
                $total                             = $request->updated_total;
            }
            else {
                $fields_to_merge['is_total_overridden'] = null;
                $fields_to_merge['original_total']      = null;
            }

            $fields_to_merge['customer_id'] = $request->customer_id;
           
        }

        $fields = array_merge($request->all(), $fields_to_merge, [
            'task_status_id'            => TaskStatus::NEW ,
            'service_id'                => $service->id,
            'additional_services_price' => $additional_services['amount'],
            'total'                     => $total,
            'added_additional_services' => $additional_services['added_services'],
            'attachments'               => $request->attachments,
            'author_payment_amount'     => $this->getAuthorPaymentAmount($service, $assignment, $total),

        ]);

        return collect([
            'name'      => $service->name,
            'type'      => InvoiceItemType::NEW_TASK,
            'title'     => $request->title,
            'price'     => $total,
            'quantity'  => 1,
            'sub_total' => $total,
            'fields'    => $fields,
        ]);
    }

    public function roundPrice($amount)
    {
        return round($amount, 2);
    }

    public function getAuthorPaymentAmount(Service $service, Assignment $assignment, $total)
    {
        if (ServiceType::CONTENT_WRITING == $service->service_type_id) {
            $commission = ($service->commission / 100) * $total;
            $amount     = $total - $commission;
        } else if (ServiceType::ACADEMIC_WRITING == $service->service_type_id) {
            $commission = ($service->commission / 100) * $total;
            $amount     = $total - $commission;
        } else if (ServiceType::FIXED_PRICE == $service->service_type_id) {
            $amount = $assignment->author_payment_amount;
        } else {
            $amount = 0;
        }

        return $amount;
    }
}
