<?php

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

use App\Http\Controllers\Grocery\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

//Web API
Route::post('/v1/login', 'v1\LoginController@login');

Route::post('/v1/addNewPage', 'v1\PermissionController@addNewPage');
Route::post('/v1/decodePermissions', 'v1\PermissionController@decodePermission');

Route::post('/v1/addNewCity', 'v1\CityController@addNewCity');

Route::post('/v1/addNewCategory', 'v1\CategoryController@addNewCategory');
Route::post('/v1/editCategoryInfo', 'v1\CategoryController@updateCategory');

Route::post('/v1/sendNotification', 'v1\NotificationController@sendNotification');
Route::post('/v1/sendNotificationToRider', 'v1\NotificationController@sendNotificationToRider');

Route::post('/v1/decodeRestaurant', 'v1\RestaurantController@getRestaurantInfo');
Route::post('/v1/updateRestaurant', 'v1\RestaurantController@updateRestaurantInfo');
Route::post('/v1/updateRestaurantStatus', 'v1\RestaurantController@updateRestaurantStatus');
Route::post('/v1/updateFeaturedRestaurants', 'v1\RestaurantController@updateFeaturedRestaurants');
Route::post('/v1/updateFeaturedRestaurantsOrder', 'v1\RestaurantController@updateFeaturedRestaurantsOrder');
Route::post('/v1/setRestaurantDiscount', 'v1\RestaurantController@setRestaurantDiscount');

Route::post('/v1/addNewItem', 'v1\MenuController@addNewItem');
Route::post('/v1/editItem', 'v1\MenuController@editItem');
Route::post('/v1/deleteItem', 'v1\MenuController@deleteItem');
Route::post('/v1/updateItemStatus', 'v1\MenuController@updateItemStatus');
Route::post('/v1/uploadRestaurantMenu', 'v1\MenuController@uploadRestaurantMenu');
Route::get('/v1/processMenuData', 'v1\MenuController@processMenuData');

Route::post('/v1/uploadOldUserData', 'v1\UserController@uploadOldUserData');
Route::get('/v1/processOldUserData', 'v1\UserController@processOldUserData');
Route::post('/v1/deleteSingleOldUserData', 'v1\UserController@deleteSingleOldUserData');

Route::post('/v1/addNewApiProvider', 'v1\APIController@addNewApiProvider');

Route::post('/v1/addNewRider', 'v1\RiderController@addNewRider');
Route::post('/v1/updateRiderPassword', 'v1\RiderController@updateRiderPassword');

Route::post('/v1/generateReport', 'v1\ReportController@generateReport');
Route::post('/v1/updatePassword', 'v1\LoginController@updatePassword');

Route::post('/v1/sendSMS', 'v1\SMSController@sendSMS');

//Page Routing
Route::get('/dashboard', 'v1\RouteController@dashboard');
Route::get('/dashboard/page', 'v1\RouteController@pageManager');

//Authentication
Route::get('/dashboard/signin', 'v1\RouteController@signin');
Route::get('/dashboard/signout', 'v1\RouteController@signout');

//Unauthorized
Route::get('/dashboard/page/unauthorized', 'v1\RouteController@unauthorized');



//Grocery Routes
Route::get('/grocery/dashboard', 'Grocery\RouteController@dashboard');

Route::get('/grocery/city', 'Grocery\RouteController@city');
Route::post('/grocery/city/create', 'Grocery\CityController@createCity');
Route::post('/grocery/city/update', 'Grocery\CityController@updateCity');
Route::post('/grocery/city/preview/update', 'Grocery\CityController@updateCityPreview');

Route::get('/grocery/city/area-coverage', 'Grocery\RouteController@areaCoverage');
Route::post('/grocery/city/area-coverage/create', 'Grocery\CityController@createArea')->name('CoverageArea.Create');
Route::post('/grocery/city/area-coverage/edit', 'Grocery\CityController@updateArea')->name('CoverageArea.Update');
Route::get('/grocery/city/area-coverage/delete', 'Grocery\CityController@deleteArea')->name('CoverageArea.Delete');

Route::get('/grocery/category', 'Grocery\RouteController@category');
Route::get('/grocery/category/regenerate', 'Grocery\CategoryController@regenerateCategory');
Route::get('/grocery/category/add', 'Grocery\RouteController@addCategory');
Route::post('/grocery/category/create', 'Grocery\CategoryController@createCategory');
Route::get('/grocery/category/edit', 'Grocery\RouteController@editCategory');
Route::post('/grocery/category/edit/confirm', 'Grocery\CategoryController@editCategory');
Route::post('/grocery/category/status/update', 'Grocery\CategoryController@categoryStatusUpdate');
Route::post('/grocery/category/prePayment/update', 'Grocery\CategoryController@categoryPrepaymentUpdate');


