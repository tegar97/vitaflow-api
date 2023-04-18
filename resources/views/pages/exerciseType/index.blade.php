@extends('layouts.default')
@section('title', 'Data List Exercise Type')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="content">
        <div class=" mt-5">
            <a href={{ route('exerciseType.create') }} class="btn btn-primary-green mb-3 ">Tambah Exercise Type</a>
            <h1></h1>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Exercise Name</th>
                        <th>Video URL</th>
                        <th>Description</th>
                        <th>Duration</th>
                        <th>Repetition</th>
                        <th>Burned Estimate</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($exerciseType as $exercise)
                        <tr>
                            <td>{{ $exercise->exercise_name }}</td>
                            <td>{{ $exercise->exercise_video_url }}</td>
                            <td>{{ $exercise->exercise_description }}</td>
                            <td>{{ $exercise->exercise_duration }}</td>
                            <td>{{ $exercise->exercise_repetition }}</td>
                            <td>{{ $exercise->calories_burned_estimate }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('exerciseType.show', $exercise) }}" class="btn btn-primary text-white">
                                    <i class="fa-regular fa-eye"></i> </a>
                                <a href="{{ route('exerciseType.edit', $exercise) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('exerciseType.destroy', $exercise) }}" method="POST">
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
