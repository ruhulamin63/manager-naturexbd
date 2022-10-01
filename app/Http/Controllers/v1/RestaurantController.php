<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\CityManager;
use App\Models\Backend\FeaturedRestaurantManager;
use App\Models\Backend\RestaurantManager;
use App\Models\Backend\RestaurantProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function getRestaurantInfo(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'restaurantID' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $restaurantID = $request->input('restaurantID');
                $restaurantDetails = RestaurantManager::select('*')->where('restaurant_id', $restaurantID)->get();
                return response()->json($restaurantDetails);
            }
        }
    }

    public function updateRestaurantInfo(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'rest_id' => 'required',
                'rest_name' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $rest_id = $request->input('rest_id');
                $updateArray = array();

                if ($request->has('rest_name')) {
                    $updateArray['restaurant_name'] = $request->input('rest_name');
                }

                if ($request->has('rest_mobile')) {
                    $updateArray['restaurant_mobile'] = $request->input('rest_mobile');
                }

                if ($request->has('rest_address')) {
                    $updateArray['restaurant_address'] = $request->input('rest_address');
                }

                if ($request->has('rest_geolocation')) {
                    $updateArray['restaurant_coordinate'] = $request->input('rest_geolocation');
                }

                if ($request->has('rest_delivery')) {
                    $updateArray['delivery_charge'] = $request->input('rest_delivery');
                } else {
                    $updateArray['delivery_charge'] = '0';
                }

                if ($request->has('rest_category')) {
                    $category = "";
                    $rest_category = $request->input('rest_category');
                    foreach ($rest_category as $key => $rest_cat) {
                        if ($key == 0) {
                            $category = $rest_cat;
                        } else {
                            $category = $category . ',' . $rest_cat;
                        }
                    }
                    $updateArray['restaurant_category'] = $category;
                }

                if ($request->has('rest_logo')) {
                    $extension = request()->rest_logo->getClientOriginalExtension();
                    $request->rest_logo->storeAs('public/app/restaurants/logo', $rest_id . '.' . $extension);
                    $imageURL = 'storage/app/restaurants/logo/' . $rest_id . '.' . $extension;
                    $updateArray['restaurant_logo'] = $imageURL;
                }

                if ($request->has('rest_preview')) {
                    $extension = request()->rest_preview->getClientOriginalExtension();
                    $request->rest_preview->storeAs('public/temp', $rest_id . '.' . $extension);
                    $imageURL = 'public/temp/' . $rest_id . '.' . $extension;
                    Storage::disk('restaurant_preview')->put($rest_id . '.' . $extension, Storage::get($imageURL));
                    Storage::delete($imageURL);
                    $imageURL = '/app/restaurants/preview/' . $rest_id . '.' . $extension;
                    $updateArray['restaurant_preview'] = $imageURL;
                }

                $update = RestaurantManager::where('restaurant_id', $rest_id)->update($updateArray);

                if ($update) {
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Restaurant info updated successfully!'
                    ]);
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Something went wrong!'
                    ]);
                }
            }
        }
    }

    public function updateRestaurantStatus(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'rest_id' => 'required',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $update = RestaurantManager::where('restaurant_id', $request->input('rest_id'))
                    ->update(['status' => $request->input('status')]);
                if ($update) {
                    return response()->json([
                        'message' => 'Restaurant status updated!'
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Something went wrong!'
                    ]);
                }
            }
        }
    }

    public function updateFeaturedRestaurants(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'city_id' => 'required',
                'category' => 'required',
                'featured_restaurants' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $cityID = $request->input('city_id');
                $category = $request->input('category');
                $featured = $request->input('featured_restaurants');

                $update = FeaturedRestaurantManager::where('category', $category)->where('city_id', $cityID)->update([
                    'featured_restaurants' => $featured
                ]);

                if ($update) {
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Featured restaurants list updated successfully!'
                    ]);
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Something went wrong!'
                    ]);
                }
            }
        }
    }

    public function updateFeaturedRestaurantsOrder(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'city_id' => 'required',
                'category' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $cityID = $request->input('city_id');
                $category = $request->input('category');
                $category = explode(",", $category);
                foreach ($category as $key => $rest_category) {
                    FeaturedRestaurantManager::where('city_id', $cityID)->where('category', $rest_category)->update([
                        'sequence' => $key + 1
                    ]);
                }
                return redirect()->back()->with([
                    'error' => false,
                    'message' => 'Featured category sequence updated successfully!'
                ]);
            }
        }
    }

    public function restaurantList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $restaurants = array();
            $city = $request->input('city');
            $cityID = CityManager::select('*')->where('city_name', $city)->get();
            $cityID = $cityID[0]->city_id;
            $restaurantList = RestaurantManager::select('*')->where('city_id', $cityID)->where('status', 'Active')->get();
            foreach ($restaurantList as $key => $restaurant) {
                $restaurantProperty = RestaurantProperty::select('*')->where('restaurant_id', $restaurant->restaurant_id)->get();
                $restaurants[$key]['restaurant_id'] = $restaurant->restaurant_id;
                $restaurants[$key]['restaurant_name'] = $restaurant->restaurant_name;
                $restaurants[$key]['restaurant_category'] = $restaurant->restaurant_category;
                $restaurants[$key]['restaurant_preview'] = $restaurant->restaurant_preview;
                $restaurants[$key]['restaurant_area'] = $restaurantProperty[0]->restaurant_area;
                $restaurants[$key]['discount_percentage'] = $restaurantProperty[0]->discount_percentage;
            }
            return response()->json([
                'error' => false,
                'message' => $restaurants
            ]);
        }
    }

    public function featureRestaurantList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $root_key = 0;
            $restaurants = array();
            $city = $request->input('city');
            $cityID = CityManager::select('*')->where('city_name', $city)->get();
            $cityID = $cityID[0]->city_id;
            $featureList = FeaturedRestaurantManager::select('*')->where('city_id', $cityID)->orderBy('sequence', 'ASC')->get();
            foreach ($featureList as $feature) {
                $restaurantIDs = $feature->featured_restaurants;
                $restaurantIDs = explode(",", $restaurantIDs);
                if (count($restaurantIDs) != 0) {
                    foreach ($restaurantIDs as $restaurant_temp) {
                        $restaurantList = RestaurantManager::select('*')->where('city_id', $cityID)->where('restaurant_id', $restaurant_temp)->get();
                        foreach ($restaurantList as $key => $restaurant) {
                            $restaurantProperty = RestaurantProperty::select('*')->where('restaurant_id', $restaurant->restaurant_id)->get();
                            $restaurants[$root_key]['restaurant_id'] = $restaurant->restaurant_id;
                            $restaurants[$root_key]['restaurant_name'] = $restaurant->restaurant_name;
                            $restaurants[$root_key]['restaurant_category'] = $restaurant->restaurant_category;
                            $restaurants[$root_key]['restaurant_preview'] = $restaurant->restaurant_preview;
                            $restaurants[$root_key]['restaurant_area'] = $restaurantProperty[0]->restaurant_area;
                            $restaurants[$root_key]['discount_percentage'] = $restaurantProperty[0]->discount_percentage;
                            $root_key += 1;
                        }
                    }
                }
            }

            return response()->json([
                'error' => false,
                'message' => $restaurants
            ]);
        }
    }

    public function recommendedRestaurantList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $root_key = 0;
            $restaurants = array();
            $city = $request->input('city');
            $cityID = CityManager::select('*')->where('city_name', $city)->get();
            $cityID = $cityID[0]->city_id;
            $restaurantList = RestaurantManager::select('*')->where('city_id', $cityID)->where('status', 'Active')->get();
            foreach ($restaurantList as $restaurant) {
                $restaurantProperty = RestaurantProperty::select('*')->where('restaurant_id', $restaurant->restaurant_id)->get();
                if ($restaurantProperty[0]->discount_percentage != 0) {
                    $restaurants[$root_key]['restaurant_id'] = $restaurant->restaurant_id;
                    $restaurants[$root_key]['restaurant_name'] = $restaurant->restaurant_name;
                    $restaurants[$root_key]['restaurant_category'] = $restaurant->restaurant_category;
                    $restaurants[$root_key]['restaurant_preview'] = $restaurant->restaurant_preview;
                    $restaurants[$root_key]['restaurant_area'] = $restaurantProperty[0]->restaurant_area;
                    $restaurants[$root_key]['discount_percentage'] = $restaurantProperty[0]->discount_percentage;
                    $root_key += 1;
                }
            }

            shuffle($restaurants);

            return response()->json([
                'error' => false,
                'message' => $restaurants
            ]);
        }
    }

    public function setRestaurantDiscount(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'rest_id' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $discountAmount = $request->input('discount_amount');
                if ($request->input('discount_status') == "YES") {
                    RestaurantProperty::where('restaurant_id', $request->input('rest_id'))->update(['discount_percentage' => $discountAmount]);
                } else {
                    RestaurantProperty::where('restaurant_id', $request->input('rest_id'))->update(['discount_percentage' => 0]);
                }
                return redirect()->back()->with([
                    'error' => false,
                    'message' => 'Restaurant discount set successfully!'
                ]);
            }
        }
    }
}