//start writing by Ruhul
Route::get('/blog/show',[BlogController::class, 'index'])->name('blog.show');
Route::get('/blog/create',[BlogController::class, 'create'])->name('blog.create');
Route::post('/blog/store',[BlogController::class, 'store'])->name('blog.store');
Route::get('/blog/edit/{id}',[BlogController::class, 'edit'])->name('blog.edit');
Route::post('/blog/update/{id}',[BlogController::class, 'update'])->name('blog.update');
Route::post('/blog/status/update', [BlogController::class, 'blogStatusUpdate'])->name('blog.status.update');

//End writing by Ruhul


Route::get('/grocery/products', 'Grocery\RouteController@products');
Route::get('/grocery/products/regenerate', 'Grocery\ProductController@regenerateProducts');
Route::get('/grocery/products/add', 'Grocery\RouteController@addProduct');



Route::post('/grocery/products/create', 'Grocery\ProductController@createProduct');
Route::get('/grocery/products/edit', 'Grocery\RouteController@editProduct');
Route::post('/grocery/products/edit/confirm', 'Grocery\ProductController@editProduct');
Route::post('/grocery/products/edit/category', 'Grocery\ProductController@editCategory');
Route::post('/grocery/products/status/update', 'Grocery\ProductController@productStatusUpdate');

Route::get('/grocery/order', 'Grocery\RouteController@orders');
Route::get('/grocery/order/new', 'Grocery\RouteController@createManualOrder');
Route::get('/grocery/order/confirm', 'Grocery\OrderController@confirmOrder');
Route::get('/grocery/order/complete', 'Grocery\OrderController@completeOrder');
Route::get('/grocery/order/pending', 'Grocery\OrderController@pendingOrder');
Route::get('/grocery/order/cancel', 'Grocery\OrderController@cancelOrder');
Route::get('/grocery/order/delete', 'Grocery\OrderController@deleteOrder');
Route::get('/grocery/products/sale', 'Grocery\RouteController@saleAnalysis');
Route::post('/grocery/order/edit/discount', 'Grocery\OrderController@applyDiscount');
Route::get('/grocery/order/edit', 'Grocery\RouteController@editOrder');
Route::post('/grocery/order/createWebOrder', 'Grocery\OrderController@createWebOrder');
Route::post('/grocery/order/schedule', 'Grocery\OrderController@changeSchedule');
Route::post('/grocery/order/updateOrder', 'Grocery\OrderController@updateOrder');

Route::get('/grocery/leads', 'Grocery\RouteController@leadsData');
Route::post('/grocery/leads/upload', 'Grocery\LeadsController@uploadLeads');
Route::post('/grocery/leads/resolve', 'Grocery\LeadsController@resolveLead');

Route::get('/grocery/print/invoice', 'Grocery\InvoiceController@orderInvoice');
Route::get('/grocery/dealer/print', 'Grocery\RouteController@dealerInvoice');
Route::post('/grocery/dealer/print/invoice', 'Grocery\InvoiceController@dealerInvoice');

Route::get('/grocery/users', 'Grocery\RouteController@userManager');
Route::get('/grocery/admins', 'Grocery\RouteController@adminManager');
Route::post('/grocery/addNewAdmin', 'Grocery\AdminController@createAdminAccount');
Route::post('/grocery/admin/addNewAccessRule', 'Grocery\AdminController@addNewAccessRule');
Route::post('/grocery/admins/updateAccess', 'Grocery\AdminController@updateAdminAccess');

Route::get('/grocery/rider', 'Grocery\RouteController@riderManager');
Route::get('/grocery/admin/changePassword', 'Grocery\RouteController@changePassword');
Route::post('/grocery/admin/updatePassword', 'Grocery\AdminController@updatePassword');
Route::get('/grocery/admin/delete', 'Grocery\AdminController@delete');

Route::get('/grocery/log/login', 'Grocery\RouteController@loginLog');

Route::get('/grocery/dashboard/signout', 'Grocery\RouteController@signout');

