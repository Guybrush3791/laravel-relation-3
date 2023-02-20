<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
	
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Tag;

use App\Mail\NewMovie;

class ApiController extends Controller
{
    
    public function getMovieWTagWGenre() {

        $movies = Movie :: with('tags') 
            -> orderBy('created_at', 'desc')
            -> get();
        $genres = Genre :: all();
        $tags = Tag :: all();

        return response() -> json([
            'success' => true,
            'response' => [
                'movies' => $movies,
                'genres' => $genres,
                'tags' => $tags,
            ]
        ]);
    }

    public function movieStore(Request $request) {

        $data = $request -> validate([
            'name' => 'required|string|min:3',
            'year' => 'required|integer|min:0',
            'cashOut' => 'required|integer|min:0',
            'genre_id' => 'required|integer|min:1',
            'tags_id' => 'nullable|array'
        ]);

        $genre = Genre :: find($data['genre_id']);
        $movie = Movie :: make($data);

        $movie -> genre() -> associate($genre);
        $movie -> save();

        if (array_key_exists('tags_id', $data)) {

            $tags = Tag :: find($data['tags_id']);
            $movie -> tags() -> sync($tags);
        }

        Mail::to('admin@bestcinema.com')
            -> send(new NewMovie($movie));

        return response() -> json([
            'success' => true,
            'response' => $movie,
            'data' => $request -> all()
        ]);
    }
    public function movieUpdate(Request $request, Movie $movie) {

        $data = $request -> validate([
            'name' => 'required|string|min:3',
            'year' => 'required|integer|min:0',
            'cashOut' => 'required|integer|min:0',
            'genre_id' => 'required|integer|min:1',
            'tags_id' => 'nullable|array'
        ]);

        $genre = Genre :: find($data['genre_id']);
        $movie -> update($data);
        $movie -> genre() -> associate($genre);
        $movie -> save();

        if (array_key_exists('tags_id', $data)) {

            $tags = Tag :: find($data['tags_id']);
            $movie -> tags() -> sync($tags);
        }
    
        return response() -> json([
            'success' => true,
            'response' => $movie,
            'data' => $request -> all()
        ]);
    }

    public function movieDelete(Movie $movie) {

        $movie -> tags() -> sync([]);
        $movie -> delete();

        return response() -> json([
            'success' => true
        ]);
    }
}
