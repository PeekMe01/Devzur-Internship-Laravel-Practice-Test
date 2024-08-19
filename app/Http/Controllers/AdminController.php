<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin/dashboard');
    }

    public function products()
    {
        return view('admin/products');
    }

    public function categories()
    {
        return view('admin/categories');
    }

    public function orders()
    {
        return view('admin/orders');
    }

    public function payments()
    {
        return view('admin/payments');
    }
}
