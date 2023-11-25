<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function active()
    {
        return Tag::where('is_deleted', 0);
    }

    //relations
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function automatedTags()
    {
        return $this->hasMany('App\Models\AutomatedTag','tag_id');
    }

    public function basedTags()
    {
        return $this->hasMany('App\Models\AutomatedTag','based_on_tag_id');
    }

    public function automatedMessageTags()
    {
        return $this->hasMany('App\Models\AutomatedMessageTag');
    }

    public function guestTags()
    {
        return $this->hasMany('App\Models\GuestTag');

    }


    public static function boot() {
        parent::boot();

        static::deleting(function($deleteModel) { // before delete() method call this

             $deleteModel->guestTags()->delete();

             $deleteModel->automatedMessageTags()->delete();

             $deleteModel->basedTags()->delete();
             
             $deleteModel->automatedTags()->delete();


             // do the rest of the cleanup...
        });
    }

}
