<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\CityManager;
use App\Models\Backend\FeaturedRestaurantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function addNewCity(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'city_name' => 'required',
                'city_status' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $city_id = mt_rand(1000, 9999);
                $existence = CityManager::select('*')->where('city_id', $city_id)->get();
                while(count($existence) != 0){
                    $city_id = mt_rand(1000, 9999);
                    $existence = CityManager::select('*')->where('city_id', $city_id)->get();
                }
                $city_name = $request->input('city_name');
                $cityExistence = CityManager::select('*')->where('city_name', $city_name)->get();
                if(count($cityExistence) == 0){
                    $new_city = new CityManager();
                    $new_city->city_name = $city_name;
                    $new_city->city_id = $city_id;
                    $new_city->status = $request->input('city_status');
                    $new_city->updated_by = $request->session()->get('MANAGER_NAME') . ',' . $request->session()->get('UID');
                    if($new_city->save()){
                        $new_featured = new FeaturedRestaurantManager();
                        $new_featured->city_id = $city_id;
                        $new_featured->category = 'Fast Food';
                        $new_featured->sequence = '1';
                        $new_featured->featured_restaurants = '';
                        $new_featured->status = 'Inactive';
                        $new_featured->save();

                        $new_featured = new FeaturedRestaurantManager();
                        $new_featured->city_id = $city_id;
                        $new_featured->category = 'Bangla';
                        $new_featured->sequence = '2';
                        $new_featured->featured_restaurants = '';
                        $new_featured->status = 'Inactive';
                        $new_featured->save();
                        
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Success! New city added successfully!'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Error! Something went wrong.'
                        ]);
                    }
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Error! City already exist.'
                    ]);
                }
            }
        }
    }
}
