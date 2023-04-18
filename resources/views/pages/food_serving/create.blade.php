@extends('layouts.default')
@section('title','Create Unit size Food')

@section('content')
<div class="content bg-white p-2 px-4">
    <div class=" mt-5">
        <form enctype="multipart/form-data" action="{{route('foodServing.store')}}" method="post">

            @csrf
            <div class="form-group  mb-5">
                <label for="name" class="mb-2">Nama</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Nama Satuan (ex: gram ,porsi)">
            </div>
               <div class="form-group  mb-5">
                <label for="serving_size" class="mb-2">Serving size</label>
                <input type="number" class="form-control" name="serving_size" id="serving_size" placeholder="Serving size (ex : 100,300)">
            </div>


            <button type=" submit" class="btn btn-green text-white ">Submit</button>
        </form>
    </div>
</div>
@stop
