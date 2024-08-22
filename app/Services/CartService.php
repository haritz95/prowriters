<?php

namespace App\Services;

use App\Enums\CartType;
use App\Models\UserCart;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;

class CartService
{
    protected $session;
    const DEFAULT_INSTANCE         = 'cart';
    const DISCOUNT_COUPON_INSTANCE = 'cart_discount_coupon';

    /**
     * Constructs a new cart object.
     *
     * @param Illuminate\Session\SessionManager $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Adds a new item to the cart.
     *
     * @param string $id
     * @param string $name
     * @param string $price
     * @param string $quantity
     * @param array $options
     * @return self
     */
    public function add(Collection $cartItem, $id = null): self
    {
        $content = $this->getContent();

        $id = ($id && $content->has($id)) ? $id : rand(10, 20) . time();

        $content->put($id, $cartItem);

        $this->session->put(self::DEFAULT_INSTANCE, $content);

        return $this;
    }

    /**
     * Returns the content of the cart.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getContent(): Collection
    {
        return $this->session->has(self::DEFAULT_INSTANCE) ? $this->session->get(self::DEFAULT_INSTANCE) : collect([]);
    }

    /**
     * Removes an item from the cart.
     *
     * @param string $id
     * @return void
     */
    public function remove($id): void
    {
        $content = $this->getContent();

        if ($content->has($id)) {
            $this->session->put(self::DEFAULT_INSTANCE, $content->except($id));
        }
    }

    /**
     * Clears the cart.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->session->forget(self::DEFAULT_INSTANCE);
        $this->removeDiscountCoupon();
    }

    /**
     * Returns total price of the items in the cart.
     *
     * @return string
     */
    protected function total(): string
    {
        // $content = $this->getContent();

        // $total = $content->reduce(function ($total, $item) {
        //     return $total += $item->get('sub_total');
        // });
        $total_before_tax_and_discount = $this->getSubtotal();
        $sales_tax                     = $this->getSalesTax();
        $total                         = ($total_before_tax_and_discount - $this->getDiscountCouponAmount()) + $sales_tax;

        return round($total, 2);
    }

    /**
     * Returns total price of the items in the cart.
     *
     * @return string
     */
    public function getSubtotal(): string
    {
        $content = $this->getContent();

        $total = $content->reduce(function ($total, $item) {
            return $total += $item->get('sub_total');
        });

        return round($total, 2);
    }

    /**
     * Checks if an item exists in the cart.
     *
     * @param string $id
     * @return bool
     */
    protected function hasItem($id): bool
    {
        $content = $this->getContent();

        if ($content->has($id)) {
            return true;
        }
    }

    /**
     * Returns the content of the cart.
     *
     * @return Illuminate\Support\Collection
     */
    public function getContentById($id): ?Collection
    {
        if ($this->hasItem($id)) {
            return $this->getContent()->get($id);
        }
        return null;
    }

    /**
     * Returns the content of the cart.
     *
     * @return Illuminate\Support\Collection
     */
    public function content(): Collection
    {
        return is_null($this->session->get(self::DEFAULT_INSTANCE)) ? collect([]) : $this->session->get(self::DEFAULT_INSTANCE);
    }

    /**
     * Generate token
     *
     * @return int
     */
    private function generateToken($user_id)
    {
        return trim(time() . $user_id . rand(10, 20));
    }

    /**
     * Save the cart items in database
     *
     * @return int
     */
    public function saveCart(string $cart_type, $user_id): int
    {
        $coupon_id        = null;
        $coupon_code      = null;
        $coupon_discount  = 0;
        $sales_tax_rate   = null;
        $sales_tax_amount = null;

        $sub_total = $this->getSubtotal();

        if ($cart_type == CartType::ORDER) {
            // Discount Coupon
            $coupon          = $this->getDiscountCoupon();
            $coupon_code     = ($coupon) ? $coupon['coupon_code'] : null;
            $coupon_discount = ($coupon) ? $coupon['discount_amount'] : 0;
            $coupon_id       = ($coupon) ? $coupon['coupon_id'] : null;

            // Sales Tax
            $tax_information  = $this->calculateSalesTax($sub_total);
            $sales_tax_rate   = $tax_information['sales_tax_rate'];
            $sales_tax_amount = $tax_information['sales_tax_amount'];
        }

        $token = $this->generateToken($user_id);

        UserCart::create([
            'type'             => $cart_type,
            'user_id'          => $user_id,
            'token'            => $token,
            'items'            => $this->getContent(),
            'sub_total'        => $sub_total,
            'coupon_id'        => $coupon_id,
            'coupon_code'      => $coupon_code,
            'coupon_discount'  => $coupon_discount,
            'sales_tax_rate'   => $sales_tax_rate,
            'sales_tax_amount' => $sales_tax_amount,
            'total'            => $this->getTotal(),
        ]);

        $this->clear();
        return $token;
    }

