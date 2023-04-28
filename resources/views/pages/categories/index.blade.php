@extends('layouts.default')
@section('title', 'Data Category')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="content">
        <div class=" mt-5">
            <a href={{ route('categories.create') }} class="btn btn-primary-green mb-3 ">Tambah Category </a>
            <h1></h1>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Category</th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td class="d-flex gap-2">


                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST">
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
