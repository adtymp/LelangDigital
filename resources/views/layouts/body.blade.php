<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="auth-id" content="{{ auth()->id() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('image/logo-pranala.png') }}">
    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <x-sidebar :menus="$menus" :badges="$badges" />

    <div class="lg:ml-64 p-4 transition-all duration-300">

        <x-toast />

        <x-alert />

        <div class="max-w-6xl mx-auto">
            @yield('content')
        </div>
    </div>
</body>

</html>