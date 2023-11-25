<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Tag;
use App\Models\AutomatedTag;
use App\Models\GuestTag;
use App\Models\AutomatedTagCondition;



use Illuminate\Http\Request;

class RestaurantAutomatedTagAPIController extends Controller
{

    public function getTags()
    {
        $tags = Tag::where('restaurant_id',\Auth::user()->id)->get();

        return $this->apiResponse($tags, false,('')); 

    }


    public function createAutoTag(Request $request)
    {

        //creating new tag first
        $new_tag = new Tag();

        if ( $request->localization == 'local') {
        
            $new_tag->name = $request->name . ' - ' . $request->branch_name;

        }else{

            $new_tag->name = $request->name;

        }
        

        $new_tag->restaurant_id = \Auth::user()->id;

        $new_tag->save();

        $object = new AutomatedTag();


        //get the forign tag id
        $object->tag_id = $new_tag->id;

        //get the forign tag id
        $object->based_on_tag_id = $request->based_on_tag;

        $object->based_on_rank_id = $request->based_on_rank;

        //global or local
        $object->localization = $request->localization;

        //if local add the branch
        $object->related_to_branch = $request->branch_id;

        //total visits - total order - total spent - avg spent per visit - last visit - order items - order combo
        $object->type = $request->type;


        //is recurring true or false
        $object->is_recurring = $request->recurring;

        //range from / to
        $object->range_from = $request->range_from;
        $object->range_to = $request->range_to;
            
        $object->times = $request->times;
    
        $object->product_id = $request->product_reference;

        $object->category_reference = $request->category_reference;


        $object->has_conditions = $request->has_conditions;

        $object->restaurant_id = \Auth::user()->id;

        $object->save();


        if ($request->has_conditions == 'yes' && count($request->other_conditions) > 0) {
            
            foreach ($request->other_conditions as $condition) {
                
                $new_condition = new AutomatedTagCondition ();

                $new_condition->condition_type = $condition['condition_type'];

                $new_condition->condition = $condition['condition'];

                $new_condition->type = $condition['type'];
                $new_condition->range_from = $condition['range_from'];
                $new_condition->range_to = $condition['range_to'];
                    
                $new_condition->times = $condition['times'];
            
                $new_condition->product_id = $condition['product_reference'];
        
                $new_condition->category_reference = $condition['category_reference'];

                $new_condition->automated_tag_id =  $object->id;

                $new_condition->save();
            }
        }

        return $this->apiResponse($object, false,('New automated tag added successfully')); 
        

    }

    public function updateAutoTag(Request $request)
    {

        $id = $request->id;


        $object = AutomatedTag::find($id);

        //updatiing tag name
        $tag_name = Tag::find($object->tag_id);

        if ($tag_name->name != $request->name ) {
            
            if ( $request->localization == 'local') {
        
                $tag_name->name = $request->name . ' - ' . $request->branch_name;
    
            }else{
    
                $tag_name->name = $request->name;
    
            }
            
        }

        $tag_name->save();



        //updating the automated tag

        //get the forign tag id
        $object->based_on_tag_id = $request->based_on_tag;

        $object->based_on_rank_id = $request->based_on_rank;

        //global or local
        $object->localization = $request->localization;

        //if local add the branch
        $object->related_to_branch = $request->branch_id;

        //total visits - total order - total spent - avg spent per visit - last visit - favorite item
        $object->type = $request->type;

        //is recurring true or false
        $object->is_recurring = $request->recurring;
        
        //range from / to
        $object->range_from = $request->range_from;
        $object->range_to = $request->range_to;
        
        $object->times = $request->times;
    
        $object->product_id = $request->product_reference;

        $object->category_reference = $request->category_reference;

        $object->save();

        if ($object->has_conditions == 'yes' && count($request->other_conditions) > 0 ) 
            {
                            
                $i = 0;
                foreach ($object->otherConditions as $condition) {
                   
                    $condition->condition_type = $request->other_conditions[$i]['condition_type'];

                    $condition->condition = $request->other_conditions[$i]['condition'];

                    $condition->type = $request->other_conditions[$i]['type'];
                    $condition->range_from = $request->other_conditions[$i]['range_from'];
                    $condition->range_to = $request->other_conditions[$i]['range_to'];
                        
                    $condition->times = $request->other_conditions[$i]['times'];
                
                    $condition->product_id = $request->other_conditions[$i]['product_reference'];
            
                    $condition->category_reference = $request->other_conditions[$i]['category_reference'];
    
                    $condition->save();

                    $i++;
                }
               
            
        }


        return $this->apiResponse($object, false,('Automated tag updated successfully')); 
        

    }

    public function getAutoTags()
    {
        $tags = AutomatedTag::where('restaurant_id',\Auth::user()->id)->get();

        $data = [];

        $i = 0;
        foreach ($tags as $key => $value) {
        
            $data[$i]['id'] = $value->id;

            $data[$i]['tag_name'] = $value->tag->name;

            $data[$i]['based_on_tag_id'] = '';
            
            $data[$i]['based_on_tag_name'] = '';


            if ($value->based_on_tag_id != null) {
            
                $data[$i]['based_on_tag_id'] = $value->basedTag->id;
                $data[$i]['based_on_tag_name'] = $value->basedTag->name;
                
            }
        
            $data[$i]['based_on_rank_id'] = '';
            
            $data[$i]['based_on_rank_name'] = '';


            if ($value->based_on_rank_id != null) {
            
                $data[$i]['based_on_rank_id'] = $value->basedRank->id;
                $data[$i]['based_on_rank_name'] = $value->basedRank->name;
                
            }
        

            $data[$i]['localization'] = $value->localization;
            $data[$i]['branch_id'] = $value->related_to_branch;

            $data[$i]['type'] = $value->type;

            $data[$i]['recurring'] = $value->is_recurring;

            $data[$i]['tag_type'] = $value->tag_type;
            
            $data[$i]['range_from'] = $value->range_from;
            $data[$i]['range_to'] = $value->range_to;

            $data[$i]['times'] = $value->times;

            $data[$i]['product_reference'] = $value->product_id;

            $data[$i]['category_reference'] = $value->category_reference;

            $data[$i]['has_conditions'] = $value->has_conditions;

            $data[$i]['conditions'] = [];

            if (!empty($value->otherConditions)) {
               
                $data[$i]['conditions'] = $value->otherConditions;

            }

            $tag_guests = GuestTag::where('tag_id' ,$value->tag_id)
            ->where('is_valid' , 'true')
            ->get();

            $guests_ids = [] ;

            foreach ($tag_guests as $tag_guest) {
            
                $guests_ids [] = $tag_guest->guest_id;

            }


            $data[$i]['guests_count'] = count(array_unique($guests_ids));


            $data[$i]['is_deleted'] = $value->is_deleted;

        $i++;
        }

        return $this->apiResponse($data, false,('')); 

    }


    public function deleteAutoTag(Request $request)
    {

        $id = $request->id;

        $object = AutomatedTag::find($id);

        $object->is_deleted = !($object->is_deleted);

        $object->save();

        return $this->apiResponse($object, false,('Automated tag deleted successfully!')); 

    }

    public function removeAutoTag(Request $request)
    {
        $automated_tag = AutomatedTag::find($request->id);
        
        if (!empty($automated_tag->otherConditions)) {
            
            foreach ($automated_tag->otherConditions as $condition) {
               $condition->delete();
            }
        }
        
        $tag = Tag::find($automated_tag->tag_id);

        $tag->delete();

        return $this->apiResponse('', false,('Automated tag removed successfully!')); 

    }

}
