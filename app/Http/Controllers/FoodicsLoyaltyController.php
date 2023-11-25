<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\DiscountCode;
use App\Models\GuestOrder;


use Illuminate\Http\Request;

class FoodicsLoyaltyController extends Controller
{
    //

    public function reward(Request $request)
    {
        
        $code = $request->reward_code;

       
        $phone = '966'.$request->customer_mobile_number;

        $reference_id = $request->business_reference;
           
        $code = DiscountCode::where('code'  , $code)
        ->where('customer_phone' , $phone)
        ->where('used_times' , 0)
        ->first();


        if (empty($code)) {

            return $this->errorResponse(__('code.invalid_code'), 404);

        }

       $code_reference = $code->automatedMessage->restaurant->reference_id;

       if ($code_reference != $reference_id ) {
          
        return $this->errorResponse(__('code.invalid_code'), 404);

       }

        $restaurant_reference = $code->restaurantDiscount->restaurant->reference_id;

        $amount = (double)$code->restaurantDiscount->amount;

        $type =  $code->restaurantDiscount->type;

        $is_percentage = ($type == 'fixed') ? false : true;

      
        $code_phone = $code->customer_phone;

        $phone = explode('966' , $code_phone );

        $customer_phone = $phone[1];

        return response()->json([
            "type"                        => 1,
            "discount_amount"             => $amount,
            "is_percent"                  => $is_percentage,
            "customer_mobile_number"      => $customer_phone,
            "mobile_country_code"         => 'SA',
            "reward_code"                 => $code->code,
            "business_reference"          => $restaurant_reference,
            "max_discount_amount"         => 1000,
            "discount_includes_modifiers" => false,
            "allowed_products"            => null,
            "is_discount_taxable"         => false

        ]);
    }


    public function redeem(Request $request)
    {

        try {

            $order = GuestOrder::where('order_id' , $request->order_id)->first();

            $code = DiscountCode::where('code' ,  $request->reward_code)->first();

            $amount = 0;

            $discount_amount = 0;

            if (!empty($order)) {
               
                if ($code->restaurantDiscount->type == 'fixed') {
               
                    $discount_amount = $order->total_amount - $code->restaurantDiscount->amount;

                    
                } else {
                    
                    $discount_amount = $order->total_amount - (($code->restaurantDiscount->amount / 100) * $order->total_amount);
                    
                }

                if ($discount_amount < 0 ) {

                    $discount_amount = 0;

                }


                $order->amount = $discount_amount;

                $order->discount_code_id = $code->id;

                $order->save();
                
            }
           
            $code->amount = $order->total_amount;

            $code->discount_amount = $discount_amount;

            $code->reward_discount = $request->discount_amount;

            $code->order_id = $order->order_id;

            $code->used_times ++;


            $code->save();

        } catch (\Exception $ex) {
            
            return $this->errorResponse(__('code.invalid_code'), 404);
        }

       
        return $this->successResponse([]);
    }


    
}
