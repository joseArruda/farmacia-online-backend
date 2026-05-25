<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventorymodel extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'stock',
        'price',
        'image'
    ];
}
