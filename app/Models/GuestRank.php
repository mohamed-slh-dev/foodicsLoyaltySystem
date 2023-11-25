<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestRank extends Model
{
    use HasFactory;

    public function rank()
    {
        return $this->belongsTo('App\Models\RestaurantRank');

    }

    public function guest()
    {
        return $this->belongsTo('App\Models\Guest');
    }

}
