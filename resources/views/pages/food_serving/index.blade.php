@extends('layouts.default')
@section('title', 'Data Serving')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="content">
        <div class=" mt-5">
            <a href={{ route('foodServing.create') }} class="btn btn-primary-green mb-3 ">Tambah Satuan serving</a>
            <h1></h1>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>nama</th>
                        <th>size</th>
                        <th>Action</th>



                    </tr>
                </thead>
                <tbody>

                    @foreach ($foodServing as $serving)
                        <tr>


                            <td>{{ $serving->name }}</td>
                            <td>{{ $serving->serving_size }}</td>

                            <td class="d-flex gap-2">
                                <a href="{{ route('foodServing.edit', $serving) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('foodServing.destroy', $serving) }}" method="POST">
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
