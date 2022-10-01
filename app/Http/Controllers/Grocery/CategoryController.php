<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Category;
use App\Models\Grocery\City;
use App\Models\Grocery\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('GR_LOGGED_IN') && $request->session()->get('GR_LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
        // return true;
    }

    private function hasPermission(Request $request, $page)
    {
        if ($request->session()->has('GR_MANAGER_EMAIL')) {
            $email = $request->session()->get('GR_MANAGER_EMAIL');
            $permission = Admin::where('email', $email)->where('permissions', 'LIKE', '%' . $page . '%')->get();
            if (count($permission) == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        // return true;
    }

    public function createCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'add_category')) {
                $validator = Validator::make($request->all(), [
                    'city_coverage' => 'required',
                    'category_name' => 'required',
                    'category_thumbnail' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityCoverage = $request->input('city_coverage');
                    $cityList = City::all();
                    foreach ($cityList as $city) {
                        $existing = Category::select('*')
                            ->where('cityID', $city->id)
                            ->where('category', $request->input('category_name'))
                            ->get();
                        if (count($existing) == 0) {
                            $imageID = strtoupper(Str::random(6));
                            $extension = request()->category_thumbnail->getClientOriginalExtension();
                            $request->category_thumbnail->storeAs('public/temp', $imageID . '.' . $extension);
                            $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                            Storage::disk('grocery_category')->put($imageID . '.' . $extension, Storage::get($imageURL));
                            Storage::delete($imageURL);
                            $imageURL = '/app/grocery/category/' . $imageID . '.' . $extension;

                            $newCategory = new Category();
                            $newCategory->cityID = $city->id;
                            $newCategory->thumbnail = $imageURL;
                            $newCategory->category = $request->input('category_name');
                            if (in_array($city->id, $cityCoverage)) {
                                $newCategory->status = 'Active';
                            } else {
                                $newCategory->status = 'Inactive';
                            }
                            $newCategory->save();
                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Category already esxists under current city.'
                            ]);
                        }
                    }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Category added successfully.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function categoryStatusUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'update_category')) {
                $validator = Validator::make($request->all(), [
                    'city_id' => 'required',
                    'category_id' => 'required',
                    'category_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $update = Category::where('cityID', $request->input('city_id'))
                        ->where('id', $request->input('category_id'))
                        ->update(['status' => $request->input('category_status')]);

                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Category updated successfully.'
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'You don\'t have permission to edit data.'
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function categoryPrepaymentUpdate(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'update_category')) {
                $validator = Validator::make($request->all(), [
                    'city_id' => 'required',
                    'category_id' => 'required',
                    'payment_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $update = Category::where('cityID', $request->input('city_id'))
                        ->where('id', $request->input('category_id'))
                        ->update(['prepayment' => $request->input('payment_status')]);

                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Category updated successfully.'
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'You don\'t have permission to edit data.'
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function editCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'edit_category')) {
                $validator = Validator::make($request->all(), [
                    'category_old_name' => 'required',
                    'category_name' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    if ($request->has('category_thumbnail')) {
                        $currentData = Category::select('*')->where('category', $request->input('category_old_name'))->get();
                        $path = public_path() . $currentData[0]->thumbnail;
                        unlink($path);
                        $imageID = strtoupper(Str::random(6));
                        $extension = request()->category_thumbnail->getClientOriginalExtension();
                        $request->category_thumbnail->storeAs('public/temp', $imageID . '.' . $extension);
                        $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                        Storage::disk('grocery_category')->put($imageID . '.' . $extension, Storage::get($imageURL));
                        Storage::delete($imageURL);
                        $imageURL = '/app/grocery/category/' . $imageID . '.' . $extension;

                        $update = Category::where('category', $request->input('category_old_name'))->update([
                            'category' => $request->input('category_name'),
                            'thumbnail' => $imageURL
                        ]);

                        Products::where('category', $request->input('category_old_name'))->update([
                            'category' => $request->input('category_name')
                        ]);
                    } else {
                        $update = Category::where('category', $request->input('category_old_name'))->update([
                            'category' => $request->input('category_name')
                        ]);

                        Products::where('category', $request->input('category_old_name'))->update([
                            'category' => $request->input('category_name')
                        ]);
                    }

                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Category updated successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            } else {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'You don\'t have permission to edit data.'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'error' => true,
                'message' => 'Please login again to continue.'
            ]);
        }
    }

    public function getCategoryList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $categoryList = Category::select('*')
                ->where('cityID', $request->input('city'))
                ->where('status', 'Active')->get();
            foreach ($categoryList as $key => $category) {
                $categoryList[$key]['thumbnail'] = url($category->thumbnail);
            }

            $offerImage = "";

            if($request->input('city') == "1"){
                $offerImage = asset('/images/offer/dhaka.jpg');
            } else {
                $offerImage = asset('/images/offer/outDhaka.jpg');
            }

            return response()->json([
                'error' => false,
                'message' => 'All Active Category List',
                'category' => $categoryList,
                'offer' => false,
                'offerImage' => $offerImage
            ]);
        }
    }

    public function regenerateCategory(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'regenerate_category')) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityID = $request->input('id');
                    $categoryList = Category::select('*')->where('cityID', '1')->get();
                    $regenCount = 0;
                    foreach ($categoryList as $category) {
                        $existing = Category::select('*')
                            ->where('cityID', $cityID)
                            ->where('category', $category->category)
                            ->get();
                        if (count($existing) == 0) {
                            $newCategory = new Category();
                            $newCategory->cityID = $cityID;
                            $newCategory->thumbnail = $category->thumbnail;
                            $newCategory->category = $category->category;
                            $newCategory->status = $category->status;
                            $newCategory->save();

                            $regenCount++;
                        }
                    }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => $regenCount . ' category regenerated successfully.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }
}
