<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Importing the DB facade

class HomeController extends Controller
{
    protected $categoryModel;
    protected $productModel;
    protected $carbon;

    // Dependency Injection through Constructor
    public function __construct(
        Category $categoryModel,
        Product $productModel,
        Carbon $carbon
    ) {
        $this->categoryModel = $categoryModel;
        $this->productModel = $productModel;
        $this->carbon = $carbon;
    }

    public function index()
    {
        try {
            $featuredCategories = $this->categoryModel->where('is_featured', true)
                ->inRandomOrder()
                ->take(3)
                ->get();

            $products = $this->productModel->where('created_at', '>=', $this->carbon::now()->subDays(7))
                ->inRandomOrder()
                ->take(10)
                ->get();

            $categories = $this->categoryModel->all();

            $bestSellers = $this->productModel->join('order_product', 'products.id', '=', 'order_product.product_id')
                ->select('products.*', DB::raw('COUNT(order_product.product_id) as total_ordered'))
                ->groupBy('products.id')
                ->orderByDesc('total_ordered')
                ->take(10)
                ->get();

            // Decode images for each product
            foreach ($products as $product) {
                $product->images = json_decode($product->images, true);
            }

            // Decode images for each best selling product
            foreach ($bestSellers as $product) {
                $product->images = json_decode($product->images, true);
            }

            return view('user/index')->with([
                'featuredCategories' => $featuredCategories,
                'products' => $products,
                'categories' => $categories,
                'bestSellers' => $bestSellers,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while loading the page.']);
        }
    }
}