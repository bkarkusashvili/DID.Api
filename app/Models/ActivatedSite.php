<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivatedSite extends Model
{
    protected $fillable = ['user_id', 'site_id', 'payment_transaction_id', 'duration_month'];
}
