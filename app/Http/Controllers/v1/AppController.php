<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\APIManager;
use App\Models\Backend\CityManager;
use App\Models\Backend\FeaturedRestaurantManager;
use App\Models\Backend\RestaurantCategory;
use App\Models\Backend\RestaurantManager;
use App\Models\Backend\RestaurantProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    public function getAppData(Request $request)
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
            $categoryList = array();
            $recommendedList = array();
            $featuredRestaurants = array();
            $featuredOrder = array();
            $allRestaurants = array();
            $city = $request->input('city');
            $cityID = CityManager::select('*')->where('city_name', $city)->get();
            if (count($cityID) != 1) {
                $city = "Rangpur";
                $cityID = CityManager::select('*')->where('city_name', 'Rangpur')->get();
            }
            $cityID = $cityID[0]->city_id;
            $categories = RestaurantCategory::all();
            foreach ($categories as $key => $category) {
                $categoryList[$key]['key_no'] = $key;
                $categoryList[$key]['title'] = $category->category;
                $categoryList[$key]['image'] = asset($category->image);
                $categoryList[$key]['places'] = count(RestaurantManager::select('*')->where('restaurant_category', 'LIKE', '%' . $category->category . '%')->get());
            }
            $restaurantProperty = RestaurantProperty::select('*')->where('discount_percentage', '>', '0')->orderBy('discount_percentage', 'DESC')->get();
            foreach ($restaurantProperty as $key => $info) {
                $restaurantInfo = RestaurantManager::select('*')->where('restaurant_id', $info->restaurant_id)->get();
                $recommendedList[$key]['key_no'] = $key;
                $recommendedList[$key]['restaurant_id'] = $restaurantInfo[0]->restaurant_id;
                $recommendedList[$key]['restaurant_name'] = $restaurantInfo[0]->restaurant_name;
                $recommendedList[$key]['restaurant_category'] = $restaurantInfo[0]->restaurant_category;
                $preview = asset($restaurantInfo[0]->restaurant_preview);
                if ($restaurantInfo[0]->restaurant_preview == "") {
                    $preview = "";
                }
                $recommendedList[$key]['restaurant_preview'] = $preview;
                $recommendedList[$key]['restaurant_area'] = $info->restaurant_area;
                $recommendedList[$key]['discount_percentage'] = $info->discount_percentage;
            }

            shuffle($recommendedList);
            $recommendedList = collect($recommendedList)->SortByDesc('discount_percentage')->values()->all();

            $featureList = FeaturedRestaurantManager::select('*')->where('city_id', $cityID)->orderBy('sequence', 'ASC')->get();
            $key = 0;
            foreach ($featureList as $fKey => $feature) {
                $featuredOrder[$fKey]['key_no'] = $fKey;
                $featuredOrder[$fKey]['category_title'] = $feature->category;
                $featuredOrder[$fKey]['category_order'] = $feature->sequence;
                $restaurantIDs = $feature->featured_restaurants;
                $restaurantIDs = explode(",", $restaurantIDs);
                if (count($restaurantIDs) != 0) {
                    foreach ($restaurantIDs as $restaurant_temp) {
                        $restaurantList = RestaurantManager::select('*')->where('city_id', $cityID)->where('restaurant_id', $restaurant_temp)->get();
                        foreach ($restaurantList as $restaurant) {
                            $restaurantProperty = RestaurantProperty::select('*')->where('restaurant_id', $restaurant->restaurant_id)->get();
                            $featuredRestaurants[$key]['key_no'] = $key;
                            $featuredRestaurants[$key]['restaurant_id'] = $restaurant->restaurant_id;
                            $featuredRestaurants[$key]['restaurant_name'] = $restaurant->restaurant_name;
                            $featuredRestaurants[$key]['restaurant_category'] = $restaurant->restaurant_category;
                            $preview = asset($restaurant->restaurant_preview);
                            if ($restaurant->restaurant_preview == "") {
                                $preview = "";
                            }
                            $featuredRestaurants[$key]['restaurant_preview'] = $preview;
                            $featuredRestaurants[$key]['restaurant_area'] = $restaurantProperty[0]->restaurant_area;
                            $featuredRestaurants[$key]['discount_percentage'] = $restaurantProperty[0]->discount_percentage;
                            $key += 1;
                        }
                    }
                }
            }

            $restaurantList = RestaurantManager::select('*')->where('city_id', $cityID)->where('status', 'Active')->get();
            foreach ($restaurantList as $key => $restaurant) {
                $restaurantProperty = RestaurantProperty::select('*')->where('restaurant_id', $restaurant->restaurant_id)->get();
                $allRestaurants[$key]['key_no'] = $key;
                $allRestaurants[$key]['restaurant_id'] = $restaurant->restaurant_id;
                $allRestaurants[$key]['restaurant_name'] = $restaurant->restaurant_name;
                $allRestaurants[$key]['restaurant_category'] = $restaurant->restaurant_category;
                $preview = asset($restaurant->restaurant_preview);
                if ($restaurant->restaurant_preview == "") {
                    $preview = "";
                }
                $allRestaurants[$key]['restaurant_preview'] = $preview;
                $allRestaurants[$key]['restaurant_area'] = $restaurantProperty[0]->restaurant_area;
                $allRestaurants[$key]['discount_percentage'] = $restaurantProperty[0]->discount_percentage;
            }

            shuffle($allRestaurants);

            return response()->json([
                'error' => false,
                'versionName' => '4.0.5',
                'city' => $city,
                'category' => $categoryList,
                'recommended' => $recommendedList,
                'featuredOrder' => $featuredOrder,
                'featured' => $recommendedList,
                'restaurants' => $allRestaurants
            ]);
        }
    }

    public function getGeoLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $APIKeyDetails = APIManager::select('*')->where('service_identifier', 'REVERSE_GEOCODING')->get();
            $APIKey = $APIKeyDetails[0]->api_key;

            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&key=" . $APIKey,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($response, true);

            $response = $response['results'];
            $areaName = "";
            $addressDetails = $response[0]['formatted_address'];

            foreach ($response as $results) {
                $addressComponent = $results['address_components'];
                foreach ($addressComponent as $address) {
                    $types = $address['types'];
                    if (count($types) == 2) {
                        if ($types[0] == "locality" && $types[1] == "political") {
                            $areaName = $address['long_name'];
                            break 2;
                        }
                    }
                }
            }

            APIManager::where('service_identifier', 'REVERSE_GEOCODING')
                ->update([
                    'api_usage' => ($APIKeyDetails[0]->api_usage) + 1,
                    'used_balance' => number_format((($APIKeyDetails[0]->api_usage) + 1) * 0.005, 2)
                ]);

            return response()->json([
                'error' => false,
                'message' => $request->input('latitude') . "#" . $request->input('longitude') . "#" . $areaName . "#" . $addressDetails,
                'geolocation' => $addressDetails
            ]);
        }
    }
}
