<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Connexion</title>
    
    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --primary-color: #2d8659;
            --primary-light: #3aa171;
            --primary-dark: #216b47;
            --accent-color: #4aad85;
            --text-dark: #333333;
            --text-muted: #6e6e6e;
            --error-color: #e53e3e;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #e8f5e9; /* Couleur de secours verte claire */
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Utiliser une image de stéthoscope comme arrière-plan */
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/images/backgrounds/medical-bg.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            opacity: 0.8;
            z-index: -1;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(45, 134, 89, 0.6), rgba(33, 107, 71, 0.7));
            z-index: 0;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(45, 134, 89, 0.2);
            transition: all 0.3s ease;
            padding: 40px;
            position: relative;
            z-index: 1;
            animation: fadeIn 0.8s ease;
        }
        
        .login-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 10px;
            text-align: center;
            position: relative;
            display: inline-block;
            letter-spacing: -0.5px;
            width: 100%;
        }
        
        .login-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-dark), var(--primary-light));
            border-radius: 4px;
        }
        
        .subtitle {
            font-size: 1.3rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-top: 15px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .form-label {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        
        .form-control {
            padding: 16px 20px;
            height: auto;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background-color: rgba(249, 250, 251, 0.7);
            font-size: 1.2rem;
            font-weight: 400;
            letter-spacing: 0.3px;
            box-shadow: none;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-light);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(45, 134, 89, 0.1);
        }
        
        .form-check-label {
            color: var(--text-muted);
            font-size: 1.1rem;
            padding-left: 4px;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-login {
            background: linear-gradient(to right, var(--primary-color), var(--primary-light));
            border: none;
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
            padding: 14px 20px;
            border-radius: 8px;
            margin-top: 25px;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(45, 134, 89, 0.3);
            transition: all 0.3s;
            width: 100%;
            text-transform: uppercase;
        }
        
        .btn-login:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(45, 134, 89, 0.3);
        }
        
        .forgot-password {
            display: block;
            text-align: center;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            margin-top: 25px;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .alert-danger {
            background-color: rgba(229, 62, 62, 0.1);
            border: 1px solid rgba(229, 62, 62, 0.2);
            color: var(--error-color);
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .login-container {
                padding: 50px 35px;
                max-width: 90%;
            }
            
            .login-title {
                font-size: 2.4rem;
            }
            
            .subtitle {
                font-size: 1.4rem;
            }
        }
        
        @media (max-width: 576px) {
            .login-container {
                padding: 40px 25px;
            }
            
            .login-title {
                font-size: 2.2rem;
            }
            
            .subtitle {
                font-size: 1.2rem;
            }
            
            .form-control {
                font-size: 1.1rem;
                padding: 14px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container animate__animated animate__fadeIn">
        <div class="text-center mb-4">
            <img src="{{ asset('/images/logo.svg') }}" alt="Logo" class="logo-img" width="150" height="150" style="max-width: 150px; height: auto; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)); margin-bottom: 15px;">
        </div>
        <h1 class="login-title">Gestion Médicale</h1>
        <h2 class="subtitle">Connexion</h2>
        
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}" class="animate__animated animate__fadeIn animate__delay-1s">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="form-label">Adresse email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label">Mot de passe</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            </div>
            
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
            
            <button type="submit" class="btn btn-login">
                Se connecter <i class="fas fa-arrow-right ms-2"></i>
            </button>
            
            @if (Route::has('password.request'))
            <a class="forgot-password" href="{{ route('password.request') }}">
                Mot de passe oublié ?
            </a>
            @endif
        </form>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
