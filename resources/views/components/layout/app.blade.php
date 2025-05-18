<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? trans('Ollama Model Comparison') }}</title>

    <!-- Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body>
<header class="header">
    <div class="container">
        <h1 class="header__title">{{ $pageTitle ?? trans('Ollama Model Comparison') }}</h1>
    </div>
</header>
<main class="main">
    <div class="container">
        {{$slot}}
    </div>
</main>
</body>

</html>
