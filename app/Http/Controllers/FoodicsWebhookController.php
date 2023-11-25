<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Http;

use App\Models\Restaurant;
use App\Models\Guest;
use App\Models\GuestOrder;
use App\Models\GuestOrderDetail;
use App\Models\GuestTag;
use App\Models\AutomatedTag;
use App\Models\AutomatedTagCondition;
use App\Models\AutomatedMessage;
use App\Models\AutomatedMessageTag;
use App\Models\RestaurantDiscount;
use App\Models\DiscountCode;
use App\Models\UnifonicMessageRecord;
use App\Models\GuestFavItem;
use App\Models\GuestFavCombo;
use App\Models\RestaurantRank;
use App\Models\GuestRank;





class FoodicsWebhookController extends Controller
{
    //

    public $total_orders;

    public function webhook(Request $request)
    {
        $event =$request->event;


        if ($event == "customer.order.created") {

            //order created method
            $this->orderCreated($request);

        } elseif($event == "customer.order.updated") {

            //order updated method
            $this->orderUpdated($request);
           
        }else{
            
            return;
        }


    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //order created event
    public function orderCreated(Request $request)
    {
        $order_status = $request->input('order.status');

        //if order returned
        if ($order_status === 5 ) {
           return;

        }//new order created status = 4

        else{

            $reference_id = $request->input('business.reference');
            
            //get the restaurant refrenec
            $restaurant = Restaurant::where('reference_id' , $reference_id)->first();

            if (empty($restaurant)) {

                $restaurant_id = null;

            }else{
                
                $restaurant_id = $restaurant->id;
            }

            $dialCode = $request->input('order.customer.dial_code') ?: '966';

            $customer_phone =  $dialCode . $request->input('order.customer.phone');

            //check customer if new or exist
            $guest = $this->checkGuest($customer_phone , $restaurant_id);

            $guest->customer_id = $request->input('order.customer.id');
            $guest->customer_name = $request->input('order.customer.name');

            $dialCode = $request->input('order.customer.dial_code') ?: '966';
            $guest->customer_phone = $dialCode . $request->input('order.customer.phone');

            $guest->customer_email = $request->input('order.customer.email');
            $guest->customer_gender = $request->input('order.customer.gender');
            $guest->customer_birthdate = $request->input('order.customer.birth_date');

          
           

            $guest->restaurant_id = $restaurant_id;

            $guest->save();

            //creating the order record
            $order = new GuestOrder();

            $order->timestamp = $request->input('timestamp');
            $order->event = $request->input('event');

            $order->reference_id = $request->input('business.reference');

            $order->branch_id = $request->input('order.branch.id');
            $order->branch_name = $request->input('order.branch.name');

            $order->total_amount = $request->input('order.total_price');

            $order->order_id = $request->input('order.id');

            $order->guest_id = $guest->id;

            $order->restaurant_id = $restaurant_id;

            $order->save();


          
            $products = collect($request->input('order.products'));

            $combo_products = collect($request->input('order.combos'));
            

            //start to check each automated tag
            
            ///calculate the total orders
            $total_orders =  $products->count() + $combo_products->count();

            $total_amount = $request->input('order.total_price');

            

            //order branch for localization tags
            $branch_id = $request->input('order.branch.id');


            foreach ($products as $key => $value) {

                $order_details = new GuestOrderDetail;

                $order_details->type = 'product';


                $order_details->guest_order_id = $order->id;

                $order_details->product_id = $value['product']['id'];
                $order_details->product_sku = $value['product']['sku'];
                $order_details->product_name = $value['product']['name'];

                $order_details->category_name = $value['product']['category']['name'];
                $order_details->category_reference = $value['product']['category']['reference'];

                $order_details->product_price = $value['unit_price'];
                $order_details->quantity = $value['quantity'];

                $order_details->save();

                $item_id = $value['product']['sku'];
                $item_name = $value['product']['name'];



                $category_reference =  $value['product']['category']['reference'];


                $this->checkFavItem($item_id , $item_name ,$guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                $this->checkCategoryReference($category_reference, $guest, $restaurant_id , $branch_id, $total_orders , $total_amount);


            }

            
            foreach ($combo_products as $combo) {

                foreach ($combo['products'] as $key => $value) {

                    $order_details = new GuestOrderDetail;

                    $order_details->type = 'combo';

                    $order_details->combo_id = $combo['combo_size']['combo']['id'];
                    $order_details->combo_sku = $combo['combo_size']['combo']['sku'];
                    $order_details->combo_name = $combo['combo_size']['combo']['name'];

                    $order_details->guest_order_id = $order->id;
                  
                    $order_details->product_id = $value['product']['id'];
                    $order_details->product_sku = $value['product']['sku'];
                    $order_details->product_name = $value['product']['name'];
                    
                    $order_details->product_price = $value['unit_price'];
                    $order_details->quantity = $value['quantity'];
    
                    $order_details->save();

                    $combo_id = $combo['combo_size']['combo']['sku'];
                    $combo_name = $combo['combo_size']['combo']['name'];


                    $this->checkFavCombo($combo_id, $combo_name, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);


                }


            }// end of foreach combo
   


             //check total visits ranks condition
             $this->checkRanks($guest,$restaurant_id , $branch_id);


             //check total visits condition
             $this->checkTotalVisits($guest,$restaurant_id , $branch_id, $total_orders , $total_amount);
 
             //check total visits condition
             $this->checkTotalOrders($guest,$restaurant_id , $branch_id, $total_orders , $total_amount);
 
 
 
             //check total spend condition
             $this->checkTotalSpend($guest,$restaurant_id , $branch_id, $total_orders , $total_amount);
 
             //check total spend average condition
             $this->checkAverageSpend($guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

        }//end of order created status = 4



      

    }//end of order created event

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //check guest method
    public function checkGuest($phone , $restaurant_id)
    {
        $guest = Guest::where('customer_phone', $phone)
        ->where('restaurant_id' , $restaurant_id )
        ->first();

        if (!empty($guest)) {

           return $guest;

        }else{

            $guest = new Guest();

            return $guest;
        }

    }//end of check guest method

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function checkRanks($guest,$restaurant_id, $branch_id)
    {

        //get the guest total visits
        $guest_total_visits = $guest->orders->count();
                
        //get all ranks that equal the guest total visits for restaurant
        $ranks = RestaurantRank::where('times' , '<=' , $guest_total_visits)
        ->where('restaurant_id',$restaurant_id)
        ->where('is_deleted', 0)
        ->get();
        
        foreach ($ranks as $rank) {

        if ($rank->localization == 'local') {

            $guest_total_visits = GuestOrder::where('guest_id' , $guest->id)
            ->where('branch_id' , $branch_id)
            ->count();
            
        }
        
            
            if ($guest_total_visits >= $rank->times) {

                //assign rank guest
                $this->assignRankToGuest($rank,$guest);

            }   

        }
        

    }//end of check guest ranks


    public function assignRankToGuest($rank,$guest)
    {

        $restaurant_id = $guest->restaurant_id;

        $guest_rank = GuestRank::where('guest_id', $guest->id)
        ->where('rank_id' , $rank->id)
        ->first();

        if (empty($guest_rank)) {
           
            $this->deletePreviousRanks($rank, $guest, $restaurant_id);

            $guest_rank = new GuestRank();
            
            $guest_rank->guest_id = $guest->id;
            $guest_rank->rank_id = $rank->id;
    
            $guest_rank->save();

        }
      


    }

    public function deletePreviousRanks($rank, $guest, $restaurant_id)
    {
       $restaurantRanks = RestaurantRank::where('restaurant_id',$restaurant_id)
       ->get();

       $ranks = [] ;

       foreach ($restaurantRanks as $key => $value) {
           
        $ranks [] = $value->id;

       }

       $guest_ranks = GuestRank::whereIn('rank_id' , $ranks)->where('guest_id' , $guest->id)->get();

       foreach ($guest_ranks as $key => $value) {

            $guest_rank = GuestRank::find($value->id);

            $guest_rank->is_valid = 'false';

            $guest_rank->save();

       }

    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 public function checkTotalVisits($guest,$restaurant_id , $branch_id, $total_orders , $total_amount)
 {
    
     //get the guest total visits
     $guest_total_visits = $guest->orders->count();


     //get all automated tage with type total visits for restaurant
     $auto_tags = AutomatedTag::where('type','total visits')
     ->where('times' , '<=' , $guest_total_visits)
     ->where('restaurant_id',$restaurant_id)
     ->where('is_deleted', 0)
     ->get();


     
     foreach ($auto_tags as $tag) {

        if ($tag->localization == 'local') {

            $guest_total_visits = GuestOrder::where('guest_id' , $guest->id)
            ->where('branch_id' , $branch_id)
            ->count();
            
        }
       

        
        if ($tag->times > 0) {
           
            if ($guest_total_visits % $tag->times  == 0) {

                if ($tag->based_on_tag_id != null || $tag->based_on_rank_id != null) {
                   
                    $based_tag = $this->checkBasedTag($tag, $guest);
                    
                    $based_rank = $this->checkBasedRank($tag, $guest);

                   
                    if ($based_tag == true &&  $based_rank == true) {
        
                        if ($tag->has_conditions == 'yes') {
                            
                            $guest_tag = GuestTag::where('guest_id', $guest->id)
                            ->where('tag_id' , $tag->tag_id)
                            ->first();
                        
                            $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;
                               
                            $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                            if ($check_conditions) {
                                
                                //check if the guest has the tag already
                                $this->ifGuestHasTag($tag,$guest);

                            }

                        }else{

                            //check if the guest has the tag already
                            $this->ifGuestHasTag($tag,$guest);
                        }
                       
                    }

                     
                }else{
    
                    if ($tag->has_conditions == 'yes') {
                            
                        $guest_tag = GuestTag::where('guest_id', $guest->id)
                        ->where('tag_id' , $tag->tag_id)
                        ->first();

                        $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                        $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                        if ($check_conditions) {
                            
                            //check if the guest has the tag already
                            $this->ifGuestHasTag($tag,$guest);

                        }
                    }else{

                        //check if the guest has the tag already
                        $this->ifGuestHasTag($tag,$guest);
                    }
                }
               
    
            }else{

                if ($tag->has_conditions == 'yes') {
                            
                    $assign = false;

                    $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                    if ($check_conditions) {
                        
                        //check if the guest has the tag already
                        $this->ifGuestHasTag($tag,$guest);

                    }
                }
            }



        }
          

     }
     

 }//end of check guest total visits

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  public function checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount)
  {
      foreach ($tag->otherConditionsOrderBy as $condition) {
                     
        if ($condition->type == 'total visits') {
            
            //get the guest total visits
            $guest_total_visits = $guest->orders->count();

            if ($tag->localization == 'local') {

                $guest_total_visits = GuestOrder::where('guest_id' , $guest->id)
                ->where('branch_id' , $branch_id)
                ->count();
                
            }

            $check = AutomatedTagCondition::where('automated_tag_id', $tag->id)
            ->where('type' , 'total visits')
            ->where('times', $condition->condition , $guest_total_visits)
            ->get();

            if ($check) {
               
                if ($condition->condition_type == 'or') {
                    
                    $assign = ($assign || true);

                }else{

                    $assign = ($assign && true);
                }
            }else{

                if ($condition->condition_type == 'or') {
                    
                    $assign = ($assign || false);

                }else{

                    $assign = ($assign && false);
                }
            }


        }//end of total visits

        elseif ($condition->type == 'total order') {
            
            if ($total_orders >= $condition->range_from && $total_orders <= $condition->range_to ) 
            {
                
                if ($condition->condition_type == 'or') {
                    
                    $assign = ($assign || true);

                }else{

                    $assign = ($assign && true);
                }

            }else{

                if ($condition->condition_type == 'or') {
                    
                    $assign = ($assign || false);

                }else{

                    $assign = ($assign && false);
                }
            }

        }//end of total order


        elseif ($condition->type == 'total spend') {
            
          

            if ($total_amount >= $condition->range_from && $total_amount <= $condition->range_to ) 
            {
                
                if ($condition->condition_type == 'or') 
                {
                    
                    $assign = ($assign || true);

                }else{

                    $assign = ($assign && true);
                }

            }else{

                if ($condition->condition_type == 'or') {
                    
                    $assign = ($assign || false);

                }else{

                    $assign = ($assign && false);
                }
            }

        }//end of total spend

        elseif($condition->type == 'average spend')
        {
            $orders_count = $guest->orders->count();
    
            $total_spend = GuestOrder::where('guest_id',$guest->id)->sum('total_amount');
        
            $average_spend = $total_spend / $orders_count;

            if ($tag->localization == 'local') {
            
                $orders_count = $guest->orders->count();

                $total_spend = GuestOrder::where('guest_id',$guest->id)
                ->where('branch_id' , $branch_id)
                ->sum('total_amount');
            
                $average_spend = $total_spend / $orders_count;

            } 

            if ($average_spend >= $condition->range_from && $average_spend <= $condition->range_to ) 
            {
                
                if ($condition->condition_type == 'or') {
                    
                    $assign = ($assign || true);

                }else{

                    $assign = ($assign && true);
                }
            }else{

                if ($condition->condition_type == 'or') {
                    
                    $assign = ($assign || false);

                }else{

                    $assign = ($assign && false);
                }
            }

        
        }//end of total average spend


        elseif ($condition->type == 'order product') {
            
 
               
                $guest_orders = $guest->orders;

                $guest_orders_ids = [];
    
                //geting all guest order 
                foreach ($guest_orders as $key => $value) {
    
                    $guest_orders_ids [] = $value->id;
    
                }
    
                $item_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
                ->where('type', 'product')
                ->where('product_sku', $condition->product_id)
                ->count();


                if ($tag->localization == 'local') {
                   
                    $guest_orders = $guest->orders->where('branch_id' , $tag->related_to_branch);

                    $guest_orders_ids = [];

                    foreach ($guest_orders as $order) {

                        $guest_orders_ids [] = $order->id;
        
                    }
        
                    $item_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
                    ->where('type', 'product')
                    ->where('product_sku', $condition->product_id)
                    ->count();

                }

                $check = AutomatedTagCondition::where('automated_tag_id', $tag->id)
                ->where('type' , 'order product')
                ->where('times', $condition->condition , $item_count)
                ->get();

                if ($check) {
                    
                    if ($condition->condition_type == 'or') {
                    
                        $assign = ($assign || true);
    
                    }else{
    
                        $assign = ($assign && true);
                    }

                }else{

                    if ($condition->condition_type == 'or') {
                        
                        $assign = ($assign || false);
    
                    }else{
    
                        $assign = ($assign && false);
                    }
                }

        }//end of order product
        
        elseif ($condition->type == 'order combo')
        {
               
                $guest_orders = $guest->orders;

                $guest_orders_ids = [];
    
                //geting all guest order 
                foreach ($guest_orders as $key => $tag) {
    
                    $guest_orders_ids [] = $tag->id;
    
                }
    
                $combo_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
                ->where('type', 'combo')
                ->where('combo_sku', $condition->product_id)
                ->count();

                if ($tag->localization == 'local') {
                   
                    $guest_orders = $guest->orders->where('branch_id' , $tag->related_to_branch);

                    $guest_orders_ids = [];

                    foreach ($guest_orders as $order) {

                        $guest_orders_ids [] = $order->id;
        
                    }
        
                    $combo_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
                    ->where('type', 'combo')
                    ->where('combo_sku', $condition->product_id)
                    ->count();

                }

                
                $check = AutomatedTagCondition::where('automated_tag_id', $tag->id)
                ->where('type' , 'order combo')
                ->where('times', $condition->condition , $combo_count)
                ->get();

                if ($check) {
                    
                    if ($condition->condition_type == 'or') {
                    
                        $assign = ($assign || true);
    
                    }else{
    
                        $assign = ($assign && true);
                    }
                }else{

                    if ($condition->condition_type == 'or') {
                        
                        $assign = ($assign || false);
    
                    }else{
    
                        $assign = ($assign && false);
                    }
                }

          

        }//end of order combo

        elseif($condition->type == 'order category')
        {
            foreach ($category_reference_array as $caregory_reference) {
                
                 $guest_orders = $guest->orders;

                $guest_orders_ids = [];

                //geting all guest order 
                foreach ($guest_orders as $order) {

                    $guest_orders_ids [] = $order->id;

                }

                $item_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
                ->where('type', 'product')
                ->where('category_reference', $condition->category_reference)
                ->count();

                if ($tag->localization == 'local') {
                    
                    $guest_orders = $guest->orders->where('branch_id' , $tag->related_to_branch);

                    $guest_orders_ids = [];

                    foreach ($guest_orders as $order) {

                        $guest_orders_ids [] = $order->id;
        
                    }
        
                    $item_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
                    ->where('type', 'product')
                    ->where('category_reference', $condition->category_reference)
                    ->count();

                }

                $check = AutomatedTagCondition::where('automated_tag_id', $tag->id)
                ->where('type' , 'order category')
                ->where('times', $condition->condition , $item_count)
                ->get();

                if ($check) {
                   
                    if ($condition->condition_type == 'or') {
                    
                        $assign = ($assign || true);
    
                    }else{
    
                        $assign = ($assign && true);
                    }

                }

            }
           


        }//end of order category

      }//end of foreach $tag->otherConditions

     
      return $assign;

  }//end ofcheckTagOtherConditions



 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function checkBasedTag($tag, $guest)
    {

        $check_guest_based_tag = GuestTag::where('guest_id' , $guest->id)
        ->where('tag_id' , $tag->based_on_tag_id)
        ->where('is_valid' , 'true')
        ->first();

        if (!empty($check_guest_based_tag) || $tag->based_on_tag_id == null) {

           return true;

        } else {
           
           return false;

        }
        

    }



    public function checkBasedRank($tag, $guest)
    {

        $check_guest_based_rank = GuestRank::where('guest_id' , $guest->id)
        ->where('rank_id' , $tag->based_on_rank_id)
        ->where('is_valid' , 'true')
        ->first();

        
        if (!empty($check_guest_based_rank) || $tag->based_on_rank_id == null) {

           return true;

        } else {
           
           return false;

        }
        

    }

 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function deletePreviousTags($tag, $guest, $restaurant_id)
    {
       $type_tags = AutomatedTag::where('type' , $tag->type)
       ->where('restaurant_id',$restaurant_id)
       ->get();

       $tags = [] ;

       foreach ($type_tags as $key => $value) {
           
        $tags [] = $value->tag_id;

       }

       $guest_tags = GuestTag::whereIn('tag_id' , $tags)->where('guest_id' , $guest->id)->get();

       foreach ($guest_tags as $key => $value) {

            $guest_tag = GuestTag::find($value->id);

            $guest_tag->is_valid = 'false';

            $guest_tag->save();

       }

    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


 public function ifGuestHasTag($tag , $guest)
 {

    $guest_id = $guest->id;

    $restaurant_id = $guest->restaurant_id;

    
    $guest_tag = GuestTag::where('guest_id', $guest_id)
    ->where('tag_id' , $tag->tag_id)
    ->first();

  

        if (!empty($guest_tag) && $tag->is_recurring == 'false') {

            
           return;

        }else{

            if ($tag->type == 'total visits') {
               
                //delete all previous tags for total visits
                $this->deletePreviousTags($tag , $guest ,$restaurant_id );

            }
          


            //assign new tag to guest
            $new_guest_tag = new GuestTag();
            
            $new_guest_tag->tag_id = $tag->tag_id;
            $new_guest_tag->guest_id = $guest_id;

            $new_guest_tag->save();

            $tag_id = $tag->tag_id;

            
            $this->ifTagHasAutomatedMessage($tag_id,$guest);

            return;
        }


 }//end of if guest has tags

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


 public function ifTagHasAutomatedMessage($tag_id, $guest)
 {
     $get_auto_msg_tag = AutomatedMessageTag::where('tag_id' , $tag_id)->first();

     //check if tag has auto message
     if (!empty($get_auto_msg_tag)) {

     $auto_msg = AutomatedMessage::where('id',$get_auto_msg_tag->automated_message_id)
     ->where("is_deleted" , 0)
     ->first();

     if (empty($auto_msg)) {

        return;

     }
    
     $body = $auto_msg->body;

     $message_id = $auto_msg->id;

     $message_body = $this->setMessageBody($message_id , $body , $guest);

     $this->sendMessage($message_body,$guest);

     }else{

         return;
     }

     

 }//end of ifTagHasAutomatedMessage

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 public function setMessageBody($message_id , $body , $guest)
 {

    $name = $guest->customer_name;

    $find_name = explode("{{name}}", $body);

    //check if body has {{name}} on it
    if (count($find_name) > 1) {

        $message_body = $find_name[0]. $name . $find_name[1];

    }else{

        $message_body = $find_name[0];
    }

    $find_code = explode("{", $message_body);

    //check if body has {{code}} on it
    if (count($find_code) > 1) {


        $code_name = $this->getCodeName($find_code[2]);


        $coupon = RestaurantDiscount::where('name','LIKE','%'.$code_name.'%')->first();


        if (!empty($coupon)) {
          
            $guest_code = new DiscountCode();

            $guest_code->automated_message_id = $message_id;

            $guest_code->restaurant_discount_id = $coupon->id;

            $random_code = random_int(100000, 999999);

            $code = $this->checkIfCodeExist($random_code);
    
            $guest_code->code = $code;
    
            $guest_code->customer_phone = $guest->customer_phone;
    
    
            $guest_code->save();
    
            
            //get the text after code
            $end_message =  $exp_1 = explode("}}",$find_code[2]);

            if (count($end_message) > 1) {

                $message_body = $find_code[0]. $code . $end_message[1];

            }else{

                $message_body = $find_code[0]. $code;

            }

        }else{

            $message_body = $find_code[0]. $find_code[2];

        }
       

    }else{

        $message_body = $find_code[0];
    }


    return $message_body;

 }//end of setMessageBody

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
    public function getCodeName($code)
    {

        $exp_1 = explode("}}",$code);

        $name = $exp_1[0];

        return $name;

    }//end of get code name



    public function checkIfCodeExist($code)
    {
        $check_code = DiscountCode::where('code' , $code)->first();

        if (!empty($check_code)) {

            $new_code = random_int(100000, 999999);

            $code =  $this->checkIfCodeExist($new_code);

        }

        return $code;

       
    }

 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 

    public function checkTotalOrders($guest,$restaurant_id , $branch_id, $total_orders , $total_amount)
    {

     //get all automated tage with type total visits for restaurant
     $auto_tags = AutomatedTag::where('type','total order')
     ->where('range_from' , '<=' , $total_orders)
     ->where('range_to' , '>=' , $total_orders)
     ->where('restaurant_id',$restaurant_id)
     ->where('is_deleted', 0)
     ->get();


     foreach ($auto_tags as $key => $value) {

       
        if ($value->localization == 'local' ) {

            //check each condition from auto tags
            $check_tags = AutomatedTag::where('type','total order')
            ->where('range_from' , '<=' , $total_orders)
            ->where('range_to' , '>=' , $total_orders)
            ->where('related_to_branch',$branch_id)
            ->where('restaurant_id',$restaurant_id)
            ->where('is_deleted', 0)
            ->get();
            
        }else{
           
         //check each condition from auto tags
        $check_tags = AutomatedTag::where('type','total order')
        ->where('range_from' , '<=' , $total_orders)
        ->where('range_to' , '>=' , $total_orders)   
        ->where('restaurant_id',$restaurant_id)
        ->where('is_deleted', 0)
        ->get();
        
        }

     


        if (!empty($check_tags)) {

            foreach ($check_tags as $tag) {

                if ($tag->based_on_tag_id != null || $tag->based_on_rank_id != null) {
                   
                    $based_tag = $this->checkBasedTag($tag, $guest);
                    
                    $based_rank = $this->checkBasedRank($tag, $guest);

                   
                    if ($based_tag == true &&  $based_rank == true) {
        
                        if ($tag->has_conditions == 'yes') {
                            
                            $assign = true;
                            $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                            if ($check_conditions) {
                                
                                //check if the guest has the tag already
                                $this->ifGuestHasTag($tag,$guest);

                            }

                        }else{

                            //check if the guest has the tag already
                            $this->ifGuestHasTag($tag,$guest);
                        }
                       
                    }

                     
                }else{
    
                    if ($tag->has_conditions == 'yes') {
                            
                        $assign = true;
                        $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                        if ($check_conditions) {
                            
                            //check if the guest has the tag already
                            $this->ifGuestHasTag($tag,$guest);

                        }
                    }else{

                        //check if the guest has the tag already
                        $this->ifGuestHasTag($tag,$guest);
                    }
                }
                
            }
         

        }else{

            $assign = false;
            $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

            if ($check_conditions) {
                
                //check if the guest has the tag already
                $this->ifGuestHasTag($tag,$guest);

            }
         }
        
     }

    }//end of checkTotalOrders

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function checkTotalSpend($guest,$restaurant_id , $branch_id, $total_orders , $total_amount)
    {

     //get all automated tage with type total spend for restaurant
     $auto_tags = AutomatedTag::where('type','total spend')
     ->where('range_from' , '<=' , $total_amount)
     ->where('range_to' , '>=' , $total_amount)
     ->where('restaurant_id',$restaurant_id)
     ->where('is_deleted', 0)
     ->get();


     foreach ($auto_tags as $key => $value) {


        if ($value->localization == 'local') {

             //check each condition from auto tags
            $check_tags = AutomatedTag::where('type','total spend')
            ->where('range_from' , '<=' , $total_amount)
            ->where('range_to' , '>=' , $total_amount) 
            ->where('related_to_branch',$branch_id)
            ->where('restaurant_id',$restaurant_id)
            ->where('is_deleted', 0)
            ->get();

            
        }else{

              //check each condition from auto tags
            $check_tags = AutomatedTag::where('type','total spend')
            ->where('range_from' , '<=' , $total_amount)
            ->where('range_to' , '>=' , $total_amount)
            ->where('restaurant_id',$restaurant_id)
            ->where('is_deleted', 0)
            ->get();
        }

       


        if (!empty($check_tags)) {

            foreach ($check_tags as $tag) {

                if ($tag->based_on_tag_id != null || $tag->based_on_rank_id != null) {
                   
                    $based_tag = $this->checkBasedTag($tag, $guest);
                    
                    $based_rank = $this->checkBasedRank($tag, $guest);

                   
                    if ($based_tag == true &&  $based_rank == true) {
        
                        if ($tag->has_conditions == 'yes') {
                            
                            $assign = true;
                            $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                            if ($check_conditions) {
                                
                                //check if the guest has the tag already
                                $this->ifGuestHasTag($tag,$guest);

                            }

                        }else{

                            //check if the guest has the tag already
                            $this->ifGuestHasTag($tag,$guest);
                        }
                       
                    }

                     
                }else{
    
                    if ($tag->has_conditions == 'yes') {
                            
                        $assign = true;
                        $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                        if ($check_conditions) {
                            
                            //check if the guest has the tag already
                            $this->ifGuestHasTag($tag,$guest);

                        }
                    }else{

                        //check if the guest has the tag already
                        $this->ifGuestHasTag($tag,$guest);
                    }
                }

            }
         

        }else{

            $assign = false;
            $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

            if ($check_conditions) {
                
                //check if the guest has the tag already
                $this->ifGuestHasTag($tag,$guest);

            }

        }
     }

    }
 

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function checkAverageSpend($guest, $restaurant_id, $branch_id, $total_orders, $total_amount)
    {

        $orders_count = $guest->orders->count();

        $total_spend = GuestOrder::where('guest_id',$guest->id)->sum('total_amount');
    
        $average_spend = $total_spend / $orders_count;

        //get all automated tage with type total spend for restaurant
        $auto_tags = AutomatedTag::where('type','average spend')
        ->where('range_from' , '<=' , $average_spend)
        ->where('range_to' , '>=' , $average_spend) 
        ->where('restaurant_id',$restaurant_id)
        ->where('is_deleted', 0)
        ->get();

  


    foreach ($auto_tags as $key => $value) {

    
        if ($value->localization == 'local') {
           
            $orders_count = $guest->orders->count();

            $total_spend = GuestOrder::where('guest_id',$guest->id)
            ->where('branch_id' , $branch_id)
            ->sum('total_amount');
        
            $average_spend = $total_spend / $orders_count;

        } 
    
        //check each condition from auto tags
        $check_tags = AutomatedTag::where('type','average spend')
        ->where('range_from' , '<=' , $average_spend)
        ->where('range_to' , '>=' , $average_spend) 
        ->where('restaurant_id',$restaurant_id)
        ->where('is_deleted', 0)
        ->get();


        if (!empty($check_tags)) {

            foreach ($check_tags as $tag) {

                if ($tag->based_on_tag_id != null || $tag->based_on_rank_id != null) {
                   
                    $based_tag = $this->checkBasedTag($tag, $guest);
                    
                    $based_rank = $this->checkBasedRank($tag, $guest);

                   
                    if ($based_tag == true &&  $based_rank == true) {
        
                        if ($tag->has_conditions == 'yes') {
                            
                            $assign = true;
                            $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                            if ($check_conditions) {
                                
                                //check if the guest has the tag already
                                $this->ifGuestHasTag($tag,$guest);

                            }

                        }else{

                            //check if the guest has the tag already
                            $this->ifGuestHasTag($tag,$guest);
                        }
                       
                    }

                     
                }else{
    
                    if ($tag->has_conditions == 'yes') {
                            
                        $assign = true;
                        $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                        if ($check_conditions) {
                            
                            //check if the guest has the tag already
                            $this->ifGuestHasTag($tag,$guest);

                        }
                    }else{

                        //check if the guest has the tag already
                        $this->ifGuestHasTag($tag,$guest);
                    }
                }
            }
        }
        else
        {
            $assign = false;
            $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

            if ($check_conditions) {
                
                //check if the guest has the tag already
                $this->ifGuestHasTag($tag,$guest);

            }
        }
    }

    }//end of checkAverageSpend

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function checkFavItem($item_id , $item_name, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount) 
    {

            $guest_orders = $guest->orders;

            $guest_orders_ids = [];

            //geting all guest order 
            foreach ($guest_orders as $key => $value) {

                $guest_orders_ids [] = $value->id;

            }

            $item_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
            ->where('type', 'product')
            ->where('product_sku', $item_id)
            ->count();

          

             //get all automated tage with type total spend for restaurant
            $auto_tags = AutomatedTag::where('type','order product')
            ->where('restaurant_id',$restaurant_id)
            ->where('is_deleted', 0)
            ->get();


        foreach ($auto_tags as $tag) {

                if ($tag->localization == 'local') {
                   
                    $guest_orders = $guest->orders->where('branch_id' , $tag->related_to_branch);

                    $guest_orders_ids = [];

                    foreach ($guest_orders as $order) {

                        $guest_orders_ids [] = $order->id;
        
                    }
        
                    $item_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
                    ->where('type', 'product')
                    ->where('product_sku', $item_id)
                    ->count();

                }

            if ($tag->times > 0) { 

                if ($item_count % $tag->times  == 0) {
                    
                    if ($tag->product_id == null ) {
                       
                        if ($tag->based_on_tag_id != null  || $tag->based_on_rank_id != null) {
                   
                                $based_tag = $this->checkBasedTag($tag, $guest);
                    
                                $based_rank = $this->checkBasedRank($tag, $guest);

                             if ($based_tag == true &&  $based_rank == true) {
                               
                                if ($tag->has_conditions == 'yes') {
                            
                                   
                                    $guest_tag = GuestTag::where('guest_id', $guest->id)
                                    ->where('tag_id' , $tag->tag_id)
                                    ->first();
            
                                    $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;
                                    
                                    $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                                    if ($check_conditions) {
                                       
                                         //create new guest fav item
                                        $this->assignGuestFavItem($item_id , $item_name , $guest);

                                        //check if the guest has the tag already
                                        $this->ifGuestHasTag($tag,$guest);
                                    }
                                }else{

                                     //create new guest fav item
                                     $this->assignGuestFavItem($item_id , $item_name , $guest);

                                     //check if the guest has the tag already
                                     $this->ifGuestHasTag($tag,$guest);
                                }
                                 
            
                            }
            
                        } else {
                           
                            if ($tag->has_conditions == 'yes') {
                            
                                $guest_tag = GuestTag::where('guest_id', $guest->id)
                                ->where('tag_id' , $tag->tag_id)
                                ->first();
        
                                $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                                $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                                if ($check_conditions) {
                                   
                                     //create new guest fav item
                                    $this->assignGuestFavItem($item_id , $item_name , $guest);

                                    //check if the guest has the tag already
                                    $this->ifGuestHasTag($tag,$guest);
                                }
                            }else{

                                 //create new guest fav item
                                 $this->assignGuestFavItem($item_id , $item_name , $guest);

                                 //check if the guest has the tag already
                                 $this->ifGuestHasTag($tag,$guest);
                            }
            
                        }

                        


                    } else {

                        if ($tag->product_id == $item_id) {

                            if ($tag->based_on_tag_id != null  || $tag->based_on_rank_id != null) {
                   
                                $based_tag = $this->checkBasedTag($tag, $guest);
                    
                                $based_rank = $this->checkBasedRank($tag, $guest);

                             if ($based_tag == true &&  $based_rank == true) {
                                   
                                    if ($tag->has_conditions == 'yes') {
                                
                                        $guest_tag = GuestTag::where('guest_id', $guest->id)
                                        ->where('tag_id' , $tag->tag_id)
                                        ->first();
                
                                        $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                                        $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                                        if ($check_conditions) {
                                        
                                            //create new guest fav item
                                            $this->assignGuestFavItem($item_id , $item_name , $guest);

                                            //check if the guest has the tag already
                                            $this->ifGuestHasTag($tag,$guest);
                                        }
                                    }else{

                                        //create new guest fav item
                                        $this->assignGuestFavItem($item_id , $item_name , $guest);

                                        //check if the guest has the tag already
                                        $this->ifGuestHasTag($tag,$guest);
                                    }
                
                                }
                
                            } else {
                               
                                if ($tag->has_conditions == 'yes') {
                            
                                    $guest_tag = GuestTag::where('guest_id', $guest->id)
                                    ->where('tag_id' , $tag->tag_id)
                                    ->first();
            
                                    $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                                    $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                                    if ($check_conditions) {
                                       
                                         //create new guest fav item
                                        $this->assignGuestFavItem($item_id , $item_name , $guest);

                                        //check if the guest has the tag already
                                        $this->ifGuestHasTag($tag,$guest);
                                    }
                                }else{

                                     //create new guest fav item
                                     $this->assignGuestFavItem($item_id , $item_name , $guest);

                                     //check if the guest has the tag already
                                     $this->ifGuestHasTag($tag,$guest);
                                }
                
                            }


                        }

                    }
                    

                  }else{

                    $assign = false;
                    $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                    if ($check_conditions) {
                       
                         //create new guest fav item
                        $this->assignGuestFavItem($item_id , $item_name , $guest);

                        //check if the guest has the tag already
                        $this->ifGuestHasTag($tag,$guest);
                    }
                  }

            }


               
        }

    }//end of checkFavItem

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function assignGuestFavItem($item_id , $item_name , $guest)
    {
        $fav_item = GuestFavItem::where('guest_id', $guest->id)
        ->where('item_id' , $item_id)
        ->first();
    
            if (!empty($fav_item)) {
    
               return;
    
            }else{
    
                //assign new tag to guest
                $new_fav_item = new GuestfavItem();
                
                $new_fav_item->guest_id = $guest->id;
                $new_fav_item->item_id = $item_id;
                $new_fav_item->item_name = $item_name;

    
                $new_fav_item->save();
        
                return;
            }
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  public function checkFavCombo($combo_id , $combo_name , $guest,$restaurant_id , $branch_id, $total_orders , $total_amount) 
    {

            $guest_orders = $guest->orders;

            $guest_orders_ids = [];

            //geting all guest order 
            foreach ($guest_orders as $key => $tag) {

                $guest_orders_ids [] = $tag->id;

            }

            $combo_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
            ->where('type', 'combo')
            ->where('combo_sku', $combo_id)
            ->count();

             //get all automated tage with type order combo for restaurant
            $auto_tags = AutomatedTag::where('type','order combo')
            ->where('restaurant_id',$restaurant_id)
            ->where('is_deleted', 0)
            ->get();


        foreach ($auto_tags as $tag) {

            
                if ($tag->localization == 'local') {
                   
                    $guest_orders = $guest->orders->where('branch_id' , $tag->related_to_branch);

                    $guest_orders_ids = [];

                    foreach ($guest_orders as $order) {

                        $guest_orders_ids [] = $order->id;
        
                    }
        
                    $combo_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
                    ->where('type', 'combo')
                    ->where('combo_sku', $combo_id)
                    ->count();

                }

            if ($tag->times > 0) { 

                if ($combo_count % $tag->times  == 0) {
                    
                    if ($tag->product_id == null) {
                       
                        if ($tag->based_on_tag_id != null || $tag->based_on_rank_id != null) {
                   
                            $based_tag = $this->checkBasedTag($tag, $guest);
                    
                            $based_rank = $this->checkBasedRank($tag, $guest);

                         if ($based_tag == true &&  $based_rank == true) {
                               
                            if ($tag->has_conditions == 'yes') {
                                
                                $guest_tag = GuestTag::where('guest_id', $guest->id)
                                ->where('tag_id' , $tag->tag_id)
                                ->first();
        
                                $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                                $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                                if ($check_conditions) {
                                    //create new guest fav combo
                                    $this->assignGuestFavCombo($combo_id , $combo_name , $guest);

                                    //check if the guest has the tag already
                                    $this->ifGuestHasTag($tag,$guest);
                                }
                            }else{

                                 //create new guest fav combo
                                 $this->assignGuestFavCombo($combo_id , $combo_name , $guest);

                                 //check if the guest has the tag already
                                 $this->ifGuestHasTag($tag,$guest);
                            }
                               
                
                            }
            
                        } else {
                           
                            if ($tag->has_conditions == 'yes') {
                                
                                $guest_tag = GuestTag::where('guest_id', $guest->id)
                                ->where('tag_id' , $tag->tag_id)
                                ->first();
        
                                $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                                $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                                if ($check_conditions) {
                                    //create new guest fav combo
                                    $this->assignGuestFavCombo($combo_id , $combo_name , $guest);

                                    //check if the guest has the tag already
                                    $this->ifGuestHasTag($tag,$guest);
                                }
                            }else{

                                 //create new guest fav combo
                                 $this->assignGuestFavCombo($combo_id , $combo_name , $guest);

                                 //check if the guest has the tag already
                                 $this->ifGuestHasTag($tag,$guest);
                            }
            
                        }

                         


                    } else {

                        if ($tag->product_id == $combo_id) {
                           
                            if ($tag->based_on_tag_id != null || $tag->based_on_rank_id != null) {
                   
                                $based_tag = $this->checkBasedTag($tag, $guest);
                    
                                $based_rank = $this->checkBasedRank($tag, $guest);

                             if ($based_tag == true &&  $based_rank == true) {
                                   
                                if ($tag->has_conditions == 'yes') {
                                
                                    $guest_tag = GuestTag::where('guest_id', $guest->id)
                                    ->where('tag_id' , $tag->tag_id)
                                    ->first();
            
                                    $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                                    $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);
    
                                    if ($check_conditions) {
                                        //create new guest fav combo
                                        $this->assignGuestFavCombo($combo_id , $combo_name , $guest);
    
                                        //check if the guest has the tag already
                                        $this->ifGuestHasTag($tag,$guest);
                                    }
                                }else{
    
                                     //create new guest fav combo
                                     $this->assignGuestFavCombo($combo_id , $combo_name , $guest);
    
                                     //check if the guest has the tag already
                                     $this->ifGuestHasTag($tag,$guest);
                                }
                    
                                }
                
                            } else {
                               
                                if ($tag->has_conditions == 'yes') {
                                
                                    $guest_tag = GuestTag::where('guest_id', $guest->id)
                                    ->where('tag_id' , $tag->tag_id)
                                    ->first();
            
                                    $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                                    $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);
    
                                    if ($check_conditions) {
                                        //create new guest fav combo
                                        $this->assignGuestFavCombo($combo_id , $combo_name , $guest);
    
                                        //check if the guest has the tag already
                                        $this->ifGuestHasTag($tag,$guest);
                                    }
                                }else{
    
                                     //create new guest fav combo
                                     $this->assignGuestFavCombo($combo_id , $combo_name , $guest);
    
                                     //check if the guest has the tag already
                                     $this->ifGuestHasTag($tag,$guest);
                                }
                
                            }

                        }

                    }
                    

                  }else{

                    $assign = false;
                    $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                    if ($check_conditions) {
                        //create new guest fav combo
                        $this->assignGuestFavCombo($combo_id , $combo_name , $guest);

                        //check if the guest has the tag already
                        $this->ifGuestHasTag($tag,$guest);
                    }
                  }
            }
            
        }

    }//end of checkFavCombo

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function assignGuestFavCombo($combo_id , $combo_name , $guest)
    {
        $fav_combo = GuestFavCombo::where('guest_id', $guest->id)
        ->where('combo_id' , $combo_id)
        ->first();
    
            if (!empty($fav_combo)) {
    
               return;
    
            }else{
    
                //assign new tag to guest
                $new_fav_combo = new GuestfavCombo();
                
                $new_fav_combo->guest_id = $guest->id;
                $new_fav_combo->combo_id = $combo_id;
                $new_fav_combo->combo_name = $combo_name;

    
                $new_fav_combo->save();
        
                return;
            }
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function checkCategoryReference($caregory_reference, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount) 
    {

            $guest_orders = $guest->orders;

            $guest_orders_ids = [];

            //geting all guest order 
            foreach ($guest_orders as $order) {

                $guest_orders_ids [] = $order->id;

            }

            $item_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
            ->where('type', 'product')
            ->where('category_reference', $caregory_reference)
            ->count();

          

             //get all automated tage with type total spend for restaurant
            $auto_tags = AutomatedTag::where('type','order category')
            ->where('restaurant_id',$restaurant_id)
            ->where('is_deleted', 0)
            ->get();


        foreach ($auto_tags as $key => $tag) {

                if ($tag->localization == 'local') {
                   
                    $guest_orders = $guest->orders->where('branch_id' , $tag->related_to_branch);

                    $guest_orders_ids = [];

                    foreach ($guest_orders as $order) {

                        $guest_orders_ids [] = $order->id;
        
                    }
        
                    $item_count = GuestOrderDetail::whereIn('guest_order_id', $guest_orders_ids)
                    ->where('type', 'product')
                    ->where('category_reference', $caregory_reference)
                    ->count();

                }
                
            if ($tag->times > 0) { 

                if ($item_count % $tag->times  == 0) {

                        if ($tag->category_reference == $caregory_reference) {

                            if ($tag->based_on_tag_id != null || $tag->based_on_rank_id != null) {
                   
                                $based_tag = $this->checkBasedTag($tag, $guest);
                    
                                $based_rank = $this->checkBasedRank($tag, $guest);

                             if ($based_tag == true &&  $based_rank == true) {

                                    if ($tag->has_conditions == 'yes') {
                                    
                                        $guest_tag = GuestTag::where('guest_id', $guest->id)
                                        ->where('tag_id' , $tag->tag_id)
                                        ->first();
                
                                        $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                                        $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);
        

                                        if ($check_conditions) {

                                            //check if the guest has the tag already
                                            $this->ifGuestHasTag($tag,$guest);

                                        }
                                    }else{

                                        //check if the guest has the tag already
                                        $this->ifGuestHasTag($tag,$guest);
                                    }
                                     
                                }
                
                            } else {
                               
                                if ($tag->has_conditions == 'yes') {
                                
                                    $guest_tag = GuestTag::where('guest_id', $guest->id)
                                    ->where('tag_id' , $tag->tag_id)
                                    ->first();
            
                                    $assign = (!empty($guest_tag) && $tag->is_recurring == 'false')? false : true ;

                                    $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);
    
                                    if ($check_conditions) {

                                        //check if the guest has the tag already
                                        $this->ifGuestHasTag($tag,$guest);

                                    }
                                }else{

                                    //check if the guest has the tag already
                                    $this->ifGuestHasTag($tag,$guest);
                                }
                
                            }


                        }

                    

                  }else{

                    $assign = false;
                    $check_conditions = $this->checkTagOtherConditions($tag, $assign, $guest,$restaurant_id , $branch_id, $total_orders , $total_amount);

                    if ($check_conditions) {

                        //check if the guest has the tag already
                        $this->ifGuestHasTag($tag,$guest);

                    }
                  }

            }


               
        }

    }//end of checkCategoryReference
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


  public function sendMessage($message_body,$guest)
    {
      

        $phone = $guest->customer_phone;

        $restaurant = Restaurant::find($guest->restaurant_id);

        $sender = $restaurant->sender_name;

        $num_of_messages = $restaurant->number_of_messages;

        if ($num_of_messages < 1 ) {
            return;
        }

      

        $res = Http::post('https://el.cloud.unifonic.com/rest/SMS/messages', [
            'AppSid' => env('UNIFONIC_APP_ID'),
            'SenderID' => $sender,
            'Body' => $message_body,
            'Recipient' => $phone,
        ]);

      

        if ($res->object()->success == true) {

            $num_of_messages = $restaurant->number_of_messages -- ;

            $restaurant->save();

            $msg = new UnifonicMessageRecord ();

            $msg->success =  'true';
            $msg->message =  $res->object()->message;//replace with message body
            $msg->status =  $res->object()->Status;
            $msg->error_code =  $res->object()->errorCode;
    
            $msg->message_id =  $res->object()->data->MessageID;
            $msg->message_status =  $res->object()->data->Status;
            $msg->number_of_units =  $res->object()->data->NumberOfUnits;
            $msg->cost =  $res->object()->data->Cost;
            $msg->balance =  $res->object()->data->Balance;
            $msg->recipient =  $res->object()->data->Recipient;
            $msg->time_created =  $res->object()->data->TimeCreated;
            $msg->restaurant_id =   $restaurant->id;
    
            $msg->save();

            
            
        }else{

            $msg = new UnifonicMessageRecord ();

            $msg->success =  'false';
            $msg->message =  $res->object()->message;
            $msg->status =  $res->object()->Status;
            $msg->error_code =  $res->object()->errorCode;

            $msg->restaurant_id =   $restaurant->id;

            $msg->save();


        }

        
    }//end of send message 

}
