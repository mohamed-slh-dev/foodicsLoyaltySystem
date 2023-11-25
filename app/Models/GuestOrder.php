<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestOrder extends Model
{
    use HasFactory;

    public function guest()
    {
        return $this->belongsTo('App\Models\Guest');

    }

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');

    }

    public function discountCode()
    {
        return $this->belongsTo('App\Models\DiscountCode');

    }

    public function orderDetails()
    {
        return $this->hasMany('App\Models\GuestOrderDetail');

    }


        // this is a recommended way to declare event handlers
        public static function boot() {
            parent::boot();
    
            static::deleting(function($deleteModel) { // before delete() method call this
                
                 $deleteModel->orderDetails()->delete();
    
                 // do the rest of the cleanup...
            });
        }


}