Route::get('/grocery/order/mango', 'Grocery\RouteController@mangoOrders');
Route::get('/grocery/order/mango/manage', 'Grocery\RouteController@manageMangoOrders')->name('mango.manage');
Route::post('/grocery/order/mango/manage/update', 'Mango\OrderController@updateMangoOrders')->name('mango.update');
Route::post('/grocery/order/mango/manage/paymentUpdate', 'Mango\OrderController@updateMangoPayment')->name('mango.payment.update');
Route::post('/grocery/order/mango/manage/orderUpdate', 'Mango\OrderController@updateMangoOrderStatus')->name('mango.status.update');
Route::get('/grocery/order/mango/paymentSMS', 'Mango\OrderController@mangoPaymentSMS')->name('mango.payment.sms');
Route::post('/grocery/order/mango/promoteSMS', 'Mango\OrderController@mangoPromoteSMS')->name('mango.promote.sms');

Route::get('/grocery/leads/mango', 'Grocery\RouteController@mangoLeadsData');
Route::post('/grocery/leads/mango/upload', 'Grocery\LeadsController@uploadMangoLeads');
Route::post('/grocery/leads/mango/resolve', 'Grocery\LeadsController@resolveMangoLead');

Route::get('/grocery/notification', 'Grocery\RouteController@push_notification');

Route::get('/grocery/mega-days/create', 'MegaDays\MegaDaysController@create')->name('megadays.create');
Route::post('/grocery/mega-days/create/store', 'MegaDays\MegaDaysController@store')->name('megadays.store');
Route::get('/grocery/mega-days/{mid}/delete', 'MegaDays\MegaDaysController@delete')->name('megadays.delete');
Route::post('/grocery/mega-days/status', 'MegaDays\MegaDaysController@status')->name('megadays.status');
Route::get('/grocery/mega-days/manage', 'MegaDays\MegaDaysController@manage')->name('megadays.manage');
Route::get('/grocery/mega-days/category/{mid}', 'MegaDays\MegaDaysController@category')->name('megadays.category');
Route::post('/grocery/mega-days/category/{mid}/store', 'MegaDays\MegaDaysController@storeCategory')->name('megadays.category.store');
Route::get('/grocery/mega-days/category/{mid}/{cid}/delete', 'MegaDays\MegaDaysController@deleteCategory')->name('megadays.category.delete');
Route::get('/grocery/mega-days/products/{mid}/{cid}', 'MegaDays\MegaDaysController@products')->name('megadays.products');
Route::post('/grocery/mega-days/products/{mid}/{cid}/store', 'MegaDays\MegaDaysController@storeProduct')->name('megadays.products.store');
Route::get('/grocery/mega-days/products/{cid}/{pid}/delete', 'MegaDays\MegaDaysController@deleteProduct')->name('megadays.products.delete');

//Payment Gateway
Route::get('/payment', 'PaymentGateway\PaymentController@walletHome');
Route::get('/payment/android', 'PaymentGateway\PaymentController@androidPayment');
Route::get('/payment/tokens', 'PaymentGateway\PaymentController@tokens');
Route::get('/payment/bkash', 'PaymentGateway\PaymentController@bkash_payments');
Route::get('/payment/bkash/refund', 'PaymentGateway\PaymentController@bkash_refunds');
Route::get('/payment/tokens/generate/{order_id}', 'PaymentGateway\PaymentController@generate_tokens');
Route::get('/payment/Res/tokens/generate/{order_id}', 'PaymentGateway\PaymentController@generate_res_tokens');

// bKash
Route::post('/payment/bkash/checkout/createPayment', 'PaymentGateway\bKash@create_payment');
Route::post('/payment/bkash/checkout/executePayment', 'PaymentGateway\bKash@execute_payment');
Route::post('/payment/bkash/trx/query', 'PaymentGateway\bKash@query_payment');
// Route::get('/payment/bkash/trx/search', 'PaymentGateway\bKash@search_transactions');
Route::post('/payment/bkash/trx/refund', 'PaymentGateway\bKash@refund_transactions');
Route::post('/payment/bkash/trx/refund/status', 'PaymentGateway\bKash@refund_status');
// Route::get('/payment/bkash/b2c/balance', 'PaymentGateway\bKash@organization_balance');
// Route::get('/payment/bkash/b2c/transfer', 'PaymentGateway\bKash@intra_account_transfer');
// Route::get('/payment/bkash/b2c/payout', 'PaymentGateway\bKash@b2c_payment');

// AamarPay
Route::get('/payment/aamarpay/checkout/{order_id}', 'PaymentGateway\Aamarpay@index');


