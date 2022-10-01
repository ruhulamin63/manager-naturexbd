<?php

namespace App\Http\Controllers\MegaDays;

use App\Http\Controllers\Controller;
use App\Models\Grocery\Admin;
use App\Models\Grocery\City;
use App\Models\Grocery\Products;
use App\Models\MegaDays\MegaDays;
use App\Models\MegaDays\MegaDaysCategory;
use App\Models\MegaDays\MegaDaysProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MegaDaysController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('GR_LOGGED_IN') && $request->session()->get('GR_LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
        return true;
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

    public function create(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                $cityList = City::all();
                return view('MegaDays.CreateMegaDays')
                    ->with('title', 'Mega Days')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function manage(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                $cityList = City::all();
                $megaDays = MegaDays::orderBy('created_at', 'DESC')->get();
                return view('MegaDays.ManageMegaDays')
                    ->with('title', 'Mega Days')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('megaDays', $megaDays);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function store(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                $slug = $request->input('slug');
                $existing = MegaDays::where('slug', $slug)->get();

                if (count($existing) > 0) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Mega days already exist with the slug.'
                    ])->withInput();
                } else {
                    $imageID = strtoupper(Str::random(6));
                    $extension = request()->banner->getClientOriginalExtension();
                    $request->banner->storeAs('public/temp', $imageID . '.' . $extension);
                    $imageURL = 'public/temp/' . $imageID . '.' . $extension;
                    Storage::disk('mega_days')->put($imageID . '.' . $extension, Storage::get($imageURL));
                    Storage::delete($imageURL);
                    $imageURL = '/app/megadays/' . $imageID . '.' . $extension;

                    $newMegaDays = new MegaDays();
                    $newMegaDays->mid = "MID" . time();
                    $newMegaDays->title = ucwords($request->input('title'));
                    $newMegaDays->slug = strtolower($request->input('slug'));
                    $newMegaDays->description = $request->input('description');
                    $newMegaDays->banner = asset($imageURL);
                    $newMegaDays->status = "Inactive";
                    $newMegaDays->save();

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Mega days created successfully.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function status(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                $mid = $request->input('mid');
                $status = $request->input('status');

                MegaDays::where('mid', $mid)->update([
                    'status' => $status
                ]);

                return response()->json([
                    'error' => false,
                    'message' => 'Status updated successfully.'
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Permission denied.'
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Please login again.'
            ]);
        }
    }

    public function category(Request $request, $mid)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                $cityList = City::all();
                $megaCategory = MegaDaysCategory::where('mid', $mid)->get();
                return view('MegaDays.MegaDaysCategories')
                    ->with('title', 'Mega Days')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('mid', $mid)
                    ->with('megaCategory', $megaCategory);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function storeCategory(Request $request, $mid)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                $existing = MegaDaysCategory::where('title', $request->input('category_title'))->get();

                if (count($existing) > 0) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Mega days category already exist with the title.'
                    ])->withInput();
                } else {
                    $newCategory = new MegaDaysCategory();
                    $newCategory->mid = $mid;
                    $newCategory->cid = 'CID' . time();
                    $newCategory->title = ucwords($request->input('title'));
                    $newCategory->subtitle = ucwords($request->input('subtitle'));
                    $newCategory->save();

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Mega days category created successfully.'
                    ]);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Permission denied.'
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Please login again.'
            ]);
        }
    }

    public function products(Request $request, $mid, $cid)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                $cityList = City::all();
                $megaProducts = MegaDaysProducts::where('cid', $cid)->get();
                $products = Products::where('cityID', '1')->get();
                return view('MegaDays.MegaDaysProducts')
                    ->with('title', 'Mega Days')
                    ->with('date', date('d-M-Y'))
                    ->with('cityList', $cityList)
                    ->with('mid', $mid)
                    ->with('cid', $cid)
                    ->with('megaProducts', $megaProducts)
                    ->with('products', $products);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function storeProduct(Request $request, $mid, $cid)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                $productID = $request->input('productID');
                $discountedPrice = $request->input('discountedPrice');

                $productInfo = Products::where('id', $productID)->get();

                $newProduct = new MegaDaysProducts();
                $newProduct->cid = $cid;
                $newProduct->pid = $productID;
                $newProduct->category_name = $productInfo[0]->category;
                $newProduct->product_name = $productInfo[0]->product_name;
                $newProduct->product_description = $productInfo[0]->product_description;
                $newProduct->regular_price = $productInfo[0]->product_price;
                $newProduct->discounted_price = $discountedPrice;
                $newProduct->product_image = $productInfo[0]->product_thumbnail;
                $newProduct->save();

                return response()->json([
                    'error' => false,
                    'message' => 'Added successfully!'
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Permission denied.'
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Please login again.'
            ]);
        }
    }

    public function deleteProduct(Request $request, $cid, $pid)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                MegaDaysProducts::where('cid', $cid)->where('pid', $pid)->delete();

                return redirect()->back()->with([
                    'error' => false,
                    'message' => 'Mega days product removed successfully.'
                ]);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function deleteCategory(Request $request, $mid, $cid)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                MegaDaysCategory::where('cid', $cid)->where('mid', $mid)->delete();
                MegaDaysProducts::where('cid', $cid)->delete();

                return redirect()->back()->with([
                    'error' => false,
                    'message' => 'Mega days category removed successfully.'
                ]);
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function delete(Request $request, $mid)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'mega_days')) {
                $getcid = MegaDaysCategory::where('mid', $mid)->get();

                if (count($getcid) == 0) {
                    MegaDays::where('mid', $mid)->delete();

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Mega days removed successfully.'
                    ]);
                } else {
                    foreach ($getcid as $item) {
                        $cid = $item->cid;
                        MegaDaysCategory::where('cid', $cid)->where('mid', $mid)->delete();
                        MegaDaysProducts::where('cid', $cid)->delete();
                    }

                    MegaDays::where('mid', $mid)->delete();

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Mega days removed successfully.'
                    ]);
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function viewMegaDays($slug){
        $response = array();
        $megaDaysInfo = MegaDays::where('slug', $slug)->get();

        if(count($megaDaysInfo) == 1){
            $response['info'] = $megaDaysInfo;
            $mid = $megaDaysInfo[0]->mid;
            $productCategories = MegaDaysCategory::where('mid', $mid)->get();
            foreach($productCategories as $key => $item){
                $cid = $item->cid;
                $megaProducts = MegaDaysProducts::where('cid', $cid)->get();
                $response['category'][$key]['category_title'] = $item->title;
                $response['category'][$key]['category_subtitle'] = $item->subtitle;
                foreach($megaProducts as $index => $product){
                    $megaProducts[$index]['product_image'] = asset($product->product_image);
                }
                $response['category'][$key]['products'] = $megaProducts;
            }

            return response()->json([
                'error' => false,
                'message' => $response
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong.'
            ]);
        }
    }
}
