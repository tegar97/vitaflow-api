<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // get all category api

    public function getCategoryData()
    {
        $categories = Category::select('id', 'name')->get();

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }
    public function index()
    {
        $categories = Category::all();

        return view('pages.categories.index', compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
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

        $category = Category::find($id);
        return view('pages.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'name' => 'required',
        ]);

        // find id
        $category = Category::find($id);

        // update data
        $category->update($request->all());



        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {


        // find id
        $category = Category::find($id);

        // delete data
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
