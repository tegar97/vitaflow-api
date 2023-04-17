<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\program_mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{

    public function index()
    {
        $missions = Mission::all();
        return response()->json(['data' => $missions]);
    }

    public function show($id)
    {
        $mission = Mission::find($id);
        return response()->json(['data' => $mission]);
    }

    public function store(Request $request)
    {
       // add missions and relation with program_missions
        $mission = new Mission();
        $mission->name = $request->name;
        $mission->description = $request->description;
        $mission->icon = $request->icon;
        $mission->color_Theme = $request->color_Theme;
        $mission->point = $request->point;
        $mission->save();

        $program_mission = new program_mission();
        $program_mission->program_id = $request->program_id;
        $program_mission->mission_id = $mission->id;
        $program_mission->save();


        return response()->json(['data' => $mission]);
    }

    public function update(Request $request, $id)
    {
        $mission = Mission::find($id);
        $mission->update($request->all());
        $mission->save();
        return response()->json(['data' => $mission]);
    }

    public function destroy($id,$program_id)
    {
        $mission = Mission::find($id);

        $mission->delete();
        // delete relation with program_missions with program_id and mission_id
        $program_mission = program_mission::where('mission_id', $id)->where('program_id', $program_id)->first();
        $program_mission->delete();

        return response()->json(['data' => $mission]);
    }
}
