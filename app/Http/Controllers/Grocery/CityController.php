<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use App\Models\AreaCoverage;
use App\Models\Grocery\Admin;
use App\Models\Grocery\City;
use App\Models\Grocery\CityPreview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
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

    public function createCity(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'add_city')) {
                $validator = Validator::make($request->all(), [
                    'city_name' => 'required',
                    'city_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityName = $request->input('city_name');
                    $exisitng = City::select('*')->where('city_name', $cityName)->get();
                    if (count($exisitng) == 0) {
                        $newCity = new City();
                        $newCity->city_name = $cityName;
                        $newCity->status = $request->input('city_status');
                        if ($newCity->save()) {
                            return redirect()->back()->with([
                                'error' => false,
                                'message' => 'City added successfully.'
                            ]);
                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Something went wrong.'
                            ]);
                        }
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'City already exists with the provided name.'
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateCity(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'city_id' => 'required',
                    'city_status' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityID = $request->input('city_id');
                    $update = City::where('id', $cityID)->update(['status' => $request->input('city_status')]);
                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'City updated successfully.'
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function getCityList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $cityList = City::select('*')->where('status', 'Active')->get();
            foreach($cityList as $key => $value){
                $cityPreview = CityPreview::where('id', $value->id)->get();
                if(count($cityPreview) == 1){
                    $cityList[$key]->preview = asset($cityPreview[0]->url);
                } else {
                    $cityList[$key]->preview = "N/A";
                }
            }
            return response()->json([
                'error' => false,
                'message' => 'All Active City List',
                'city' => $cityList
            ]);
        }
    }

    public function createArea(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'area_coverage')) {
                $validator = Validator::make($request->all(), [
                    'city_id' => 'required',
                    'area_name' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $city_id = $request->input('city_id');
                    $areaName = $request->input('area_name');
                    $exisitng = AreaCoverage::select('*')->where('area_name', 'LIKE', '%' . $areaName . '%')->get();
                    if (count($exisitng) == 0) {
                        $newArea = new AreaCoverage();
                        $newArea->city_id = $city_id;
                        $newArea->area_name = $areaName;
                        if ($newArea->save()) {
                            return redirect()->back()->with([
                                'error' => false,
                                'message' => 'Area added successfully.'
                            ]);
                        } else {
                            return redirect()->back()->with([
                                'error' => true,
                                'message' => 'Something went wrong.'
                            ]);
                        }
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Area already exists with the provided name.'
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateArea(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'area_coverage')) {
                $validator = Validator::make($request->all(), [
                    'city_id' => 'required',
                    'area_id' => 'required',
                    'area_name' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $cityID = $request->input('city_id');
                    $areaID = $request->input('area_id');
                    $areaName = $request->input('area_name');
                    $update = AreaCoverage::where('id', $areaID)->update(['area_name' => $areaName]);
                    if ($update) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Area updated successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function deleteArea(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'area_coverage')) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $areaID = $request->input('id');
                    $delete = AreaCoverage::where('id', $areaID)->delete();
                    if ($delete) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Area deleted successfully.'
                        ]);
                    } else {
                        return redirect()->back()->with([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
            } else {
                return redirect(url('/dashboard/page/unauthorized'));
            }
        } else {
            return redirect(url('/dashboard/signin'));
        }
    }

    public function updateCityPreview(Request $request){
        if ($this->isLoggedIn($request)) {
            if ($this->hasPermission($request, 'update_city')) {
                $validator = Validator::make($request->all(), [
                    'city_id' => 'required',
                    'city_preview' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $city_id = $request->input('city_id');
                    
                    if ($request->has('city_preview')) {
                        $extension = request()->city_preview->getClientOriginalExtension();
                        $request->city_preview->storeAs('public/city_preview', $city_id . '.' . $extension);
                        $city_preview = '/storage/city_preview/' . $city_id . '.' . $extension;

                        if(count(CityPreview::where('id', $city_id)->get()) == 0){
                            $cityPreview = new CityPreview();
                            $cityPreview->id = $city_id;
                            $cityPreview->url = $city_preview;
                            $cityPreview->save();
                        }
                    }

                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'City preview updated successfully.'
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
