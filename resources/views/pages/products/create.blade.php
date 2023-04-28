@extends('layouts.default')
@section('title','Create Product')

@section('content')

<div class="content bg-white p-2 px-4">
    <div class="mt-5">
        <form enctype="multipart/form-data" action="{{route('products.store')}}" method="post">

            @csrf
            <div class="form-group mb-5">
                <label for="name" class="mb-2">Nama</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Nama Product">
            </div>

            <div class="form-group mb-5">
                <label for="image" class="mb-2">Gambar</label>
                <input type="file" class="form-control" name="image" id="image">
            </div>

            <div class="form-group mb-5">
                <label for="description" class="mb-2">Deskripsi</label>
                <textarea class="form-control" name="description" id="description" placeholder="Deskripsi Product"></textarea>
            </div>

            <div class="form-group mb-5">
                <label for="price" class="mb-2">Harga</label>
                <input type="text" class="form-control" name="price" id="price" placeholder="Harga Product">
            </div>

            <div class="form-group mb-5">
                <label for="category_id" class="mb-2">Kategori</label>
                <select class="form-control" name="category_id" id="category_id">
                    @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-green text-white">Submit</button>
        </form>
    </div>

</div>
@stop
