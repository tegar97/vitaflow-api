<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'midtrans_order_id',
        'user_id',
        'amount',
        'payment_url',
        'expire_time_unix',
        'expire_time_str',
        'service_name',
        'service_code',
        'payment_status',
        'payment_status_str',
        'payment_code',
        'payment_key',
        'snap_url',
    ];
}
