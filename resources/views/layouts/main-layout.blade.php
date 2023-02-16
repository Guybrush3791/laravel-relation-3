<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel Relation</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])

    @yield('head')

</head>
<body>
    
    @include('components.header')
    @include('components.errors')
    <div class="container">
        @yield('content')
    </div>
    @include('components.footer')

</body>
</html>