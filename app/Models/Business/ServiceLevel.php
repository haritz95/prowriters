<?php

namespace App\Models\Business;

use App\Enums\PriceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'is_default',
        'price',
        'name',
        'description',
    ];

    static function priceTypeAsDropdown()
    {
        return [
            ['id' => PriceType::FIXED, 'name' => __('Fixed')],
            ['id' => PriceType::PERCENTAGE, 'name' => __('Percent of Order Total')],
        ];
    }
}
