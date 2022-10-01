<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private function isLoggedIn(Request $request)
    {
        if ($request->session()->has('LOGGED_IN') && $request->session()->get('LOGGED_IN')) {
            return true;
        } else {
            return false;
        }
    }

    public function generateReport(Request $request){
        if($this->isLoggedIn($request)){
            $date = date('Y-m-d', strtotime($request->input('date')));
            return redirect(url('/dashboard/page?id=7158&date=' . $date));
        } else {
            return redirect('/dashboard');
        }
    }
}
