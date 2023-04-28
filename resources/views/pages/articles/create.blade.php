@extends('layouts.default')
@section('title','Create Article')

@section('content')

<div class="content bg-white p-2 px-4">
    <div class="mt-5">
        <form enctype="multipart/form-data" action="{{route('articles.store')}}" method="post">
                    @csrf
        <div class="form-group mb-5">
            <label for="title" class="mb-2">Judul</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Judul Artikel">
        </div>

        <div class="form-group mb-5">
            <label for="image" class="mb-2">Gambar</label>
            <input type="file" class="form-control" name="image" id="image">
        </div>

        <div class="form-group mb-5">
            <label for="content" class="mb-2">Konten</label>
            <textarea class="form-control" name="content" id="content" placeholder="Konten Artikel"></textarea>
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
