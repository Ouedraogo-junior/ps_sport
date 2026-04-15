<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.coupons.index');
    }
}