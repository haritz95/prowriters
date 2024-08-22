<?php

namespace App\Services\Calculator;

use App\Models\User;

class CouponDiscountAmount
{

    private $data;

    /**
     * Class constructor.
     *   
     * @param array $params Array containing the necessary params.
     *    $params = [   
     *      'coupon_code'               => (string) Coupon code
     *      'sub_total'                 => (float) Sub total
     *      'user'                      => (object|null) User model object
     *    ]   
     */
    public function __construct(array $params)
    {

        $this->data = new \stdClass();
        $this->data->couponCode = $params['coupon_code'];
        $this->data->subTotal = $params['sub_total'];
        $this->data->user = $params['user'];
    }

    /**
     *
     * Calculate discount for coupon
     *
     * @return array $params
     *  $params = [   
     *      'amount'                    => (float) Coupon discount amount
     *      'coupon'                    => (object|null) Coupon model object
     *      'couponErrors'              => (array|null) Coupon errors
     *    ]   
     *
     */
    function get(): array
    {
        // Calculate Coupon Discount
        $amount = 0;
        $coupon = ['id' => NULL, 'code' => NULL];
        $couponErrors = [];

        if ($this->data->couponCode) {
            $couponService =  app()->make('App\Services\CouponService');

            $couponServiceResponse = $couponService->verify($this->data->couponCode, [], $this->data->subTotal, 0, $this->data->user);

            if (empty($couponServiceResponse['has_error']) && $couponServiceResponse['amount']) {

                $amount = $couponServiceResponse['amount'];
                $coupon['id'] = $couponServiceResponse['coupon']->id;
                $coupon['code'] = $couponServiceResponse['coupon']->code;
            } else {
                $couponErrors[] = $couponServiceResponse['errors'];
            }
        }

        return [
            'amount' => $amount,
            'coupon' => $coupon,
            'couponErrors' => $couponErrors,
        ];
    }
}
