<?php

namespace App\Http\Controllers;

use App\Models\foodServingUnit;
use Illuminate\Http\Request;

class FoodServingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foodServing = foodServingUnit::all();
        return view('pages.food_serving.index')->with('foodServing', $foodServing);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.food_serving.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

       // validate  name unique
        $request->validate([
            'name' => 'required|unique:food_serving_units|max:255',
        ]);
        //create new food serving unit
        $foodServing = new foodServingUnit();
        $foodServing->name = $request->name;
        $foodServing->serving_size = $request->serving_size;
        $foodServing->save();
        return redirect()->route('foodServing.index');
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
        // edit
        $foodServing = foodServingUnit::find($id);
        return view('pages.food_serving.edit')->with('foodServing', $foodServing);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // update
        $foodServing = foodServingUnit::find($id);
        $foodServing->name = $request->name;
        $foodServing->serving_size = $request->serving_size;
        $foodServing->save();
        return redirect()->route('foodServing.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // destory

        $foodServing = foodServingUnit::find($id);
        $foodServing->delete();
        return redirect()->route('foodServing.index');
    }
}
