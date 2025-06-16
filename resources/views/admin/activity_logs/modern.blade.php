@extends('layouts.admin')

@section('title', 'Journal d\'activité')

@section('styles')
<style>
    /* Variables de couleurs */
    :root {
        --primary: #2563eb;
        --primary-dark: #1e40af;
        --primary-light: #dbeafe;
        --secondary: #4b5563;
        --success: #10b981;
        --success-light: #d1fae5;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --warning: #f59e0b;
        --warning-light: #fef3c7;
        --info: #3b82f6;
        --info-light: #dbeafe;
        --dark: #1f2937;
        --light: #f3f4f6;
        --white: #ffffff;
        --gray-100: #f9fafb;
        --gray-200: #f3f4f6;
        --gray-300: #e5e7eb;
        --gray-400: #d1d5db;
        --gray-500: #9ca3af;
        --gray-600: #6b7280;
        --gray-700: #4b5563;
        --gray-800: #374151;
        --gray-900: #1f2937;
        
        /* Variables pour les états de logs */
        --blue-subtle: #dbeafe;
        --red-subtle: #fee2e2;
        --orange-subtle: #fef3c7;
        --green-subtle: #d1fae5;
        --purple-subtle: #ede9fe;
    }

    /* Styles globaux améliorés */
    .container-fluid {
        padding: 1.5rem 2rem;
    }
    
    /* Cartes avec ombres et animations */
    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Carte d'en-tête avec gradient modernisé */
    .header-card {
        background: linear-gradient(135deg, var(--primary), var(--info));
        color: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .header-card::before {
        content: '';
        position: absolute;
        top: -10%;
        right: -10%;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        z-index: 0;
    }
    
    .header-card h1, .header-card p {
        position: relative;
        z-index: 1;
    }
    
    /* Cartes de statistiques avec hover effects */
    .stats-card {
        border-radius: 0.75rem;
        padding: 1.25rem;
        height: 100%;
        border: none;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .stats-blue { 
        background: linear-gradient(to right, var(--info-light), var(--primary-light));
        border-left: 4px solid var(--primary);
    }
    
    .stats-red { 
        background: linear-gradient(to right, var(--danger-light), #fee2e2);
        border-left: 4px solid var(--danger);
    }
    
    .stats-orange { 
        background: linear-gradient(to right, var(--warning-light), #fff7ed);
        border-left: 4px solid var(--warning);
    }
    
    .stats-green { 
        background: linear-gradient(to right, var(--success-light), #ecfdf5);
        border-left: 4px solid var(--success);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .stats-card h4 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: var(--gray-800);
    }
    
    .stats-card .text-muted {
        font-weight: 500;
        color: var(--gray-600) !important;
    }
    
    /* Icônes de statistiques avec animation */
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }
    
    .stats-icon i {
        transition: transform 0.3s ease-in-out;
    }
    
    .stats-card:hover .stats-icon i {
        transform: scale(1.2) rotate(5deg);
    }
    
    .icon-blue { background-color: var(--primary-light); color: var(--primary); }
    .icon-red { background-color: var(--danger-light); color: var(--danger); }
    .icon-orange { background-color: var(--warning-light); color: var(--warning); }
    .icon-green { background-color: var(--success-light); color: var(--success); }
    
    /* Cartes de log redessinées */
    .log-card {
        border-radius: 0.75rem;
        border: none;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    
    .log-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .log-security { border-left: 5px solid var(--danger); }
    .log-data { border-left: 5px solid var(--warning); }
    .log-user { border-left: 5px solid var(--success); }
    .log-system { border-left: 5px solid var(--info); }
    
    /* Header de carte de log amélioré */
    .log-card .card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    /* Badges de log rédesignés */
    .log-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 50rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-security { background-color: var(--danger-light); color: var(--danger); }
    .badge-data { background-color: var(--warning-light); color: var(--warning); }
    .badge-user { background-color: var(--success-light); color: var(--success); }
    .badge-system { background-color: var(--info-light); color: var(--info); }
    
    /* Panneau de détails de log amélioré */
    .log-details {
        background-color: var(--gray-100);
        padding: 1rem;
        border-radius: 0.5rem;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: 0.85rem;
        border: 1px solid var(--gray-200);
        margin-top: 0.75rem;
        max-height: 300px;
        overflow-y: auto;
        position: relative;
    }
    
    .log-details div {
        padding: 0.25rem 0;
        border-bottom: 1px dashed var(--gray-200);
    }
    
    .log-details div:last-child {
        border-bottom: none;
    }
    
    /* Boutons d'action stylisés */
    .action-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        background-color: var(--gray-100);
        color: var(--gray-600);
        margin-left: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        background-color: var(--primary);
        color: white;
        transform: translateY(-2px);
    }
    
    /* Toast notifications stylisées */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .toast {
        padding: 12px 20px;
        margin-bottom: 10px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: slideIn 0.3s ease-in-out, fadeOut 0.5s ease-in-out 2.5s forwards;
    }
    
    .toast-success {
        background-color: var(--success);
        color: white;
    }
    
    .toast-success i {
        color: var(--success);
        margin-right: 0.75rem;
    }
    
    /* Personnalisation des filtres */
    .filters-card {
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .filters-card .card-body {
        padding: 1.5rem;
    }
    
    .form-label {
        font-weight: 500;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }
    
    .form-select, .form-control {
        border-radius: 0.5rem;
        border: 1px solid var(--gray-300);
        padding: 0.5rem 0.75rem;
        transition: all 0.15s ease;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }
    
    /* Boutons améliorés */
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-2px);
    }
    
    .btn-light {
        background-color: var(--light);
        border-color: var(--gray-300);
        color: var(--gray-700);
    }
    
    .btn-light:hover {
        background-color: var(--gray-200);
        color: var(--gray-800);
    }
    
    /* Pagination stylisée */
    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }
    
    .page-item:not(:first-child) .page-link {
        margin-left: 0.25rem;
    }
    
    .page-link {
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        border: none;
        color: var(--gray-700);
        background-color: var(--light);
        transition: all 0.2s ease;
    }
    
    .page-link:hover {
        background-color: var(--primary-light);
        color: var(--primary);
        transform: translateY(-2px);
    }
    
    .page-item.active .page-link {
        background-color: var(--primary);
        color: white;
    }
    
    /* Styles pour la vue en liste des logs */
    .logs-container {
        transition: all 0.3s ease;
    }
    
    .logs-container.list-view .log-card {
        border-radius: 0.25rem;
        margin-bottom: 0.5rem !important;
    }
    
    .logs-container.list-view .log-card .card-header {
        padding: 0.5rem 1rem;
    }
    
    .logs-container.list-view .log-card .card-body {
        display: none;
    }
    
    .logs-container.list-view .log-card.expanded .card-body {
        display: block;
    }
    
    .logs-container.list-view .log-card:hover {
        transform: translateX(5px);
        border-left: 3px solid var(--primary);
    }
    
    /* Effet de transition et d'apparition */
    .animate__animated {
        animation-duration: 0.5s;
    }
    
    .animate__fadeIn {
        animation-name: fadeIn;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Optimisations pour mobiles et tablettes */
    @media (max-width: 767.98px) {
        .header-card h1 {
            font-size: 1.5rem;
        }
        
        .stats-card {
            padding: 0.75rem;
        }
        
        .log-card .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .log-card .card-header .d-flex:last-child {
            margin-top: 0.75rem;
            align-self: flex-end;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec titre et actions -->
    <div class="card header-card">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="h3 mb-1 fw-bold"><i class="fas fa-history me-2"></i> Journal d'activité</h1>
                <p class="mb-0 opacity-75">Suivi des actions réalisées sur la plateforme</p>
            </div>
            <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex justify-content-lg-end gap-2">
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="fas fa-file-export me-1"></i> Exporter
                    </button>
                    <button id="clearFiltersBtn" class="btn btn-light">
                        <i class="fas fa-filter me-1"></i> Réinitialiser
                    </button>
                    <button type="button" class="btn btn-primary d-flex align-items-center" id="toggleFiltersBtn">
                        <i class="fas fa-sliders-h me-md-2"></i> <span class="d-none d-md-inline">Filtres</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card stats-blue shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="stats-icon icon-blue">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <h4 class="h4 mb-0 fw-bold">{{ $stats['today'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Aujourd'hui</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">{{ now()->format('d/m/Y') }}</small>
                    <a href="/admin/activity-logs-direct?date_range=today" class="btn btn-sm btn-link p-0 text-primary">Voir <i class="fas fa-chevron-right fs-xs"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card stats-red shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="stats-icon icon-red">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h4 class="h4 mb-0 fw-bold">{{ $stats['security'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Sécurité</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="fas fa-lock me-1"></i> Connexions et accès</small>
                    <a href="/admin/activity-logs-direct?type=security" class="btn btn-sm btn-link p-0 text-danger">Voir <i class="fas fa-chevron-right fs-xs"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card stats-orange shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="stats-icon icon-orange">
                        <i class="fas fa-database"></i>
                    </div>
                    <div>
                        <h4 class="h4 mb-0 fw-bold">{{ $stats['data'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Données</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="fas fa-table me-1"></i> Modifications</small>
                    <a href="/admin/activity-logs-direct?type=data" class="btn btn-sm btn-link p-0 text-warning">Voir <i class="fas fa-chevron-right fs-xs"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card stats-green shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="stats-icon icon-green">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h4 class="h4 mb-0 fw-bold">{{ $stats['user'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Utilisateurs</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="fas fa-users me-1"></i> Actions utilisateurs</small>
                    <a href="/admin/activity-logs-direct?type=user" class="btn btn-sm btn-link p-0 text-success">Voir <i class="fas fa-chevron-right fs-xs"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="card mb-4 filter-card" id="filterSection">
        <div class="card-header bg-light d-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i> Filtres avancés</h5>
            <button type="button" class="btn btn-sm btn-link text-muted" id="collapseFilters" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body pb-3">
                <form action="/admin/activity-logs-direct" method="GET" class="row g-3" id="filterForm">
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <label for="user_id" class="form-label"><i class="fas fa-user text-primary me-1"></i> Utilisateur</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">Tous les utilisateurs</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (is_array(request('user_id')) ? in_array($user->id, request('user_id')) : request('user_id') == $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <label for="type" class="form-label"><i class="fas fa-tag text-danger me-1"></i> Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Tous les types</option>
                                <option value="security" {{ (is_array(request('type')) ? in_array('security', request('type')) : request('type') == 'security') ? 'selected' : '' }}>Sécurité</option>
                                <option value="data" {{ (is_array(request('type')) ? in_array('data', request('type')) : request('type') == 'data') ? 'selected' : '' }}>Données</option>
                                <option value="user" {{ (is_array(request('type')) ? in_array('user', request('type')) : request('type') == 'user') ? 'selected' : '' }}>Utilisateur</option>
                                <option value="system" {{ (is_array(request('type')) ? in_array('system', request('type')) : request('type') == 'system') ? 'selected' : '' }}>Système</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <label for="date_range" class="form-label"><i class="fas fa-calendar-alt text-warning me-1"></i> Période</label>
                            <select name="date_range" id="date_range" class="form-select">
                                <option value="">Toutes les périodes</option>
                                <option value="today" {{ (is_array(request('date_range')) ? in_array('today', request('date_range')) : request('date_range') == 'today') ? 'selected' : '' }}>Aujourd'hui</option>
                                <option value="yesterday" {{ (is_array(request('date_range')) ? in_array('yesterday', request('date_range')) : request('date_range') == 'yesterday') ? 'selected' : '' }}>Hier</option>
                                <option value="last_7_days" {{ (is_array(request('date_range')) ? in_array('last_7_days', request('date_range')) : request('date_range') == 'last_7_days') ? 'selected' : '' }}>7 derniers jours</option>
                                <option value="this_month" {{ (is_array(request('date_range')) ? in_array('this_month', request('date_range')) : request('date_range') == 'this_month') ? 'selected' : '' }}>Ce mois-ci</option>
                                <option value="custom" {{ (is_array(request('date_range')) ? in_array('custom', request('date_range')) : request('date_range') == 'custom') ? 'selected' : '' }}>Personnalisée</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <label for="action" class="form-label"><i class="fas fa-bolt text-success me-1"></i> Action</label>
                            <select name="action" id="action" class="form-select">
                                <option value="">Toutes les actions</option>
                                <option value="login" {{ (is_array(request('action')) ? in_array('login', request('action')) : request('action') == 'login') ? 'selected' : '' }}>Connexion</option>
                                <option value="logout" {{ (is_array(request('action')) ? in_array('logout', request('action')) : request('action') == 'logout') ? 'selected' : '' }}>Déconnexion</option>
                                <option value="create" {{ (is_array(request('action')) ? in_array('create', request('action')) : request('action') == 'create') ? 'selected' : '' }}>Création</option>
                                <option value="update" {{ (is_array(request('action')) ? in_array('update', request('action')) : request('action') == 'update') ? 'selected' : '' }}>Modification</option>
                                <option value="delete" {{ (is_array(request('action')) ? in_array('delete', request('action')) : request('action') == 'delete') ? 'selected' : '' }}>Suppression</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="customDateContainer" class="row g-3 mt-1 px-2 py-2 rounded {{ (is_array(request('date_range')) ? !in_array('custom', request('date_range')) : request('date_range') != 'custom') ? 'd-none' : '' }}" style="background-color: var(--bs-light)">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="start_date" class="form-label"><i class="fas fa-calendar-minus me-1"></i> Date de début</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ is_array(request('start_date')) ? '' : request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="end_date" class="form-label"><i class="fas fa-calendar-plus me-1"></i> Date de fin</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ is_array(request('end_date')) ? '' : request('end_date') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-3 d-flex justify-content-between">
                        <button type="button" id="resetFilterBtn" class="btn btn-light">
                            <i class="fas fa-undo me-1"></i> Réinitialiser
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Appliquer les filtres
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des logs -->
    <div class="card mb-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-list text-white"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">Journal d'activité</h5>
                    <p class="mb-0 small text-muted">{{ $logs->total() }} enregistrements trouvés</p>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sort me-1"></i> <span class="d-none d-md-inline">Trier par</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><h6 class="dropdown-header">Date</h6></li>
                        <li><a class="dropdown-item" href="/admin/activity-logs-direct?sort=created_at&direction=desc"><i class="fas fa-clock me-2 text-primary"></i> Plus récentes</a></li>
                        <li><a class="dropdown-item" href="/admin/activity-logs-direct?sort=created_at&direction=asc"><i class="fas fa-history me-2 text-primary"></i> Plus anciennes</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Autres critères</h6></li>
                        <li><a class="dropdown-item" href="/admin/activity-logs-direct?sort=user_id&direction=asc"><i class="fas fa-user me-2 text-success"></i> Par utilisateur</a></li>
                        <li><a class="dropdown-item" href="/admin/activity-logs-direct?sort=action&direction=asc"><i class="fas fa-bolt me-2 text-warning"></i> Par action</a></li>
                        <li><a class="dropdown-item" href="/admin/activity-logs-direct?sort=type&direction=asc"><i class="fas fa-tag me-2 text-danger"></i> Par type</a></li>
                    </ul>
                </div>
                
                <button class="btn btn-outline-primary" id="toggleCardsView" title="Changer de vue">
                    <i class="fas fa-th-list"></i>
                </button>
            </div>
        </div>
    </div>
    
    <div class="logs-container" id="logsContainer">

    @if($logs->count() > 0)
        @foreach($logs as $log)
            @php
                $logType = $log->type ?? 'system';
                $typeIcons = [
                    'security' => 'shield-alt',
                    'data' => 'database',
                    'user' => 'user-edit',
                    'system' => 'cogs'
                ];
                $typeColors = [
                    'security' => 'danger',
                    'data' => 'warning',
                    'user' => 'success',
                    'system' => 'primary'
                ];
                $actionIcons = [
                    'login' => 'sign-in-alt',
                    'logout' => 'sign-out-alt',
                    'create' => 'plus-circle',
                    'update' => 'edit',
                    'delete' => 'trash-alt',
                    'view' => 'eye'
                ];
                $icon = $typeIcons[$logType] ?? 'info-circle';
                $color = $typeColors[$logType] ?? 'primary';
                $actionIcon = $actionIcons[$log->action] ?? 'bolt';
                $timeAgo = \Carbon\Carbon::parse($log->created_at)->diffForHumans();
            @endphp
            
            <div class="card log-card shadow-sm mb-3 border-0 animate__animated animate__fadeIn">
                <div class="card-header bg-white d-flex justify-content-between align-items-center p-3 border-bottom border-light">
                    <div class="d-flex align-items-center">
                        <div class="log-icon-container me-3 rounded-circle bg-{{ $color }}-subtle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="fas fa-{{ $icon }} text-{{ $color }}" style="font-size: 1.1rem;"></i>
                        </div>
                        
                        <div>
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                <span class="log-badge badge-{{ $logType }}">
                                    <i class="fas fa-{{ $actionIcon }} me-1"></i> {{ ucfirst($log->action) }}
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="far fa-clock me-1"></i> {{ $timeAgo }}
                                </span>
                            </div>
                            
                            <div>
                                @if($log->user)
                                    <span class="fw-medium">{{ $log->user->prenom }} {{ $log->user->nom }}</span>
                                    <span class="badge bg-secondary ms-1">{{ $log->user->role }}</span>
                                @else
                                    <span class="fw-medium">Système</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#details-{{ $log->id }}" title="Détails">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        
                        <button class="btn btn-sm btn-light" onclick="copyToClipboard('{{ $log->id }}')" title="Copier l'ID">
                            <i class="fas fa-copy"></i>
                        </button>
                        
                        @if(auth()->user()->role === 'ADMIN')
                            <form action="/admin/activity-logs/{{ $log->id }}/delete" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cette entrée ?')" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    <p class="mb-2">{{ $log->description }}</p>
                    
                    <div class="collapse" id="details-{{ $log->id }}">
                        <div class="log-details mt-3 p-3 rounded bg-light">
                            <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i> Détails de l'activité</h6>
                            
                            <div class="row mb-2">
                                <div class="col-md-4 fw-bold">Date et heure</div>
                                <div class="col-md-8">{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-md-4 fw-bold">Adresse IP</div>
                                <div class="col-md-8">{{ $log->ip_address ?? 'Non disponible' }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-md-4 fw-bold">Navigateur</div>
                                <div class="col-md-8">{{ $log->user_agent ?? 'Non disponible' }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-md-4 fw-bold">ID</div>
                                <div class="col-md-8">{{ $log->id }}</div>
                            </div>
                            
                            @if ($log->properties)
                                <div class="mt-3">
                                    <h6 class="fw-bold">Propriétés :</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Propriété</th>
                                                    <th>Valeur</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $properties = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                                                @endphp
                                                @if (is_array($properties))
                                                    @foreach ($properties as $key => $value)
                                                        <tr>
                                                            <td class="fw-medium">{{ $key }}</td>
                                                            <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="2">{{ $log->properties }}</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i> Aucune donnée supplémentaire disponible
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        
        @if($logs->hasPages())
        <div class="card shadow-sm mt-3">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">
                            Affichage de <span class="fw-bold">{{ $logs->firstItem() ?? 0 }}</span> à <span class="fw-bold">{{ $logs->lastItem() ?? 0 }}</span> sur <span class="fw-bold">{{ $logs->total() }}</span> résultats
                        </p>
                    </div>
                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    @else
        <div class="alert alert-info shadow-sm">
            <i class="fas fa-info-circle me-2"></i> Aucune activité trouvée selon les critères de recherche.
        </div>
    @endif
    </div><!-- Fin de logs-container -->
</div><!-- Fin du container-fluid -->

<!-- Conteneur pour les notifications toast -->
<div class="toast-container"></div>

<!-- Modal d'exportation -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Exporter les journaux d'activité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm" action="/admin/activity-logs/export" method="POST">
                    @csrf
                    <input type="hidden" name="export_filters" id="exportFilters" value="">
                    
                    <p class="text-muted mb-3">Choisissez les options d'exportation ci-dessous :</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Format d'exportation</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="formatCsv" value="csv" checked>
                                <label class="form-check-label" for="formatCsv">CSV</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="formatJson" value="json">
                                <label class="form-check-label" for="formatJson">JSON</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" id="formatPdf" value="pdf">
                                <label class="form-check-label" for="formatPdf">PDF</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Étendue de l'exportation</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="export_scope" id="scopeCurrent" value="current" checked>
                            <label class="form-check-label" for="scopeCurrent">
                                Exporter uniquement les entrées filtrées actuellement
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="export_scope" id="scopeAll" value="all">
                            <label class="form-check-label" for="scopeAll">
                                Exporter toutes les entrées
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Options supplémentaires</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_details" id="includeDetails" value="1" checked>
                            <label class="form-check-label" for="includeDetails">
                                Inclure les détails techniques
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_user_info" id="includeUserInfo" value="1" checked>
                            <label class="form-check-label" for="includeUserInfo">
                                Inclure les informations utilisateur
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="startExport">
                    <i class="fas fa-download me-1"></i> Télécharger
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Affichage du champ de date personnalisée
        const dateRangeSelect = document.getElementById('date_range');
        const customDateContainer = document.getElementById('customDateContainer');
        
        if (dateRangeSelect) {
            dateRangeSelect.addEventListener('change', function() {
                customDateContainer.style.display = this.value === 'custom' ? 'block' : 'none';
            });
        }
        
        // Bouton d'effacement des filtres
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                window.location.href = '/admin/activity-logs';
            });
        }
        
        // Préparation de l'export
        const startExportBtn = document.getElementById('startExport');
        const exportForm = document.getElementById('exportForm');
        const exportFilters = document.getElementById('exportFilters');
        
        if (startExportBtn && exportForm && exportFilters) {
            startExportBtn.addEventListener('click', function() {
                // Récupérer les filtres actuels
                const currentFilters = {
                    user_id: '{{ is_array(request("user_id")) ? json_encode(request("user_id")) : request("user_id") }}',
                    type: '{{ is_array(request("type")) ? json_encode(request("type")) : request("type") }}',
                    action: '{{ is_array(request("action")) ? json_encode(request("action")) : request("action") }}',
                    date_range: '{{ is_array(request("date_range")) ? json_encode(request("date_range")) : request("date_range") }}',
                    date_start: '{{ is_array(request("date_start")) ? json_encode(request("date_start")) : request("date_start") }}',
                    date_end: '{{ is_array(request("date_end")) ? json_encode(request("date_end")) : request("date_end") }}',
                    search: '{{ is_array(request("search")) ? json_encode(request("search")) : request("search") }}',
                    sort: '{{ is_array(request("sort")) ? json_encode(request("sort")) : request("sort") }}',
                    direction: '{{ is_array(request("direction")) ? json_encode(request("direction")) : request("direction") }}'
                };
                
                // Les enregistrer dans le champ caché
                exportFilters.value = JSON.stringify(currentFilters);
                
                // Soumettre le formulaire
                exportForm.submit();
            });
        }
    });
    
    // Toggle des filtres avec le bouton dédié
    const toggleFiltersBtn = document.getElementById('toggleFiltersBtn');
    const filterSection = document.getElementById('filterSection');
    const filterCollapse = document.getElementById('filterCollapse');
    const collapseFilters = document.getElementById('collapseFilters');
    
    if (toggleFiltersBtn && filterSection) {
        // Cacher les filtres initialement si on n'a pas de filtres actifs
        const hasActiveFilters = false; // Valeur par défaut sans utiliser request()
        
        if (!hasActiveFilters) {
            const collapse = new bootstrap.Collapse(filterCollapse, {
                toggle: false
            });
            collapse.hide();
            collapseFilters.querySelector('i').classList.remove('fa-chevron-up');
            collapseFilters.querySelector('i').classList.add('fa-chevron-down');
        }
        
        toggleFiltersBtn.addEventListener('click', function() {
            const collapse = new bootstrap.Collapse(filterCollapse);
            if (filterCollapse.classList.contains('show')) {
                collapse.hide();
                collapseFilters.querySelector('i').classList.remove('fa-chevron-up');
                collapseFilters.querySelector('i').classList.add('fa-chevron-down');
            } else {
                collapse.show();
                collapseFilters.querySelector('i').classList.remove('fa-chevron-down');
                collapseFilters.querySelector('i').classList.add('fa-chevron-up');
            }
        });
    }
    
    // Toggle du style de vue des logs (cartes ou liste)
    const toggleCardsView = document.getElementById('toggleCardsView');
    const logsContainer = document.getElementById('logsContainer');
    
    if (toggleCardsView && logsContainer) {
        // Vérifier s'il y a une préférence utilisateur stockée
        const viewMode = localStorage.getItem('logsViewMode') || 'cards';
        
        // Appliquer le mode de vue initial
        if (viewMode === 'list') {
            logsContainer.classList.add('list-view');
            toggleCardsView.querySelector('i').classList.remove('fa-th-list');
            toggleCardsView.querySelector('i').classList.add('fa-th-large');
        }
        
        toggleCardsView.addEventListener('click', function() {
            logsContainer.classList.toggle('list-view');
            
            if (logsContainer.classList.contains('list-view')) {
                localStorage.setItem('logsViewMode', 'list');
                toggleCardsView.querySelector('i').classList.remove('fa-th-list');
                toggleCardsView.querySelector('i').classList.add('fa-th-large');
                showToast('Vue liste activée');
            } else {
                localStorage.setItem('logsViewMode', 'cards');
                toggleCardsView.querySelector('i').classList.remove('fa-th-large');
                toggleCardsView.querySelector('i').classList.add('fa-th-list');
                showToast('Vue cartes activée');
            }
        });
        
        // Gestion des clics sur les cartes en vue liste
        const logCards = document.querySelectorAll('.log-card');
        
        logCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Ne pas activer si on a cliqué sur un bouton ou un lien
                if (e.target.closest('button, a, .action-btn') && !e.target.closest('.card-header')) {
                    return;
                }
                
                // Comportement spécial en mode liste
                if (logsContainer.classList.contains('list-view')) {
                    // Toggle la classe expanded pour afficher/masquer le contenu
                    this.classList.toggle('expanded');
                    
                    // Si on a cliqué sur le header mais pas sur un bouton
                    if (e.target.closest('.card-header') && !e.target.closest('button, a, .action-btn')) {
                        // Chercher le bouton détails dans cette carte
                        const detailsBtn = this.querySelector('[data-bs-toggle="collapse"]');
                        if (detailsBtn) {
                            // Simuler un clic sur le bouton détails
                            detailsBtn.click();
                        }
                    }
                }
            });
        });
    }
    
    // Réinitialisation des filtres
    const resetFilterBtn = document.getElementById('resetFilterBtn');
    
    if (resetFilterBtn) {
        resetFilterBtn.addEventListener('click', function() {
            const filterForm = document.getElementById('filterForm');
            const formElements = filterForm.querySelectorAll('input, select');
            
            formElements.forEach(element => {
                if (element.type === 'checkbox' || element.type === 'radio') {
                    element.checked = false;
                } else {
                    element.value = '';
                }
            });
            
            filterForm.submit();
        });
    }
    
    // Fonction pour copier les détails d'un log dans le presse-papier
    function copyToClipboard(logId) {
        const logDetails = document.querySelector(`#details-${logId} .log-details`);
        if (!logDetails) return;
        
        const textArea = document.createElement('textarea');
        textArea.value = logDetails.textContent.trim();
        document.body.appendChild(textArea);
        textArea.select();
        
        try {
            document.execCommand('copy');
            showToast('Détails copiés dans le presse-papier');
        } catch (err) {
            console.error('Erreur lors de la copie:', err);
        }
        
        document.body.removeChild(textArea);
    }
    
    // Afficher un toast de notification
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast toast-success';
        toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        
        const container = document.querySelector('.toast-container');
        if (!container) {
            const newContainer = document.createElement('div');
            newContainer.className = 'toast-container';
            document.body.appendChild(newContainer);
            newContainer.appendChild(toast);
        } else {
            container.appendChild(toast);
        }
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>
@endsection
