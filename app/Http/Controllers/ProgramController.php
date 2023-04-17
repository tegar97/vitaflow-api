<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        return response()->json(['data' => $programs]);
    }

    public function show($id)
    {
        $program = Program::find($id);
        return response()->json(['data' => $program]);
    }

    public function store(Request $request)
    {
       // validate before store
        $program = Program::create($request->all());
        return response()->json(['data' => $program]);


    }

    public function update(Request $request, $id)
    {
        $program = Program::find($id);
        $program->update($request->all());
        $program->save();





        $program->save();


        return response()->json(['data' => $program]);
    }

    public function destroy($id)
    {
        $program = Program::find($id);
        $program->delete();
        return response()->json(['data' => $program]);
    }


}
