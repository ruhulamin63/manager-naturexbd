<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\MenuManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function addNewItem(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'rest_id' => 'required',
                'item_name' => 'required',
                'item_price' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $restaurantID = $request->input('rest_id');
                $itemName = $request->input('item_name');
                $itemPrice = $request->input('item_price');

                $itemID = strtoupper(Str::random(6));

                $new_menu = new MenuManager();
                $new_menu->restaurant_id = $restaurantID;
                $new_menu->item_id = $itemID;
                $new_menu->item_name = $itemName;
                $new_menu->price = trim($itemPrice);
                $new_menu->discount = "NO";
                $new_menu->discount_type = "-";
                $new_menu->discount_amount = "-";
                $new_menu->status = "Inactive";
                if($request->has('item_description') && $request->input('item_description') != ""){
                    $new_menu->item_description = $request->input('item_description');
                }
                $new_menu->updated_by = $request->session()->get('MANAGER_NAME') . ',' . $request->session()->get('UID');
                if ($new_menu->save()) {
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Item added successfully!'
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

    public function editItem(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'rest_id' => 'required',
                'item_id' => 'required',
                'item_name' => 'required',
                'item_price' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $restaurantID = $request->input('rest_id');
                $itemID = $request->input('item_id');
                $itemName = $request->input('item_name');
                $itemPrice = $request->input('item_price');
                $itemDescription = "";

                if($request->has('item_description') && $request->input('item_description') != ""){
                    $itemDescription = $request->input('item_description');
                }

                $update = MenuManager::where('restaurant_id', $restaurantID)->where('item_id', $itemID)->update([
                    'item_name' => $itemName,
                    'item_description' => $itemDescription,
                    'price' => $itemPrice
                ]);

                if ($update) {
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Item updated successfully!'
                    ]);
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Something went wrong!'
                    ]);
                }
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function deleteItem(Request $request){
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'rest_id' => 'required',
                'item_id' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $restaurantID = $request->input('rest_id');
                $itemID = $request->input('item_id');

                $delete = MenuManager::where('restaurant_id', $restaurantID)->where('item_id', $itemID)->delete();

                if ($delete) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Item deleted successfully!'
                    ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Something went wrong!'
                    ]);
                }
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateItemStatus(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $update = MenuManager::where('item_id', $request->input('item_id'))
                    ->update(['status' => $request->input('status')]);
                if ($update) {
                    return response()->json([
                        'message' => 'Item status updated!'
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Something went wrong!'
                    ]);
                }
            }
        }
    }

    public function restaurantMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $menu = array();
            $restaurantID = $request->input('restaurant_id');
            $menuList = MenuManager::select('*')->where('restaurant_id', $restaurantID)->where('status', 'Active')->get();
            foreach ($menuList as $key => $menuItem) {
                $menu[$key]['item_id'] = $menuItem->item_id;
                $menu[$key]['item_name'] = trim($menuItem->item_name);
                $menu[$key]['item_price'] = trim($menuItem->price);
                if($menuItem->item_description != ""){
                    $menu[$key]['item_description'] = $menuItem->item_description;
                } else {
                    $menu[$key]['item_description'] = "-";
                }
            }

            return response()->json([
                'error' => false,
                'message' => $menu
            ]);
        }
    }

    public function processMenuData(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $csvRead = 'temp/MENU_DATA.csv';
            if (File::exists($csvRead)) {
                $file_handle = fopen($csvRead, 'r');
                if ($file_handle) {
                    while ($line = fgetcsv($file_handle)) {
                        $new_item = new MenuManager();
                        $new_item->restaurant_id = $request->input('restaurant');
                        $new_item->item_id = strtoupper(Str::random(6));
                        $new_item->item_name = $line[1];
                        $new_item->price = $line[2];
                        $new_item->discount = "NO";
                        $new_item->discount_type = "-";
                        $new_item->discount_amount = "-";
                        $new_item->status = "Active";
                        $new_item->updated_by = $request->session()->get('MANAGER_NAME') . ',' . $request->session()->get('UID');;
                        $new_item->save();
                    }
                    fclose($file_handle);
                    unlink($csvRead);
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Menu processed successfully!'
                    ]);
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'No existing csv file found!'
                    ]);
                }
            } else {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'No existing csv file found!'
                ]);
            }
        } else {
            return redirect(url('/dashboard/page/unauthorized'));
        }
    }

    public function uploadRestaurantMenu(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'csv_file' => 'required',
                'restaurant' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                if ($request->has('csv_file')) {
                    $extension = request()->csv_file->getClientOriginalExtension();
                    if ($extension == "csv") {
                        $restaurantID = $request->input('restaurant');
                        MenuManager::where('restaurant_id', $restaurantID)->delete();
                        $request->csv_file->storeAs('public/app/restaurants/data', 'MENU_DATA' . '.' . $extension);
                        $csvFile = 'public/app/restaurants/data/MENU_DATA.csv';
                        Storage::disk('csv')->put('MENU_DATA.csv', Storage::get($csvFile));
                        Storage::delete($csvFile);
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Menu csv uploaded successfully!'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Please upload a CSV file!'
                        ]);
                    }
                } else {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Something went wrong!'
                    ]);
                }
            }
        }
    }
}
