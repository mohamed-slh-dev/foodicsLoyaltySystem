<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\RestaurantRank;
use App\Models\Tag;

use Illuminate\Http\Request;

class RestaurantRankAPIController extends Controller
{
    //


    public function getRanks()
    {
        $ranks = RestaurantRank::where('restaurant_id' , \Auth::user()->id)->get();

        foreach ($ranks as $rank) {
            
            $rank['name'] = $rank['name'] . $rank['branch_name'];

            $rank['branch_name'] = null;


        }

        return $this->apiResponse($ranks, false,('')); 


    }

    public function createRank(Request $request)
    {
        $object = new RestaurantRank();

        if ( $request->localization == 'local') {
        
            $object->name = $request->name;

            $object->branch_name = ' - ' . $request->branch_name;

        }else{

            $object->name = $request->name;

            $object->branch_name = null;
        }

        $object->times = $request->times;

        //global or local
        $object->localization = $request->localization;

        //if local add the branch
        $object->related_to_branch = $request->branch_id;

        $object->restaurant_id = \Auth::user()->id;

        $object->save();

        return $this->apiResponse($object, false,('New rank created successfully')); 


    }


    public function updateRank(Request $request)
    {
        $object = RestaurantRank::find($request->id);

        $rank_name = $object->name . $object->branch_name;

        $request_name = $request->name . $request->branch_name;

        if ( $rank_name != $request_name ) {
            
            if ( $request->localization == 'local') {
        
                $object->name = $request->name;
    
                $object->branch_name = ' - ' . $request->branch_name;

            }else{
    
                $object->name = $request->name;
    
                $object->branch_name = null;
            }
            
        }
        
       

        $object->times = $request->times;

        //global or local
        $object->localization = $request->localization;

        //if local add the branch
        $object->related_to_branch = $request->branch_id;

        $object->save();

        return $this->apiResponse($object, false,('Rank updated successfully')); 


    }

    public function deleteRank(Request $request)
    {
        $object = RestaurantRank::find($request->id);

        $object->is_deleted = !($object->is_deleted);

        $object->save();

        return $this->apiResponse($object, false,('Rank deleted successfully')); 


    }

    public function removeRank(Request $request)
    {
        $object = RestaurantRank::find($request->id);

        if (!empty($object->automatedTags)) {
            
            foreach ($object->automatedTags as $AutoTag) {
                
                $tag = Tag::find($AutoTag->tag_id);

                $tag->delete();

                $AutoTag->delete();

            }
        }

        $object->delete();


        return $this->apiResponse('', false,('Rank removed successfully')); 


    }

  
}
