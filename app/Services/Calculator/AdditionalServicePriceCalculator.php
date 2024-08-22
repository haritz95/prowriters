<?php

namespace App\Services\Calculator;

use App\Enums\PriceType;
use App\Enums\ServiceType;
use App\Enums\UnitType;
use App\Models\Business\AdditionalService;

class AdditionalServicePriceCalculator
{
    private $data;

    /**
     * Class constructor.
     *
     * @param Service $service The service model object.
     * @param array $params Array containing the necessary params.
     *    $params = [
     *      'added_additional_services'               => (array|null) Array of added additional services
     *      'quantity'                                => (int) Total order quantity
     *      'basic_price'                             => (float) Basic price of the order
     *      'service_id'                             => (int) Service Id
     *    ]
     */
    public function __construct(array $params)
    {
        $this->data                            = new \stdClass();
        $this->data->added_additional_services = $params['added_additional_services'];
        $this->data->basicPrice                = $params['basic_price'];
        $this->data->service_id                = $params['service_id'];
        // $this->data->quantity                  = $params['quantity'];
        // $this->data->unit_name                 = $params['unit_name'];   
    }

    /**
     *
     * Calculate additional services price
     *
     * @return array $params Array containing the necessary params.
     *    $params = [
     *      'amount'               => (array|null) Array of added additional services
     *      'added_services'     => [
     *                        'additional_service_id' =>  (int)  additional service id
     *                        'quantity' => (int),
     *                        'price' => (float),
     *
     *          ]
     *
     *    ]
     */
    public function get()
    {
        $data = [
            'amount'         => 0,
            'added_services' => [],
        ];

        $amount = 0;
        if ($this->data->added_additional_services && is_array($this->data->added_additional_services)) {

            // Extract the additional service ids from the array
            foreach ($this->data->added_additional_services as $addedService) {
                $additionalServiceIds[] = $addedService['id'];
                // determine the quantity of the service
                $additionalServiceQuantity[$addedService['id']] = (isset($addedService['quantity'])) ? $addedService['quantity'] : 1;
            }

            // Get the additional services
            $additionalServices = AdditionalService::whereIn('id', $additionalServiceIds)->get();

            // Calculate the amount
            foreach ($additionalServices as $additionalService) {
                $price = $this->getPrice($additionalService->type, $additionalServiceQuantity[$additionalService->id], $additionalService->price,  $this->data->basicPrice);
                $data['amount'] += $price;
                $data['added_services'][] = [
                    'id'                    => $additionalService->id,
                    'price'                 => $price,
                    'quantity'              => $additionalServiceQuantity[$additionalService->id],
                ];
            }
        }

        return $data;
    }

    public function getPrice($additionalServiceType, $additionalServiceQuantity, $price, $basicPrice)
    {
        switch ($additionalServiceType) {
            case PriceType::FIXED:
                return $price;
                break;
            case PriceType::PERCENTAGE:
                return ($price / 100) * $basicPrice;
                break;
            // case PriceType::PER_UNIT:
            //     return $price * $this->getQuantityInLargestUnitOfMeasurement($orderQuantity);
            //     break;
            case PriceType::PER_ENTERED_QUANTITY:
                return $price * $additionalServiceQuantity;
                break;
            default:
                return 0;
                break;
        }
    }

    // public function getQuantityInLargestUnitOfMeasurement($orderQuantity)
    // {
    //     if (
    //         $this->data->service_id == ServiceType::ACADEMIC_WRITING ||
    //         $this->data->service_id == ServiceType::CONTENT_WRITING
    //     ) {
    //         if ($this->data->unit_name == UnitType::WORD) {
    //             ceil($orderQuantity / 275);
    //         }
    //     }
    //     return $orderQuantity;
    // }
}
