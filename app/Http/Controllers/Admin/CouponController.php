<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CouponType;
use Illuminate\Http\Request;
use App\Models\Accounting\Coupon;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponsRequest;

class CouponController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {      

        return inertia('Admin/Coupons/Index', [
            'data'    => [
                'title' => __('Coupons'),
                'urls'  => [
                    'new_item' => route('admin.coupons.create'),
                    'search'   => route('admin.coupons.index'),
                ],
            ],
            'filters' => $request->only('filters'),
            'coupons' => Coupon::query()
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('code', 'like', '%' . $request->filters['search'] . '%');
                })
                ->when(!(($request->input('filters.inactive') == 'true')), function ($q) use ($request) {
                    return $q->active();
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
        // $data = Coupon::dropdown();
        return inertia('Admin/Coupons/Create', [
            'data' => [
                'title'              => __('Add coupon'),
                'previous_link_text' => __('Back to coupons'),
                'urls'               => [
                    'previous_page' => route('admin.coupons.index'),
                    'submit_form'   => route('admin.coupons.store'),
                ],
                'dropdowns'          => [
                    'coupon_types' => CouponType::asDropdown(),
                    'customers'    => [],
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCouponsRequest $request)
    {
        Coupon::create($request->all());
        return redirect()->route('admin.coupons.index')->withSuccess(__('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return inertia('Admin/Coupons/Create', [
            'data'            => [
                'title'              => __('Edit Coupon'),
                'previous_link_text' => __('Back to coupons'),
                'urls'               => [
                    'submit_form'   => route('admin.coupons.update', $coupon->id),
                    'previous_page' => route('admin.coupons.index'),
                ],
                'dropdowns'          => [
                    'coupon_types' => CouponType::asDropdown(),
                    'customers'    => ($coupon->specific_customer_only) ? $coupon->customer()->get() : [],
                ],
            ],
            'existing_record' => $coupon,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCouponsRequest $request, $id)
    {
        Coupon::findOrFail($id)->fill($request->all())->update();
        return redirect()->route('admin.coupons.index')->withSuccess(__('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $redirect = redirect()->route('admin.coupons.index');
        try {
            $coupon->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException$e) {
            $redirect->withFail(__('You cannot delete the item as it is associated with one or multiple orders'));
        } catch (\Exception$e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

}
