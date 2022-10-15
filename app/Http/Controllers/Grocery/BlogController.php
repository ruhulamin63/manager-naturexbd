<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use App\Models\Grocery\Admin;
use App\Models\Grocery\Blog;
use App\Models\Grocery\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $cityList = City::all();

        return view('grocery.blogs.indexBlog')
            ->with('title', 'Blog')
            ->with('cityList', $cityList);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $cityList = City::all();

        return view('grocery.blogs.createBlog')
            ->with('title', 'Create Blog')
            ->with('cityList', $cityList);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
//        dd($request->all());
//        if ($this->isLoggedIn($request)) {
//            if ($this->hasPermission($request, 'blog_create')) {
//                $validator = Validator::make($request->all(), [
//                    'title' => 'required',
//                    'slug' => 'required',
//                    'description' => 'required',
//                ]);
//
//                if ($validator->fails()) {
//                    return redirect()->back()->with([
//                        'error' => true,
//                        'message' => 'Please fill all the fields'
//                    ]);
//                }
//                else {
//                    $cityCoverage = $request->input('city_coverage');
//                    $cityList = City::all();
                    $imageURL = "";
//                    foreach ($cityList as $city) {
//                        $existing = Products::select('*')
//                            ->where('cityID', $city->id)
//                            ->where('product_name', $request->input('product_name'))
//                            ->get();


                        $newBlog = new Blog();
                        $newBlog->title = $request->title;
                        $newBlog->slug = $request->slug;
                        $newBlog->description = $request->blog_description;

                        if($request->hasFile('blog_image')){

                            $file = $request->file('blog_image');
                            $filename = $file->getClientOriginalExtension();
                            $path = public_path().'/blogs/images/';
                            $newBlog->image_path = $path.$filename;

                            $file->move($path, $filename);
                        }

                        if($request->hasFile('blog_video')){
                            $file = $request->file('blog_video');
                            $filename = $file->getClientOriginalExtension();
                            $path = '/blogs/videos/';
                            $newBlog->video_path = $path.$filename;

                            $file->move($path, $filename);
                        }

//                        if (in_array($city->id, $cityCoverage)) {
//                            $newProduct->status = 'Active';
//                        } else {
//                            $newProduct->status = 'Inactive';
//                        }
                        $newBlog->save();


//                    }
                    return redirect()->back()->with([
                        'error' => false,
                        'message' => 'Create successfully.'
                    ]);
//                }
//            } else {
//                return redirect(url('/dashboard/page/unauthorized'));
//            }
//        } else {
//            return redirect(url('/dashboard/signin'));
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
