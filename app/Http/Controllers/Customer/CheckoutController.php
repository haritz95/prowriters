<?php

namespace App\Http\Controllers\Customer;

use App\Enums\CartType;
use App\Events\NewOfflinePaymentApproveRequestEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginOrRegisterRequest;
use App\Http\Requests\ProcessOfflinePaymentRequest;
use App\Models\Payments\OfflinePaymentMethod;
use App\Models\Payments\Payment;
use App\Models\ProjectManagement\Bid;
use App\Services\CartService;
use App\Services\CouponService;
use App\Services\LoginOrRegisterService;
use App\Services\PaymentOptionsService;
use App\Services\PaymentRecordService;
use App\Services\SavedCartProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    private $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        $sub_total = $this->cart->getSubtotal();

        return inertia('Checkout/Index', [
            'data' => [
                'title'                 => __('Checkout'),
                'items'                 => $this->cart->content(),
                'sub_total'             => $sub_total,
                'total'                 => $this->cart->getTotal(),
                'coupon'                => $this->cart->getDiscountCoupon(),
                'is_logged_in'          => auth()->check(),
                'sales_tax_information' => $this->cart->calculateSalesTax($sub_total),
                'links'                 => [
                    'proceed_to_payment' => route('proceed_to_payment'),
                    'create_order'       => route('customer.tasks.create'),
                ],
                'no_item_message'       => __('You have no items in your cart'),
            ],
        ]);
    }

    public function destroyCartItem(Request $request)
    {
        $this->cart->remove($request->id);
        return redirect()->back();
    }

    public function loginOrRegister(LoginOrRegisterRequest $request, LoginOrRegisterService $loginOrRegisterService)
    {
        $loginOrRegisterService($request->validated());

        return redirect()->back();
    }

    public function verifyCoupon(Request $request, CouponService $couponService, CartService $cartService)
    {
        $request->validate([
            'coupon_code' => 'required',
        ]);

        $response = $couponService->getDiscountTotal(trim($request->coupon_code), $cartService->getSubtotal(), auth()->user());

        if (empty($response['has_error']) && $response['amount']) {

            $cartService->addDiscountCoupon($response['coupon_id'], trim($request->coupon_code), $response['amount']);

            return response()->json(['status' => 1, 'amount' => $response['amount']]);
        } else {
            return response()->json(['status' => 2, 'message' => $response['errors'][0]]);
        }
    }

    public function removeCoupon(CartService $cartService)
    {
        $cartService->removeDiscountCoupon();
    }

    public function proceedToPayment(Request $request)
    {
        return redirect()->route('choose_payment_method', ['token' => $this->cart->saveCart(CartType::ORDER, auth()->user()->id)]);
    }

    /**
     * @param Request $request
     * @param PaymentOptionsService $paymentOptions
     *
     * @return void
     */
    public function choosePaymentMethod(Request $request, PaymentOptionsService $paymentOptions)
    {
        if (!($userCart = $this->cart->getSavedCart($request->token, auth()->user()->id))) {
            abort(404);
        }

        if (($userCart->type == CartType::LOAD_WALLET)) {
            $payment_options = $paymentOptions->all($request->token);
            //if $userCart->type != CartType::LOAD_WALLET
            $show_wallet_option = false;
        } else {
            $payment_options    = $paymentOptions->getOnline($request->token);
            $show_wallet_option = true;
        }

        return inertia('Checkout/SelectPaymentMethod', [
            'data' => [
                'title'              => __('Select a payment method'),
                'total'              => $userCart->total,
                'payment_options'    => $payment_options,
                'show_wallet_option' => $show_wallet_option,
                'wallet_balance'     => format_money(auth()->user()->wallet()->balance()),
                'checkout_page_link' => route('checkout'),
                'cover_image'        => asset('images/payment.svg'),
                'token'              => $request->token,
            ],
        ]);
    }

    /*
    The following function gets executed after a successful payment
    Both online and wallet
     */
    public function paymentSuccess(Request $request)
    {
        $userCart = $this->cart->getSavedCart($request->token, auth()->user()->id);
        $task_url = null;

        if (!($userCart)) {
            return redirect()->route('customer.dashboard');
        }

        /*
        If the payment was for loading wallet balance then there will no invoice
        so take the user to payment receipt page
         */
        if ($userCart->type == CartType::LOAD_WALLET) {
            $cart    = $userCart->payment_id;
            $payment = Payment::select('uuid')->where('id', $userCart->payment_id)->get()->first();
            $this->cart->destroySavedCart($request->token);
            return redirect()->route('customer.payments.show', $payment->uuid);
        }
        /*
        For everything else there should be an invoice. So if there is no invoice id
        redirect the user to customer dashboard
         */
        if (!$userCart->invoice_id) {
            return redirect()->route('customer.dashboard');
        }

        // Destroy the cart in database
        $this->cart->destroySavedCart($request->token);
        $task_url = null;

        /*
        If the payment was made for paying for a bid then update relevant information
        to the task and bid request table in database
         */
        if ($userCart->type == CartType::PAYMENT_FOR_BID) {
            $cart = $userCart->items[0];
            Bid::handleSuccessfulPaymentForBid($cart['bid_id'], $cart['task_id']);
            $task_url = route('customer.tasks.show', $cart['task_uuid']);
        }

        return inertia('Checkout/Confirm', [
            'data' => [
                'title'    => __('Payment complete'),
                'task_url' => $task_url,
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param SavedCartProcessingService $savedCartProcessor
     *
     * @return redirect
     */
    public function handleSuccessfulOnlinePayment(Request $request, SavedCartProcessingService $savedCartProcessor)
    {
        if (!($userCart = $this->cart->getSavedCartIfPaymentIsComplete($request->token, auth()->user()->id))) {
            // Payment is not complete
            abort(400, 'Bad Request');
        }

        DB::beginTransaction();
        $success = false;
        try {

            $savedCartProcessor->onlinePayment($userCart, auth()->user());

            $success = true;
            DB::commit();
        } catch (\Exception$e) {
            $success = false;
            DB::rollback();
        }

        if ($success) {
            // the transaction worked ...
            return redirect()->route('payment.successful', ['token' => $request->token]);

        } else {

            return redirect()->route('customer.dashboard')->withFail(__('Payment has been received but the operation was not complete'));
        }

    }

    public function processWalletPayment(Request $request, SavedCartProcessingService $savedCartProcessor)
    {
        $userCart = $this->cart->getSavedCart($request->token, auth()->user()->id);

        if ($userCart->type == CartType::LOAD_WALLET) {
            return redirect()->back()->withFail(__('You can pay using your wallet only for placing orders'));
        }
        if (empty($userCart->total)) {
            return redirect()->back()->withFail(__('Your cart is empty'));
        }
        if ($userCart->total > auth()->user()->wallet()->balance()) {
            return redirect()->back()->withFail(__('You wallet doesn\'t have sufficient balance'));
        }

        DB::beginTransaction();
        $success = false;
        try {

            $savedCartProcessor->onlinePayment($userCart, auth()->user());

            $success = true;
            DB::commit();
        } catch (\Exception$e) {
            $success = false;
            DB::rollback();           
        }

        if ($success) {
            // the transaction worked ...
            return redirect()->route('payment.successful', ['token' => $request->token]);

        } else {

            return redirect()->back()->withFail(__('Sorry the request was not successful, please try again'));
        }
    }

    public function payUsingOfflineMethod(Request $request, OfflinePaymentMethod $payment_method)
    {
        if (!($userCart = $this->cart->getSavedCart($request->token, auth()->user()->id))) {
            abort(404);
        }

        return inertia('Checkout/OfflinePayment', [
            'data'    => [
                'title'  => __('Pay with') . ' ' . $payment_method->name,
                'total'  => $userCart->total,
                'config' => [
                    'allowed_file_extensions'           => '.jpg,.png,.gif,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.pdf,.zip,.rar',
                    'maximum_number_of_files_to_upload' => 5,
                    'maximum_file_size'                 => 10,
                    'urls'                              => [
                        'upload_attachment' => route('attachments.store'),
                    ],
                ],
                'urls'   => [
                    'submit_form' => route('process_pay_with_offline_method', [$payment_method->slug, 'token' => $request->token]),
                ],
            ],
            'gateway' => $payment_method,
        ]);
    }

    public function processOfflinePayment(ProcessOfflinePaymentRequest $request, OfflinePaymentMethod $payment_method, PaymentRecordService $paymentRecordService)
    {
        if (!($userCart = $this->cart->getSavedCart($request->token, auth()->user()->id))) {
            abort(404);
        }

        DB::beginTransaction();
        $success = false;
        try {

            $pending_for_approval_payment = $paymentRecordService->storeOfflinePayment($payment_method, auth()->user()->id, $userCart->total, $request->reference, $request->input('files'));
            event(new NewOfflinePaymentApproveRequestEvent($pending_for_approval_payment));
            $success = true;
            DB::commit();
        } catch (\Exception$e) {
            $success = false;
            DB::rollback();
        }

        if ($success) {
            // the transaction worked ...
            return redirect()->route('offline_payment_success', ['token' => $request->token])
                ->with('offline_payment_success', $payment_method->success_message);
        } else {

            return redirect()->back()->withFail('Sorry the request was not successful, please try again');
        }
    }

    public function offlinePaymentSuccess(Request $request)
    {
        if (empty(session()->has('offline_payment_success'))) {
            return redirect()->route(get_default_route_by_user(auth()->user()));
        }

        $this->cart->destroySavedCart($request->token);

        return inertia('Checkout/OfflinePaymentSuccess', [
            'data' => [
                'title'   => __('Information received'),
                'message' => session()->get('offline_payment_success'),
            ],
        ]);
    }

}
