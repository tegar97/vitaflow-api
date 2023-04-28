@extends('layouts.default')
@section('title', 'Data Article')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="content">
        <div class=" mt-5">
            <a href={{ route('articles.create') }} class="btn btn-primary-green mb-3 ">Tambah Artikel </a>
            <h1></h1>
            <table id="table" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>photo</th>
                        <th>title</th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr>
                            <td><img src="{{ env('OSS_DOMAIN_PUBLIC') }}/images/{{ $article->image }}" alt="image articles "
                                    width="70" height="70" />
                            <td>{{ $article->name }}</td>

                            <td class="d-flex gap-2">


                                <a href="{{ route('articles.edit', $article) }}" class="btn btn-green text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('articles.destroy', $article) }}" method="POST">
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
