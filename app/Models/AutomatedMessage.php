<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomatedMessage extends Model
{
    use HasFactory;

    //relations

    public function automatedMessageTags()
    {
        return $this->hasMany('App\Models\AutomatedMessageTag');

    }


    public function discountCodes()
    {
        return $this->hasMany('App\Models\DiscountCode');

    }

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');

    }

      // this is a recommended way to declare event handlers
      public static function boot() {
        parent::boot();

        static::deleting(function($autoMessage) { // before delete() method call this
            
             $autoMessage->automatedMessageTags()->delete();

             $autoMessage->discountCodes()->delete();

             // do the rest of the cleanup...
        });
    }

}
