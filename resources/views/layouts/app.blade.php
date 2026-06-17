<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Handman - Sistem Management Tugas Kantor')</title>

    {{-- SEO Meta Tags --}}
    <meta name="description" content="Handman adalah sistem manajemen tugas kantor yang membantu tim mengelola tugas, jadwal, dan laporan secara efisien. Kelola tugas, pantau progres, dan tingkatkan produktivitas tim Anda.">
    <meta name="keywords" content="handman, sistem manajemen tugas, task management, manajemen tugas kantor, kelola tugas, jadwal kerja, laporan tugas, produktivitas tim, project management, kantor">
    <meta name="author" content="Handman">
    <meta name="robots" content="index, follow">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="Handman - Sistem Management Tugas Kantor">
    <meta property="og:description" content="Sistem manajemen tugas kantor yang membantu tim mengelola tugas, jadwal, dan laporan secara efisien.">
    <meta property="og:image" content="{{ asset('assets/logo.png') }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ config('app.url') }}">
    <meta name="twitter:title" content="Handman - Sistem Management Tugas Kantor">
    <meta name="twitter:description" content="Sistem manajemen tugas kantor yang membantu tim mengelola tugas, jadwal, dan laporan secara efisien.">
    <meta name="twitter:image" content="{{ asset('assets/logo.png') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="user-role" content="{{ auth()->user() ? auth()->user()->nama_role : '' }}">
    <meta name="user-departemen-id" content="{{ auth()->user() ? auth()->user()->departemen_id : '' }}">
    @if(config('broadcasting.default') === 'reverb')
    <meta name="reverb-key" content="{{ config('reverb.apps.apps.0.key') }}">
    <meta name="reverb-host" content="{{ config('reverb.apps.apps.0.options.host') }}">
    <meta name="reverb-port" content="{{ config('reverb.apps.apps.0.options.port') }}">
    <meta name="reverb-scheme" content="{{ config('reverb.apps.apps.0.options.scheme') }}">
    @endif
    @if(config('broadcasting.default') === 'pusher')
    <meta name="pusher-key" content="{{ config('broadcasting.connections.pusher.key') }}">
    <meta name="pusher-cluster" content="{{ config('broadcasting.connections.pusher.options.cluster') }}">
    <meta name="pusher-scheme" content="{{ config('broadcasting.connections.pusher.options.scheme', 'https') }}">
    @endif
    @if(config('broadcasting.default') === 'reverb' || config('broadcasting.default') === 'pusher')
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/reverb.js'])
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased" x-data="{ sidebarOpen: false }">

    @auth
        <div class="flex min-h-screen bg-gray-50">
            @include('components.sidebar')

            <div id="app-body-container" class="flex-1 flex flex-col min-w-0">
                @include('components.topbar')

                <main class="flex-1 px-4 pb-4 sm:px-6 mt-4 overflow-x-hidden md:ml-64">
                    @yield('content')
                </main>
            </div>
        </div>
    @endauth

    @guest
        @yield('content')
    @endguest
</body>
</html>
