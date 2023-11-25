<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestOrderDetail extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo('App\Models\GuestOrder');

    }
}
