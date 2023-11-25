<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Http;

use App\Services\FoodicsService;

use App\Models\Restaurant;
use App\Models\RestaurantDiscount;
use App\Models\Guest;
use App\Models\GuestTag;
use App\Models\Tag;
use App\Models\AutomatedTag;
use App\Models\AutomatedMessage;
use App\Models\AutomatedMessageTag;
use App\Models\DiscountCode;
use App\Models\UnifonicMessageRecord;
use App\Models\RestaurantUser;
use App\Models\GuestOrder;


use Illuminate\Support\Facades\Hash;


use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Carbon\Carbon;

use Illuminate\Http\Request;



class RestaurantAPIController extends Controller
{
    //


    public function restaurantLogin(Request $request)
    {
        // if found then check password (he pass)
       
        $credentials = request(['email', 'password']);


        $token = auth('restaurant-api')->attempt($credentials, ['exp' => Carbon::now()->addDays(1)->timestamp]);

        if (!$token) {
            return $this->apiResponse('', true,('email or password not correct')); 
        }

        $access = RestaurantUser::where('username' , $request->username)->first();


        if (empty($access)) {

            return $this->apiResponse('', true,('username not correct'));     
        }


        $restaurant = auth('restaurant-api')->user();

        if ($restaurant->is_deleted == 0) {
           
            $restaurant->token = $token;

            $restaurant->access = $access;
            
            return $this->apiResponse($restaurant, false,('login successfully')); 
         
        }else{

            return $this->apiResponse('', true,('Error with login')); 

        }
     
       
    }

    public function restaurantLogout(Request $request)
    {

        $token = $request -> header('token');

        if ($token) {
            JWTAuth::setToken($token)->invalidate();

            return $this->apiResponse('', false,('logged out successfully')); 
        
        }else{
            return $this->apiResponse('', true,('Something went wrong!')); 

        }
       
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////general functions///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function getGeneral()
    {
        $restaurant = Restaurant::find(\Auth::user()->id);

        return $this->apiResponse($restaurant, false,''); 

    }


    public function updateGeneral(Request $request)
    {

        $restaurant = Restaurant::find(\Auth::user()->id);

        $restaurant->name_eng = $request->name_eng;
        $restaurant->name_ar = $request->name_ar;
        $restaurant->location = $request->location;
        $restaurant->manager_name = $request->manager_name;
        $restaurant->manager_phone = $request->manager_phone;
        $restaurant->email = $request->email;


        
        if (!empty($request->password)) {
            $restaurant->password = Hash::make($request->password);
        }


        $restaurant->save();



        return $this->apiResponse($restaurant, false,('General info updated successfully')); 

    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////Branches functions///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function getBranches( FoodicsService $foodicsService)
    {

        $branches = [] ; 

        $restaurant = Restaurant::find(\Auth::user()->id);

        if (empty($restaurant->access_token)) {
           
            return $this->apiResponse( $branches , true ,'The access token is empty'); 

        }

        try {

           
            $response = $foodicsService->getBusinessBranches($restaurant->access_token);

            $response = $response->json('data');
    
            $i = 0 ;
    
            foreach ($response as $key => $value) {
    
               $branches[$i]['branch_id'] = $value['id'];
               $branches[$i]['branch_name'] = $value['name'];
    
               $i++;
    
            }

        }catch (Exception $exception){

            return $this->apiResponse( $branches , true ,$exception->getMessage()); 

        }
      


        return $this->apiResponse($branches, false,('')); 

    }
  


}
