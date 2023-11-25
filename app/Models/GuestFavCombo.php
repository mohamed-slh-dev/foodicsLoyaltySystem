<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestFavCombo extends Model
{
    use HasFactory;

    public function guest()
    {
        return $this->belongsTo('App\Models\Guest');

    }
}
