<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminRestaurantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AdminController::class, 'login'])->name('admin.login');
Route::post('/check-login', [AdminController::class, 'checkLogin'])->name('admin.checkLogin');
Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::get('/deleteRests', [AdminController::class, 'deleteRests']);


Route::prefix('documentaion')->group(function () {

    //automated tag documentation English
    Route::get('/automated-tags-doc-en', function () {
        return view('automated-tags-documentation-en');
    })->name('automated-tags-doc-en');
    //orders structure
    Route::get('/orders-structure-doc-en', function () {
        return view('orders-structure-documentation-en');
    })->name('orders-structure-doc-en');

});


Route::prefix('admin')->group(function () {

    //dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');


    //restaurants routes
    Route::get('/restaurants', [AdminRestaurantController::class, 'restaurants'])->name('admin.restaurants');

    Route::post('/create-restaurant', [AdminRestaurantController::class, 'createRestaurant'])->name('admin.createRestaurant');

    Route::post('/reset-restaurant', [AdminRestaurantController::class, 'resetRestaurantPassword'])->name('admin.resetRestaurantPassword');

    Route::get('/delete-restaurant/{id}', [AdminRestaurantController::class, 'deleteRestaurant'])->name('admin.deleteRestaurant');

    Route::post('/restaurant-add-quota', [AdminRestaurantController::class, 'restaurantAddQuota'])->name('admin.restaurantAddQuota');

    Route::get('/restaurants-messages-requests', [AdminRestaurantController::class, 'restaurantsMessagesRequests'])->name('admin.restaurantsMessagesRequests');

    Route::post('/update-request-status', [AdminRestaurantController::class, 'updateRequestStatus'])->name('admin.updateRequestStatus');

    Route::post('/restaurants-sender-name', [AdminRestaurantController::class, 'restaurantSenderName'])->name('admin.restaurantSenderName');

    Route::post('/restaurants-update-modules', [AdminRestaurantController::class, 'restaurantUpdateModules'])->name('admin.restaurantUpdateModules');


    //users routes
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/create-user', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::post('/reset-user', [AdminController::class, 'resetUserPassword'])->name('admin.resetUserPassword');
    Route::get('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
    Route::post('/update-password', [AdminController::class, 'updateProfilePassword'])->name('admin.updateProfilePassword');
    
    //tags
    Route::get('/tags', [AdminRestaurantController::class, 'tags'])->name('admin.tags');
    // Route::post('/create-tag', [AdminController::class, 'createTag'])->name('admin.createTag');
    // Route::post('/update-tag', [AdminController::class, 'updateTag'])->name('admin.updateTag');
    // Route::get('/delete-tag/{id}', [AdminController::class, 'deleteTag'])->name('admin.deleteTag');
    

    //codes
    Route::get('/codes', [AdminRestaurantController::class, 'codes'])->name('admin.codes');

    //messages
    Route::get('/messages', [AdminRestaurantController::class, 'messages'])->name('admin.messages');

    //orders
    Route::get('/orders', [AdminRestaurantController::class, 'orders'])->name('admin.orders');

    //messages records
    Route::get('/messages-records', [AdminController::class, 'messagesRecords'])->name('admin.messagesRecords');

    //reset restaurant data
    Route::get('/reset-Xxyz97', [AdminController::class, 'resetRestaurantsData']);
    Route::post('/delete-rest-data', [AdminController::class, 'deleteRestaurantData'])->name('admin.deleteRestaurantData');

});
