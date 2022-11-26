<?php

namespace App\Http\Controllers\Grocery;

use App\Http\Controllers\Controller;
use App\Models\Grocery\Blog;
use App\Models\Grocery\City;
use App\Models\Grocery\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $cityList = City::all();
        $offerList = Offer::orderBy('id', 'DESC')->get();

        return view('Grocery.offers.indexOffer', compact('offerList'))
            ->with('title', 'Offer')
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

        return view('Grocery.offers.createOffer')
            ->with('title', 'Create Offer')
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

//                $data = $request->validate([
//                    'offer_name' => ['required'],
//                    'offer_description' => ['required'],
//                    'image_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//
//                ]);
                $validator = Validator::make($request->all(), [
                    'offer_name' => 'required',
                    'offer_description' => 'required',
                ]);
//
                if ($validator->fails()) {
                    return redirect()->back()->with([
                        'error' => true,
                        'message' => 'Please fill all the fields'
                    ]);
                }
                else {
//                    $cityCoverage = $request->input('city_coverage');
//                    $cityList = City::all();
//                $imageURL = "";
//                    foreach ($cityList as $city) {
//                        $existing = Products::select('*')
//                            ->where('cityID', $city->id)
//                            ->where('product_name', $request->input('product_name'))
//                            ->get();


                    $newOffer = new Offer();
                    $newOffer->offer_name = $request->offer_name;
                    $newOffer->url = $request->url;
                    $newOffer->meta_keyword =  Str::slug($request->meta_keyword);
                    $newOffer->description = $request->offer_description;

//                    dd('here');

                    if ($request->file('offer_image')) {
                        $file = $request->file('offer_image');
                        $name = '/offers/images/' . uniqid() . '.' . $file->extension();
                        $file->storePubliclyAs('public', $name);
                        $data['offer_image'] = $name;
                        $newOffer->image_path = $data['offer_image'];
                    }
//                    dd('ok');
                    $newOffer->save();
                }
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        $offerData = Offer::findOrFail($id);
        $cityList = City::all();

//        dd($blogData);

        return view('Grocery.offers.editOffer', compact('offerData'))
            ->with('title', 'Edit Offer')
            ->with('cityList', $cityList);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
//        dd($request->all());

        $data = $request->validate([
            'offer_name' => ['required'],
            'offer_description' => ['required'],
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
//        $imageURL = "";
//                    foreach ($cityList as $city) {
//                        $existing = Products::select('*')
//                            ->where('cityID', $city->id)
//                            ->where('product_name', $request->input('product_name'))
//                            ->get();


        $newOffer = Offer::findOrFail($id);
        $newOffer->offer_name = $request->offer_name;
        $newOffer->url = $request->url;
        $newOffer->meta_keyword =  Str::slug($request->meta_keyword);
        $newOffer->description = $request->offer_description;

        if ($request->file('offer_image')) {
            $file = $request->file('offer_image');
            $name = '/offers/images/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);
            $data['offer_image'] = $name;
            $newOffer->image_path = $data['offer_image'];
        }
        $newOffer->update();

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

    public function offerStatusUpdate(Request $request)
    {
//        if ($this->isLoggedIn($request)) {
//            if ($this->hasPermission($request, 'update_product')) {
        $validator = Validator::make($request->all(), [
//                    'city_id' => 'required',
            'offer_id' => 'required',
            'status' => 'required'
        ]);
//                dd($request->all());

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Required data missing.'
            ]);
        } else {
            $update = Offer::where('id', $request->input('offer_id'))
                ->update(['status' => $request->input('status')]);

            if ($update) {
                return response()->json([
                    'error' => false,
                    'message' => 'Offer status updated'
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
