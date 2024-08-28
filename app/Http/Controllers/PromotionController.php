<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{

    protected $productModel;

    // Constructor Dependency Injection
    public function __construct(
        Product $product,
    ) {
        $this->productModel = $product;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
    
        // Start the query for products with a discounted price
        $query = $this->productModel::whereNotNull('discounted_price');
    
        // If a search term is provided, filter products based on it
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                //   ->orWhere('description', 'like', '%' . $search . '%')
                //   ->orWhere('category', 'like', '%' . $search . '%');
            });
        }
    
        // Paginate the filtered results
        $products = $query->paginate(20);
    
        // Decode JSON images for each product
        foreach ($products as $product) {
            $product->images = json_decode($product->images, true);
        }
    
        // Return the view with the products and search term
        return view('user.promotion', [
            'products' => $products,
            'search' => $search
        ]);
    }     
}
