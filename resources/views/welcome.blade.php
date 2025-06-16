<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Médicale - Accueil</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2d8659;
            --primary-light: #38a169;
            --primary-dark: #276749;
            --success-color: #48bb78;
            --info-color: #63b3ed;
            --warning-color: #f6ad55;
            --accent-color: #38a169;
            --text-color: #2d3748;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand img {
            height: 50px;
        }
        
        .top-bar {
            background-color: #e6f7ef;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .hero-section {
            background: url('https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2091&q=80') center/cover no-repeat;
            padding: 150px 0;
            position: relative;
            overflow: hidden;
            color: white;
        }
        
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-image: linear-gradient(135deg, rgba(56, 161, 105, 0.8) 0%, rgba(39, 103, 73, 0.8) 100%);
            z-index: 0;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-heading {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .hero-subheading {
            font-size: 1.5rem;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.9);
            max-width: 700px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .cta-button {
            background-color: var(--accent-color);
            color: white;
            padding: 15px 30px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 1.1rem;
        }
        
        .cta-button:hover {
            background-color: #276749;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .features-section {
            padding: 80px 0;
        }
        
        .feature-card {
            padding: 30px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 40px;
            margin-bottom: 20px;
            color: var(--accent-color);
        }

        .btn-connexion {
            background-color: transparent;
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .btn-connexion:hover {
            background-color: var(--accent-color);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <span class="me-3"><i class="fas fa-phone me-1"></i> +221 77 666 66 66</span>
                <span><i class="fas fa-envelope me-1"></i> contact@gestion-medicale.com</span>
            </div>
            <div>
                <a href="{{ route('login') }}" class="text-decoration-none">
                    <i class="fas fa-user-md me-1"></i> Espace professionnel
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#" style="font-weight: 700; color: #2d8659; font-size: 24px;">
                <i class="fas fa-hospital-user me-2" style="color: #2d8659;"></i>Gestion Médicale
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Nos centres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Spécialités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-connexion" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Connexion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-10 hero-content">
                    <h1 class="hero-heading">Favoriser l'accès aux soins pour tous</h1>
                    <p class="hero-subheading">Notre plateforme de gestion médicale facilite le parcours de soins des patients et optimise le travail des professionnels de santé.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="mb-4">Nos services</h2>
                    <p class="lead">Découvrez les avantages de notre plateforme de gestion médicale</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-calendar-check feature-icon"></i>
                        <h4>Prise de rendez-vous en ligne</h4>
                        <p>Prenez rendez-vous avec nos médecins 24h/24 et 7j/7 depuis notre plateforme sécurisée.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-user-md feature-icon"></i>
                        <h4>Médecins spécialistes</h4>
                        <p>Accédez à une équipe de médecins spécialistes qualifiés dans différents domaines médicaux.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-folder-open feature-icon"></i>
                        <h4>Dossier médical numérique</h4>
                        <p>Consultez votre dossier médical numérique sécurisé et accédez à votre historique de soins.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-success text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Gestion Médicale</h5>
                    <p class="mt-3">Notre mission est de favoriser l'accès aux soins pour tous en proposant une plateforme de gestion médicale complète et intuitive.</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Accueil</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Nos centres</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Spécialités</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5>Contact</h5>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Rue de la Santé, Casablanca</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +212 555-555-555</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> contact@gestion-medicale.com</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Suivez-nous</h5>
                    <div class="mt-3">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Gestion Médicale. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
