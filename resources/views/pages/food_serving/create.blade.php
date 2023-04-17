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


            <button type=" submit" class="btn btn-green text-white ">Submit</button>
        </form>
    </div>
</div>
@stop
