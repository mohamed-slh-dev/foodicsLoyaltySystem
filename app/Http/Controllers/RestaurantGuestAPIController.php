<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Guest;
use App\Models\AutomatedTag;
use App\Models\GuestOrder;

use Illuminate\Http\Request;

class RestaurantGuestAPIController extends Controller
{
    public function createGuest(Request $request)
    {

        $opject = new Guest();

        $opject->customer_name = $request->name;
        $opject->customer_phone = $request->phone;

        $opject->customer_birthdate = $request->birthdate;
        $opject->customer_email = $request->email;
        $opject->customer_gender = $request->gender;

       // $opject->customer_language = $request->language;
       
        $opject->restaurant_id = \Auth::user()->id;

        $opject->save();


        return $this->apiResponse($opject, false,('New Guest added successfully')); 
        

    }

    public function updateGuest(Request $request)
    {
        $opject =  Guest::find($request->id);

        $opject->customer_name = $request->name;

        $opject->save();

        return $this->apiResponse($opject, false,('Guest name updated successfully')); 

    }

    public function getGuests()
    {
        $guests_data = Guest::where('restaurant_id',\Auth::user()->id)->get();


        $guests = [];

        $i = 0;

        foreach ($guests_data as $guest) {

            $guests[$i]['id'] = $guest->id;
            $guests[$i]['customer_id'] = $guest->customer_id;
            $guests[$i]['customer_name'] = $guest->customer_name;
            $guests[$i]['customer_phone'] = $guest->customer_phone;
            $guests[$i]['customer_email'] = $guest->customer_email;
            $guests[$i]['customer_birthdate'] = $guest->customer_birthdate;
            $guests[$i]['customer_gender'] = $guest->customer_gender;
            $guests[$i]['restaurant_id'] = $guest->restaurant_id;

            $guests[$i]['visits_count'] = $guest->orders->count();
            $guests[$i]['spend_amount'] = $guest->orders->sum('total_amount');

            $guests[$i]['orders_count'] = 0;

            foreach ($guest->orders as $order) {

                $guests[$i]['orders_count'] += $order->orderDetails->count();
               
            }

            $guests[$i]['tags'] = [];
          


            $t = 0;
            foreach ($guest->guestTags->where('is_valid' , 'true') as $tag) {

                $guests[$i]['tags'][$t] ['id'] = $tag->tag->id;
                $guests[$i]['tags'][$t] ['name'] = $tag->tag->name;
              
                $t++;
            }

            $guests[$i]['ranks'] = [];
            $r = 0;
            foreach ($guest->guestRanks->where('is_valid' , 'true') as $rank) {

                $guests[$i]['ranks'][$r] ['id'] = $rank->id;
                $guests[$i]['ranks'][$r] ['name'] = $rank->name;
              
                $t++;
            }

            $i++;
        }
        return $this->apiResponse($guests, false,('')); 

    }



    public function getGuestTags(Request $request)
    {
        $guest = Guest::find($request->id);

        $data = [];


        foreach ($guest->guestRanks->where('is_valid' , 'true') as $guestRank) {
           
            $data['ranks'][]['name'] = $guestRank->rank->name;
 
        }

      
        foreach ($guest->guestTags->where('is_valid' , 'true') as $guestTag) {
           
            $data['tags'][]['tag_name']  = $guestTag->tag->name;
 
        }

        
            //get all automated tage with type total visits for restaurant
            $auto_tags = AutomatedTag::where('type','last visit')
            ->where('restaurant_id',\Auth::user()->id)
            ->get();

            $guest_last_visit = GuestOrder::where('guest_id' , $guest->id)->orderBy('id' , 'DESC')->first();

            $today = date('Y-m-d');

            $last_visit = date('Y-m-d', strtotime($guest_last_visit->created_at));;

            
            $difference = strtotime($today) - strtotime($last_visit);

            //Calculate difference in days
            $days = abs($difference/(60 * 60)/24);

            foreach ($auto_tags as $key => $value) {
                
       
                //check each condition from auto tags
                $check_tags = AutomatedTag::where('type','last visit')
                ->where('range_from' , '<=' , $days)
                ->where('restaurant_id',\Auth::user()->id)
                ->get();
        
                
                if (!empty($check_tags)) {
        
                    foreach ($check_tags as $tag) {
        
                        $data['tags'][]['tag_name']  = $tag->tag->name;
        
                    }
                 
        
                }

            }

        return $this->apiResponse($data, false,('')); 


    }


    public function getGuestOrders (Request $request)
    {
        $guest = Guest::find($request->id);

        $orders = [] ;

        if (empty($guest->orders)) {
           
            return $this->apiResponse($orders, false,('')); 

        }
        
        $i = 0;

        foreach ($guest->orders->where('amount' , '!=' , null) as $value) {
            
            $orders[$i]['id'] = $value->id;

            $orders[$i]['guest_name'] = $value->guest->customer_name;
            $orders[$i]['guest_phone'] = $value->guest->customer_phone;

            $orders[$i]['order_id'] = $value->order_id;
            $orders[$i]['reference_id'] = $value->reference_id;
            $orders[$i]['branch_id'] = $value->branch_id;
            $orders[$i]['branch_name'] = $value->branch_name;
            $orders[$i]['total_amount'] = $value->amount;
            $orders[$i]['amount'] = $value->total_amount;

            $orders[$i]['created_at'] = $value->created_at;

            $i++;
        }

        return $this->apiResponse($orders, false,('')); 

    }

    public function getGuestProducts(Request $request)
    {
        $guest = Guest::find($request->id);

        $products = [];

        if (!empty($guest->favProducts)) {

            $products = $guest->favProducts;
        }
      

        return $this->apiResponse($products, false,('')); 


    }
    

    public function getGuestCombos(Request $request)
    {
        $guest = Guest::find($request->id);

        $combos = [];

        if (!empty($guest->favCombos)) {
           
            $combos = $guest->favCombos;

        }

        return $this->apiResponse($combos, false,('')); 


    }
}
