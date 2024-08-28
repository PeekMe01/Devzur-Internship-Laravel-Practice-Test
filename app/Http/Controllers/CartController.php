<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    protected $cartModel;
    protected $productModel;
    protected $auth;
    protected $request;

    // Dependency Injection through Constructor
    public function __construct(Cart $cartModel, Product $productModel, Request $request)
    {
        $this->cartModel = $cartModel;
        $this->productModel = $productModel;
        $this->auth = Auth::class;  // Auth is used as a facade, no direct injection
        $this->request = $request;
    }

    public function addToCart()
    {
        try {
            $user = $this->auth::user();
            $product_id = $this->request->input('product_id');
            $quantity = $this->request->input('quantity', 1);

            // Get or create the cart for the user
            $cart = $this->cartModel::firstOrCreate(['user_id' => $user->id]);

            // Check if the product is already in the cart
            $cartProduct = DB::table('cart_product')
                            ->where('cart_id', $cart->id)
                            ->where('product_id', $product_id)
                            ->first();

            if ($cartProduct) {
                // Update the quantity if the product is already in the cart
                DB::table('cart_product')
                    ->where('cart_id', $cart->id)
                    ->where('product_id', $product_id)
                    ->update(['quantity' => $quantity]);
            } else {
                $product = $this->productModel::find($product_id);
                if ($product->quantity == 0) {
                    $notification = [
                        'message' => 'This item is out of stock.',
                        'alert-type' => 'error'
                    ];
                    return redirect()->back()->with($notification);
                }
                // Add the product to the cart if it's not there yet
                DB::table('cart_product')->insert([
                    'cart_id' => $cart->id,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $notification = [
                'message' => 'Successfully Done',
                'alert-type' => 'success'
            ];
            
            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            $notification = [
                'message' => 'There was an error: ' . $th->getMessage(),
                'alert-type' => 'error'
            ];
            
            return redirect()->back()->with($notification);
        }
    } 

    public function viewCart()
    {
        try {
            $cart = $this->auth::user()->cart;

            return view('user.cart')->with('cart', $cart);
        } catch (\Throwable $th) {
            $notification = [
                'message' => 'There was an error: ' . $th->getMessage(),
                'alert-type' => 'error'
            ];
            
            return redirect()->back()->with($notification);
        } 
    }

    public function increaseQuantity($product_id)
    {
        $cart = $this->auth::user()->cart;
        $cartProduct = $cart->products()->where('product_id', $product_id)->first();
        if ($cartProduct && $cartProduct->pivot->quantity < $cartProduct->quantity) {
            $cartProduct->pivot->quantity += 1;
            $cartProduct->pivot->save();
        }

        return redirect()->back()->with('success', 'Product quantity increased.');
    }

    public function decreaseQuantity($product_id)
    {
        $cart = $this->auth::user()->cart;
        $cartProduct = $cart->products()->where('product_id', $product_id)->first();

        if ($cartProduct && $cartProduct->pivot->quantity > 1) {
            $cartProduct->pivot->quantity -= 1;
            $cartProduct->pivot->save();
        }

        return redirect()->back()->with('success', 'Product quantity decreased.');
    }

    public function remove($product_id)
    {
        try {
            $user = $this->auth::user();
            $cart = $user->cart;

            // Remove the product from the cart
            $cart->products()->detach($product_id);

            $notification = [
                'message' => 'Product removed from cart successfully.',
                'alert-type' => 'success'
            ];

            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            $notification = [
                'message' => 'There was an error: ' . $th->getMessage(),
                'alert-type' => 'error'
            ];
            
            return redirect()->back()->with($notification);
        }
    }
}