<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomatedTag extends Model
{
    use HasFactory;

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag' , 'tag_id');
    }

    public function basedTag()
    {
        return $this->belongsTo('App\Models\Tag', 'based_on_tag_id');
    }

    public function basedRank()
    {
        return $this->belongsTo('App\Models\RestaurantRank', 'based_on_rank_id');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function otherConditions()
    {
        return $this->hasMany('App\Models\AutomatedTagCondition');
    }

    public function otherConditionsOrderBy()
    {
        return $this->hasMany('App\Models\AutomatedTagCondition')->orderBy('condition_type' , 'ASC');
    }




    public static function boot() {
        parent::boot();

        static::deleting(function($deleteModel) { // before delete() method call this

             $deleteModel->otherConditions()->delete();

             // do the rest of the cleanup...
        });
    }
   
}
