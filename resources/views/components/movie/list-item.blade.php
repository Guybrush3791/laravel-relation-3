<li>
    {{ $movie -> name }}
    - <a href="{{ route('movie.edit', $movie) }}">EDIT</a>
    - <a href="{{ route('movie.delete', $movie) }}">DELETE</a>
    <ul>
        @foreach ($movie -> tags as $tag)
            <li>{{ $tag -> name }}</li>
        @endforeach
    </ul>
</li>