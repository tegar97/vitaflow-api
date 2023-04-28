<?php

namespace App\Http\Controllers;

use App\Models\category_article;
use Illuminate\Http\Request;

class CategoryArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categories = category_article::all();

        return view('pages.category_articles.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.category_articles.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
        ]);

        category_article::create($request->all());

        return redirect()->route('category-articles.index')
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

        $category = category_article::find($id);
        return view('pages.category_articles.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'name' => 'required',
        ]);

        $category = category_article::find($id);
        $category->update($request->all());

        return redirect()->route('category-articles.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $category = category_article::find($id);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
