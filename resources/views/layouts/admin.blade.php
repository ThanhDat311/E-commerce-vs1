<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
        }

        *::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        *::-webkit-scrollbar-track {
            background: transparent;
        }

        *::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .scrollbar-hide {
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        body {
            font-family: 'Nunito', sans-serif;
        }

        #wrapper {
            display: flex;
            height: 100vh;
        }

        #content-wrapper {
            flex: 1;
            overflow: hidden;
        }

        #content {
            flex: 1;
            overflow-y: auto;
        }

        /* Toast Notification Styles */
        #notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        .toast {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: none;
            margin-bottom: 10px;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(450px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in-right {
            animation: slideInRight 0.3s ease-out;
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .btn-close {
            opacity: 0.7;
        }

        .btn-close:hover {
            opacity: 1;
        }

        footer {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>

<body data-admin-notifications>
    <div id="wrapper">
        <!-- Real-time Notifications Container -->
        <div id="notification-container"></div>

        @include('admin.partials.sidebar')
        <div id="content-wrapper" class="flex flex-col">

            <div id="content">

                @include('admin.partials.navbar')
                <div class="p-8">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-6" role="alert">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-6" role="alert">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    @endif

                    @yield('content')

                </div>
            </div>
            <footer class="mt-auto">
                <div class="py-4 px-8">
                    <div class="text-center text-sm text-gray-500">
                        <span>Copyright &copy; Electro E-commerce {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <!-- Load Echo only on dashboard page for real-time notifications -->
    @if(Route::currentRouteName() === 'admin.dashboard')
    @vite('resources/js/echo.js')
    @endif

    @stack('scripts')
</body>

</html>