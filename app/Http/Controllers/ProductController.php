<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
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
        $search = $request->input('search');

        $products = $this->productModel::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(10); // Adjust pagination as needed

        return view('admin.products.products', compact('products'));
    } 

    public function addProductForm(){
        try {
            $categories = $this->categoryModel::all();
            return view('admin.products.addProductsForm')->with('categories', $categories);
        } catch (\Throwable $th) {
            Log::error('Error in addProductForm: ' . $th->getMessage());
        }
    }

    public function editProductForm($product_id){
        try {
            $categories = $this->categoryModel::all();
            $product = $this->productModel::findOrFail($product_id);
            $product->images = json_decode($product->images, true);
            return view('admin.products.editProductForm')->with([
                'categories' => $categories,
                'product' => $product
            ]);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in edit product form: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return view('admin.products.editProductForm')->with($notification);
        }
    }

    public function editProduct(Request $request, $product_id){
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_description' => 'required|string|max:1000',
                'product_price' => 'required|numeric|min:0',
                'product_quantity' => 'required|integer|min:0',
                'product_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
                'product_category' => 'required|integer|exists:categories,id',
                'discounted_product_price' => 'integer|nullable',
            ]);

            $productName = $validated['product_name'];
            $productDescription = $validated['product_description'];
            $productPrice = $validated['product_price'];
            $productQuantity = $validated['product_quantity'];
            $productCategory = $validated['product_category'];
            $productDiscountedPrice = $validated['discounted_product_price'];

            // Fetch the product to be updated
            $product = $this->productModel::findOrFail($product_id);

            // Handle file uploads
            $productPhotos = $request->file('product_photos');
            $photoUrls = [];

            if ($productPhotos) {
                $i = 0;
                foreach ($productPhotos as $photo) {
                    $photoName = time() . '-' . $photo->getClientOriginalName();
                    $localPath = public_path('photos') . '/' . $photoName;

                    // Move the photo to a local folder
                    if ($photo->move(public_path('photos'), $photoName)) {
                        // Upload the photo to Firebase Storage
                        $firebaseStoragePath = 'images/products/' . $photoName;
                        $uploadedFile = fopen($localPath, 'r');
                        $bucket = app('firebase.storage')->getBucket();
                        $object = $bucket->upload($uploadedFile, [
                            'name' => $firebaseStoragePath,
                            'predefinedAcl' => 'publicRead',
                        ]);

                        // Get the public URL
                        $photoUrls[] = $object->info()['mediaLink'];

                        // Optionally delete the local file
                        unlink($localPath);
                    }
                }
            } else {
                // If no new photos are uploaded, keep the existing ones
                $photoUrls = json_decode($product->images, true) ?? [];
            }

            // Update the product with new details
            $product->name = $productName;
            $product->description = $productDescription;
            $product->price = $productPrice;
            $product->discounted_price = $productDiscountedPrice;
            $product->quantity = $productQuantity;
            $product->images = json_encode($photoUrls);
            $product->category_id = $productCategory;
            $product->save();

            $notification = array(
                'message' => 'Product updated successfully.',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in edit product: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function addProduct(Request $request){
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_description' => 'required|string|max:1000',
                'product_price' => 'required|numeric|min:0',
                'product_quantity' => 'required|integer|min:0',
                'product_photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'product_category' => 'required|integer|exists:categories,id',
                'discounted_product_price' => 'nullable|integer|min:0',
            ]);

            $productName = $validated['product_name'];
            $productDescription = $validated['product_description'];
            $productPrice = $validated['product_price'];
            $productQuantity = $validated['product_quantity'];
            $productCategory = $validated['product_category'];
            $discountedProductPrice = $validated['discounted_product_price'];

            // Handle file uploads
            $productPhotos = $request->file('product_photos');
            $photoUrls = [];

            if ($productPhotos) {
                Log::info('Number of photos: ' . count($productPhotos)); // Log the number of photos
                $i = 0;
                foreach ($productPhotos as $photo) {
                    Log::info('Processing photo ' . $i); // Log which photo is being processed
                    $currentPhoto = $photo;
                    $photoName = time() . '-' . $currentPhoto->getClientOriginalName();
                    $localPath = public_path('photos') . '/' . $photoName;
            
                    // Move the photo to a local folder
                    if ($currentPhoto->move(public_path('photos'), $photoName)) {
                        // Upload the photo to Firebase Storage
                        $firebaseStoragePath = 'images/products/' . $photoName;
                        $uploadedFile = fopen($localPath, 'r');
                        $bucket = app('firebase.storage')->getBucket();
                        $object = $bucket->upload($uploadedFile, [
                            'name' => $firebaseStoragePath,
                            'predefinedAcl' => 'publicRead',
                        ]);
            
                        // Get the public URL
                        $photoUrls[$i] = $object->info()['mediaLink'];
                        $i++;
            
                        // Optionally delete the local file
                        unlink($localPath);
                    } else {
                        Log::error('Failed to move photo: ' . $photoName); // Log an error if the move fails
                    }
                }
            } else {
                Log::error('No photos found.');
            }
            
            $product = new Product();
            $product->name = $productName;
            $product->description = $productDescription;
            $product->price = $productPrice;
            $product->discounted_price = $discountedProductPrice;
            $product->quantity = $productQuantity;
            $product->category_id = $productCategory;
            $product->images = json_encode($photoUrls);
            $product->save();

            $notification = array(
                'message' => 'Successfully Done',
                'alert-type' => 'success'
            );
            
            return redirect()->back()->with($notification);
          } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
          }
    }

    public function deleteProduct($product_id){
        try {
            $product = $this->productModel::findOrFail($product_id);

            $product->delete();

            $notification = array(
                'message' => 'Successfully Done',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in delete product: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function viewProduct($product_id){
        try {
            $product = $this->productModel::findOrFail($product_id);
            $product->images = json_decode($product->images, true);
            return view('admin.products.viewProduct')->with('product', $product);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in view product: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}