<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    protected $orderModel;
    protected $userModel;
    protected $carbon;

    public function __construct(Order $orderModel, User $userModel, Carbon $carbon)
    {
        $this->orderModel = $orderModel;
        $this->userModel = $userModel;
        $this->carbon = $carbon;
    }

    public function index()
    {
        // Total Sales
        $totalSales = $this->orderModel::sum('total_amount');
        
        // Today's Sales
        $todayStart = $this->carbon::now()->startOfDay();
        $todaySales = $this->orderModel::where('created_at', '>=', $todayStart)->sum('total_amount');
        
        // Total Sign-ups
        $totalSignUps = $this->userModel::count();
        
        // Today's Sign-ups
        $todaySignUps = $this->userModel::where('created_at', '>=', $todayStart)->count();
        
        // Last 5 Orders
        $orders = $this->orderModel::orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('admin.dashboard', compact('totalSales', 'todaySales', 'totalSignUps', 'todaySignUps', 'orders'));
    }
}
