<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTransaction extends Model
{
    use HasFactory;

    // Define the fillable attributes that can be mass assigned
    protected $fillable = [
        'subscription_id',
        'subscription_product_id',
        'payment_id',
        'duration_months',
        'product_id',
        'chosen_payment_option',
    ];
}
