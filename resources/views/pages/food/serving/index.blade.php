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

                        <th>Satuan</th>




                    </tr>
                </thead>
                <tbody>

                    @foreach ($foods->foodServingUnit as $foodServingUnit)

                        <tr>


                            <td>{{ $unit->name }}</td>




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
