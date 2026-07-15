<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table = 'shipments';

    protected $fillable = [
        'shipment_id',
        'order_id',
        'customer_name',
        'product_name',
        'qty',
        'courier',
        'box_used',
        'tracking_number',
        'status',
        'address',
        'due_date',
    ];
}