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

{{--
    LAYOUT STRUCTURE:
    ┌─────────────────────────────────────────┐
    │  NAVBAR  h-16  z-50  fixed top-0        │
    ├──────────┬──────────────────────────────┤
    │          │                              │
    │ SIDEBAR  │  MAIN CONTENT               │
    │ w-64     │  ml-0 (mobile)              │
    │ fixed    │  lg:ml-64 (desktop)         │
    │ top-16   │  pt-16 (offset navbar)      │
    │          │                              │
    └──────────┴──────────────────────────────┘

    - Navbar: z-50, tetap di atas semua elemen
    - Overlay sidebar mobile: z-40 (di bawah sidebar, di atas konten)
    - Sidebar: z-50 mobile, z-30 desktop
    - Main content: tidak perlu z-index, mengalir secara normal
--}}
<body class="bg-gray-100 antialiased min-h-screen">

    {{-- NAVBAR UTAMA --}}
    <x-navbar />

    {{-- Spacer untuk kompensasi navbar fixed --}}
    <div class="h-20 shrink-0"></div>

    <div class="flex min-h-screen">

        {{-- SIDEBAR UTAMA --}}
        <x-sidebar :menus="$menus" :badges="$badges" />

        {{--
            MAIN CONTENT
            - Mobile/tablet: ml-0 (sidebar di-overlay, tidak menggeser konten)
            - Desktop (lg+): ml-64 (konten geser ke kanan sebesar lebar sidebar)
        --}}
        <main class="flex-1 w-full lg:ml-64 p-3 sm:p-4 md:p-6 transition-all duration-300">
            <x-toast />
            <x-alert />

            <div class="max-w-6xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

</body>

</html>