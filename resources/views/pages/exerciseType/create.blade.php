@extends('layouts.default')
@section('title','Create Exercise Type')

@section('content')

<div class="content bg-white p-2 px-4">
    <div class=" mt-5">
        <form enctype="multipart/form-data" action="{{route('exerciseType.store')}}" method="post">
                    @csrf
        <div class="form-group mb-3">
            <label for="exercise_name" class="mb-2">Exercise Name</label>
            <input type="text" class="form-control" name="exercise_name" id="exercise_name" placeholder="Enter exercise name">
        </div>

        <div class="form-group mb-3">
            <label for="exercise_video_url" class="mb-2">Exercise Video URL</label>
            <input type="text" class="form-control" name="exercise_video_url" id="exercise_video_url" placeholder="Enter exercise video URL">
        </div>

        <div class="form-group mb-3">
            <label for="exercise_description" class="mb-2">Exercise Description</label>
            <textarea class="form-control" name="exercise_description" id="exercise_description" rows="3" placeholder="Enter exercise description"></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="exercise_duration" class="mb-2">Exercise Duration (dalam detik)</label>
            <input type="number" class="form-control" name="exercise_duration" id="exercise_duration" placeholder="Enter exercise duration">
        </div>

        <div class="form-group mb-3">
            <label for="exercise_repetition" class="mb-2">Exercise Repetition</label>
            <input type="text" class="form-control" name="exercise_repetition" id="exercise_repetition" placeholder="Enter exercise repetition">
        </div>

        <div class="form-group mb-3">
            <label for="calories_burned_estimate" class="mb-2">Exercise Burned Estimate</label>
            <input type="number" class="form-control" name="calories_burned_estimate" id="calories_burned_estimate" placeholder="Enter exercise burned estimate">
        </div>



        <button type="submit" class="btn btn-green text-white">Submit</button>
    </form>
</div>
</div>
@stop
