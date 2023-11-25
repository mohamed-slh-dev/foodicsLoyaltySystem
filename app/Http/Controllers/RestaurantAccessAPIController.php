<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\RestaurantUser;

class RestaurantAccessAPIController extends Controller
{
    //

    public function createAccessUser (Request $request)
    {
        $user = new RestaurantUser();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->access_level = $request->access_level;

        $user->general_update = $request->general_update;
        $user->general_update_integration = $request->general_update_integration;

        $user->auto_tag_create = $request->auto_tag_create;
        $user->auto_tag_update = $request->auto_tag_update;

        $user->email_campagin_create = $request->email_campagin_create;
        $user->email_campagin_update = $request->email_campagin_update;

        $user->sms_campagin_create = $request->sms_campagin_create;
        $user->sms_campagin_update = $request->sms_campagin_update;

        $user->promocode_campagin_create = $request->promocode_campagin_create;
        $user->promocode_campagin_update = $request->promocode_campagin_update;

        $user->reports_access = $request->reports_access;

        $user->guest_create = $request->guest_create;
        $user->guest_update = $request->guest_update;

        $user->access_create = $request->access_create;
        $user->access_update = $request->access_update;

        $user->restaurant_id = \Auth::user()->id;

        $user->save();


        return $this->apiResponse($user, false,('Access user created successfully!')); 

    }


    public function updateAccessUser (Request $request)
    {
        $user = RestaurantUser::find($request->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->access_level = $request->access_level;

        $user->general_update = $request->general_update;
        $user->general_update_integration = $request->general_update_integration;

        $user->auto_tag_create = $request->auto_tag_create;
        $user->auto_tag_update = $request->auto_tag_update;

        $user->email_campagin_create = $request->email_campagin_create;
        $user->email_campagin_update = $request->email_campagin_update;

        $user->sms_campagin_create = $request->sms_campagin_create;
        $user->sms_campagin_update = $request->sms_campagin_update;

        $user->promocode_campagin_create = $request->promocode_campagin_create;
        $user->promocode_campagin_update = $request->promocode_campagin_update;

        $user->reports_access = $request->reports_access;

        $user->guest_create = $request->guest_create;
        $user->guest_update = $request->guest_update;

        $user->access_create = $request->access_create;
        $user->access_update = $request->access_update;

        $user->restaurant_id = \Auth::user()->id;

        $user->save();


        return $this->apiResponse($user, false,('Access user created successfully!')); 

    }

    public function deleteAccessUser(Request $request)
    {
        $user = RestaurantUser::find($request->id);

        $user->delete();

        return $this->apiResponse('', false, 'Access user deleted successfully'); 

    }


    public function getAccessUsers()
    {
        $users = RestaurantUser::where('restaurant_id' , \Auth::user()->id)->get();

        return $this->apiResponse($users, false, ''); 

    }
}
