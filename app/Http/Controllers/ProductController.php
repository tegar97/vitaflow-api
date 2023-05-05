<?php

namespace App\Http\Controllers;

use App\Helper\imageResizer;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProductByCategory(Request $request)
    {
        $categoryId = $request->input('category');

        $query = Product::query();
        $query->select('products.*', 'categories.name AS category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $query->leftJoin('categories', 'products.category_id', '=', 'categories.id');

        $products = $query->get();

        return response()->json([
            'message' => 'Success',
            'data' => $products
        ], 200);
    }


    // search product
    public function searchProduct(Request $request)
    {
        $validatedData = $request->validate([
            'search' => 'required|string|min:1',
        ]);

        $search = $validatedData['search'];

        $query = Product::query();

        $query->where('name', 'LIKE', "%{$search}%");

        // Add additional search parameters here using `orWhere()`

        $products = $query->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.id', 'products.name', 'products.description', 'products.price', 'categories.name as category')
        ->paginate(10);

        $products->appends(['search' => $search]);

        return response()->json([
            'message' => 'Success',
            'data' => $products
        ], 200);
    }




    public function getProductDetail($productId)
    {
        $product = Product::find($productId);

        return response()->json([
            'message' => 'Success',
            'data' => $product
        ], 200);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return view('pages.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        // load category
        $categories = Category::all();

        return view('pages.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required',
        ]);

        $image = $request->file('image');
        $getImageName = imageResizer::ResizeImage($image, 'images', 'images', 300, 300);


        $product = new Product();
        $product->name = $request->name;
        $product->image = $getImageName;
        $product->description = $request->description;
       // for price covnert in decimal to int
       $price = str_replace(',', '', $request->price);
         $product->price = $price;

        $product->category_id = $request->category_id;
        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $product = Product::find($id);
        $categories = Category::all();

        return view('pages.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required',
        ]);

        $product = Product::find($id);

        $product->name = $request->name;
        $product->description = $request->description;
        // for price covnert in decimal to int
        $price = str_replace(',', '', $request->price);
        $product->price = $price;
        $product->category_id = $request->category_id;

        if ($request->file('image')) {
            $image = $request->file('image');
            $getImageName = imageResizer::ResizeImage($image, 'images', 'images', 300, 300);
            $product->image = $getImageName;
        }

        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $product = Product::find($id);
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
