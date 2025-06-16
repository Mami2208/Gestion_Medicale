<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Espace Médecin') - Gestion Médicale</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('css/medecin.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
                <h3>Gestion Médicale</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('medecin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('medecin.dashboard') }}">
                        <i class='bx bxs-dashboard'></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('medecin.patients.*') ? 'active' : '' }}">
                    <a href="{{ route('medecin.patients.index') }}">
                        <i class='bx bxs-user-detail'></i>
                        <span>Patients</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('medecin.rendez-vous.*') ? 'active' : '' }}">
                    <a href="{{ route('medecin.rendez-vous.index') }}">
                        <i class='bx bxs-calendar'></i>
                        <span>Rendez-vous</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('medecin.consultations.*') ? 'active' : '' }}">
                    <a href="{{ route('medecin.consultations.index') }}">
                        <i class='bx bxs-file'></i>
                        <span>Consultations</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('medecin.dossiers.*') ? 'active' : '' }}">
                    <a href="{{ route('medecin.dossiers.index') }}">
                        <i class='bx bxs-folder'></i>
                        <span>Dossiers médicaux</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('medecin.statistiques.*') ? 'active' : '' }}">
                    <a href="{{ route('medecin.statistiques.index') }}">
                        <i class='bx bxs-bar-chart-alt-2'></i>
                        <span>Statistiques</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('medecin.parametres.*') ? 'active' : '' }}">
                    <a href="{{ route('medecin.parametres.index') }}">
                        <i class='bx bxs-cog'></i>
                        <span>Paramètres</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn">
                        <i class='bx bx-menu'></i>
                    </button>

                    <div class="d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <button class="btn position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown">
                                <i class='bx bxs-bell'></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class='bx bxs-user-circle fs-4'></i>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <p class="mb-0">{{ $notification->data['message'] }}</p>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="dropdown-item text-center">Aucune notification</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="dropdown">
                            <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <img src="{{ asset('images/avatar.png') }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                <span>{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('medecin.parametres.index') }}">
                                    <i class='bx bxs-user me-2'></i>Mon profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class='bx bxs-log-out me-2'></i>Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="container-fluid py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/medecin.js') }}"></script>
    
    @stack('scripts')
</body>
</html> 