<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class CategoryController extends Controller
{


    public function getCategoryData()
    {
        $redis = Redis::connection(); // memanggil koneksi Redis

        // memeriksa apakah data sudah tersimpan di cache
        if ($redis->exists('category_data')) {
            $result = json_decode($redis->get('category_data'), true);
        } else {
            $categories = Category::select('id', 'name')->get();

            // menyimpan hasil ke dalam cache Redis
            $redis->set('category_data', json_encode($categories));
            $redis->expire('category_data', 60); // set expire time 60 detik

            $result = $categories;
        }

        // return with json
        return response()->json([
            'status' => 'success',
            'data' => $result,
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
