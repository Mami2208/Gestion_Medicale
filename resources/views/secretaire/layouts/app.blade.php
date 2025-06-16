<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Gestion Médicale</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2d8659;
            --primary-light: #38a169;
            --primary-dark: #1a4d2e;
            --secondary-color: #4a90e2;
            --success-color: #48bb78;
            --info-color: #63b3ed;
            --warning-color: #f6ad55;
            --danger-color: #e53e3e;
            --light-color: #f7fafc;
            --dark-color: #2d3748;
            --gray-100: #f7fafc;
            --gray-200: #edf2f7;
            --gray-300: #e2e8f0;
            --gray-400: #cbd5e0;
            --gray-500: #a0aec0;
            --gray-600: #718096;
            --gray-700: #4a5568;
            --gray-800: #2d3748;
            --gray-900: #1a202c;
            --sidebar-width: 250px;
            --header-height: 60px;
        }

        .wrapper {
            display: flex;
        }
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: #e6f7ef;
            border-right: 1px solid rgba(0,0,0,0.1);
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: var(--primary-color);
            color: white;
        }
        #sidebar ul {
            list-style: none;
            padding: 0;
        }
        #sidebar ul li {
            padding: 10px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        #sidebar ul li a {
            color: var(--text-color);
            text-decoration: none;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #sidebar ul li a:hover {
            background: var(--primary-light);
            color: white;
            border-radius: 4px;
        }
        #sidebar ul li a.active {
            background: var(--primary-color);
            color: white;
            border-radius: 4px;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            margin-top: 80px;
        }
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 80px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        .topbar h4 {
            color: var(--text-color);
        }
        .topbar .btn {
            background: var(--primary-color);
            color: white;
            border: none;
        }
        .topbar .btn:hover {
            background: var(--primary-dark);
        }
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .card-header {
            background: var(--background-light);
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        .table thead th {
            background: var(--background-light);
            border-bottom: 2px solid rgba(0,0,0,0.1);
        }
        .badge.bg-success {
            background-color: var(--success-color);
        }
        .badge.bg-warning {
            background-color: var(--warning-color);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                margin-top: 80px;
            }
            #sidebar {
                display: none;
            }
            .topbar {
                left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex align-items-center w-100">
                <h4 class="mb-0 me-4">@yield('title')</h4>
                
                <!-- Barre de recherche -->
                <form action="{{ route('secretaire.search') }}" method="GET" class="position-relative me-auto" style="max-width: 500px;">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Rechercher patient, dossier..." name="q" value="{{ request()->get('q') }}" aria-label="Recherche" aria-describedby="search-button">
                        <button class="btn btn-success" type="submit" id="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <div class="d-flex align-items-center ms-auto">
                    <!-- Notifications -->
                    <div class="dropdown me-3">
                        <a href="#" class="text-dark position-relative" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell fs-5"></i>
                            @php
                                $notificationsCount = auth()->user()->notifications()->whereNull('read_at')->count();
                            @endphp
                            @if($notificationsCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    {{ $notificationsCount }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="width: 300px;">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            @php
                                $notifications = auth()->user()->notifications()->whereNull('read_at')->latest()->take(5)->get();
                            @endphp
                            @forelse($notifications as $notification)
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('secretaire.notifications.index') }}">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-bell text-success me-2"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <p class="mb-0 fw-bold">{{ $notification->title }}</p>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item text-center py-3">Aucune notification</span></li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-center" href="{{ route('secretaire.notifications.index') }}">
                                    Voir toutes les notifications
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Profile -->
                    <div class="dropdown me-3">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-sm me-2" style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden;">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo de profil" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="Photo de profil" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                            <span class="d-none d-md-inline">{{ auth()->user()->prenom }}</span>
                            <i class="fas fa-chevron-down ms-1 small"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><h6 class="dropdown-header">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</h6></li>
                            <li><a class="dropdown-item" href="{{ route('secretaire.profile') }}"><i class="fas fa-user-circle me-2"></i>Mon Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                    @yield('topbar-actions')
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h3>Gestion Médicale</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="nav-item">
                    <a href="{{ route('secretaire.dashboard') }}" class="nav-link {{ request()->routeIs('secretaire.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('secretaire.rendez-vous.index') }}" class="nav-link {{ request()->routeIs('secretaire.rendez-vous.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Rendez-vous</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="nav-link {{ request()->routeIs('secretaire.dossiers-medicaux.*') ? 'active' : '' }}">
                        <i class="fas fa-folder-medical"></i>
                        <span>Dossiers médicaux</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('secretaire.patients.index') }}" class="nav-link {{ request()->routeIs('secretaire.patients.*') && !request()->routeIs('secretaire.patients.assign') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Patients</span>
                    </a>
                </li>
                

            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
