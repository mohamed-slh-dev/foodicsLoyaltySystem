<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\Restaurant;

use App\Models\GuestOrder;
use App\Models\GuestOrderDetail;

class RestaurantOrderAPIController extends Controller
{
    //

    public function getOrders()
    {
        $restaurant = Restaurant::find(\Auth::user()->id);

        $orders = [];

       $restaurant_orders = $restaurant->orders;

       if (empty($restaurant_orders)) {
            
        return $this->apiResponse($orders, false,''); 
      
        }

       $i = 0;
        foreach ($restaurant_orders as $key => $value) {
           
            $orders[$i]['id'] = $value->id;

            $orders[$i]['guest_name'] = $value->guest->customer_name;
            $orders[$i]['guest_phone'] = $value->guest->customer_phone;

            $orders[$i]['order_id'] = $value->order_id;
            $orders[$i]['reference_id'] = $value->reference_id;
            $orders[$i]['branch_id'] = $value->branch_id;
            $orders[$i]['branch_name'] = $value->branch_name;
            $orders[$i]['total_amount'] = $value->total_amount;
            $orders[$i]['created_at'] = $value->created_at;


            $i++;
        }

            return $this->apiResponse($orders, false,''); 
             
    }

    public function getOrderDetails(Request $request)
    {
        $order = GuestOrder::find($request->id);

        $details = [];

        if (empty($order->orderDetails)) {
            
            return $this->apiResponse($details, false,''); 
          
            }

        $i = 0;

        foreach ($order->orderDetails as $key => $value) {
           
            $details[$i]['type'] = $value->type;
            $details[$i]['combo_id'] = $value->combo_id;

            $details[$i]['combo_sku'] = $value->combo_sku;
            $details[$i]['combo_name'] = $value->combo_name;
            $details[$i]['product_id'] = $value->product_id;
            $details[$i]['product_name'] = $value->product_name;
            $details[$i]['product_sku'] = $value->product_sku;
            $details[$i]['category_reference'] = $value->category_reference;

            $details[$i]['product_price'] = $value->product_price;
            $details[$i]['quantity'] = $value->quantity;
           
            $i++;
        }

        return $this->apiResponse($details, false,''); 


    }

}
