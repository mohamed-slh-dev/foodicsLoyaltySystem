<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantMessageRequest extends Model
{
    use HasFactory;

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');

    }
}
