<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\RestaurantCategory;
use App\Models\Backend\RestaurantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantCategoryController extends Controller
{
    public function getFoodCategoryList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $allCategories = RestaurantCategory::all();
            $categoryList = array();
            foreach($allCategories as $key => $category){
                $categoryList[$key]['title'] = $category->category;
                $categoryList[$key]['image'] = "http://103.198.136.1/pizza.png";
                $categoryList[$key]['places'] = count(RestaurantManager::select('*')->where('restaurant_category', 'LIKE', '%' . $category->category . '%')->get());
            }
            return response()->json([
                'error' => false,
                'message' => $categoryList
            ]);
        }
    }
}
