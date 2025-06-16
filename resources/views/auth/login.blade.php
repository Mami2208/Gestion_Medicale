<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Connexion</title>
    
    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --primary-color: #0078d4;
            --primary-light: #2b88d8;
            --primary-dark: #005a9e;
            --accent-color: #00b294;
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
            background-color: #f0f8ff;
            background-image: url('/images/backgrounds/medical-bg.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 120, 212, 0.5), rgba(0, 178, 148, 0.6));
            z-index: 0;
        }
        
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding: 30px 15px;
        }
        
        .login-container {
            width: 100%;
            max-width: 500px;
            background-color: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 120, 212, 0.15);
            transition: all 0.3s ease;
            padding: 60px;
            animation: fadeIn 0.8s ease;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .logo-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        
        .logo-img:hover {
            transform: scale(1.05);
        }
        
        .brand-title {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            letter-spacing: -0.5px;
        }
        
        .brand-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 5px;
            background: linear-gradient(to right, var(--primary-light), var(--accent-color));
            border-radius: 4px;
        }
        
        .welcome-text {
            font-size: 1.5rem;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 35px;
        }
        
        .login-title {
            font-size: 2.4rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .subtitle {
            font-size: 1.3rem;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 40px;
        }
        
        .form-group {
            margin-bottom: 30px;
            position: relative;
        }
        
        .form-control {
            padding: 20px 20px 20px 60px;
            height: auto;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background-color: rgba(249, 250, 251, 0.7);
            font-size: 1.25rem;
            font-weight: 400;
            letter-spacing: 0.3px;
            box-shadow: none;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-light);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(0, 120, 212, 0.1);
        }
        
        .form-icon {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.5rem;
            z-index: 2;
        }
        
        .login-btn {
            background: linear-gradient(to right, var(--primary-color), var(--primary-light));
            border: none;
            color: white;
            font-weight: 600;
            font-size: 1.3rem;
            padding: 18px 20px;
            border-radius: 12px;
            margin-top: 30px;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(0, 120, 212, 0.15);
            transition: all 0.3s;
            width: 100%;
            text-transform: uppercase;
        }
        
        .login-btn:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 120, 212, 0.25);
        }
        
        .form-check-label {
            color: var(--text-muted);
            font-size: 1.2rem;
            padding-left: 8px;
        }
        
        .form-check {
            margin-bottom: 25px;
        }
        
        .forgot-password {
            display: block;
            text-align: center;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            margin-top: 35px;
            font-size: 1.2rem;
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
            
            .brand-title {
                font-size: 2.6rem;
            }
            
            .login-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .login-container {
                padding: 40px 25px;
            }
            
            .brand-title {
                font-size: 2.2rem;
            }
            
            .login-title {
                font-size: 1.8rem;
            }
            
            .subtitle {
                font-size: 1.15rem;
            }
            
            .form-control {
                font-size: 1.15rem;
                padding: 16px 15px 16px 55px;
            }
            
            .logo-img {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="login-container animate__animated animate__fadeIn">
            <div class="logo-section">
                <img src="/images/logo.svg" alt="Logo Gestion Médicale" class="logo-img">
                <h1 class="brand-title">Gestion Médicale</h1>
            </div>
            
            <div class="login-form-container">
                <h2 class="login-title animate__animated animate__fadeIn">Connexion</h2>
                <p class="subtitle animate__animated animate__fadeIn animate__delay-1s">Accédez à votre espace de santé personnalisé</p>
                
                @if ($errors->any())
                <div class="alert alert-danger animate__animated animate__fadeIn" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" class="animate__animated animate__fadeIn animate__delay-1s">
                    @csrf
                    
                    <div class="form-group">
                        <i class="fas fa-envelope form-icon"></i>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Adresse e-mail">
                    </div>
                    
                    <div class="form-group">
                        <i class="fas fa-lock form-icon"></i>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Mot de passe">
                    </div>
                    
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>
                    
                    <button type="submit" class="login-btn">
                        Connexion <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    
                    @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        Mot de passe oublié ?
                    </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>