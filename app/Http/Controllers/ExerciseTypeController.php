<?php

namespace App\Http\Controllers;

use App\Models\exerciseType;
use Illuminate\Http\Request;

class ExerciseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $exerciseType = exerciseType::all();

        return view('pages.exerciseType.index')->with('exerciseType', $exerciseType);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.exerciseType.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // attribute :  exercise_name,exercise_video_url ,exercise_description, exercise_duration, exercise_repetition , exercise_burned_esstimate

        // validate
            $request->validate([
            'exercise_name' => 'required',
            'exercise_video_url' => 'required',
            'exercise_description' => 'required',
            'exercise_duration' => 'required',
            'exercise_repetition' => 'required',
            'calories_burned_estimate' => 'required',
        ]);

        $exerciseType = new exerciseType();

        $exerciseType->exercise_name = $request->exercise_name;
        $exerciseType->exercise_video_url = $request->exercise_video_url;
        $exerciseType->exercise_description = $request->exercise_description;
        $exerciseType->exercise_duration = $request->exercise_duration;
        $exerciseType->exercise_repetition = $request->exercise_repetition;
        $exerciseType->calories_burned_estimate = $request->calories_burned_estimate;

        $exerciseType->save();

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
        $exerciseType = exerciseType::find($id);

        return view('pages.exerciseType.edit')->with('exerciseType', $exerciseType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // validate
        $request->validate([
            'exercise_name' => 'required',
            'exercise_video_url' => 'required',
            'exercise_description' => 'required',
            'exercise_duration' => 'required',
            'exercise_repetition' => 'required',
            'exercise_burned_estimate' => 'required',
        ]);

        $exerciseType = exerciseType::find($id);

        $exerciseType->exercise_name = $request->exercise_name;
        $exerciseType->exercise_video_url = $request->exercise_video_url;
        $exerciseType->exercise_description = $request->exercise_description;
        $exerciseType->exercise_duration = $request->exercise_duration;
        $exerciseType->exercise_repetition = $request->exercise_repetition;
        $exerciseType->exercise_burned_estimate = $request->exercise_burned_estimate;

        $exerciseType->save();

        return redirect()->route('exerciseType.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $exerciseType = exerciseType::find($id);
        $exerciseType->delete();

        return redirect()->route('exerciseType.index');
    }
}
