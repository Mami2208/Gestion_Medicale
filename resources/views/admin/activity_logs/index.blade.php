@extends('layouts.admin')

@section('title', 'Journal d\'activité')

@section('styles')
<style>
    /* Styles élégants et simplifiés */
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        border: none;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }
    
    .header-card {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .stats-card {
        border-top: 4px solid;
        padding: 15px;
        height: 100%;
    }
    
    .stats-blue { border-top-color: #3498db; }
    .stats-red { border-top-color: #e74c3c; }
    .stats-orange { border-top-color: #f39c12; }
    .stats-green { border-top-color: #2ecc71; }
    
    .stats-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 18px;
    }
    
    .icon-blue { background-color: rgba(52, 152, 219, 0.15); color: #3498db; }
    .icon-red { background-color: rgba(231, 76, 60, 0.15); color: #e74c3c; }
    .icon-orange { background-color: rgba(243, 156, 18, 0.15); color: #f39c12; }
    .icon-green { background-color: rgba(46, 204, 113, 0.15); color: #2ecc71; }
    
    .log-card {
        border-left: 5px solid #3498db;
    }
    
    .log-security { border-left-color: #e74c3c; }
    .log-data { border-left-color: #f39c12; }
    .log-user { border-left-color: #2ecc71; }
    .log-system { border-left-color: #3498db; }
    
    .log-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 20px;
        text-transform: uppercase;
    }
    
    .badge-security { background-color: rgba(231, 76, 60, 0.15); color: #e74c3c; }
    .badge-data { background-color: rgba(243, 156, 18, 0.15); color: #f39c12; }
    .badge-user { background-color: rgba(46, 204, 113, 0.15); color: #2ecc71; }
    .badge-system { background-color: rgba(52, 152, 219, 0.15); color: #3498db; }
    
    .log-details {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 13px;
        border: 1px solid #eaeaea;
        margin-top: 10px;
    }
    
    .action-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background-color: #f8f9fa;
        color: #6c757d;
        margin-left: 5px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        background-color: #3498db;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec titre et actions -->
    <div class="card header-card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 mb-1"><i class="fas fa-history me-2"></i> Journal d'activité</h1>
                <p class="mb-0">Suivi des actions réalisées sur la plateforme</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button type="button" class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="fas fa-file-export me-1"></i> Exporter
                </button>
                <button id="clearFiltersBtn" class="btn btn-light">
                    <i class="fas fa-filter-circle-xmark me-1"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stats-card stats-blue">
                <div class="d-flex align-items-center">
                    <div class="stats-icon icon-blue">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <h4 class="h5 mb-0">{{ $stats['today'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">Aujourd'hui</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stats-card stats-red">
                <div class="d-flex align-items-center">
                    <div class="stats-icon icon-red">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h4 class="h5 mb-0">{{ $stats['security'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">Sécurité</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stats-card stats-orange">
                <div class="d-flex align-items-center">
                    <div class="stats-icon icon-orange">
                        <i class="fas fa-database"></i>
                    </div>
                    <div>
                        <h4 class="h5 mb-0">{{ $stats['data'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">Données</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stats-card stats-green">
                <div class="d-flex align-items-center">
                    <div class="stats-icon icon-green">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h4 class="h5 mb-0">{{ $stats['user'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">Utilisateurs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3"><i class="fas fa-filter me-2"></i> Filtrer les journaux</h5>
            <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="user_id" class="form-label">Utilisateur</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">Tous les utilisateurs</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->prenom }} {{ $user->nom }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="type" class="form-label">Type d'activité</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Tous les types</option>
                        <option value="security" {{ request('type') == 'security' ? 'selected' : '' }}>Sécurité</option>
                        <option value="data" {{ request('type') == 'data' ? 'selected' : '' }}>Données</option>
                        <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>Système</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="action" class="form-label">Action</label>
                    <select class="form-select" id="action" name="action">
                        <option value="">Toutes les actions</option>
                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Création</option>
                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Modification</option>
                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Suppression</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Connexion</option>
                        <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Déconnexion</option>
                        <option value="view" {{ request('action') == 'view' ? 'selected' : '' }}>Consultation</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="date_range" class="form-label">Période</label>
                    <select class="form-select" id="date_range" name="date_range">
                        <option value="">Toute la période</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Hier</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Ce mois-ci</option>
                        <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Personnalisée</option>
                    </select>
                </div>
                
                <div class="col-md-4" id="customDateContainer" style="{{ request('date_range') == 'custom' ? '' : 'display: none;' }}">
                    <label for="date_start" class="form-label">Période personnalisée</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="date_start" name="date_start" value="{{ request('date_start') }}">
                        <span class="input-group-text">à</span>
                        <input type="date" class="form-control" id="date_end" name="date_end" value="{{ request('date_end') }}">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                
                <div class="col-md-12 mt-3 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Appliquer les filtres
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des logs -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i> Résultats ({{ $logs->total() }})</h5>
        
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-sort me-1"></i> Trier par
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="?{{ http_build_query(array_merge(request()->except(['sort', 'direction']), ['sort' => 'created_at', 'direction' => 'desc'])) }}">Plus récentes</a></li>
                <li><a class="dropdown-item" href="?{{ http_build_query(array_merge(request()->except(['sort', 'direction']), ['sort' => 'created_at', 'direction' => 'asc'])) }}">Plus anciennes</a></li>
                <li><a class="dropdown-item" href="?{{ http_build_query(array_merge(request()->except(['sort', 'direction']), ['sort' => 'user_id', 'direction' => 'asc'])) }}">Par utilisateur</a></li>
                <li><a class="dropdown-item" href="?{{ http_build_query(array_merge(request()->except(['sort', 'direction']), ['sort' => 'action', 'direction' => 'asc'])) }}">Par action</a></li>
            </ul>
        </div>
    </div>

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
                $icon = $typeIcons[$logType] ?? 'info-circle';
            @endphp
            
            <div class="card log-card log-{{ $logType }}">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-{{ $icon }} me-3" style="font-size: 1.2rem; color: #{{ $logType == 'security' ? 'e74c3c' : ($logType == 'data' ? 'f39c12' : ($logType == 'user' ? '2ecc71' : '3498db')) }};"></i>
                        
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <span class="log-badge badge-{{ $logType }} me-2">{{ $log->action }}</span>
                                <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                            </div>
                            
                            <div class="small">
                                @if($log->user)
                                    <strong>{{ $log->user->prenom }} {{ $log->user->nom }}</strong>
                                    <span class="text-muted">({{ $log->user->role }})</span>
                                @else
                                    <strong>Système</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="action-btn" data-bs-toggle="collapse" data-bs-target="#details-{{ $log->id }}" title="Détails">
                            <i class="fas fa-info"></i>
                        </div>
                        
                        <div class="action-btn" onclick="copyToClipboard('{{ $log->id }}')" title="Copier">
                            <i class="fas fa-copy"></i>
                        </div>
                        
                        @if(auth()->user()->role === 'ADMIN')
                            <form action="{{ route('admin.activity-logs.delete', $log->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn border-0 bg-transparent" onclick="return confirm('Voulez-vous vraiment supprimer cette entrée ?')" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    <p class="mb-2">{{ $log->description }}</p>
                    
                    <div class="collapse" id="details-{{ $log->id }}">
                        <div class="log-details">
                            @if ($log->properties)
                                @php
                                    $properties = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                                @endphp
                                @if (is_array($properties))
                                    @foreach ($properties as $key => $value)
                                        <div><strong>{{ $key }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</div>
                                    @endforeach
                                @else
                                    {{ $log->properties }}
                                @endif
                            @else
                                <em>Aucune donnée supplémentaire disponible</em>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $logs->appends(request()->except('page'))->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Aucune activité trouvée selon les critères de recherche.
        </div>
    @endif
</div>

<!-- Modal d'exportation -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Exporter les journaux d'activité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm" action="{{ route('admin.activity-logs.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="export_filters" id="exportFilters" value="">
                    
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
                    <i class="fas fa-file-export me-1"></i> Exporter
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'affichage des dates personnalisées
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
                window.location.href = '{{ route("admin.activity-logs.index") }}';
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
                    user_id: '{{ request("user_id") }}',
                    type: '{{ request("type") }}',
                    action: '{{ request("action") }}',
                    date_range: '{{ request("date_range") }}',
                    date_start: '{{ request("date_start") }}',
                    date_end: '{{ request("date_end") }}',
                    search: '{{ request("search") }}',
                    sort: '{{ request("sort") }}',
                    direction: '{{ request("direction") }}'
                };
                
                // Les enregistrer dans le champ caché
                exportFilters.value = JSON.stringify(currentFilters);
                
                // Soumettre le formulaire
                exportForm.submit();
            });
        }
    });
    
    // Fonction pour copier les détails d'un log dans le presse-papier
    function copyToClipboard(logId) {
        const detailsElement = document.querySelector(`#details-${logId} .log-details`);
        if (detailsElement) {
            navigator.clipboard.writeText(detailsElement.textContent)
                .then(() => {
                    alert('Détails copiés dans le presse-papier');
                })
                .catch(err => {
                    console.error('Erreur lors de la copie: ', err);
                    alert('Impossible de copier les détails');
                });
        }
    }
</script>
@endsection
