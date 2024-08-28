<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryModel;
    protected $request;

    // Dependency Injection through Constructor
    public function __construct(Category $categoryModel,Request $request)
    {
        $this->categoryModel = $categoryModel;
        $this->request = $request;
    }

    public function index()
    {
        $search = $this->request->input('search');

        $categories = $this->categoryModel::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(10); // Adjust pagination as needed

        return view('admin.categories.categories', compact('categories'));
    }
    
    public function addCategoryForm(){
        try {
            return view('admin.categories.addCategoryForm');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function editCategoryForm($category_id){
        try {
            $category = $this->categoryModel::findOrFail($category_id);
            $category->images = json_decode($category->images, true);
            return view('admin.categories.editCategoryForm')->with([
                'category' => $category,
            ]);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in edit category form: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return view('admin.categories.editCategoryForm')->with($notification);
        }
    }

    public function editCategory($category_id){
        try {
            $validated = $this->request->validate([
                'category_name' => 'required|string|max:255',
                'category_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
                'feature_category' => 'nullable',
            ]);

            // Fetch the product to be updated
            $category = Category::findOrFail($category_id);

            // Handle file uploads
            $categoryPhoto = $this->request->file('category_photo');
            $photoUrl = null;

            if ($categoryPhoto) {
                $photo = $this->request->file('category_photo');
                $photoName = time() . '-' . $photo->getClientOriginalName();
                $localPath = public_path('photos') . '/' . $photoName;

                // Move the photo to a local folder
                $photo->move(public_path('photos'), $photoName);

                // Upload the photo to Firebase Storage
                $firebaseStoragePath = 'images/categories/' . $photoName;
                $uploadedFile = fopen($localPath, 'r');
                $bucket = app('firebase.storage')->getBucket();
                $object = $bucket->upload($uploadedFile, [
                    'name' => $firebaseStoragePath,
                    'predefinedAcl' => 'publicRead' // Make the file publicly accessible
                ]);

                // Get the public URL
                $photoUrl = $object->info()['mediaLink'];

                // Optionally delete the local file
                unlink($localPath);
            } else {
                // If no new photos are uploaded, keep the existing ones
                $photoUrl = $category->image;
            }

            // Handle the checkbox input
            $featureProduct = $this->request->has('feature_category') ? 1 : 0;

            // Update the product with new details
            $category->name = $this->request->input('category_name');
            $category->image = $photoUrl;
            $category->is_featured = $featureProduct;
            $category->save();

            $notification = array(
                'message' => 'Category updated successfully.',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in edit category: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function addCategory(){
        try {
            $this->request->validate([
                'category_name' => 'required|string|max:255',
                'category_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'feature_category' => 'nullable',
            ]);

            // Handle the photo upload if present
            $photoUrl = null;
            if ($this->request->hasFile('category_photo')) {
                $photo = $this->request->file('category_photo');
                $photoName = time() . '-' . $photo->getClientOriginalName();
                $localPath = public_path('photos') . '/' . $photoName;

                // Move the photo to a local folder
                $photo->move(public_path('photos'), $photoName);

                // Upload the photo to Firebase Storage
                $firebaseStoragePath = 'images/categories/' . $photoName;
                $uploadedFile = fopen($localPath, 'r');
                $bucket = app('firebase.storage')->getBucket();
                $object = $bucket->upload($uploadedFile, [
                    'name' => $firebaseStoragePath,
                    'predefinedAcl' => 'publicRead' // Make the file publicly accessible
                ]);

                // Get the public URL
                $photoUrl = $object->info()['mediaLink'];

                // Optionally delete the local file
                unlink($localPath);
            }

            // Handle the checkbox input
            $featureProduct = $this->request->has('feature_category') ? 1 : 0;

            // Save category
            $category = new Category();
            $category->name = $this->request->input('category_name');
            $category->image = $photoUrl;
            $category->is_featured = $featureProduct;
            $category->save();

            $notification = array(
                'message' => 'Successfully Done',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            $notification = array(
                'message' => 'There was an error: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function deleteCategory($category_id){
        try {
            $category = $this->categoryModel::findOrFail($category_id);

            $category->delete();
            
            $notification = array(
                'message' => 'Successfully Done',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'There was an error in delete category: ' . $th->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}