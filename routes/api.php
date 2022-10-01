<?php

use Illuminate\Http\Request;

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

//User API
Route::post('/v1/createUser', 'v1\UserController@createUser');
Route::post('/v1/checkExistingUser', 'v1\UserController@checkExistingUser');

//Restaurant & Food
Route::post('/v1/getAppData', 'v1\AppController@getAppData');
Route::post('/v1/getRestaurantMenu', 'v1\MenuController@restaurantMenu');

//Order Management
Route::post('/v1/placeOrder', 'v1\OrderController@placeOrder');
Route::post('/v1/getOrderList', 'v1\OrderController@getOrderList');
Route::post('/v1/getOrderDetails', 'v1\OrderController@getOrderDetails');

//Reverse Geocoding
Route::post('/v1/getGeoLocation', 'v1\AppController@getGeoLocation');

//Notification List
Route::post('/v1/getNotificationList', 'v1\NotificationController@getNotificationList');

//Rider API
Route::post('/v1/riderLogin', 'v1\RiderController@riderLogin');

//Rider APP
Route::post('/v1/getAllOrderList', 'v1\OrderController@getAllOrderList');
Route::post('/v1/assignRider', 'v1\OrderController@assignRider');
Route::post('/v1/cancelOrder', 'v1\OrderController@cancelOrder');
Route::post('/v1/markDelivered', 'v1\OrderController@markDelivered');

//Temporary Api
// Route::post('/v1/uploadRangpur', 'v1\TemporaryController@uploadRangpur');
// Route::post('/v1/uploadBogura', 'v1\TemporaryController@uploadBogura');
// Route::post('/v1/createBackendLogin', 'v1\LoginController@createLogin');


//Grocery APIs
Route::post('/homeData', 'HomeController@getHomeData');
Route::post('/cityList', 'Grocery\CityController@getCityList');
Route::post('/categoryList', 'Grocery\CategoryController@getCategoryList');
Route::post('/productList', 'Grocery\ProductController@getProductList');
Route::post('/homeProductList', 'Grocery\ProductController@getHomeProductList');
Route::post('/productDetails', 'Grocery\ProductController@getProductDetails');
Route::post('/grocery/user/register', 'Grocery\UserController@registerUser');
Route::post('/createWebOrder', 'Grocery\OrderController@createWebOrder');

Route::post('/deliveryCharge', 'Grocery\OrderController@calculateDeliveryCharge');
Route::post('/applyPromo', 'Grocery\OrderController@applyPromo');
Route::post('/placeOrder', 'Grocery\OrderController@newOrder');
Route::post('/myOrders', 'Grocery\OrderController@myOrderList');
Route::post('/orderDetails', 'Grocery\OrderController@orderDetails');
Route::post('/searchProduct', 'Grocery\ProductController@searchProducts');

Route::post('/createMangoOrder', 'Mango\OrderController@createOrder');
Route::post('/mango/details', 'Mango\OrderController@getOrderDetails');

Route::post('/bkash/payment/mango', 'bkashGateway@mangoManualPayment');

Route::get('/facebook/chat/notify', 'FacebookChatController@notify');

Route::get('/mega-days/{slug}', 'MegaDays\MegaDaysController@viewMegaDays');

//Payment Gateway API
Route::post('/payment/bkash/webhook-endpoint', 'PaymentGateway\bKash@webhook_endpoint');
Route::post('/payment/tokens/generate', 'PaymentGateway\PaymentController@generate_android_tokens');
Route::get('/payment/clearjunk', 'PaymentGateway\PaymentController@clear_payment_junk');
