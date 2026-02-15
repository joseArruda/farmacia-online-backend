<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory;

class Cart extends Model
{
    protected $fillable = [
        'id_product',
        'quantity'
    ];

    public function product()
{
    return $this->belongsTo(Inventory::class, 'id_product');
}
}