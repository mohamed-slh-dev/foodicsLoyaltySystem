<?php

use Illuminate\Http\Request;

use App\Http\Controllers\RestaurantAPIController;
use App\Http\Controllers\UnifonicMessagesController;
use App\Http\Controllers\FoodicsController;
use App\Http\Controllers\FoodicsLoyaltyController;
use App\Http\Controllers\FoodicsWebhookController;
use App\Http\Controllers\RestaurantAccessAPIController;
use App\Http\Controllers\RestaurantOrderAPIController;
use App\Http\Controllers\RestaurantGuestAPIController;
use App\Http\Controllers\RestaurantAutomatedTagAPIController;
use App\Http\Controllers\RestaurantDiscountAPIController;
use App\Http\Controllers\RestaurantAutomatedMessageAPIController;
use App\Http\Controllers\RestaurantRankAPIController;




use App\Http\Middleware\FoodicsLoyaltyAuth;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('restaurant/v1')->group(function () {

    //login/logout routes
    Route::post('login', [RestaurantAPIController::class, 'restaurantLogin'])->name('restaurant.login');

	Route::post('/send-message',[UnifonicMessagesController::class,'sendMessage']);	


});

//Foodics route
Route::prefix('foodics')->group(function () {

	//webhook
	Route::post('/webhook', [FoodicsWebhookController::class,'webhook']);

	//loyalty
	Route::prefix('loyalty')
        ->middleware([FoodicsLoyaltyAuth::class])
        ->group(
            function () {
                Route::post('/reward',[ FoodicsLoyaltyController::class,'reward']);
                Route::post('/redeem', [FoodicsLoyaltyController::class,'redeem']);
            }
        );


	//register
	Route::post('/register',[FoodicsController::class,'register']);	
	Route::post('/register-with-code',[FoodicsController::class,'registerWithCode']);	

	//testing webhooks route
	Route::post('/test-webhook',[FoodicsWebhookController::class,'webhook']);		


});


Route::group(['prefix' => 'restaurant/v1','middleware' => ['assign.guard:restaurant-api']],function ()
{
    //logout
    Route::post('logout', [RestaurantAPIController::class,'restaurantLogout']);

	//branches
	Route::get('/get-branches',[RestaurantAPIController::class,'getBranches']);	

	//general
	Route::get('/get-general',[RestaurantAPIController::class,'getGeneral']);
	Route::post('/update-general',[RestaurantAPIController::class,'updateGeneral']);

    //guests
	Route::post('/create-guest',[RestaurantGuestAPIController::class,'createGuest']);
	Route::post('/update-guest',[RestaurantGuestAPIController::class,'updateGuest']);
	Route::get('/get-guests',[RestaurantGuestAPIController::class,'getGuests']);	
	Route::post('/get-guest-tags',[RestaurantGuestAPIController::class,'getGuestTags']);	
	Route::post('/get-guest-orders',[RestaurantGuestAPIController::class,'getGuestOrders']);	
	Route::post('/get-guest-products',[RestaurantGuestAPIController::class,'getGuestProducts']);	
	Route::post('/get-guest-combos',[RestaurantGuestAPIController::class,'getGuestCombos']);	

    //tags
	Route::get('/get-tags',[RestaurantAutomatedTagAPIController::class,'getTags']);	

	//automated tage
	Route::post('/create-auto-tag',[RestaurantAutomatedTagAPIController::class,'createAutoTag']);
	Route::get('/get-auto-tags',[RestaurantAutomatedTagAPIController::class,'getAutoTags']);	
	Route::post('/delete-auto-tag',[RestaurantAutomatedTagAPIController::class,'deleteAutoTag']);
	Route::post('/update-auto-tag',[RestaurantAutomatedTagAPIController::class,'updateAutoTag']);
	Route::post('/remove-auto-tag',[RestaurantAutomatedTagAPIController::class,'removeAutoTag']);


    //discounts
	Route::post('/create-coupon',[RestaurantDiscountAPIController::class,'createCoupon']);
	Route::get('/get-coupons',[RestaurantDiscountAPIController::class,'getCoupons']);	
	Route::post('/delete-coupon',[RestaurantDiscountAPIController::class,'deleteCoupon']);
	Route::post('/update-coupon',[RestaurantDiscountAPIController::class,'updateCoupon']);	
	Route::post('/remove-coupon',[RestaurantDiscountAPIController::class,'removeCoupon']);	


	//automated messages
	Route::post('/create-auto-message',[RestaurantAutomatedMessageAPIController::class,'createAutoMessage']);
	Route::post('/update-auto-message',[RestaurantAutomatedMessageAPIController::class,'updateAutoMessage']);
	Route::get('/get-auto-messages',[RestaurantAutomatedMessageAPIController::class,'getAutoMessages']);	
	Route::post('/delete-auto-message',[RestaurantAutomatedMessageAPIController::class,'deleteAutoMessage']);
	Route::post('/remove-auto-message',[RestaurantAutomatedMessageAPIController::class,'removeAutoMessage']);
	Route::post('/create-test-message',[RestaurantAutomatedMessageAPIController::class,'sendTestMessage']);



	//integrate with foodics
	Route::get('/get-integrate-url',[FoodicsController::class,'getIntegrateUrl']);	
	Route::post('/integrate-with-foodics',[FoodicsController::class,'integrateWithFoodics']);	


	//access
	Route::post('/create-access-user',[RestaurantAccessAPIController::class,'createAccessUser']);	
	Route::post('/update-access-user',[RestaurantAccessAPIController::class,'updateAccessUser']);	
	Route::post('/delete-access-user',[RestaurantAccessAPIController::class,'deleteAccessUser']);	
	Route::get('/get-access-users',[RestaurantAccessAPIController::class,'getAccessUsers']);	


	//orders
	Route::get('/get-orders',[RestaurantOrderAPIController::class,'getOrders']);	
	Route::post('/get-order-details',[RestaurantOrderAPIController::class,'getOrderDetails']);	

	//ranks
	Route::post('/create-rank',[RestaurantRankAPIController::class,'createRank']);	
	Route::post('/update-rank',[RestaurantRankAPIController::class,'updateRank']);	
	Route::post('/delete-rank',[RestaurantRankAPIController::class,'deleteRank']);	
	Route::get('/get-ranks',[RestaurantRankAPIController::class,'getRanks']);	
	Route::post('/remove-rank',[RestaurantRankAPIController::class,'removeRank']);	

});
