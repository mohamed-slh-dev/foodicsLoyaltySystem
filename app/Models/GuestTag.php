<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestTag extends Model
{
    use HasFactory;


    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }

    public function guest()
    {
        return $this->belongsTo('App\Models\Guest');
    }
}