    public function saveCartForInvoicePayment($customer_id, $invoice_uuid, $total): int
    {
        $this->add(collect([
            'sub_total' => $total,
        ]));

        $token = $this->generateToken($customer_id);
        UserCart::updateOrCreate([
            'invoice_uuid' => $invoice_uuid,
            'user_id'      => $customer_id,
        ], [
            'type'  => CartType::PAYMENT_FOR_INVOICE,
            'token' => $token,
            'total' => $total,
        ]);
        $this->clear();
        return $token;
    }

    public function destroySavedCart($token)
    {
        UserCart::where('token', $token)->delete();
    }

    public function updateInvoiceId($token, $invoice_id)
    {
        UserCart::where('token', $token)->update(['invoice_id' => $invoice_id]);
    }

    public function saveCartForLoadWallet($user_id, $amount)
    {
        $token = $this->generateToken($user_id);
        UserCart::create([
            'type'      => CartType::LOAD_WALLET,
            'user_id'   => $user_id,
            'token'     => $token,
            'sub_total' => $amount,
            'total'     => $amount,
        ]);

        return $token;
    }

    public function saveCartForBidPayment($user_id, $amount, array $items)
    {
        $token = $this->generateToken($user_id);
        UserCart::create([
            'type'      => CartType::PAYMENT_FOR_BID,
            'user_id'   => $user_id,
            'token'     => $token,
            'sub_total' => $amount,
            'total'     => $amount,
            'items'     => $items,
        ]);

        return $token;
    }

    public function destroySavedCartLoadWallet($user_id)
    {
        UserCart::where('type', CartType::LOAD_WALLET)->where('user_id', $user_id)->delete();
    }

    /**
     * Get the cart items from database
     *
     * @return UserCart
     */
    public function getSavedCart($token, $user_id): ?UserCart
    {
        $data = UserCart::where('token', trim($token))->where('user_id', $user_id)->get();
        if ($data->count() > 0) {
            return $data->first();
        }
        return null;
    }

    public function getTotal()
    {
        return $this->total();
    }

    public function getTotalFromSavedCart($token)
    {
        return UserCart::select('total')->where('token', trim($token))->get()->first()->total;
    }

    public function getCurrency()
    {
        $currencyCode = settings('currency_code');
        return ($currencyCode) ? $currencyCode : 'USD';
    }

    public function markPaymentAsComplete($token, $payment_id)
    {
        UserCart::where('token', $token)->update(['payment_id' => $payment_id]);
    }

    public function getSavedCartIfPaymentIsComplete($token, $user_id): ?UserCart
    {
        if ($userCart = $this->getSavedCart($token, $user_id)) {
            return ($userCart->payment_id) ? $userCart : null;
        }
        return null;
    }

    public function addDiscountCoupon($coupon_id, $coupon_code, $discount_amount)
    {
        $this->session->put(self::DISCOUNT_COUPON_INSTANCE, [
            'coupon_id'       => $coupon_id,
            'coupon_code'     => $coupon_code,
            'discount_amount' => $discount_amount,
        ]);
    }

    public function getDiscountCoupon()
    {
        return $this->session->has(self::DISCOUNT_COUPON_INSTANCE) ? $this->session->get(self::DISCOUNT_COUPON_INSTANCE) : null;
    }

    public function getDiscountCouponAmount()
    {
        $coupon = $this->getDiscountCoupon();

        if ($coupon) {
            return $coupon['discount_amount'];
        }
        return 0;
    }

    public function removeDiscountCoupon()
    {
        $this->session->forget(self::DISCOUNT_COUPON_INSTANCE);
    }

    public function getSalesTax()
    {
        $tax_information = $this->calculateSalesTax($this->getSubtotal());
        return $tax_information['sales_tax_amount'];
    }

    public function calculateSalesTax($total_before_tax_and_discount)
    {
        $data = [
            'sales_tax_rate'   => settings('sales_tax_rate'),
            'sales_tax_amount' => 0,
            'enable_sales_tax' => settings('enable_sales_tax'),
        ];

        /*
        A $100 purchase with 6% sales tax and a 10% discount costs $96.
        100*.06=6.00 100*.10=10.00… 100+6–10=96.
         */
        if ($data['enable_sales_tax'] && $data['sales_tax_rate'] && ($total_before_tax_and_discount > 0)) {
            $data['sales_tax_amount'] = round(($total_before_tax_and_discount * $data['sales_tax_rate'] / 100), 2);
        }
        return $data;
    }
}
