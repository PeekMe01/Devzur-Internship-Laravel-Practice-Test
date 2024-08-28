<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Charge;
use App\Mail\OrderReceipt;
use App\Mail\AdminOrderNotification;
use App\Events\TestNotification;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $orderModel;
    protected $paymentModel;
    protected $orderReceipt;
    protected $adminOrderNotification;
    protected $testNotification;

    // Constructor Dependency Injection
    public function __construct(
        Order $order,
        Payment $payment,
        OrderReceipt $orderReceipt,
        AdminOrderNotification $adminOrderNotification,
        TestNotification $testNotification
    ) {
        $this->orderModel = $order;
        $this->paymentModel = $payment;
        $this->orderReceipt = $orderReceipt;
        $this->adminOrderNotification = $adminOrderNotification;
        $this->testNotification = $testNotification;
    }

    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart;
    
        // Fetch the most recent order directly from the query builder
        $latestOrder = $this->orderModel::where('user_id', $user->id)
            ->latest()
            ->first();

        $totalAmount = $cart->products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        $validated = [
            'first_name' => '',
            'last_name' => '',
            'email' => $user->email,
            'phone' => '',
            'address' => '',
            'postal_code' => '',
            'city' => '',
            'country' => '',
            'location_lat' => '',
            'location_lng' => '',
            'total_amount' => $totalAmount
        ];

        // Save checkout data to session
        session(['checkout_data' => $validated]);

        return view('user.checkout.checkout', compact('latestOrder', 'cart'));
    }

    public function payment()
    {
        try {
            // Retrieve data from session
            $validated = session('checkout_data');
    
            // Pass the data to the view
            return view('user.checkout.payment', compact('validated'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    private function getNextSequentialNumber($date) {
        // Get the latest order for the given date
        $latestOrder = DB::table('orders')
            ->whereDate('created_at', $date)
            ->orderBy('id', 'desc')
            ->first();
    
        if ($latestOrder) {
            // Extract the sequential number and increment it
            $lastSequentialNumber = intval(substr($latestOrder->invoice, -4));
            return str_pad($lastSequentialNumber + 1, 4, '0', STR_PAD_LEFT);
        }
    
        // Return the first sequential number if no orders for the day
        return '1001';
    }

    private function generateInvoiceCode() {
        $date = Carbon::now()->format('Ymd');
        $sequentialNumber = $this->getNextSequentialNumber($date);
    
        return "{$date}-{$sequentialNumber}";
    }

    private function createOrder($data, $orderStatus, $paymentStatus, $paymentType, $chargeId = null)
    {
        try {
            $user = Auth::user();

            // Calculate total amount
            $totalAmount = $user->cart->products->sum(function ($product) {
                return $product->price * $product->pivot->quantity;
            });

            // Create new order
            $order = $this->orderModel::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'invoice' => $this->generateInvoiceCode(),
                'payment_type' => $paymentType,
                'payment_status' => $paymentStatus,
                'order_status' => $orderStatus,
                'location_lat' => $data['location_lat'],
                'location_lng' => $data['location_lng'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'postal_code' => $data['postal_code'],
                'city' => $data['city'],
                'country' => $data['country'],
            ]);

            // Attach products to the order
            foreach ($user->cart->products as $product) {
                $order->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
                $product->decrement('quantity', $product->pivot->quantity);
            }

            // Clear the user's cart
            $user->cart->products()->detach();

            if ($paymentType == "Credit Card") {
                $this->paymentModel::create([
                    'transaction_id' => $chargeId,
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                ]);
            }

            // Send email to user
            Mail::to($user->email)->send($this->orderReceipt);

            // Send email to admin
            $adminEmail = 'ralphdaher6@gmail.com'; // Replace with the actual admin email address
            Mail::to($adminEmail)->send($this->adminOrderNotification);

            // Dispatch the event with the post data
            event(new $this->testNotification([
                'orderId' => $order->id,
                'message' => "New Order!",
            ]));

            // Redirect to the success page
            $notification = array(
                'message' => 'Order successfully placed.',
                'alert-type' => 'success'
            );
            return redirect()->route('home')->with($notification);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            $notification = array(
                'message' => 'There was an error: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function processCheckout(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:15',
                'address' => 'required|string',
                'postal_code' => 'required|string|max:10',
                'city' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'location_lat' => 'required|numeric',
                'location_lng' => 'required|numeric',
                'payment_method' => 'required|string'
            ]);

            // Handle payment method
            if ($request->payment_method == 'credit_card') {
                // Redirect to Stripe payment page with the validated data
                $totalAmount = Auth::user()->cart->products->sum(function ($product) {
                    return $product->price * $product->pivot->quantity;
                });
                $validated['total_amount'] = $totalAmount;
                session()->put('checkout_data', $validated);
                return view('user.checkout.payment');
            } else {
                // Cash on delivery: Create the order directly and redirect to success page
                return $this->createOrder($validated, 'Processing', 'Pending', 'Cash On Delivery');
            }
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error: ' . $th->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
    }

    public function handlePayment(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $token = $request->input('stripeToken');
            $totalAmount = session('checkout_data')['total_amount'];
    
            $charge = Charge::create([
                'amount' => $totalAmount * 100, // Amount in cents
                'currency' => 'usd',
                'source' => $token,
                'description' => 'Order Payment',
            ]);

            $validated = session('checkout_data');
            return $this->createOrder($validated, 'Processing', 'Paid', 'Credit Card', $charge->id);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'Payment failed: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}