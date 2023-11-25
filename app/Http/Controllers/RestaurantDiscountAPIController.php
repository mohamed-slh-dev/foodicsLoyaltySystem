<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\RestaurantDiscount;

use Illuminate\Http\Request;

class RestaurantDiscountAPIController extends Controller
{
    public function createCoupon(Request $request)
    {

        $object = new RestaurantDiscount();

        $object->name = $request->name;
        $object->description = $request->description;
        $object->type = $request->type;
        $object->amount = $request->amount;

        $object->restaurant_id = \Auth::user()->id;

        $object->save();


        return $this->apiResponse($object, false,('New discount coupon added successfully')); 
        

    }

    public function updateCoupon(Request $request)
    {

        $object = RestaurantDiscount::find($request->id);

        $object->description = $request->description;
        $object->type = $request->type;
        $object->amount = $request->amount;

        $object->save();


        return $this->apiResponse($object, false,('Discount coupon updated successfully')); 
        

    }


    public function getCoupons()
    {
        $tags = RestaurantDiscount::where('restaurant_id',\Auth::user()->id)->get();

        $discounts = [];

        $i = 0;

        foreach ($tags as $key => $value) {
        
            $discounts[$i]['id'] = $value->id;

            $discounts[$i]['name'] = $value->name;
            $discounts[$i]['description'] = $value->description;
            $discounts[$i]['type'] = $value->type;
            $discounts[$i]['amount'] = $value->amount;
            $discounts[$i]['is_deleted'] = $value->is_deleted;

            $discounts[$i]['number_of_codes'] = $value->codes->count();
            $discounts[$i]['used_codes'] = $value->codes->where('used_times' , 1)->count();
            $discounts[$i]['unused_codes'] = $value->codes->where('used_times' , 0)->count();

            $y = 0 ;
            foreach ($value->codes as $code) {
            
                $discounts[$i]['customers'][$y]['code'] = $code->code;
                $discounts[$i]['customers'][$y]['customer_phone'] = $code->customer_phone;
                $discounts[$i]['customers'][$y]['used_times'] = $code->used_times;
            
            
                $y++;
            }


            $i++;

        }
        return $this->apiResponse($discounts, false,('')); 

    }

    public function deleteCoupon(Request $request)
    {

        $id = $request->id;

        $object = RestaurantDiscount::find($id);

        $object->is_deleted =  !($object->is_deleted);

        $object->save();

        return $this->apiResponse($object, false,('Coupon deleted successfully!')); 

    }


    public function removeCoupon(Request $request)
    {

        $object = RestaurantDiscount::find($request->id);

        if (!empty($object->codes)) {
           
            foreach ($object->codes as $code) {

                if (!empty($code->guestOrder)) {
                   
                    $code->guestOrder->delete();

                }
            }
        }
        $object->delete();


        return $this->apiResponse('', false,('Coupon removed successfully!')); 

    }

}
