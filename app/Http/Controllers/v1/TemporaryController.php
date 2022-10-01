<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\RestaurantManager;
use App\Models\Backend\RestaurantProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TemporaryController extends Controller
{
    private function readCSV($csvFile)
    {
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 1024);
        }
        fclose($file_handle);
        return $line_of_text;
    }

    public function uploadRangpur(Request $request)
    {
        $csvFile = 'Rangpur.csv';
        $cityID = '9876';

        $csv = $this->readCSV($csvFile);

        foreach($csv as $key => $data) {
            $restaurantName = $data[0];
            $restaurantAddress = $data[2];
            $restaurantMobile = $data[3];
            $restaurantCoordinate = $data[4];
            echo $restaurantName . "\n";
            $rest_id = strtoupper(Str::random(8));
            $new_restaurant = new RestaurantManager();
            $new_restaurant->city_id = $cityID;
            $new_restaurant->restaurant_id = $rest_id;
            $new_restaurant->restaurant_name = $restaurantName;
            $new_restaurant->restaurant_category = $data[10];
            $new_restaurant->restaurant_address = $restaurantAddress;
            $new_restaurant->restaurant_mobile = $restaurantMobile;
            $new_restaurant->restaurant_coordinate = $restaurantCoordinate;
            $new_restaurant->restaurant_logo = "";
            $new_restaurant->restaurant_preview = "";
            $new_restaurant->delivery_charge = "45";
            $new_restaurant->status = "Active";
            $new_restaurant->updated_by = "System,0";
            $new_restaurant->save();

            $new_res_property = new RestaurantProperty();
            $new_res_property->restaurant_id = $rest_id;
            $new_res_property->restaurant_rating = $data[8];
            $new_res_property->opening_time = $data[6];
            $new_res_property->closing_time = $data[7];
            $new_res_property->restaurant_area = $data[5];
            $new_res_property->discount_percentage = "0";
            $new_res_property->save();
            if($key == 54){
                break;
            }
        }
    }
}
