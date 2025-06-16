@extends('layouts.admin')

@section('title', 'Paramètres de sécurité')

@section('styles')
<style>
    /* Design global */
    .security-page {
        background-color: #f9fbff;
        font-family: 'Nunito', sans-serif;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        padding: 25px 0;
        margin-bottom: 30px;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .dashboard-title {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .dashboard-subtitle {
        opacity: 0.9;
        font-weight: 300;
    }
    
    /* Cards */
    .security-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        overflow: hidden;
    }
    
    .security-card-header {
        padding: 15px 20px;
        background-color: rgba(0,0,0,0.02);
        border-bottom: 1px solid #eaeaea;
    }
    
    .security-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0;
    }
    
    .security-card-body {
        padding: 20px;
    }
    
    /* Statistiques */
    .stat-card {
        border-radius: 12px;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 25px;
        transition: all 0.3s;
        border-top: 4px solid transparent;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .stat-card-blue { border-color: #3498db; }
    .stat-card-red { border-color: #e74c3c; }
    .stat-card-orange { border-color: #f39c12; }
    .stat-card-green { border-color: #2ecc71; }
    
    .stat-value {
        font-size: 36px;
        font-weight: bold;
        line-height: 1;
        margin-bottom: 8px;
    }
    
    .stat-label {
        color: #95a5a6;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .icon-container {
        width: 60px;
        height: 60px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
    }
    
    .icon-blue {
        background-color: rgba(52, 152, 219, 0.15);
        color: #3498db;
    }
    
    .icon-red {
        background-color: rgba(231, 76, 60, 0.15);
        color: #e74c3c;
    }
    
    .icon-orange {
        background-color: rgba(243, 156, 18, 0.15);
        color: #f39c12;
    }
    
    .icon-green {
        background-color: rgba(46, 204, 113, 0.15);
        color: #2ecc71;
    }
    
    /* Tableaux */
    .security-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .security-table th {
        background-color: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
        padding: 12px 15px;
        border-bottom: 2px solid #eaeaea;
    }
    
    .security-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eaeaea;
        vertical-align: middle;
    }
    
    .security-table tr:hover {
        background-color: rgba(52, 152, 219, 0.05);
    }
    
    /* Formulaires */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #eaeaea;
        padding: 10px 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #2193b0;
        box-shadow: 0 0 0 0.2rem rgba(33, 147, 176, 0.25);
    }
    
    .form-label {
        color: #34495e;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 8px;
    }
    
    /* Boutons */
    .btn-cosem {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-cosem:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(33, 147, 176, 0.3);
    }
    
    .btn-outline-cosem {
        background: transparent;
        border: 2px solid #2193b0;
        color: #2193b0;
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-outline-cosem:hover {
        background-color: rgba(33, 147, 176, 0.1);
    }
    
    /* Badges */
    .badge-active {
        background-color: rgba(46, 204, 113, 0.15);
        color: #2ecc71;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 30px;
    }
    
    .badge-locked {
        background-color: rgba(231, 76, 60, 0.15);
        color: #e74c3c;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 30px;
    }
    
    .badge-inactive {
        background-color: rgba(149, 165, 166, 0.15);
        color: #95a5a6;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 30px;
    }
    
    /* Avatar */
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 16px;
    }
    
    /* Risk indicators */
    .risk-indicator {
        width: 100%;
        height: 6px;
        border-radius: 3px;
        background-color: #ecf0f1;
        margin-top: 5px;
        overflow: hidden;
    }
    
    .risk-level {
        height: 100%;
        border-radius: 3px;
    }
    
    .risk-low { 
        background-color: #2ecc71;
        width: 25%;
    }
    
    .risk-medium { 
        background-color: #f39c12;
        width: 50%;
    }
    
    .risk-high { 
        background-color: #e74c3c;
        width: 75%;
    }
    
    .risk-critical { 
        background-color: #c0392b;
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="security-page">
    <!-- Dashboard header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="dashboard-title">
                        <i class="fas fa-shield-alt me-3"></i> Paramètres de sécurité
                    </h1>
                    <p class="dashboard-subtitle">Gestion et surveillance de la sécurité système</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-light">
                        <i class="fas fa-history me-2"></i> Journal d'activité
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <!-- Statistiques de sécurité -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card stat-card-blue">
                    <div class="d-flex align-items-center">
                        <div class="icon-container icon-blue">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $securityStats['successful_login_count'] ?? 0 }}</div>
                            <div class="stat-label">Connexions</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card stat-card-red">
                    <div class="d-flex align-items-center">
                        <div class="icon-container icon-red">
                            <i class="fas fa-user-lock"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $securityStats['failed_login_count'] ?? 0 }}</div>
                            <div class="stat-label">Échecs</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card stat-card-orange">
                    <div class="d-flex align-items-center">
                        <div class="icon-container icon-orange">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $securityStats['account_lockouts'] ?? 0 }}</div>
                            <div class="stat-label">Verrouillages</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card stat-card-green">
                    <div class="d-flex align-items-center">
                        <div class="icon-container icon-green">
                            <i class="fas fa-key"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $securityStats['password_changes'] ?? 0 }}</div>
                            <div class="stat-label">MDP changés</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Paramètres de sécurité -->
            <div class="col-md-7">
                <div class="security-card">
                    <div class="security-card-header">
                        <h5 class="security-card-title"><i class="fas fa-cogs me-2"></i> Configuration de sécurité</h5>
                    </div>
                    <div class="security-card-body">
                        <form action="{{ route('admin.security.update') }}" method="POST">
                            @csrf
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password_min_length" class="form-label">Longueur min. mot de passe</label>
                                    <input type="number" class="form-control" id="password_min_length" name="password_min_length" 
                                        value="{{ $securitySettings['password_min_length'] ?? 8 }}" min="8" max="30">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password_expires_days" class="form-label">Expiration des mots de passe (jours)</label>
                                    <input type="number" class="form-control" id="password_expires_days" name="password_expires_days" 
                                        value="{{ $securitySettings['password_expires_days'] ?? 90 }}" min="0" max="365">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="max_login_attempts" class="form-label">Tentatives de connexion max.</label>
                                    <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" 
                                        value="{{ $securitySettings['max_login_attempts'] ?? 5 }}" min="3" max="10">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="session_timeout_minutes" class="form-label">Timeout de session (minutes)</label>
                                    <input type="number" class="form-control" id="session_timeout_minutes" name="session_timeout_minutes" 
                                        value="{{ $securitySettings['session_timeout_minutes'] ?? 30 }}" min="5" max="240">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="inactive_account_days" class="form-label">Inactivité compte (jours)</label>
                                    <input type="number" class="form-control" id="inactive_account_days" name="inactive_account_days" 
                                        value="{{ $securitySettings['inactive_account_days'] ?? 60 }}" min="0" max="365">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password_complexity" class="form-label">Complexité des mots de passe</label>
                                    <select class="form-select" id="password_complexity" name="password_complexity">
                                        <option value="low" {{ ($securitySettings['password_complexity'] ?? 'medium') == 'low' ? 'selected' : '' }}>Faible</option>
                                        <option value="medium" {{ ($securitySettings['password_complexity'] ?? 'medium') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                        <option value="high" {{ ($securitySettings['password_complexity'] ?? 'medium') == 'high' ? 'selected' : '' }}>Élevée</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="enable_two_factor" name="enable_two_factor" 
                                            {{ ($securitySettings['enable_two_factor'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_two_factor">Activer l'authentification à deux facteurs</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="require_captcha" name="require_captcha" 
                                            {{ ($securitySettings['require_captcha'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="require_captcha">Activer CAPTCHA pour les connexions</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="log_all_actions" name="log_all_actions" 
                                            {{ ($securitySettings['log_all_actions'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="log_all_actions">Journaliser toutes les actions</label>
                                    </div>
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-cosem">
                                        <i class="fas fa-save me-2"></i> Enregistrer les paramètres
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Comptes verrouillés -->
            <div class="col-md-5">
                <div class="security-card">
                    <div class="security-card-header">
                        <h5 class="security-card-title"><i class="fas fa-user-lock me-2"></i> Comptes verrouillés</h5>
                    </div>
                    <div class="security-card-body">
                        @if($lockedAccounts && $lockedAccounts->count() > 0)
                            <div class="table-responsive">
                                <table class="security-table">
                                    <thead>
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>Verrouillé le</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lockedAccounts as $account)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar me-2" style="background-color: #{{ substr(md5($account->id), 0, 6) }}">
                                                            {{ strtoupper(substr($account->prenom, 0, 1) . substr($account->nom, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div>{{ $account->prenom }} {{ $account->nom }}</div>
                                                            <small class="text-muted">{{ $account->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $account->locked_at ? Carbon\Carbon::parse($account->locked_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                                <td>
                                                    <form action="{{ route('admin.security.toggle-lock', $account->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-cosem">
                                                            <i class="fas fa-unlock me-1"></i> Déverrouiller
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> Aucun compte verrouillé actuellement
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Analyse des risques -->
                <div class="security-card mt-4">
                    <div class="security-card-header">
                        <h5 class="security-card-title"><i class="fas fa-exclamation-triangle me-2"></i> Analyse des risques</h5>
                    </div>
                    <div class="security-card-body">
                        @if($riskAnalysis && count($riskAnalysis) > 0)
                            @foreach(array_slice($riskAnalysis, 0, 3) as $risk)
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2" style="background-color: #{{ substr(md5($risk['user']->id), 0, 6) }}">
                                                {{ strtoupper(substr($risk['user']->prenom, 0, 1) . substr($risk['user']->nom, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $risk['user']->prenom }} {{ $risk['user']->nom }}</strong>
                                                <div class="text-muted small">{{ $risk['user']->email }}</div>
                                            </div>
                                        </div>
                                        <span class="badge {{ $risk['risk_level'] == 'high' ? 'badge-locked' : ($risk['risk_level'] == 'medium' ? 'badge-inactive' : 'badge-active') }}">
                                            {{ $risk['risk_level'] == 'high' ? 'Élevé' : ($risk['risk_level'] == 'medium' ? 'Moyen' : 'Faible') }}
                                        </span>
                                    </div>
                                    
                                    <div class="risk-indicator">
                                        <div class="risk-level {{ $risk['risk_level'] == 'high' ? 'risk-high' : ($risk['risk_level'] == 'medium' ? 'risk-medium' : 'risk-low') }}"></div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        @foreach($risk['risk_factors'] as $factor)
                                            <div class="small text-muted"><i class="fas fa-exclamation-circle me-1 {{ $risk['risk_level'] == 'high' ? 'text-danger' : ($risk['risk_level'] == 'medium' ? 'text-warning' : 'text-success') }}"></i> {{ $factor }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            
                            @if(count($riskAnalysis) > 3)
                                <div class="text-center">
                                    <a href="{{ route('admin.security.report') }}" class="btn btn-sm btn-outline-cosem">
                                        <i class="fas fa-chart-line me-1"></i> Voir le rapport complet
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> Aucun risque de sécurité détecté
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
