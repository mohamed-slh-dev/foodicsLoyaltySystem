<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Restaurant;
use App\Models\RestaurantUser;
use App\Models\RestaurantDiscount;
use App\Models\RestaurantMessageRequest;
use App\Models\AutomatedTag;
use App\Models\DiscountCode;
use App\Models\UnifonicMessageRecord;
use App\Models\GuestOrder;



use Illuminate\Support\Facades\Hash;

class AdminRestaurantController extends Controller
{
    //

    public function restaurants()
        {
            $restaurants = Restaurant::paginate(10);

            return view('restaurants',compact('restaurants'));
        }
    
    public function createRestaurant(Request $request)
        {
            $rest = new Restaurant();

            $rest->name_eng = $request->name_eng;
            $rest->name_ar = $request->name_ar;

            $rest->manager_name = $request->manager_name;
            $rest->manager_phone = $request->manager_phone;
            $rest->manager_email = $request->manager_email;

            $rest->has_branch = $request->branch;
            $rest->type = $request->type;

            $rest->online_ordering_pickup = $request->pickup;
            $rest->online_ordering_delivery = $request->delivery;

            $rest->email = $request->email;
            $rest->password = Hash::make($request->password);

            $rest->save();

            $user = new RestaurantUser();

            $user->username = $request->username;
            $user->name = $request->username;
            $user->access_level = 'Admin';

            $user->restaurant_id = $rest->id;

            $user->save();

            return redirect()->back()->with('success','New Resaturant Added Successfully');
        }

    public function deleteRestaurant($id)
    {
        $rest = Restaurant::find($id);

        $rest->is_deleted = !($rest->is_deleted);

        $rest->save();

        return redirect()->back()->with('success','Resaturant Disabled Successfully');

    }

    public function resetRestaurantPassword(Request $request)
    {
        $rest = Restaurant::find($request->id);

        $rest->password = Hash::make($request->password);

        $rest->save();

        return redirect()->back()->with('success','Resaturant Password Reset Successfully');

    }

    public function restaurantAddQuota(Request $request)
    {
        $restaurant = Restaurant::find($request->id);

        $restaurant->number_of_messages += $request->quota;

        $restaurant->save();


        return redirect()->back()->with('success','Resaturant Messages Quota Added Successfully');

    }


   

    public function updateRequestStatus(Request $request)
    {
        $msg_request = RestaurantMessageRequest::find($request->id);

        $msg_request->status = $request->status;

        $msg_request->save();


        return redirect()->back()->with('success','Request Status Updated Successfully');

    }


    public function restaurantSenderName(Request $request)
    {
        $rest = Restaurant::find($request->id);

        $rest->sender_name = $request->sender;

        $rest->save();


        return redirect()->back()->with('success','Restaurant Sender Name Updated Successfully');

    }


    public function restaurantUpdateModules(Request $request)
    {
        $rest = Restaurant::find($request->id);

        
        $rest->online_ordering_pickup = $request->pickup;
        $rest->online_ordering_delivery = $request->delivery;
        $rest->returntion = $request->returntion;

        $rest->save();


        return redirect()->back()->with('success','Restaurant Sender Name Updated Successfully');

    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////Restaurants Pages /////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function restaurantsMessagesRequests()
    {
        $requests = RestaurantMessageRequest::paginate(10);

        return view('messages-requests',compact('requests'));
    }

    public function tags()
    {
        $restaurants = Restaurant::paginate(10);

        $tags = AutomatedTag::all();

        return view('tags',compact('restaurants' , 'tags'));
    }
    

    public function codes()
    {
        $restaurants = Restaurant::paginate(10);

        $codes = DiscountCode::all();

        return view('codes',compact('restaurants' , 'codes'));
    }

    public function messages()
    {
        $restaurants = Restaurant::paginate(10);

        $messages = UnifonicMessageRecord::all();

        return view('messages',compact('restaurants' , 'messages'));
    }

    public function orders()
    {
        $restaurants = Restaurant::paginate(10);

        $orders = GuestOrder::all();

        return view('orders',compact('restaurants' , 'orders'));
    }


   
}
