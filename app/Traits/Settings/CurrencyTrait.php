<?php

namespace App\Traits\Settings;

use App\Models\Setting;
use Illuminate\Http\Request;

trait CurrencyTrait
{

    public function currency()
    {
        return inertia('Admin/Settings/Currency', [
            'records' => $this->getRecords([
                'currency_symbol',
                'currency_code',
                'digit_grouping_method',
                'decimal_symbol',
                'thousand_separator',
                'currency_position',
                'currency_precision',
            ]),
            'data'    => [
                'title'     => __('Currency'),
                'dropdowns' => Setting::currency_dropdown(),
                'urls'      => [
                    'submit_form' => route('admin.settings.currency.update'),
                ],

            ],
        ]);
    }

    public function updateCurrency(Request $request)
    {
        $this->saveRecords($request->validate([
            'currency_symbol'       => 'required',
            'currency_code'         => 'required|size:3',
            'digit_grouping_method' => 'required',
            'decimal_symbol'        => 'required',
            'thousand_separator'    => 'required',
            'currency_position'     => 'required',
            'currency_precision'    => 'required',
        ]));

        return redirect()->back()->withSuccess(__('Successfully updated'));
    }

}
