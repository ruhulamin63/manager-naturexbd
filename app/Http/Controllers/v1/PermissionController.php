<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Backend\PageManager;
use App\Models\PermissionList;
use App\Models\UserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function addNewPage(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'page_title' => 'required',
                    'page_view' => 'required'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Error! Please fill up all the fields.'
                    ]);
                } else {
                    $page_id = mt_rand(1000, 9999);
                    $existence = PageManager::select('*')->where('page_id', $page_id)->get();
                    while (count($existence) != 0) {
                        $page_id = mt_rand(1000, 9999);
                        $existence = PageManager::select('*')->where('page_id', $page_id)->get();
                    }
                    $new_page = new PageManager();
                    $new_page->page_title = $request->input('page_title');
                    $new_page->page_id = $page_id;
                    $new_page->page_view = $request->input('page_view');
                    $new_page->updated_by = $request->session()->get('MANAGER_NAME') . ',' . $request->session()->get('UID');
                    if ($new_page->save()) {
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Success! New page added successfully!'
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

    public function decodePermission(Request $request)
    {
        if ($this->isLoggedIn($request)) {
            $validator = Validator::make($request->all(), [
                'permissionList' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect(url('/dashboard/page/unauthorized'));
            } else {
                $decodedResult = array();
                $permissionList = $request->input('permissionList');
                $permissionList = explode(',', $permissionList);
                foreach ($permissionList as $key => $permission) {
                    $permission = explode('_', $permission);
                    $pageTitle = PageManager::select('*')->where('page_id', $permission[0])->get();
                    $decodedResult[$key]['page_title'] = $pageTitle[0]->page_title;
                    $decodedResult[$key]['page_permission'] = $permission[1];
                }
                return response()->json($decodedResult);
            }
        }
    }
}
