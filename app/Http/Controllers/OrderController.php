<?php

namespace App\Http\Controllers;

use App\Mail\OrderDelivered;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    protected $orderModel;

    // Constructor Dependency Injection
    public function __construct(
        Order $order,
    ) {
        $this->orderModel = $order;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = $this->orderModel::query()
            ->when($search, function ($query, $search) {
                return $query->where('invoice', 'like', '%' . $search . '%');
            })
            ->paginate(10); // Adjust pagination as needed

        return view('admin.orders.orders', compact('orders'));
    }

    public function orderDetails($order_id){
        $order = $this->orderModel::with('products')->find($order_id);

        if (!$order) {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    
        return view('admin/orders/orderDetails', compact('order'));
    }

    public function markOrderDelivered($order_id)
    {
        try {
            $order = $this->orderModel::find($order_id);
    
            if (!$order) {
                $notification = [
                    'message' => 'Something went wrong, the order wasn\'t found',
                    'alert-type' => 'error'
                ];
                return redirect()->back()->with($notification);
            }
    
            $order->order_status = 'Delivered';
            $order->save();
    
            // Send an email to the user
            Mail::to($order->email)->send(new OrderDelivered($order));
    
            $notification = [
                'message' => 'Order status updated to Delivered and notification sent.',
                'alert-type' => 'success'
            ];
            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            $notification = [
                'message' => 'There was an error in marking the order as delivered: ' . $th->getMessage(),
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }
    

    public function markPaymentPaid($order_id){
        try {
            $order = $this->orderModel::find($order_id);
    
            if (!$order) {
                $notification = array(
                    'message' => 'Something went wrong, the order wasn\'t found',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
    
            $order->payment_status = 'Paid';
            $order->save();
    
            return redirect()->back();
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in mark payment paid: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function markOrderPending($order_id){
        try {
            $order = $this->orderModel::find($order_id);
    
            if (!$order) {
                $notification = array(
                    'message' => 'Something went wrong, the order wasn\'t found',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
    
            $order->order_status = 'Pending';
            $order->save();
    
            return redirect()->back();
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in mark order pending: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function markPaymentPending($order_id){
        try {
            $order = $this->orderModel::find($order_id);
    
            if (!$order) {
                $notification = array(
                    'message' => 'Something went wrong, the order wasn\'t found',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
    
            $order->payment_status = 'Pending';
            $order->save();
    
            return redirect()->back();
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in mark payment pending: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function cancelOrder($order_id){
        try {
            $order = $this->orderModel::find($order_id);
    
            if (!$order) {
                $notification = array(
                    'message' => 'Something went wrong, the order wasn\'t found',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
    
            $order->delete();
    
            return redirect()->route('adminOrders');
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}