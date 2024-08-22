<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfflinePaymentMethodRequest;
use App\Models\Payments\OfflinePaymentMethod;
use Illuminate\Http\Request;

class OfflinePaymentMethodController extends Controller
{

    private function getNote()
    {
        return __("After your client submits payment request via offline methods, you have to manually approve the payment from")
        . ' ' . __("Pending Payment Approval") . ' ' . __("List");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Settings/PaymentGateways/Offline/Index', [
            'data'    => [
                'title'            => __('Offline Payment Methods'),
                'create_link_text' => __('Add New'),
                'urls'             => [
                    'new_item' => route('admin.settings.offline.payment.methods.create'),
                    'search'   => route('admin.settings.offline.payment.methods.index'),
                ],
                'note'             => $this->getNote(),
            ],
            'filters' => $request->only('filters'),
            'methods' => OfflinePaymentMethod::query()
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('name', 'like', '%' . $request->filters['search'] . '%');
                })
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Settings/PaymentGateways/Offline/Create', [
            'data' => [
                'title'              => __('Add offline payment method'),
                'previous_link_text' => __('Back to offline payment methods'),
                'urls'               => [
                    'previous_page' => route('admin.settings.offline.payment.methods.index'),
                    'submit_form'   => route('admin.settings.offline.payment.methods.store'),
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOfflinePaymentMethodRequest $request)
    {
        $paymentMethod = OfflinePaymentMethod::create($request->validated());
     
        return redirect()->route('admin.settings.offline.payment.methods.index')->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OfflinePaymentMethod $method)
    {
        return inertia('Admin/Settings/PaymentGateways/Offline/Create', [
            'data'            => [
                'title'              => __('Edit offline payment method'),
                'previous_link_text' => __('Back to offline payment methods'),
                'urls'               => [
                    'previous_page' => route('admin.settings.offline.payment.methods.index'),
                    'submit_form'   => route('admin.settings.offline.payment.methods.update', $method->id),
                ],
            ],
            'existing_record' => $method,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOfflinePaymentMethodRequest $request, $id)
    {

        OfflinePaymentMethod::find($id)->fill($request->validated())->update();
      
        return redirect()->route('admin.settings.offline.payment.methods.index')->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $redirect = redirect()->route('admin.settings.offline.payment.methods.index');
        try {
            $paymentMethod = OfflinePaymentMethod::find($id);
            $paymentMethod->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException$e) {

            $redirect->withFail(__('Cannot be deleted as it is associated with one or multiple orders'));
        } catch (\Exception$e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }
}
