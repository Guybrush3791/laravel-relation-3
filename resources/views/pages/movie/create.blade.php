@extends('layouts.main-layout')

@section('content')

    <h1>CREATE NEW MOVIE</h1>
    <form method="POST" action="{{ route('movie.store') }}">
        @csrf
        <label for="name">Name</label>
        <input type="text" name="name">
        <br>
        <label for="year">Year</label>
        <input type="number" name="year">
        <br>
        <label for="cashOut">Cash out</label>
        <input type="number" name="cashOut">
        <br>
        <label for="genre_id">Genre</label>
        <select name="genre_id">
            @foreach ($genres as $genre)
                <option value="{{ $genre -> id }}">
                    {{ $genre -> name }}
                </option>
            @endforeach
        </select>
        <br>
        <label>Tag</label>
        <br>
        @foreach ($tags as $tag)
            <input type="checkbox" name="tags_id[]" value="{{ $tag -> id }}" id="{{ $tag -> id }}">
            <label for="{{ $tag -> id }}">{{ $tag -> name }}</label>
            <br>
        @endforeach
        <input type="submit" value="CREATE NEW MOVIE">
    </form>

@endsection