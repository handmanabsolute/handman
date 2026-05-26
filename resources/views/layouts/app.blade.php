<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Handman - Sistem Management Tugas Kantor')</title>
    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased" x-data="{ sidebarOpen: false }">

    @auth
        @include('components.sidebar')

        <div class="md:pl-64 flex flex-col min-h-screen">
            @include('components.topbar')

            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    @endauth

    @guest
        @yield('content')
    @endguest



</body>
</html>
