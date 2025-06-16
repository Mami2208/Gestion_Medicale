<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Espace Patient</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2d8659;
            --primary-light: #38a169;
            --primary-dark: #276749;
            --success-color: #48bb78;
            --info-color: #63b3ed;
            --warning-color: #f6ad55;
            --danger-color: #ed64a6;
            --background-light: #f7fafc;
            --text-color: #4a5568;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--text-color);
        }

        .wrapper {
            display: flex;
        }
        
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-right: 1px solid rgba(0,0,0,0.1);
            height: 100vh;
            position: fixed;
            z-index: 100;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            color: white;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        
        #sidebar ul li {
            padding: 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        #sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border-radius: 6px;
            margin: 5px 10px;
        }
        
        #sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }
        
        #sidebar ul li a.active {
            background: white;
            color: var(--primary-dark);
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }
        
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .card-header h5 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 8px;
            width: 18px;
            height: 18px;
            background-color: var(--danger-color);
            color: white;
            font-size: 11px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
        }
        
        .dicom-thumbnail {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        
        .dicom-thumbnail:hover {
            transform: scale(1.05);
        }
        
        .dicom-card {
            transition: all 0.3s ease;
        }
        
        .dicom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .medical-info-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .medical-info-item .label {
            font-weight: 600;
            min-width: 180px;
            color: var(--text-color);
        }
        
        .medical-info-item .value {
            flex: 1;
        }
        
        .pdf-item {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }
        
        .pdf-item:hover {
            background-color: rgba(56, 161, 105, 0.05);
            transform: translateX(5px);
        }
        
        .pdf-icon {
            font-size: 24px;
            color: #e53e3e;
            margin-right: 15px;
        }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
                position: fixed;
                min-height: 100vh;
                z-index: 999;
                transition: all 0.3s;
            }
            
            #sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            #sidebarCollapse {
                display: block;
            }
            
            .overlay {
                display: none;
                position: fixed;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
                opacity: 0;
                transition: all 0.5s ease-in-out;
            }
            
            .overlay.active {
                display: block;
                opacity: 1;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('patient.partials.sidebar')
        
        <!-- Page Content -->
        <div class="main-content">
            <div class="container-fluid">
                <div class="topbar d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-dark mb-0">@yield('page_title')</h2>
                    <div class="topbar-actions d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <a href="#" class="text-muted position-relative" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#">Aucune notification</a></li>
                            </ul>
                        </div>
                        
                        <!-- Profil -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar-circle me-2" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user-circle text-success"></i>
                                </div>
                                <div>
                                    <span class="d-none d-md-inline fw-bold">{{ Auth::user()->nom ?? 'Patient' }} {{ Auth::user()->prenom ?? '' }}</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('patient.profile') }}"><i class="fas fa-user-edit me-2"></i>Mon profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay"></div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('.overlay').toggleClass('active');
            });
            
            $('.overlay').on('click', function() {
                $('#sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
