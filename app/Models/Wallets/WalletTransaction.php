<?php

namespace App\Models\Wallets;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{

    /**
     * Get the type
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function type(): Attribute
    {
        return Attribute::make(
            get:fn($value) => self::translateJargon($value),
        );
    }

    public function wallet()
    {
        return $this->belongsTo('App\Models\Wallets\Wallet');
    }

    public function relatedTable()
    {
        return $this->morphTo('transactionable')->withTrashed();
    }

    public static function translateJargon($key)
    {
        $jargons = [
            // Types
            'deposit'           => __('Deposit'),
            'spent'             => __('Spent'),
            'refund'            => __('Refund'),
            'award'             => __('Award'),
            // Related Models
            'invoice'           => __('Invoice'),
            'payment'           => __('Payment'),
            'wallet_adjustment' => __('Wallet Adjustment'),

        ];
        return isset($jargons[$key]) ? $jargons[$key] : '';
    }

    public static function getReferenceLinkForAdmin($transaction)
    {

        switch ($transaction->transactionable_type) {
            case 'invoice':
                return route('admin.invoices.show', $transaction->relatedTable->uuid);
                break;
            case 'payment':
                return route('admin.payments.show', $transaction->relatedTable->uuid);
                break;
            case 'wallet_adjustment':
                return route('admin.walletAdjustments.show', $transaction->relatedTable->uuid);
                break;
            default:
                return null;
                break;
        }
    }

}
