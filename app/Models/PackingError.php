<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackingError extends Model
{
    /**
     * packing_errors lives on the default connection (same as orders),
     * NOT the "inventory" connection — unlike PackingMaterial.
     */
    protected $table = 'packing_errors';

    protected $fillable = [
        'order_id',
        'material',
        'reason',
    ];
}
