<?php

namespace App\Services;

use Carbon\Carbon;
use App\Enums\CouponType;
use App\Models\Accounting\Coupon;
use App\Models\Accounting\Invoice;

class CouponService
{
    protected $errors = [];

    public function getDiscountTotal($code, $total, $customer = null)
    {
        $data = [
            'amount'    => 0,
            'coupon_id' => null,
            'has_error' => true,
            'errors'    => [],
        ];

        if ($coupon = $this->getCoupon($code)) {

            $data['coupon_id'] = $coupon->id;

            // Check if coupon is valid
            if ($this->passedGeneralValidation($coupon, $total)) {

                if ($this->requiresAuthenticatedUser($coupon)) {
                    if (empty($customer)) {
                        $this->setError(__('The coupon you selected requires a logged in user. Please login before you use this discount'));
                    } else {
                        if ($this->passedUserRelevantValidation($coupon, $customer)) {
                            // Get the coupon
                            $data['amount']    = $this->calculateDiscountAmount($coupon, $total);
                            $data['has_error'] = null;
                        }

                    }
                } else {
                    // Get the coupon
                    $data['amount']    = $this->calculateDiscountAmount($coupon, $total);
                    $data['has_error'] = null;
                }

            }
        } else {
            $this->setError(__('Invalid coupon code'));
        }

        $data['errors'] = $this->getErrors();

        return $data;
    }

    public function logUsage(int $coupon_id)
    {
        Coupon::where('id', $coupon_id)->increment('usage', 1);
    }

    protected function roundPrice($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    protected function calculateDiscountAmount(Coupon $coupon, $total)
    {
        if ($coupon->type == CouponType::Percentage) {
            $discount_amount = (($total * $coupon->amount) / 100);

            // Discount amount cannot be more than maximum discount amount
            if ($discount_amount > $coupon->maximum_discount) {
                $discount_amount = $coupon->maximum_discount;
            }
        } else {
            // Fixed Amount
            $discount_amount = $coupon->amount;
        }

        return $this->roundPrice($discount_amount);
    }

    protected function passedGeneralValidation($coupon, $total)
    {
        return (
            $this->isActive($coupon)
            && $this->isValidSubTotal($coupon, $total)
            && $this->usageLimitPerCoupon($coupon)
        );
    }

    protected function requiresAuthenticatedUser(Coupon $coupon)
    {
        // Check usage limit per user
        // Check if first order only for the user
        // Check if specific customer only

        return (
            $coupon->usage_limit_per_user
            || $coupon->first_order_only
            || $coupon->specific_customer_only
        );
    }

    protected function passedUserRelevantValidation(Coupon $coupon, $customer)
    {
        $status = true;
        // Check if specific user only
        // usage limit
        // Check if first order only
        if ($coupon->specific_customer_only) {
            if ($coupon->customer_id != $customer->id) {
                $this->setError(__('You are not allowed to use the coupon'));
                $status = false;
            }
        }
        if ($coupon->usage_limit_per_user) {
            if ($this->hasUsageLimitPerUserExceeded($coupon, $customer)) {
                $this->setError(__('You have reached the redemption limit of this coupon'));
                $status = false;
            }
        }

        if ($coupon->first_order_only) {
            if ($this->alreadyPlacedOrderOnce($coupon, $customer)) {
                $this->setError(__('You have reached the redemption limit of this coupon'));
                $status = false;
            }

        }
        return $status;
    }

    public function getCoupon($code)
    {
        return Coupon::where('code', $code)->whereNull('inactive')->get()->first();
    }

    // public function getCouponById($id)
    // {
    //     return Coupon::where('id', $id)->whereNull('inactive')->get()->first();
    // }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function setError($message)
    {
        array_push($this->errors, $message);
    }

    protected function isActive(Coupon $coupon)
    {
        // Check the starting/active date of the coupon
        if ($coupon->active_date) {
            $activeFrom = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->active_date);

            if (Carbon::now()->lessThan($activeFrom)) {
                $this->setError(__('Inactive coupon code'));
                return false;
            }
        }
        // Check the expiry date of the coupon
        if ($coupon->expiry_date) {
            $expireDate = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->expiry_date);

            if (Carbon::now()->greaterThan($expireDate)) {
                $this->setError(__('The coupon is no longer valid'));
                return false;
            }
        }

        if ($coupon->archive || $coupon->inactive) {
            $this->setError(__('The coupon is no longer valid'));
            return false;
        }
        return true;
    }

    protected function isValidSubTotal(Coupon $coupon, $total)
    {
        if ($total < $coupon->minimum_spend) {
            $this->setError(__('The minimum order value of :order_value for the coupon has not been reached', ['order_value' => $this->roundPrice($coupon->minimum_spend)]));
            return false;
        }
        return true;
    }

    protected function usageLimitPerCoupon(Coupon $coupon)
    {
        if ($coupon->usage_limit_per_coupon) {
            if ($coupon->usage >= $coupon->usage_limit_per_coupon) {
                $this->setError(__('Coupon usage limit has crossed'));
                return false;
            }
        }
        return true;
    }

    protected function hasUsageLimitPerUserExceeded(Coupon $coupon, $customer)
    {
        $number_of_times_the_coupon_has_been_used = Invoice::where('customer_id', $customer->id)
            ->where('coupon_id', $coupon->id)
            ->count();

        if ($number_of_times_the_coupon_has_been_used >= $coupon->usage_limit_per_user) {

            return true;
        }

        return false;
    }

    protected function alreadyPlacedOrderOnce(Coupon $coupon, $customer)
    {
        return Invoice::where('customer_id', $customer->id)
            ->where('coupon_id', $coupon->id)->count() ?? false;
    }

}
