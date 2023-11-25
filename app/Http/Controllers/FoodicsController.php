<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Restaurant;

use App\Services\FoodicsService;
use Exception;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;

class FoodicsController extends Controller
{
    //

    public function register(Request $request, FoodicsService $foodicsService)
    {
        try {

            $rest = new Restaurant();

            $rest->name_eng = $request->name_eng;
            $rest->name_ar = $request->name_ar;
            $rest->email = $request->email;
            $rest->password = Hash::make($request->password);
            
            $rest->manager_phone = $request->manager_phone;
            $rest->manager_name = $request->manager_name;

            $rest->location = $request->location;

          
            $rest->save();
        }catch (Exception $exception){

            return $this->apiResponse('', true,$exception->getMessage()); 

        }

            return $this->apiResponse($rest, false,'Restaurant register successfully'); 
    }


    public function registerWithCode(Request $request, FoodicsService $foodicsService)
    {
        if(empty($request->code || $request->code == null)){

            return $this->apiResponse('', true,'The code is missing'); 

        }
        try {
            $accessTokenResponse = $foodicsService->getAccessToken($request->code);
            $accessToken = $accessTokenResponse->json('access_token');

            $businessInfoResponse = $foodicsService->getBusinessInfo($accessToken);

            $rest = new Restaurant();

            $rest->name_eng = $request->name_eng;
            $rest->name_ar = $request->name_ar;
            $rest->email = $request->email;
            $rest->password = Hash::make($request->password);
            
            $rest->manager_phone = $request->manager_phone;
            $rest->manager_name = $request->manager_name;

            $rest->location = $request->location;

            $rest->reference_id = $businessInfoResponse->json('data.business.reference');
            $rest->business_name = $businessInfoResponse->json('data.business.name');
            $rest->business_id = $businessInfoResponse->json('data.business.id');
            $rest->owner_email = $businessInfoResponse->json('data.business.owner_email');

            $rest->access_token = $accessToken;

           // $rest->refresh_token =$accessTokenResponse->json('refresh_token');

            $rest->save();
        }catch (Exception $exception){

            return $this->apiResponse('', true,$exception->getMessage()); 

        }

            return $this->apiResponse($rest, false,'Restaurant register successfully'); 
    }

    public function integrateWithFoodics(Request $request, FoodicsService $foodicsService)
    {
        if(empty($request->code || $request->code == null)){
            
            return $this->apiResponse('', true,'The code is missing'); 

        }

        try {
            $accessTokenResponse = $foodicsService->getAccessToken($request->code);
            $accessToken = $accessTokenResponse->json('access_token');

            $businessInfoResponse = $foodicsService->getBusinessInfo($accessToken);

            $rest =  Restaurant::find(\Auth::user()->id);

            $rest->reference_id = $businessInfoResponse->json('data.business.reference');
            $rest->business_name = $businessInfoResponse->json('data.business.name');
            $rest->business_id = $businessInfoResponse->json('data.business.id');
            $rest->owner_email = $businessInfoResponse->json('data.business.owner_email');

            $rest->access_token = $accessToken;

           // $rest->refresh_token =$accessTokenResponse->json('refresh_token');

            $rest->save();
        }catch (Exception $exception){

            return $this->apiResponse('', true,$exception->getMessage()); 

            //return $this->errorResponse(__($exception->getMessage()), 500);
        }

            return $this->apiResponse($rest, false,'Integrated successfully with foodics'); 
    }




    public function getIntegrateUrl(FoodicsService $foodicsService)
    {

        $integrateURL = $foodicsService->getAuthorizationUrl();

        return $this->apiResponse($integrateURL, false,''); 

    }



}
