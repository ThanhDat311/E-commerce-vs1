<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendor Portal')</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">

    <style>
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

        .toast-header {
            border-bottom: 2px solid #28a745;
        }

        .toast-body {
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Vendor Portal Branding */
        .vendor-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 5px;
        }
    </style>
</head>

<body data-vendor-notifications>
    <div id="wrapper">
        <!-- Real-time Notifications Container -->
        <div id="notification-container"></div>

        @include('vendor.partials.sidebar')
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                @include('vendor.partials.navbar')
                <div class="container-fluid py-4">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @yield('content')

                </div>
            </div>
            <footer class="sticky-footer bg-white py-3 mt-auto">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Vendor Portal &copy; {{ date('Y') }} | Your E-commerce Platform</span>
                    </div>
                </div>
            </footer>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <!-- Vite-built Echo with fallback for development -->
    @if(file_exists(public_path('build/manifest.json')))
    @vite('resources/js/echo.js')
    @else
    <!-- Development fallback: Load echo.js directly -->
    <script type="module">
        // Fallback import for development when Vite dev server is not running
        try {
            // Try to import from built resources
            import('{{ asset("resources/js/echo.js") }}').then(module => {
                window.showOrderNotification = module.showOrderNotification;
                window.playNotificationSound = module.playNotificationSound;
            }).catch(err => {
                console.warn('Echo module not available - real-time notifications disabled', err);
            });
        } catch (e) {
            console.warn('Vite manifest not found and direct import failed - start Vite dev server with: npm run dev');
        }
    </script>
    @endif

    @stack('scripts')
</body>

</html>