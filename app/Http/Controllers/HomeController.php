<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function getHomeData(Request $request){
        $validator = Validator::make($request->all(), [
            'mobile' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $explore = array();
            $explore[0]['image'] = url('/app/grocery/home/1.jpg');
            $explore[1]['image'] = url('/app/grocery/home/2.jpg');
            $explore[2]['image'] = url('/app/grocery/home/3.jpg');
            $explore[3]['image'] = url('/app/grocery/home/4.jpg');
            $explore[4]['image'] = url('/app/grocery/home/5.jpg');

            return response()->json([
                'error' => false,
                'message' => 'All Active Service List',
                'shopTitle' => "Super Shop",
                'shopImg' => url('/app/grocery/home/super-shop.png'),
                'medicineTitle' => "Pharmacy",
                'medicineImg' => url('/app/grocery/home/medicine.png'),
                'restaurantTitle' => "Restaurant",
                'restaurantImg' => url('/app/grocery/home/restaurant.png'),
                'exploreTitle' => "Explore Naturex",
                'explore' => $explore
            ]);
        }
    }
}