//start(new-R)
Route::get('/grocery/SeasonalProduct/add', 'Grocery\RouteController@addSeasonalProduct');
Route::get('/grocery/SeasonalProduct/addSeasonalCampain', 'Grocery\RouteController@addSeasonalCampain');

Route::get('/grocery/marketing-banner/add', 'Grocery\RouteController@addMarketingBanner');
Route::post('/grocery/marketing-banner/create', 'Grocery\RouteController@createMarketingBanner');

Route::get('/grocery/homepage-banner/add', 'Grocery\RouteController@addHomepageBanner');
Route::post('/grocery/homepage-banner/create', 'Grocery\RouteController@createHomepageBanner');

Route::post('/grocery/seasonalProducts/create', 'Grocery\ProductController@createSeasonalProducts');
Route::post('/grocery/seasonalProducts/createCampain', 'Grocery\ProductController@createSeasonalProductsCampain');
Route::get('/grocery/manageSeasonalCampain', 'Grocery\RouteController@manageSeasonalCampain');
Route::post('/grocery/SeasonalCampain/status/update', 'Grocery\ProductController@SeasonalCampainStatusUpdate');
Route::get('/grocery/SeasonalCampain/edit', 'Grocery\RouteController@SeasonalCampainEdit');
Route::post('/grocery/SeasonalCampain/edit/confirm', 'Grocery\ProductController@SeasonalCampainEdit');
Route::post('/grocery/SeasonalCampain/edit/images', 'Grocery\ProductController@SeasonalCampainEditImages');

//Promo
Route::get('/grocery/addPromo', 'Grocery\RouteController@addPromo');
Route::post('/grocery/promo/create', 'Grocery\PromoController@creatPromo');
Route::get('/grocery/managePromo', 'Grocery\RouteController@managePromo');
Route::post('/grocery/managePromo/updatePromoStatus', 'Grocery\PromoController@updatePromoStatus');
Route::post('/grocery/managePromo/deletePromo', 'Grocery\PromoController@deletePromo');
Route::post('/grocery/managePromo/editPromo', 'Grocery\PromoController@editPromo');
Route::post('/grocery/managePromo/updatePromoImage', 'Grocery\PromoController@updatePromoImage');

//invoice picture Manage
Route::get('/grocery/invoiceManage', 'Grocery\RouteController@invoiceManage');
Route::post('/grocery/invoiceManage/create', 'Grocery\InvoiceController@invoiceAddImage');
Route::post('/grocery/invoiceManage/updateStatus', 'Grocery\InvoiceController@invoiceImageUpdateStatus');

//end


// Restaurant START
Route::get('/restaurant/addRestaurant', 'Restaurant\RouteController@addRestaurant');
Route::post('/restaurant/create', 'Restaurant\ResturantController@create');
Route::get('/restaurant/RestaurantList', 'Restaurant\RouteController@RestaurantList');
Route::post('/restaurant/RestaurantList/status/update', 'Restaurant\ResturantController@RestaurantStatusUpdate');
Route::post('/restaurant/RestaurantList/restaurantUpdate', 'Restaurant\ResturantController@restaurantUpdate');
Route::post('/restaurant/RestaurantList/restaurantCategoryUpdate', 'Restaurant\ResturantController@restaurantCategoryUpdate');

// Property
Route::get('/restaurant/RestaurantList/addProperty', 'Restaurant\RouteController@addProperty');
Route::post('/restaurant/RestaurantList/addProperty/create', 'Restaurant\ResturantController@PropertyCreate');
Route::post('/restaurant/RestaurantList/addProperty/updateDiscount', 'Restaurant\ResturantController@PropertyDiscountUpdate');
Route::get('/restaurant/RestaurantList/addProperty/removeDiscount', 'Restaurant\ResturantController@PropertyDiscountRemove');
Route::get('/restaurant/RestaurantList/editProperty', 'Restaurant\RouteController@editProperty');
Route::post('/restaurant/RestaurantList/editProperty/updateProperty', 'Restaurant\ResturantController@PropertyUpdate');

//Branch Start
Route::get('/restaurant/addBranch', 'Restaurant\RouteController@addBranch');
Route::post('/restaurant/addBranch/create', 'Restaurant\BrachController@Create');
Route::post('/restaurant/addBranch/updateStatus', 'Restaurant\BrachController@addBranchUpdateStatus');
Route::post('/restaurant/addBranch/updateBranchName', 'Restaurant\BrachController@updateBranchName');
Route::post('/restaurant/addBranch/deleteBranch', 'Restaurant\BrachController@DeleteBranch');

