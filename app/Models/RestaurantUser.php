<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantUser extends Model
{
    use HasFactory;


    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');

    }
}
