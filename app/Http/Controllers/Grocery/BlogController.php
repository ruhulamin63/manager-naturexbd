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
        $blogList = Blog::orderBy('id', 'DESC')->get();

        return view('grocery.blogs.indexBlog', compact('blogList'))
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
        $data = $request->validate([

            'title' => ['required'],
            'blog_description' => ['required'],
            'image_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

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
                        $newBlog->slug =  Str::slug($request->title);
                        $newBlog->description = $request->blog_description;

//                        if($request->hasFile('blog_image')){
//
//                            $file = $request->file('blog_image');
//                            $filename = $file->getClientOriginalExtension();
//                            $path = public_path().'/blogs/images/';
//                            $newBlog->image_path = $path.$filename;
//
//                            $file->move($path, $filename);
//                        }

                        if ($request->file('blog_image')) {
                            $file = $request->file('blog_image');
                            $name = '/blogs/images/' . uniqid() . '.' . $file->extension();
                            $file->storePubliclyAs('public', $name);
                            $data['blog_image'] = $name;
                            $newBlog->image_path = $data['blog_image'];
                        }

                        if ($request->file('blog_video')) {
                            $file = $request->file('blog_video');
                            $name = '/blogs/videos/' . uniqid() . '.' . $file->extension();
                            $file->storePubliclyAs('public', $name);
                            $data['blog_video'] = $name;
                            $newBlog->video_path = $data['blog_video'];
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blogData = Blog::findOrFail($id);
        $cityList = City::all();

//        dd($blogData);

        return view('grocery.blogs.editBlog', compact('blogData'))
            ->with('title', 'Edit Blog')
            ->with('cityList', $cityList);
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
//        dd($request->all());

        $data = $request->validate([
            'title' => ['required'],
            'blog_description' => ['required'],
            'image_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

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


                        $newBlog = Blog::findOrFail($id);
                        $newBlog->title = $request->title;
                        $newBlog->slug =  Str::slug($request->title);
                        $newBlog->description = $request->blog_description;

//                        if($request->hasFile('blog_image')){
//
//                            $file = $request->file('blog_image');
//                            $filename = $file->getClientOriginalExtension();
//                            $path = public_path().'/blogs/images/';
//                            $newBlog->image_path = $path.$filename;
//
//                            $file->move($path, $filename);
//                        }

                        if ($request->file('blog_image')) {
                            $file = $request->file('blog_image');
                            $name = '/blogs/images/' . uniqid() . '.' . $file->extension();
                            $file->storePubliclyAs('public', $name);
                            $data['blog_image'] = $name;
                            $newBlog->image_path = $data['blog_image'];
                        }

                        if ($request->file('blog_video')) {
                            $file = $request->file('blog_video');
                            $name = '/blogs/videos/' . uniqid() . '.' . $file->extension();
                            $file->storePubliclyAs('public', $name);
                            $data['blog_video'] = $name;
                            $newBlog->video_path = $data['blog_video'];
                        }
//                        if (in_array($city->id, $cityCoverage)) {
//                            $newProduct->status = 'Active';
//                        } else {
//                            $newProduct->status = 'Inactive';
//                        }
                        $newBlog->update();

//                    }
                        return redirect()->back()->with([
                            'error' => false,
                            'message' => 'Update successfully.'
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function blogStatusUpdate(Request $request)
    {
//        if ($this->isLoggedIn($request)) {
//            if ($this->hasPermission($request, 'update_product')) {
                $validator = Validator::make($request->all(), [
//                    'city_id' => 'required',
                    'blog_id' => 'required',
                    'status' => 'required'
                ]);
//                dd($request->all());

                if ($validator->fails()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Required data missing.'
                    ]);
                } else {
                    $update = Blog::where('id', $request->input('blog_id'))
                        ->update(['status' => $request->input('status')]);

                    if ($update) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Blog status updated successfully.'
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => 'Something went wrong.'
                        ]);
                    }
                }
//            } else {
//                return response()->json([
//                    'error' => true,
//                    'message' => 'You don\'t have permission to edit data.'
//                ]);
//            }
//        } else {
//            return response()->json([
//                'error' => true,
//                'message' => 'Please login again to continue.'
//            ]);
//        }
    }

}
