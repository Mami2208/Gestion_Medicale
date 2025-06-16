<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système DICOM Médical</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Styles personnalisés -->
    <style>
        :root {
            --medical-primary: #2c3e50;
            --medical-secondary: #34495e;
            --medical-accent: #3498db;
            --medical-success: #27ae60;
            --medical-light: #ecf0f1;
            --medical-dark: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .medical-header {
            background: linear-gradient(135deg, var(--medical-primary), var(--medical-secondary));
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .medical-form {
            background-color: var(--medical-light);
            padding: 2rem;
        }

        .medical-input {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .medical-input:focus {
            border-color: var(--medical-accent);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }

        .medical-btn {
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-success.medical-btn {
            background-color: var(--medical-success);
            border-color: var(--medical-success);
        }

        .btn-success.medical-btn:hover {
            background-color: #219a52;
            border-color: #219a52;
            transform: translateY(-2px);
        }

        .btn-outline-secondary.medical-btn:hover {
            background-color: var(--medical-secondary);
            color: white;
            transform: translateY(-2px);
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3.5rem + 2px);
            line-height: 1.25;
        }

        .form-floating > textarea.form-control {
            height: 100px;
        }

        .form-floating > label {
            padding: 1rem 0.75rem;
            color: var(--medical-dark);
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .invalid-feedback {
            color: #e74c3c;
            font-weight: 500;
        }

        .form-floating {
            position: relative;
            transition: all 0.3s ease;
        }

        .form-floating:hover {
            transform: translateY(-2px);
        }

        .fas {
            color: var(--medical-accent);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100">
    @if (empty($hideNavbar))
        @include('components.navbar')
    @endif

    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>
