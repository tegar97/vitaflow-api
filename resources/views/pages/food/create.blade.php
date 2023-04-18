@extends('layouts.default')
@section('title', 'Create Food')

@section('content')
    <div class="content bg-white p-2 px-4">
        <div class=" mt-5">
            <form enctype="multipart/form-data" action="{{ route('food.store') }}" method="post">
                @csrf
                <div class="form-group mb-3">
                    <label for="food_name" class="mb-2">Nama</label>
                    <input type="text" class="form-control" name="food_name" id="food_name"
                        placeholder="Nama Satuan (ex: gram, porsi)">
                </div>
                <div class="form-group mb-3">
                    <label for="food_rating" class="mb-2">Food Rating</label>
                    <select name="food_rating" id="food_rating" class="form-control">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="food_image" class="mb-2">Gambar</label>
                    <input type="file" name="food_image" id="food_image" class="form-control" />
                </div>
                <div class="form-group mb-3">
                    <label for="food_serving_unit_id" class="mb-2">Satuan Porsi</label>
                    <select name="food_serving_unit_id" id="food_serving_unit_id" class="form-control">
                        @foreach ($foodServing as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="serving_size" class="mb-2">Ukuran Porsi Default</label>
                    <input type="number" class="form-control" name="serving_size" id="serving_size"
                        placeholder="Ukuran Porsi (ex: 100, 300)">
                </div>
                <div class="form-group mb-3">
                    <label for="food_calories" class="mb-2">Kalori</label>
                    <input type="number" step="0.01" class="form-control" name="food_calories" id="food_calories"
                        placeholder="Kalori dalam (terkecil)">
                </div>
                <div class="form-group mb-3">
                    <label for="food_fat" class="mb-2">Lemak</label>
                    <input type="number" step="0.01" class="form-control" name="food_fat" id="food_fat"
                        placeholder="Lemak dalam (terkecil) ">
                </div>
                <div class="form-group mb-3">
                    <label for="food_saturated_fat" class="mb-2">Lemak Tersaturasi</label>
                    <input type="number" step="0.01" class="form-control" name="food_saturated_fat"
                        id="food_saturated_fat" placeholder="Lemak Tersaturasi (terkecil)">
                </div>

                <div class="form-group mb-3">
                    <label for="food_cholesterol" class="mb-2">Kolesterol</label>
                    <input type="number" step="0.01" class="form-control" name="food_cholesterol" id="food_cholesterol"
                        placeholder="Kolesterol (ex: 25.50)">
                </div>
                <div class="form-group mb-3">
                    <label for="food_sodium" class="mb-2">Natrium</label>
                    <input type="number" step="0.01" class="form-control" name="food_sodium" id="food_sodium"
                        placeholder="Natrium (ex: 300.00)">
                </div>
                <div class="form-group mb-3">
                    <label for="food_carbohydrate" class="mb-2">Karbohidrat</label>
                    <input type="number" step="0.01" class="form-control" name="food_carbohydrate"
                        id="food_carbohydrate" placeholder="Karbohidrat (ex: 20.00)">
                </div>
                <div class="form-group mb-3">
                    <label for="food_fiber" class="mb-2">Serat</label>
                    <input type="number" step="0.01" class="form-control" name="food_fiber" id="food_fiber"
                        placeholder="Serat (ex: 2.50)">
                </div>
                <div class="form-group mb-3">
                    <label for="food_sugar" class="mb-2">Gula</label>
                    <input type="number" step="0.01" class="form-control" name="food_sugar" id="food_sugar"
                        placeholder="Gula (ex: 5.00)">
                </div>
                <div class="form-group mb-3">
                    <label for="food_protein" class="mb-2">Protein</label>
                    <input type="number" step="0.01" class="form-control" name="food_protein" id="food_protein"
                        placeholder="Food Protein (ex: 5.00)">
                </div>

                <div class="form-group">
                    <label for="serving_units">Serving Units:</label><br>
                    @foreach ($foodServing as $servingUnit)
                        <input type="checkbox" name="serving_units[]" value="{{ $servingUnit->id }}"
                            >
                        {{ $servingUnit->name }}<br>
                    @endforeach
                </div>





                <button type=" submit" class="btn btn-green text-white ">Submit</button>
            </form>
        </div>
    </div>
@stop
