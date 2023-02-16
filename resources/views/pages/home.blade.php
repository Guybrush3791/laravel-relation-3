@extends('layouts.main-layout')

@section('content')
    
    <h1>Movies</h1>
    <a href="{{ route('movie.create') }}">CREATE NEW MOVIE</a>
    @foreach ($genres as $genre)
        <h3>
            {{ $genre -> name }}
            <ul>
                @foreach ($genre -> movies as $movie)
                    @include('components.movie.list-item')
                @endforeach
            </ul>
        </h3>        
    @endforeach

@endsection