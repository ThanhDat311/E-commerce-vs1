<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200 min-h-screen flex flex-col justify-center items-center p-6 relative overflow-hidden">

    <!-- Decorative background elements -->
    <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
        <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
    </div>

    <div class="max-w-md w-full text-center space-y-8 z-10 relative">
        <div class="relative">
            <!-- Animated subtle glow -->
            <div class="absolute inset-0 bg-indigo-500/20 dark:bg-indigo-500/10 blur-3xl rounded-full animate-pulse"></div>
            
            <div class="relative flex justify-center items-center mb-6">
                <!-- Glitch/Shadow effect for the error code -->
                <span class="text-9xl font-black text-indigo-600 dark:text-indigo-400 tracking-tighter drop-shadow-lg" style="text-shadow: 4px 4px 0px rgba(79, 70, 229, 0.1);">
                    @yield('code')
                </span>
            </div>
        </div>

        <div class="space-y-4">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                @yield('message')
            </h1>
            <p class="text-base text-gray-500 dark:text-gray-400">
                @yield('description', "We're sorry, but something went wrong or you don't have permission to view this page.")
            </p>
        </div>

        <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="{{ url('/') }}" class="rounded-full bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-300 hover:scale-105 active:scale-95">
                Go back home
            </a>
            <a href="javascript:history.back()" class="text-sm font-semibold text-gray-900 dark:text-gray-300 hover:text-indigo-500 transition-colors duration-200 flex items-center gap-2 group">
                <span aria-hidden="true" class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span> Go back
            </a>
        </div>
    </div>

    <!-- Decorative background elements -->
    <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
        <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
    </div>

</body>
</html>
