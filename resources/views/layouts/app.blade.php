<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système DICOM Médical</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    @if (empty($hideNavbar))
        @include('components.navbar')
    @endif

    <main class="container mx-auto p-4">
        @yield('content')
    </main>
</body>
</html>
