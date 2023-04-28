@extends('layouts.default')
@section('title', 'Edit Product')

@section('content')

<div class="content bg-white p-2 px-4">
    <div class="mt-5">
        <form enctype="multipart/form-data" action="{{ route('products.update', $product->id) }}" method="post">

                    @csrf
        @method('PUT')
        <div class="form-group mb-5">
            <label for="name" class="mb-2">Nama</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $product->name }}" placeholder="Nama Product">
        </div>

        <div class="form-group mb-5">
            <label for="description" class="mb-2">Deskripsi</label>
            <textarea class="form-control" name="description" id="description" placeholder="Deskripsi Product">{{ $product->description }}</textarea>
        </div>

        <div class="form-group mb-5">
            <label for="price" class="mb-2">Harga</label>
            <input type="number" class="form-control" name="price" id="price" value="{{ $product->price }}" placeholder="Harga Product">
        </div>

        <div class="form-group mb-5">
            <label for="category_id" class="mb-2">Kategori</label>
            <select name="category_id" id="category_id" class="form-control">
                @foreach($categories as $category)
                <option value="{{ $category->id }}" @if($product->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-5">
            <label for="image" class="mb-2">Gambar</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-green text-white">Simpan</button>
    </form>
</div>
</div>
@stop
