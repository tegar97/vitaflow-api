@extends('layouts.default')
@section('title', 'Workout Days List')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="content">
        <div class=" mt-5">
            <a href={{ route('createExerciseWorkout',$exercise) }} class="btn btn-primary-green mb-3 ">Tambah workout day</a>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Day Number</th>
                        <th>Workout Name</th>
                        <th>Workout Duration</th>
                        <th>Workout Intensity</th>
                        <th>Workout Description</th>
                        <th>Workout Background</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($workoutdays as $workoutday)
                        <tr>
                            <td>{{ $workoutday->day_number }}</td>
                            <td>{{ $workoutday->workout_name }}</td>
                            <td>{{ $workoutday->workout_duration }}</td>
                            <td>{{ $workoutday->workout_intensity }}</td>
                            <td>{{ $workoutday->workout_description }}</td>
                            <td>{{ $workoutday->workout_background }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('workoutdays.show', $workoutday) }}" class="btn btn-primary text-white">
                                    <i class="fa-regular fa-eye"></i> </a>
                                <a href="{{ route('workoutdays.edit', $workoutday) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('workoutdays.destroy', $workoutday) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
