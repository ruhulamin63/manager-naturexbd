<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\RestaurantCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function addNewCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'category_title' => 'required',
                    'category_image' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Error! Please fill up all the fields.'
                    ]);
                } else {
                    $image_name = strtoupper(Str::random(6));
                    $extension = request()->category_image->getClientOriginalExtension();
                    $request->category_image->storeAs('public/app/restaurants/category/', $image_name . '.' . $extension);
                    $imageURL = 'storage/app/restaurants/category/' . $image_name . '.' . $extension;

                    $category_title = $request->input('category_title');

                    $new_category = new RestaurantCategory();
                    $new_category->category = $category_title;
                    $new_category->image = $imageURL;
                    $new_category->updated_by = $request->session()->get('MANAGER_NAME') . ',' . $request->session()->get('UID');
                    if ($new_category->save()) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Success! New category added successfully!'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Error! Something went wrong.'
                        ]);
                    }
                }
            }
        } else {
            return redirect('/dashboard');
        }
    }

    public function updateCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    '_category_id' => 'required',
                    '_category_title' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Error! Please fill up all the fields.'
                    ]);
                } else {
                    $category_id = $request->input('_category_id');
                    $title = $request->input('_category_title');

                    $currentDetails = RestaurantCategory::select('*')->where('id', $category_id)->get();

                    if ($request->has('_category_image')) {
                        $image_name = strtoupper(Str::random(6));
                        $extension = request()->_category_image->getClientOriginalExtension();
                        $request->_category_image->storeAs('public/temp', $image_name . '.' . $extension);
                        $imageURL = 'public/temp/' . $image_name . '.' . $extension;

                        Storage::disk('restaurant_category')->put($image_name . '.' . $extension, Storage::get($imageURL));
                        Storage::delete($imageURL);

                        $imageURL = '/app/restaurants/category/' . $image_name . '.' . $extension;

                        if ($currentDetails[0]->image != null || $currentDetails[0]->image != "") {
                            unlink($currentDetails[0]->image);
                        }

                        RestaurantCategory::where('id', $category_id)->update(['category' => $title, 'image' => $imageURL]);
                    } else {
                        RestaurantCategory::where('id', $category_id)->update(['category' => $title]);
                    }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Success! Category updated successfully!'
                    ]);
                }
            }
        } else {
            return redirect('/dashboard');
        }
    }
}
