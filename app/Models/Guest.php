<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');

    }

    public function orders()
    {
        return $this->hasMany('App\Models\GuestOrder');

    }


    public function guestTags()
    {
        return $this->hasMany('App\Models\GuestTag');

    }

    public function guestRanks()
    {
        return $this->hasMany('App\Models\GuestRank');

    }

  

    public function favProducts()
    {
        return $this->hasMany('App\Models\GuestFavItem');

    }

    public function favCombos()
    {
        return $this->hasMany('App\Models\GuestFavCombo');

    }

     // this is a recommended way to declare event handlers
     public static function boot() {
        parent::boot();

        static::deleting(function($deleteGuest) { // before delete() method call this
            
             $deleteGuest->guestTags()->delete();

             $deleteGuest->favProducts()->delete();

             $deleteGuest->favCombos()->delete();

             // do the rest of the cleanup...
        });
    }
   
}

