<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <h1>Hello admin!</h1>
    <span>One of your use has just created a new movie for you:</span>
    <h3>{{ $movie -> name }} - {{ $movie -> year }}</h3>
    <div>
        {{ $movie -> cashOut }}$
        <br>
        {{ $movie -> genre -> name }}
    </div>
    <h3>Tags:</h3>
    <ul>
        @foreach ($movie -> tags as $tag)
            <li>
                {{ $tag -> name }}
            </li>
        @endforeach
    </ul>

</body>
</html>