<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestFavItem extends Model
{
    use HasFactory;

    public function guest()
    {
        return $this->belongsTo('App\Models\Guest');

    }
    
}
