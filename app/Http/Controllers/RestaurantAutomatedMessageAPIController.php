<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

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

use Carbon\Carbon;


class RestaurantAutomatedMessageAPIController extends Controller
{
    
    public function createAutoMessage(Request $request)
    {

        $opject = new AutomatedMessage();

        $opject->name = $request->name;
        $opject->description = $request->description;

        //one time or auto
        $opject->type = $request->type;

        //SMS or Email
        $opject->message_type = $request->message_type;

        //send after hours
        $opject->send_after = $request->send_after;

        $opject->body = $request->body;
    

        $opject->restaurant_id = \Auth::user()->id;

        $opject->save();

        if ($request->type == "one time") {

            $guests_ids = [];

            $tags = $request->tags;

            foreach ($tags as $tag) {
            
                $tag_type = AutomatedTag::where('tag_id' , $tag)->first();

                if ($tag_type->type == 'last visit') {
                
                    //get restaurant's all guests

                    $rest_geusts = Guest::where('restaurant_id' ,\Auth::user()->id)->get();

                    foreach ($rest_geusts as $rest_guest) {
                    
                        $guest_last_visit = GuestOrder::where('guest_id' , $rest_guest->id)->orderBy('id' , 'DESC')->first();

                        $today = date('Y-m-d');
        
                        $last_visit = date('Y-m-d', strtotime($guest_last_visit->created_at));;
            
                        
                        $difference = strtotime($today) - strtotime($last_visit);
            
                        //Calculate difference in days
                        $days = abs($difference/(60 * 60)/24);

                        if ($tag_type->range_from <= $days) {
                            
                            $guests_ids [] = $rest_guest->id;
                        }

                    }


                }else{

                    $guests = GuestTag::whereIn('tag_id' , $tags)
                    ->where('is_valid' , 'true')
                    ->get();

                    foreach ($guests as $key => $value) {
            
                    $guests_ids [] = $value->guest_id;
            
                    }

                }
            }

        

            //Remove duplicate values from an array
            $guests_ids = array_unique($guests_ids);
                  
            $body = $request->body;

            $message_id = $opject->id;

            $this->prepareMessage($message_id, $body, $guests_ids);
        
            return $this->apiResponse('', false,('One time messages sent successfully')); 
        

        } elseif($request->type == "automated"){

        
            $tags = $request->tags;
        
            foreach ($tags as $key => $value) {
            
            $tag = new AutomatedMessageTag();
        
            $tag->automated_message_id = $opject->id;
            $tag->tag_id = $value;
        
            $tag->save();
        
            }

            return $this->apiResponse($opject, false,('New automated message added successfully')); 

        }else{

            return $this->apiResponse('', true,('the type must be (on time) OR (automated) it\'s case sensitive)')); 

        }
    

        

    }


        public function updateAutoMessage(Request $request)
        {

            $opject =  AutomatedMessage::find($request->id);

            $opject->name = $request->name;
            $opject->description = $request->description;

            //send after hours
            $opject->send_after = $request->send_after;

            $opject->body = $request->body;

            $opject->save();

            return $this->apiResponse($opject, false,('Automated message updated successfully')); 

        }

    public function getAutoMessages()
    {
        $autoMessages = AutomatedMessage::where('restaurant_id',\Auth::user()->id)->get();


        $data = [];

        $i = 0;

    $data['recipient'] = 0;
    $data['customers'] = 0;
    $data['orders'] = 0;
    $data['revenue'] = 0;

    $data['data'] = [];
    
        foreach ($autoMessages as $value) {
        
            $data['data'][$i]['id'] = $value->id;
            $data['data'][$i]['name'] = $value->name;
            $data['data'][$i]['description'] = $value->description;
            $data['data'][$i]['type'] = $value->type;
            $data['data'][$i]['message_type'] = $value->message_type;

            $data['data'][$i]['body'] = $value->body;

            $data['data'][$i]['recipient'] = $value->discountCodes->count();
            $data['recipient'] += $value->discountCodes->count();

            $data['data'][$i]['customers'] = $value->discountCodes->where('used_times' , 1)->count();
            $data['customers'] += $value->discountCodes->where('used_times' , 1)->count();

            $data['data'][$i]['revenue'] = $value->discountCodes->sum('discount_amount');
            $data['revenue'] += $value->discountCodes->sum('discount_amount');


            $data['data'][$i]['orders'] = 0 ;

            foreach ($value->discountCodes as $discount) {
                
                if (!empty($discount->guestOrder)) {
                    
                    $orders = $discount->guestOrder->orderDetails->count();

                    $data['data'][$i]['orders'] += $orders;

                    $data['orders'] += $orders;
                }
            

            }
        

            $data['data'][$i]['is_deleted'] = $value->is_deleted;


            $t = 0;
            foreach ($value->automatedMessageTags as $messageTag) {

                if (!empty($messageTag->tag)) {

                    $data['data'][$i]['tags'][$t]['tag_name'] = $messageTag->tag->name;

                }

            $t++;
            }


        $i++;
        }

        return $this->apiResponse($data, false,('')); 

    }

