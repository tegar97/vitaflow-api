<?php

namespace App\Http\Controllers;

use App\Models\exerciseDay;
use App\Models\exerciseProgram;
use App\Models\exerciseType;
use App\Models\workoutDays;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function getExerciseProgram()
    {
        $exerciseType = exerciseType::all();

        return response()->json([
            'status' => 'success',
            'data' => $exerciseType
        ]);
    }

    public function searchExerciseProgram(Request $request)
    {
        $exerciseType = exerciseType::where('exercise_name', 'like', '%' . $request->search . '%')->get();
        return response()->json([
            'status' => 'success',
            'data' => $exerciseType
        ]);
    }





    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $exercise = exerciseProgram::all();

        return view('pages.exercise.index')->with('exercise', $exercise);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.exercise.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // validate
        $request->validate([
            'program_name' => 'required',
            'program_goal' => 'required',
            'program_duration' => 'required',
            'program_difficulty' => 'required',
        ]);

        $exercise = new exerciseProgram();
        $exercise->program_name = $request->program_name;
        $exercise->program_goal = $request->program_goal;
        $exercise->program_duration = $request->program_duration;
        $exercise->program_difficulty = $request->program_difficulty;

        $exercise->save();

        return redirect()->route('exercise.index');



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
        $exercise = exerciseProgram::find($id);
        return view('pages.exercise.edit', compact('exercise'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'program_name' => 'required',
            'program_goal' => 'required',
            'program_duration' => 'required',
            'program_difficulty' => 'required',
        ]);

        $exercise = exerciseProgram::find($id);
        $exercise->program_name = $request->program_name;
        $exercise->program_goal = $request->program_goal;
        $exercise->program_duration = $request->program_duration;
        $exercise->program_difficulty = $request->program_difficulty;

        $exercise->save();

        return redirect()->route('exercise.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $exercise = exerciseProgram::find($id);
        $exercise->delete();

        return redirect()->route('exercise.index');
    }

    public function ExerciseWorkout($id)
    {
        $exercise = exerciseProgram::find($id);
        $workoutdays = workoutDays::where('program_id', $id)->get();

        return view('pages.exercise.workout_days.index', compact('workoutdays', 'exercise'));
    }

    //createExerciseWorkout

    public function createExerciseWorkout($id)
    {
        $exercise = exerciseProgram::find($id);
        $exerciseType = exerciseType::all();

        return view('pages.exercise.workout_days.create', compact('exercise', 'exerciseType'));
    }

    // storeExerciseWorkout

    public function storeExerciseWorkout($id,Request $request)
    {
        $validatedData = $request->validate([
            'program_id' => 'required|integer',
            'day_number' => 'required|integer',
            'workout_name' => 'required|string',
            'workout_duration' => 'required|integer',
            'workout_intensity' => 'required|string',
            'workout_description' => 'required|string',
            'workout_background' => 'required|string',
            'exerciseType' => 'required|array',
            'exerciseType.*' => 'integer',
        ]);

        $workout = new workoutDays();
        $workout->program_id = $validatedData['program_id'];
        $workout->day_number = $validatedData['day_number'];
        $workout->workout_name = $validatedData['workout_name'];
        $workout->workout_duration = $validatedData['workout_duration'];
        $workout->workout_intensity = $validatedData['workout_intensity'];
        $workout->workout_description = $validatedData['workout_description'];
        $workout->workout_background = $validatedData['workout_background'];
        $workout->workout_image = 'default.png';

        $workout->save();

        // save to  exercise day table
        // save to  exercise day table
        foreach ($validatedData['exerciseType'] as $exerciseTypeId) {
            $i = 1;
            $exerciseDay = new ExerciseDay();
            $exerciseDay->workout_day_id = $workout->id;
            $exerciseDay->exercise_type_id = $exerciseTypeId;
            $exerciseDay->exercise_order = $i;
            $exerciseDay->save();

            $i = $i + 1;
        }



        return redirect()->route('exercise.index');
    }

}
