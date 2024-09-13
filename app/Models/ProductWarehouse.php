<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductWarehouse extends Pivot
{
    protected $table = 'product_warehouse';

    protected $fillable = [
        'product_id', 'warehouse_id', 'quantity'
    ];
}