    public function deleteAutoMessage(Request $request)
    {
        $opject = AutomatedMessage::find($request->id);

        $opject->is_deleted = !($opject->is_deleted);

        $opject->save();

        return $this->apiResponse($opject, false,('Automated message deleted successfully')); 

    }

    public function removeAutoMessage(Request $request)
    {
        $message = AutomatedMessage::find($request->id);

        if (!empty($message->discountCodes)) {
           
            foreach ($message->discountCodes as $code) {
               
               if (!empty($code->guestOrder)) {
                  
                    $order = GuestOrder::find($code->guestOrder->id);
                    $order->delete();
               }
            }
        }

        $message->delete();

        return $this->apiResponse('', false,('Automated message removed successfully!')); 

    }


    public function sendTestMessage(Request $request)
    {
        $message_body = $this->setTestMessageBody($request->body);

        $guest =  (object)array("customer_phone"=> '966' . $request->phone );

        $messageStatus = $this->sendMessage($message_body, $guest);

        if ($messageStatus == 'success') {

            return $this->apiResponse('', false,'Message sent successfully'); 

        }else{

            return $this->apiResponse('', true,$messageStatus); 

        }


    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
        public function setMessageBody($message_id , $body , $guest)
        {
            

        $name = $guest->customer_name;

        $find_name = explode("{{name}}", $body);

        //check if body has {name} on it
        if (count($find_name) > 1) {

            $message_body = $find_name[0]. $name . $find_name[1];

        }else{

            $message_body = $find_name[0];
        }

        $find_code = explode("{", $message_body);

        //check if body has {code} on it
        if (count($find_code) > 1) {

            $code_name = $this->getCodeName($find_code[2]);


            $coupon = RestaurantDiscount::where('name' , 'like' , '%' . $code_name . '%')->first();

            $guest_code = new DiscountCode();

            $guest_code->automated_message_id = $message_id;

            $guest_code->restaurant_discount_id = $coupon->id;

            $length = 6;    
            $code =  random_int(100000, 999999);

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

            $message_body = $find_code[0];
        }


        return $message_body;

        }//end of setMessageBody

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        public function setTestMessageBody($body)
        {
            

        $name = '(Customer name)';

        $find_name = explode("{{name}}", $body);

        //check if body has {name} on it
        if (count($find_name) > 1) {

            $message_body = $find_name[0]. $name . $find_name[1];

        }else{

            $message_body = $find_name[0];
        }

        $find_code = explode("{", $message_body);

        //check if body has {code} on it
        if (count($find_code) > 1) {

            $code =  '(Code number)';

            //get the text after the code
            $end_message =  $exp_1 = explode("}}",$find_code[2]);
            
            if (count($end_message) > 1) {

                $message_body = $find_code[0]. $code . $end_message[1];

            }else{

                $message_body = $find_code[0]. $code;

            }


        }else{

            $message_body = $find_code[0];
        }

        return $message_body;

        }//end of setTestMessageBody

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        public function getCodeName($code)
        {

        $exp_1 = explode("}}",$code);

        $name = $exp_1[0];

        return $name;

        }//end of get code name

        
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        public function prepareMessage($message_id , $body , $guests_ids)
        {
            
            foreach ($guests_ids as $guest_id) {
                
                $guest = Guest::find($guest_id);

                $message_body = $this->setMessageBody($message_id , $body , $guest);

                $this->sendMessage($message_body,$guest);
            }
        }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function sendMessage($message_body , $guest)
        {
        

            $phone = $guest->customer_phone;

            $restaurant = Restaurant::find(\Auth::user()->id);

            $sender = $restaurant->sender_name;

            $num_of_messages = $restaurant->number_of_messages;

            if ($num_of_messages < 1 ) {
                return 'Message quota has ended!';
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

                return 'success';
                
            }else{

                $msg = new UnifonicMessageRecord ();

                $msg->success =  'false';
                $msg->message =  $res->object()->message;
                $msg->status =  $res->object()->Status;
                $msg->error_code =  $res->object()->errorCode;

                $msg->restaurant_id =   $restaurant->id;

                $msg->save();


                return  $res->object()->message ;
            }

            
        }//end of send message
}
