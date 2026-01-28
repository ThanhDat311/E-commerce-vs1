<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Electro Admin') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-gray-900 antialiased">

    <!-- WRAPPER: h-screen = exact viewport height -->
    <div class="flex h-screen">

        <!-- SIDEBAR -->
        @include('admin.partials.sidebar')

        <!-- MAIN: flex-1 takes remaining space, flex col for navbar + content -->
        <main class="flex-1 flex flex-col overflow-hidden">

            <!-- NAVBAR: fixed height, sticky positioning -->
            @include('admin.partials.navbar')

            <!-- CONTENT: flex-1 takes remaining, scroll internally -->
            <div class="flex-1 overflow-y-auto p-8 bg-slate-50 dark:bg-slate-900">
                @yield('content')
            </div>

        </main>
    </div>

</body>
</html>