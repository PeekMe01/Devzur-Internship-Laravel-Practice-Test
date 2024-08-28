<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class UserProductController extends Controller
{

    protected $categoryModel;
    protected $productModel;

    // Constructor Dependency Injection
    public function __construct(
        Category $category,
        Product $product,
    ) {
        $this->categoryModel = $category;
        $this->productModel = $product;
    }

    public function index(Request $request)
    {
        $category = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $search = $request->input('search'); // Add this line to get the search query
        $categories = $this->categoryModel::all(); // Fetch all categories

        $products = $this->productModel::query();

        // Filter by category
        if ($category) {
            $products->where('category_id', $category);
            
        }
        // Query for the category
        $currentCategory = $this->categoryModel::find($category);

        // Determine the category name or default to "All"
        $categoryName = $currentCategory ? $currentCategory->name : "All";

        // Filter by price range if provided
        if ($minPrice && $maxPrice) {
            $products->where(function($query) use ($minPrice, $maxPrice) {
                $query->whereBetween('price', [(float)$minPrice, (float)$maxPrice])
                      ->orWhereBetween('discounted_price', [(float)$minPrice, (float)$maxPrice]);
            });
        }

        // Filter by search query if provided
        if ($search) {
            $products->where('name', 'like', '%' . $search . '%');
        }

        $products = $products->paginate(10); // Adjust pagination as needed

        foreach ($products as $product) {
            $product->images = json_decode($product->images, true);
        }

        return view('user.shop', [
            'products' => $products,
            'categories' => $categories,
            'category' => $category,
            'categoryName' => $categoryName,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'search' => $search // Pass the search parameter to the view
        ]);
    }

    public function viewProduct($category_name, $product_id)
    {
        // Find the category by name
        $category = $this->categoryModel::where('name', $category_name)->first();

        // Find the product by ID
        $product = $this->productModel::find($product_id);

        // If the product or category is not found, you might want to handle this
        if (!$product) {
            abort(404, 'Product not found');
        }

        // Optionally check if the product belongs to the category
        if ($category && !$product->category_id == $category->id) {
            abort(404, 'Product does not belong to this category');
        }

        $product->images = json_decode($product->images, true);

        return view('user.single', [
            'product' => $product,
            'category' => $category,
        ]);
    }
}
