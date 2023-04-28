@extends('layouts.default')
@section('title', 'Data product')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="content">
        <div class=" mt-5">
            <a href={{ route('products.create') }} class="btn btn-primary-green mb-3 ">Tambah Category </a>
            <h1></h1>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>photo</th>
                        <th>nama product</th>
                        <th>harga</th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td><img src="{{ env('OSS_DOMAIN_PUBLIC') }}/images/{{ $product->image }}" alt="image products "
                                    width="70" height="70" />
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->price }}</td>

                            <td class="d-flex gap-2">


                                <a href="{{ route('products.edit', $product) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST">
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
