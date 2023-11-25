<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    public function restaurantDiscount()
    {
        return $this->belongsTo('App\Models\RestaurantDiscount');

    }

    public function automatedMessage()
    {
        return $this->belongsTo('App\Models\AutomatedMessage');

    }

    public function guestOrder()
    {
        return $this->hasOne('App\Models\GuestOrder');

    }


        // this is a recommended way to declare event handlers
        public static function boot() {
            parent::boot();
    
            static::deleting(function($deleteModel) { // before delete() method call this
                
                 $deleteModel->guestOrder()->delete();
    
                 // do the rest of the cleanup...
            });
        }

}
