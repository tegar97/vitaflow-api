@extends('layouts.default')
@section('title','Edit Program')

@section('content')

<div class="content bg-white p-2 px-4">
    <div class=" mt-5">
        <form enctype="multipart/form-data" action="{{ route('exercise.update', $exercise->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="program_name" class="mb-2">Program Name</label>
                <input type="text" class="form-control" name="program_name" id="program_name" placeholder="Program Name" value="{{ $exercise->program_name }}">
            </div>
            <div class="form-group mb-3">
                <label for="program_goal" class="mb-2">Program Goal</label>
                <input type="text" class="form-control" name="program_goal" id="program_goal" placeholder="Program Goal" value="{{ $exercise->program_goal }}">
            </div>
            <div class="form-group mb-3">
                <label for="program_duration" class="mb-2">Program Duration</label>
                <input type="text" class="form-control" name="program_duration" id="program_duration" placeholder="Program Duration" value="{{ $exercise->program_duration }}">
            </div>
            <div class="form-group mb-3">
                <label for="program_difficulty" class="mb-2">Program Difficulty</label>
                <select class="form-control" name="program_difficulty" id="program_difficulty">
                    <option value="1" {{ $exercise->program_difficult == 1 ? 'selected' : '' }}>Easy</option>
                    <option value="2" {{ $exercise->program_difficult == 2 ? 'selected' : '' }}>Medium</option>
                    <option value="3" {{ $exercise->program_difficult == 3 ? 'selected' : '' }}>Hard</option>
                </select>
            </div>
            <button type="submit" class="btn btn-green text-white">Update</button>
        </form>
    </div>
</div>
@stop
