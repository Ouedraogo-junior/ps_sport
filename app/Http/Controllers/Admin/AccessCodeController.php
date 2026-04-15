<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AccessCodeController extends Controller
{
    public function index()
    {
        return view('admin.codes.index');
    }
}