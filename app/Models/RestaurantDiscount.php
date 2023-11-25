<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantDiscount extends Model
{
    use HasFactory;

    public function codes()
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

        static::deleting(function($deleteModel) { // before delete() method call this
            
             $deleteModel->codes()->delete();

             // do the rest of the cleanup...
        });
    }


}
