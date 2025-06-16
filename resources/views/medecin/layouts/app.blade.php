<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'DICOM Médical') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-green-50">
    <style>
        :root {
            --primary-color: #2d8659; /* Vert médical */
            --primary-light: #e8f5e9; /* Vert clair */
            --primary-dark: #1b5e20; /* Vert foncé */
        }
        .bg-primary { background-color: var(--primary-color) !important; }
        .text-primary { color: var(--primary-color) !important; }
        .border-primary { border-color: var(--primary-color) !important; }
        .hover\:bg-primary:hover { background-color: var(--primary-dark) !important; }
        .focus\:ring-primary:focus { --tw-ring-color: var(--primary-color) !important; }
        /* Remplacer le bleu par du vert dans l'interface */
        .bg-blue-600 { background-color: var(--primary-color) !important; }
        .hover\:bg-blue-700:hover { background-color: var(--primary-dark) !important; }
        .focus\:bg-blue-700:focus { background-color: var(--primary-dark) !important; }
        .active\:bg-blue-900:active { background-color: var(--primary-dark) !important; }
        .focus\:ring-blue-500:focus { --tw-ring-color: var(--primary-color) !important; }
        .focus\:border-blue-500:focus { border-color: var(--primary-color) !important; }
    </style>
    <div class="min-h-screen">
        <!-- Sidebar -->
        @include('medecin.partials.sidebar')

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Topbar -->
            @include('components.navbar')

            <!-- Page Content -->
            <main class="py-6 pt-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')

    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('aside');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 1024) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = sidebarToggle && sidebarToggle.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script>
</body>
</html> 