//category
Route::get('/restaurant/ProductCategory', 'Restaurant\RouteController@ProductCategory');
Route::post('/restaurant/ProductCategory/create', 'Restaurant\ResturantController@ProductCategoryCreate');
Route::post('/restaurant/ProductCategory/updateStatus', 'Restaurant\ResturantController@ProductCategoryUpdateStatus');
Route::post('/restaurant/ProductCategory/deleteCategory', 'Restaurant\ResturantController@ProductCategoryDeleteCategory');
Route::get('/restaurant/RestaurantCategory', 'Restaurant\RouteController@RestaurantCategory');
Route::post('/restaurant/RestaurantCategory/create', 'Restaurant\ResturantController@RestaurantCategoryCreate');
Route::post('/restaurant/RestaurantCategory/updateStatus', 'Restaurant\ResturantController@RestaurantCategoryUpdateStatus');
Route::post('/restaurant/RestaurantCategory/deleteCategory', 'Restaurant\ResturantController@RestaurantCategorydeleteCategory');

//Restaurant Product
Route::get('/restaurant/addProduct', 'Restaurant\RouteController@addProduct');
Route::get('/restaurant/addProduct/showRestaurantList', 'Restaurant\ResturantController@addProductShowRestaurantList');
Route::get('/restaurant/addProduct/showBranchList', 'Restaurant\ResturantController@addProductShowBranchList');
Route::post('/restaurant/addProduct/create', 'Restaurant\ProductController@AddProductCreate');
Route::get('/restaurant/allProduct', 'Restaurant\RouteController@AllProduct');
Route::post('/restaurant/allProduct/updateStatus', 'Restaurant\ProductController@updateStatus');
Route::post('/restaurant/allProduct/updateDiscount', 'Restaurant\ProductController@DiscountUpdate');
Route::post('/restaurant/allProduct/updateProduct', 'Restaurant\ProductController@ProdcutInfoUpdate');
Route::post('/restaurant/allProduct/updateImage', 'Restaurant\ProductController@updateImage');


//Restautnt Promo
Route::get('/restaurant/addPromo', 'Restaurant\RouteController@addPromo');
Route::post('/restaurant/promo/create', 'Restaurant\PromoController@creatPromo');
Route::get('/restaurant/managePromo', 'Restaurant\RouteController@managePromo');
Route::post('/restaurant/managePromo/updatePromoStatus', 'Restaurant\PromoController@updatePromoStatus');
Route::post('/restaurant/managePromo/deletePromo', 'Restaurant\PromoController@deletePromo');
Route::post('/restaurant/managePromo/editPromo', 'Restaurant\PromoController@editPromo');
Route::post('/restaurant/managePromo/updatePromoImage', 'Restaurant\PromoController@updatePromoImage');

//invoice
Route::get('/restaurant/print/invoice', 'Restaurant\InvoiceController@orderInvoice');

//order manage
Route::get('/restaurant/order', 'Restaurant\RouteController@orders');
Route::get('/restaurant/order/edit', 'Restaurant\RouteController@editOrder');
Route::post('/restaurant/order/updateOrder', 'Restaurant\OrderController@updateOrder');
// Route::get('/grocery/order/new', 'Grocery\RouteController@createManualOrder');
Route::get('/restaurant/order/confirm', 'Restaurant\OrderController@confirmOrder');
Route::get('/restaurant/order/complete', 'Restaurant\OrderController@completeOrder');
Route::get('/restaurant/order/pending', 'Restaurant\OrderController@pendingOrder');
Route::get('/restaurant/order/cancel', 'Restaurant\OrderController@cancelOrder');
Route::get('/restaurant/order/delete', 'Restaurant\OrderController@deleteOrder');
// Route::get('/grocery/products/sale', 'Grocery\RouteController@saleAnalysis');
Route::post('/restaurant/order/edit/discount', 'Restaurant\OrderController@applyDiscount');
// Route::post('/grocery/order/createWebOrder', 'Grocery\OrderController@createWebOrder');
Route::post('/restaurant/order/schedule', 'Restaurant\OrderController@changeSchedule');

// Restaurant END

//server Maintenance
Route::get('/serverMaintenance', 'Grocery\MaintenanceController@serverMaintenance');
Route::post('/serverMaintenance/update', 'Grocery\MaintenanceController@update');
