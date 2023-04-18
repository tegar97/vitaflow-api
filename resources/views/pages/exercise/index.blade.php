@extends('layouts.default')
@section('title', 'Data List program latihan ')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="content">
        <div class=" mt-5">
            <a href={{ route('exercise.create') }} class="btn btn-primary-green mb-3 ">Tambah program latihan</a>
            <h1></h1>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>

                        <th>
                            Exercise Name
                        </th>
                        <th>
                            Exercise Goal
                        </th>

                        <th>
                            Exercise duration
                        </th>
                        <th>
                            Exercise difficulty
                        </th>
                        <th>
                            Action
                        </th>






                    </tr>
                </thead>
                <tbody>

                    @foreach ($exercise as $exercise)
                        <tr>
                            <td>
                                {{ $exercise->program_name }}
                            </td>
                            <td>
                                {{ $exercise->program_goal }}
                            </td>
                            <td>
                                {{ $exercise->program_duration }}
                            </td>
                            <td>
                                {{ $exercise->program_difficulty }}
                            </td>






                            <td class="d-flex gap-2">
                                <a href="{{ route('listExerciseWorkout', $exercise) }}" class="btn btn-primary text-white">
                                    <i class="fa-regular fa-eye"></i> </a>
                                <a href="{{ route('exercise.edit', $exercise) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('exercise.destroy', $exercise) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>

                                </form>
        </div>
        </td>

        </tr>
        @endforeach

    </div>
    </tbody>


    </table>
    </div>
    </div>
@stop
