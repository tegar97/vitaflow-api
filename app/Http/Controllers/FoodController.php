<?php

namespace App\Http\Controllers;

use App\Helper\ImageResizer;
use App\Models\convertFoodUnit;
use App\Models\Food;
use App\Models\foodServingUnit;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource
     */

    // api
    public function getFoodData()
    {
        $foods =  Food::with('foodServingUnit')->get();


        $result = [];

        foreach ($foods as $food) {
            $defaultServing = $food->foodServingUnit->name;
            $defaultSize = $food->default_size;
            $calories = $food->food_calories * $food->foodServingUnit->serving_size * $defaultSize;
            $carbs = $food->food_carbohydrate * $food->foodServingUnit->serving_size * $defaultSize;
            $fat = $food->food_fat * $food->foodServingUnit->serving_size * $defaultSize;
            $protein = $food->food_protein * $food->foodServingUnit->serving_size * $defaultSize;

            $result[] = [
                'id' => $food->id, // tambah id untuk nanti di klik ke detail food

                'name' => $food->food_name,
                'default_serving' => $defaultServing,
                'default_size' => $defaultSize,
                'calories' => $calories,
                'carbs' => $carbs,
                'fat' => $fat,
                'protein' => $protein,
            ];
        }

        return response()->json([
            'message' => 'Success',
            'data' => $result
        ], 200);
    }

    public function searchFood(Request $request)
    {
        $search = $request->get('search');
        $foods =  Food::with('foodServingUnit')
        ->when($search, function ($query) use ($search) {
            return $query->where('food_name', 'like', '%' . $search . '%');
        })
            ->get();

        $result = [];

        foreach ($foods as $food) {
            $defaultServing = $food->foodServingUnit->name;
            $defaultSize = $food->default_size;
            $calories = $food->food_calories * $food->foodServingUnit->serving_size * $defaultSize;
            $carbs = $food->food_carbohydrate * $food->foodServingUnit->serving_size * $defaultSize;
            $fat = $food->food_fat * $food->foodServingUnit->serving_size * $defaultSize;
            $protein = $food->food_protein * $food->foodServingUnit->serving_size * $defaultSize;

            $result[] = [
                'id' => $food->id, // tambah id untuk nanti di klik ke detail food
                'name' => $food->food_name,
                'default_serving' => $defaultServing,
                'default_size' => $defaultSize,
                'calories' => $calories,
                'carbs' => $carbs,
                'fat' => $fat,
                'protein' => $protein,
            ];
        }

        return response()->json([
            'message' => 'Success',
            'data' => $result
        ], 200);
    }

    public function getFoodDataById($id)
    {
        $food =  Food::with('foodServingUnit')->find($id);

        $defaultServing = $food->foodServingUnit->name;
        $defaultSize = $food->default_size;
        $calories = $food->food_calories * $defaultSize;

        $result = [
            'name' => $food->food_name,
            'default_serving' => $defaultServing,
            'default_size' => $defaultSize,
            'calories' => $calories,
        ];

        return $result;
    }

    public function getFoodDetail(Request $request, $foodId)
    {
        // Ambil data makanan berdasarkan id
        $food = Food::findOrFail($foodId);

        // Ambil default serving unit dari makanan
        $defaultServingUnit = $food->foodServingUnit;

        // Ambil parameter satuan dan serving size dari query string
        $servingUnitId = $request->query('serving_unit_id', $defaultServingUnit->id);
        $servingSize = $request->query('serving_size', $defaultServingUnit->serving_size);

        // Ambil data serving unit berdasarkan id
        $servingUnit = FoodServingUnit::findOrFail($servingUnitId);


        // Hitung jumlah kalori dan nutrisi berdasarkan serving size yang diatur


        $calories = ($food->food_calories   * $servingUnit->serving_size)  * $servingSize;
        $fat = ($food->food_fat   * $servingUnit->serving_size)  * $servingSize;
        $saturatedFat = ($food->food_saturated_fat * $servingUnit->serving_size) * $servingSize;;
        $transFat = ($food->food_trans_fat * $servingUnit->serving_size) * $servingSize;
        $cholesterol = ($food->food_cholesterol * $servingUnit->serving_size) * $servingSize;
        $sodium = ($food->food_sodium * $servingUnit->serving_size) * $servingSize;
        $carbohydrate = ($food->food_carbohydrate * $servingUnit->serving_size) * $servingSize;
        $fiber = ($food->food_fiber * $servingUnit->serving_size)  * $servingSize;
        $sugar = ($food->food_sugar * $servingUnit->serving_size)  * $servingSize;
        $protein = ($food->food_protein * $servingUnit->serving_size) * $servingSize;



        // Format data untuk response
        $data = [
            'food_name' => $food->food_name,
            'food_image' => $food->food_image,
            'food_rating' => $food->food_rating,
            'serving_unit_name' => $servingUnit->name,
            'serving_size' => $servingSize,
            'calories' => $calories,
            'fat' => $fat,
            'saturated_fat' => $saturatedFat,
            'trans_fat' => $transFat,
            'cholesterol' => $cholesterol,
            'sodium' => $sodium,
            'carbohydrate' => $carbohydrate,
            'fiber' => $fiber,
            'sugar' => $sugar,
            'protein' => $protein,
        ];

        return response()->json($data);
    }

    // get available serving unit for food
    public function getFoodServingUnit($foodId)
    {
        // relationship with convertFoodUnit
        $food =  convertFoodUnit::with('foodServingUnit')->where('food_id', $foodId)->get();


        $result = [];

        foreach ($food as $food) {
            $result[] = [
                'id' => $food->foodServingUnit->id,
                'name' => $food->foodServingUnit->name,
                'serving_size' => $food->foodServingUnit->serving_size,
            ];
        }


        return $result;

    }





    public function index()
    {

        $food = Food::all();
        return view('pages.food.index')->with('foods', $food);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $foodServingUnit = foodServingUnit::all();

        return view('pages.food.create')->with('foodServing', $foodServingUnit);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // validate  name unique
        $validatedData  =  $request->validate([
            'food_name' => 'required|max:255',
            'food_rating' => 'required',
            'food_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'food_serving_unit_id' => 'required',
            'serving_size' => 'required|numeric|min:1',
            'food_calories' => 'nullable|numeric|min:0',
            'food_fat' => 'nullable|numeric|min:0',
            'food_saturated_fat' => 'nullable|numeric|min:0',
            'food_trans_fat' => 'nullable|numeric|min:0',
            'food_cholesterol' => 'nullable|numeric|min:0',
            'food_sodium' => 'nullable|numeric|min:0',
            'food_carbohydrate' => 'nullable|numeric|min:0',
            'food_fiber' => 'nullable|numeric|min:0',
            'food_sugar' => 'nullable|numeric|min:0',
            'food_protein' => 'nullable|numeric|min:0',
            'serving_units' => 'required|array|min:1',
        ]);

        $image = $request->file('food_image');
        $getImageName = ImageResizer::ResizeImage($image, 'images', 'images', 80, 80);


        //create new food serving unit
        $food = new Food();
        $food->food_name =  $request->input('food_name');
        $food->food_image = $getImageName;
        $food->food_rating =
            $request->input('food_rating');
        $food->food_serving_unit_id = $request->input('food_serving_unit_id');
        $food->default_size = $request->input('serving_size');
        $food->food_calories = $request->input('food_calories');
        $food->food_fat = $request->input('food_fat');
        $food->food_saturated_fat = $request->input('food_saturated_fat');
        $food->food_trans_fat = $request->input('food_trans_fat');
        $food->food_cholesterol = $request->input('food_cholesterol');
        $food->food_sodium = $request->input('food_sodium');
        $food->food_carbohydrate = $request->input('food_carbohydrate');
        $food->food_fiber = $request->input('food_fiber');
        $food->food_sugar = $request->input('food_sugar');
        $food->food_protein = $request->input('food_protein');
        $food->save();
        // attach to food_food_serving_unit table
        foreach ($validatedData['serving_units'] as $servingUnitId) {
            $servingUnit = FoodServingUnit::find($servingUnitId);

            if ($servingUnit) {
                // attach to convert serving unit table
               $foodFoodServingUnit = new convertFoodUnit();
                $foodFoodServingUnit->food_id = $food->id;
                $foodFoodServingUnit->food_serving_unit_id = $servingUnit->id;

                $foodFoodServingUnit->save();


            }
        }

        return redirect()->route('food.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // show data with relation covnvert_serving_unit
        $food = Food::with('foodServingUnit')->find($id);
        $foodServingUnits = $food->foodServingUnit;

        // Looping through the foodServingUnits collection
        foreach ($foodServingUnits as $foodServingUnit) {
            // get data from food_food_serving_unit table
            dd($foodServingUnit);


            // Your code to handle the data from the food_food_serving_unit table
        }


        return view('pages.food.serving.index')->with('foods', $food);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
