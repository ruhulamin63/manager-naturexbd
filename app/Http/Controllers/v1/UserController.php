<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\OldUser;
use App\Models\UserDevice;
use App\Models\UserInfo;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function createUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required',
                'user_email' => 'required',
                'user_address' => 'required',
                'user_coordinate' => 'required',
                'user_phone' => 'required',
                'user_gender' => 'required',
                'user_photo' => 'required',
                'user_referral' => 'required',
                'device_token' => 'required',
                'device_info' => 'required',
                'blood_group' => 'required',
                'date_of_birth' => 'required',
                'favorite_category' => 'required',
                'favorite_item' => 'required',
                'favorite_restaurant' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data validation failed!'
                ]);
            } else {
                $userID = strtoupper(Str::random(6));
                $userName = $request->input('user_name');
                $userEmail = $request->input('user_email');
                $userAddress = $request->input('user_address');
                $userCoordinate = $request->input('user_coordinate');
                $userPhone = $request->input('user_phone');
                $userGender = $request->input('user_gender');
                $userPhoto = $request->input('user_photo');
                $userReferral = $request->input('user_referral');
                $bloodGroup = $request->input('blood_group');
                $dateOfBirth = $request->input('date_of_birth');

                $userToken = $request->input('user_token');
                $deviceToken = $request->input('device_token');
                $deviceInfo = $request->input('device_info');

                $deviceInfo = explode(',', $deviceInfo);

                // $imageURL = '/app/users/images/' . $userPhone . ".JPG";
                // if ($userPhoto == "None") {
                //     $imageURL = '/app/defaults/images/avatar.png';
                // }

                $imageURL = '/app/defaults/images/avatar.png';

                $new_user_info = new UserInfo();
                $new_user_info->user_id = $userID;
                $new_user_info->user_phone = $userPhone;
                $new_user_info->user_name = $userName;
                $new_user_info->user_email = $userEmail;
                $new_user_info->user_address = $userAddress;
                $new_user_info->user_coordinate = $userCoordinate;
                $new_user_info->user_gender = $userGender;
                $new_user_info->blood_group = $bloodGroup;
                $new_user_info->date_of_birth = $dateOfBirth;
                $new_user_info->user_photo = $imageURL;
                $new_user_info->user_referral = "-";

                $new_device_info = new UserDevice();
                $new_device_info->user_id = $userID;
                $new_device_info->version_release = $deviceInfo[0];
                $new_device_info->version_sdk = $deviceInfo[1];
                $new_device_info->manufacturer = $deviceInfo[2];
                $new_device_info->model = $deviceInfo[3];
                $new_device_info->device_token = $deviceToken;

                if ($new_user_info->save() && $new_device_info->save()) {
                    // if ($userPhoto != "None") {
                    //     $decodedImage = base64_decode(trim($userPhoto));
                    //     Storage::disk('user_image')->put($userPhone . '.JPG', $decodedImage);
                    // }

                    $new_preference = new UserPreference();
                    $new_preference->user_id = $userID;
                    $new_preference->favorite_category = $request->input('favorite_category');
                    $new_preference->favorite_item = $request->input('favorite_item');
                    $new_preference->favorite_restaurant = $request->input('favorite_restaurant');

                    if ($new_preference->save()) {
                        OldUser::where('mobile', $userPhone)->delete();
                        return response()->json([
                            'error' => false,
                            'message' => 'User account created successfully!',
                            'userID' => $userID,
                            'photoURL' => asset($imageURL)
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong!'
                        ]);
                    }
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Something went wrong!'
                    ]);
                }
            }
        }
    }

    public function processOldUserData(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $oldData = array();
            $csvRead = 'temp/OLD_USER_DATA.csv';
            if (File::exists($csvRead)) {
                $file_handle = fopen($csvRead, 'r');
                if ($file_handle) {
                    $key = 0;
                    while ($line = fgetcsv($file_handle)) {
                        $mobileNumber = $line[2];
                        if (strlen($mobileNumber) == 14) {
                            $mobileNumber = substr($line[2], 3);
                        }
                        $entryCheck = count(OldUser::select('*')->where('mobile', $mobileNumber)->get());
                        if ($entryCheck == 0) {
                            $new_entry = new OldUser();
                            $new_entry->name = $line[0];
                            if ($line[1] == "Not Provided" || $line[1] == "") {
                                $new_entry->email = "-";
                            } else {
                                $new_entry->email = $line[1];
                            }
                            $new_entry->mobile = $mobileNumber;
                            $new_entry->gender = $line[3];
                            $new_entry->save();
                            $key += 1;
                        }
                    }
                    OldUser::insert($oldData);
                    fclose($file_handle);
                    unlink($csvRead);
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Old user info processed successfully!'
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

    public function uploadOldUserData(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'csv_file' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                if ($request->has('csv_file')) {
                    $extension = request()->csv_file->getClientOriginalExtension();
                    if ($extension == "csv") {
                        DB::table('kt_app_old_users')->truncate();
                        $request->csv_file->storeAs('public/users/data/old', 'OLD_USER_DATA' . '.' . $extension);
                        $csvFile = 'public/users/data/old/OLD_USER_DATA.csv';
                        Storage::disk('csv')->put('OLD_USER_DATA.csv', Storage::get($csvFile));
                        Storage::delete($csvFile);
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Old user csv uploaded successfully!'
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

    public function deleteSingleOldUserData(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'user_mobile' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $delete = OldUser::where('mobile', $request->input('user_mobile'))->delete();
                if ($delete) {
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Old user deleted successfully!'
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

    public function checkExistingUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_mobile' => 'required',
            'device_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Data validation failed!'
            ]);
        } else {
            $mobileNumber = $request->input('user_mobile');
            $deviceToken = $request->input('device_token');
            $oldUser = OldUser::select('*')->where('mobile', $mobileNumber)->get();
            $existingUser = UserInfo::select('*')->where('user_phone', $mobileNumber)->get();
            $existingData = array();
            if (count($existingUser) > 0) {
                UserDevice::where('user_id', $existingUser[0]->user_id)->update(['device_token' => $deviceToken]);
                $existingData[0]['existing_user'] = true;
                $existingData[0]['old_user'] = false;
                $existingData[0]['new_user'] = false;
                $existingData[0]['id'] = $existingUser[0]->user_id;
                $existingData[0]['name'] = $existingUser[0]->user_name;
                $existingData[0]['photo'] = $existingUser[0]->user_photo;
            } else if (count($oldUser) > 0) {
                $existingData[0]['existing_user'] = false;
                $existingData[0]['old_user'] = true;
                $existingData[0]['new_user'] = false;
                $existingData[0]['name'] = $oldUser[0]->name;
                $existingData[0]['email'] = $oldUser[0]->email;
                $existingData[0]['gender'] = $oldUser[0]->gender;
            } else {
                $existingData[0]['existing_user'] = false;
                $existingData[0]['old_user'] = false;
                $existingData[0]['new_user'] = true;
            }
            return response()->json([
                'error' => false,
                'message' => $existingData
            ]);
        }
    }
}
