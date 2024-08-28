<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{

    protected $orderModel;

    // Constructor Dependency Injection
    public function __construct(
        Order $order,
    ) {
        $this->orderModel = $order;
    }

    public function index(){
        try {
            $orders = Auth::user()->orders;

            return view('user.orders')->with(["orders" => $orders]);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->route('home')->with($notification);
        } 
    }
    public function showOrderDetails($order_id)
    {
        try {
            // Retrieve the currently authenticated user
            $user = Auth::user();
            
            // Fetch the order, ensuring it belongs to the current user
            $order = $this->orderModel::where('id', $order_id)
                        ->where('user_id', $user->id)
                        ->firstOrFail();

            // Pass the order to the view
            return view('user.order_details', compact('order'));

        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'Order not found or access denied: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
    public function cancelOrder($order_id)
    {
        try {
            // Retrieve the currently authenticated user
            $user = Auth::user();
            
            // Fetch the order, ensuring it belongs to the current user
            $order = $this->orderModel::where('id', $order_id)
                        ->where('user_id', $user->id)
                        ->firstOrFail();

            // Delete the order
            $order->delete();

            // Redirect back with a success message
            $notification = array(
                'message' => 'Order successfully canceled.',
                'alert-type' => 'success'
            );
            return redirect()->route('orders.view')->with($notification);

        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'Order not found or access denied: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

}
