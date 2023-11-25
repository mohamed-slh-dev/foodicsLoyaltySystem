<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomatedMessageTag extends Model
{
    use HasFactory;

    //relations

    public function automatedMessage()
    {
        return $this->belongsTo('App\Models\AutomatedMessage');
    }


    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }

   
}
