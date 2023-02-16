@extends('layouts.main-layout')

@section('content')
    
    <h1>Movies</h1>
    <a href="{{ route('movie.create') }}">CREATE NEW MOVIE</a>
    <ul>
        @foreach ($movies as $movie)
            @include('components.movie.list-item')
        @endforeach
    </ul>

@endsection