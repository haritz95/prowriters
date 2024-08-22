<?php

namespace App\Models;

use App\Models\Billing\Bill;
use App\Models\Wallets\Wallet;
use App\Models\Payments\Payment;
use App\Models\Accounting\Invoice;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\WalletAdjustment;

class NumberGenerator extends Model
{

    public $timestamps = false;

    static function gen($generatable_type)
    {
        $obj = self::where('generatable_type', $generatable_type)->get()->first();

        if ($obj) {
            $obj->last_generated_value++;
            $generated_number = sprintf('%06d', $obj->last_generated_value);
        } else {
            $obj = new NumberGenerator();
            $obj->generatable_type = $generatable_type;
            $generated_number = "000001";
        }

        $obj->last_generated_value = $generated_number;
        $obj->save();

        return $generated_number;
        //return self::get_prefix($generatable_type) . "-" . $generated_number;
    }

    // private static function get_prefix($generatable_type)
    // {
    //     $prefix_list = [
    //         Bill::class => 'BILL',
    //         Task::class => 'TSK',
    //         Invoice::class => 'INV',
    //         Payment::class => 'PMT',
    //         Wallet::class => 'WAL',          
    //         WalletAdjustment::class => 'WLA'
    //     ];

    //     return (isset($prefix_list[$generatable_type])) ? $prefix_list[$generatable_type] : NULL;
    // }
}
