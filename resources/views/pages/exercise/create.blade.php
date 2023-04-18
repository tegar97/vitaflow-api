@extends('layouts.default')
@section('title','Create Program')

@section('content')

<div class="content bg-white p-2 px-4">
    <div class=" mt-5">
        <form enctype="multipart/form-data" action="{{route('exercise.store')}}" method="post">
                    @csrf
        <div class="form-group  mb-3">
            <label for="program_name" class="mb-2">Program Name</label>
            <input type="text" class="form-control" name="program_name" id="program_name" placeholder="Program Name">
        </div>
        <div class="form-group mb-3">
            <label for="program_goal" class="mb-2">Program Goal</label>
            <input type="text" class="form-control" name="program_goal" id="program_goal" placeholder="Program Goal">
        </div>
        <div class="form-group mb-3">
            <label for="program_duration" class="mb-2">Program Duration</label>
            <input type="number" class="form-control" name="program_duration" id="program_duration" placeholder="Program Duration">
        </div>
        <div class="form-group mb-3">
            <label for="program_difficulty" class="mb-2">Program Difficulty</label>
            <select class="form-control" name="program_difficulty" id="program_difficulty">
                <option value="1">Easy</option>
                <option value="2">Medium</option>
                <option value="3">Hard</option>
            </select>
        </div>

        <button type="submit" class="btn btn-green text-white">Submit</button>
    </form>
</div>
</div>
@stop
