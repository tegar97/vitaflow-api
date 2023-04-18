@extends('layouts.default')
@section('title', 'Data Makanan')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="content">
        <div class=" mt-5">
            <a href={{ route('food.create') }} class="btn btn-primary-green mb-3 ">Tambah Makanan</a>
            <h1></h1>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>
                            Gambar
                        </th>
                        <th>Nama Makanan</th>
                        <th>Rating</th>
                        <th>Action</th>



                    </tr>
                </thead>
                <tbody>

                    @foreach ($foods as $food)
                        <tr>


                            <td><img src="{{ env('OSS_DOMAIN_PUBLIC') }}/images/{{ $food->food_image }}" alt="image makanan "
                                    width="70" height="70" />

                            <td>{{ $food->food_name }}</td>
                            <td>{{ $food->food_rating }}</td>

                            <td class="d-flex gap-2">
                                <a href="{{ route('food.show', $food) }}" class="btn btn-primary text-white">
                                    <i class="fa-regular fa-eye"></i> </a>
                                <a href="{{ route('food.edit', $food) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('food.destroy', $food) }}" method="POST">
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
