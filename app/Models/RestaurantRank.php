<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantRank extends Model
{
    use HasFactory;

    public function automatedTags()
    {
        return $this->hasMany('App\Models\AutomatedTag' , 'based_on_rank_id');

    }

    public function guestRanks()
    {
        return $this->hasMany('App\Models\GuestRank' , 'rank_id');

    }


    public static function boot() {
        parent::boot();

        static::deleting(function($deleteModel) { // before delete() method call this

             $deleteModel->guestRanks()->delete();
             
             $deleteModel->automatedTags()->delete();


             // do the rest of the cleanup...
        });
    }
}
