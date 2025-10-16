<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'food_code',
        'category_id',
        'restaurant_id'
    ];
}
