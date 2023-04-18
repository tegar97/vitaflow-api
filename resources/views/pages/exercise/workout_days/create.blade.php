@extends('layouts.default')
@section('title', 'Create Workout Day')

@section('content')
    <div class="content">
        <form action="{{ route('storeExerciseWorkout',$exercise) }}" method="POST">
            @csrf
                            <input type="number" class="form-control" id="program_id" name="program_id" value={{$exercise->id}} required>

            <div class="mb-3">
                <label for="day_number" class="form-label">Day Number</label>
                <input type="number" class="form-control" id="day_number" name="day_number" required>
            </div>
            <div class="mb-3">
                <label for="workout_name" class="form-label">Workout Name</label>
                <input type="text" class="form-control" id="workout_name" name="workout_name" required>
            </div>
            <div class="mb-3">
                <label for="workout_duration" class="form-label">Workout Duration</label>
                <input type="number" class="form-control" id="workout_duration" name="workout_duration" required>
            </div>
            <div class="mb-3">
                <label for="workout_intensity" class="form-label">Workout Intensity</label>
                <select class="form-select" id="workout_intensity" name="workout_intensity" required>
                    <option value="" selected disabled>Select intensity</option>
                    <option value="1">Low</option>
                    <option value="2">Medium</option>
                    <option value="3">High</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="workout_description" class="form-label">Workout Description</label>
                <textarea class="form-control" id="workout_description" name="workout_description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="workout_background" class="form-label">Workout Background</label>
                <textarea class="form-control" id="workout_background" name="workout_background" required></textarea>
            </div>

            <!-- Exercise Type -->
<div class="mb-3">
  <div class="form-group">
                    <label for="serving_units">
                        Latihan
                    </label><br>
                    @foreach ($exerciseType as $exerciseType)
                        <input type="checkbox" name="exerciseType[]" value="{{ $exerciseType->id }}"
                            >
                        {{ $exerciseType->exercise_name }}<br>
                    @endforeach
                </div>
</div>

            <button type="submit" class="btn btn-primary-green">Create</button>
        </form>
    </div>
@endsection
