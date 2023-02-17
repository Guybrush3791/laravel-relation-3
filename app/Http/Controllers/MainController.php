<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Tag;

class MainController extends Controller
{
    
    public function home() {

        $genres = Genre :: all();

        return view('pages.home', compact('genres'));
    }
    public function movieAll() {

        $movies = Movie :: all();

        return view('pages.movie.all', compact('movies'));
    }

    public function movieCreate() {

        $genres = Genre :: all();
        $tags = Tag :: all();

        return view('pages.movie.create', compact('genres', 'tags'));
    }
    public function movieStore(Request $request) {

        $data = $request -> validate([
            'name' => 'required|string|min:3',
            'year' => 'required|integer|min:0',
            'cashOut' => 'required|integer|min:0',
            'genre_id' => 'required|integer|min:1',
            'tags_id' => 'required|array'
        ]);

        $genre = Genre :: find($data['genre_id']);
        $movie = Movie :: make($data);

        $movie -> genre() -> associate($genre);
        $movie -> save();

        $tags = Tag :: find($data['tags_id']);
        $movie -> tags() -> sync($tags);

        return redirect() -> route('home');
    }

    public function movieEdit(Movie $movie) {

        $genres = Genre :: all();
        $tags = Tag :: all();

        return view('pages.movie.update', 
            compact('movie', 'genres', 'tags')
        );
    }
    public function movieUpdate(Request $request, Movie $movie) {

        $data = $request -> validate([
            'name' => 'required|string|min:3',
            'year' => 'required|integer|min:0',
            'cashOut' => 'required|integer|min:0',
            'genre_id' => 'required|integer|min:1',
            'tags_id' => 'required|array'
        ]);

        $genre = Genre :: find($data['genre_id']);
        $movie -> update($data);
        $movie -> genre() -> associate($genre);
        $movie -> save();

        $tags = Tag :: find($data['tags_id']);
        $movie -> tags() -> sync($tags);

        return redirect() -> route('home');
    }
    public function movieDelete(Movie $movie) {

        $movie -> tags() -> sync([]);
        $movie -> delete();

        return redirect() -> route('home');
    }
}
