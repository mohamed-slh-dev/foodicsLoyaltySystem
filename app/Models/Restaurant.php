<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Restaurant extends Authenticatable implements JWTSubject
{
    use HasFactory;

     // table name
     protected $table = 'restaurants';

    //active (not deleted)
    public function active()
    {
        return Restaurant::where('is_deleted', 0);
    }

    //relations
   

    public function messagesRequests()
    {
        return $this->hasMany('App\Models\RestaurantMessageRequest');

    }


    public function guests()
    {
        return $this->hasMany('App\Models\Guest');

    }

    public function messages()
    {
        return $this->hasMany('App\Models\UnifonicMessageRecord');

    }

    public function autoMessages()
    {
        return $this->hasMany('App\Models\AutomatedMessage');

    }

    public function tags()
    {
        return $this->hasMany('App\Models\Tag');

    }

    public function autoTags()
    {
        return $this->hasMany('App\Models\AutomatedTag');

    }


    public function discounts()
    {
        return $this->hasMany('App\Models\RestaurantDiscount');

    }


    public function orders()
    {
        return $this->hasMany('App\Models\GuestOrder');

    }

    public function restaurantUsers()
    {
        return $this->hasMany('App\Models\RestaurantUser');

    }
 


  

    //login token JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
