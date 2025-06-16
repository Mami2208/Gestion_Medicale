@extends('secretaire.layouts.app')

@section('title', 'Gestion des Notifications')

@section('styles')
<style>
    .notification-card {
        transition: all 0.2s ease;
        border-left: 4px solid #3490dc;
        margin-bottom: 15px;
    }
    
    .notification-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .notification-card.unread {
        background-color: rgba(52, 144, 220, 0.05);
    }
    
    .notification-card.high {
        border-left-color: #e3342f;
    }
    
    .notification-card.medium {
        border-left-color: #f6993f;
    }
    
    .notification-card.low {
        border-left-color: #38c172;
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .notification-status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 5px;
    }
    
    .unread .notification-status-dot {
        background-color: #3490dc;
    }
    
    .notification-type-badge {
        font-size: 0.7rem;
        text-transform: uppercase;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête avec titre et boutons d'action -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">
                    <i class="fas fa-bell text-primary me-2"></i> Gestion des Notifications
                </h4>
                <p class="text-muted mb-0">Gérez et envoyez des notifications aux patients et au personnel médical</p>
            </div>
            <div>
                <a href="{{ route('secretaire.notifications.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Nouvelle notification
                </a>
            </div>
        </div>
    </div>
    
    <!-- Statistiques et filtres -->
    <div class="row mb-4">
        <!-- Statistiques -->
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-bell text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted">Total</p>
                                    <h4 class="mb-0">{{ $total_notifications ?? $notifications->total() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-envelope-open text-warning"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted">Non lues</p>
                                    <h4 class="mb-0">{{ $unread_notifications ?? $notifications->where('read_at', null)->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-calendar-day text-danger"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted">Aujourd'hui</p>
                                    <h4 class="mb-0">{{ $today_notifications ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-paper-plane text-success"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted">Envoyées</p>
                                    <h4 class="mb-0">{{ $sent_notifications ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('secretaire.notifications.index') }}" method="GET" class="row g-2">
                        <div class="col-md-6">
                            <select name="type" class="form-select form-select-sm">
                                <option value="">Tous les types</option>
                                <option value="RENDEZ_VOUS" {{ request('type') == 'RENDEZ_VOUS' ? 'selected' : '' }}>Rendez-vous</option>
                                <option value="DOCUMENT" {{ request('type') == 'DOCUMENT' ? 'selected' : '' }}>Documents</option>
                                <option value="RESULTAT" {{ request('type') == 'RESULTAT' ? 'selected' : '' }}>Résultats</option>
                                <option value="INFO" {{ request('type') == 'INFO' ? 'selected' : '' }}>Information</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Tous les statuts</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Non lues</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lues</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Onglets des notifications -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-3" id="notificationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">
                        <i class="fas fa-stream me-1"></i> Toutes
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="outgoing-tab" data-bs-toggle="tab" href="#outgoing" role="tab" aria-controls="outgoing" aria-selected="false">
                        <i class="fas fa-paper-plane me-1"></i> Notifications envoyées
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="scheduled-tab" data-bs-toggle="tab" href="#scheduled" role="tab" aria-controls="scheduled" aria-selected="false">
                        <i class="fas fa-clock me-1"></i> Notifications planifiées
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="templates-tab" data-bs-toggle="tab" href="#templates" role="tab" aria-controls="templates" aria-selected="false">
                        <i class="fas fa-clipboard-list me-1"></i> Modèles
                    </a>
                </li>
            </ul>
            
            <div class="tab-content" id="notificationTabsContent">
                <!-- Toutes les notifications -->
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    @if($notifications->count() > 0)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="text-muted mb-0">{{ $notifications->total() }} notification(s) trouvée(s)</p>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2" id="markAllAsRead">
                                    <i class="fas fa-check-double me-1"></i> Tout marquer comme lu
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-sort me-1"></i> Trier
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="?sort=created_at&direction=desc">Plus récentes</a></li>
                                        <li><a class="dropdown-item" href="?sort=created_at&direction=asc">Plus anciennes</a></li>
                                        <li><a class="dropdown-item" href="?sort=read_at&direction=asc">Non lues d'abord</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        @foreach($notifications as $notification)
                            <div class="card notification-card {{ $notification->read_at ? '' : 'unread' }} mb-3">
                                <div class="card-body">
                                    <div class="notification-header">
                                        <div>
                                            <h5 class="mb-0">
                                                @if(!$notification->read_at)
                                                    <span class="notification-status-dot"></span>
                                                @endif
                                                {{ $notification->data['title'] ?? ($notification->data['titre'] ?? 'Notification') }}
                                            </h5>
                                            <span class="badge bg-{{ $notification->read_at ? 'secondary' : 'primary' }} notification-type-badge">
                                                {{ $notification->data['type'] ?? 'INFO' }}
                                            </span>
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-calendar-alt me-1"></i> {{ $notification->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <p class="mb-2">{{ $notification->data['message'] ?? 'Aucun message' }}</p>
                                    <div class="d-flex justify-content-end">
                                        @if(!$notification->read_at)
                                            <form action="{{ route('secretaire.notifications.marquer-comme-lu', $notification->id) }}" method="POST" class="d-inline me-2">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-check me-1"></i> Marquer comme lu
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('secretaire.notifications.delete', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                                                <i class="fas fa-trash me-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-bell-slash me-2"></i> Aucune notification trouvée.
                        </div>
                    @endif
                </div>
                
                <!-- Notifications envoyées -->
                <div class="tab-pane fade" id="outgoing" role="tabpanel" aria-labelledby="outgoing-tab">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> L'historique des notifications envoyées sera disponible ici.
                    </div>
                </div>
                
                <!-- Notifications planifiées -->
                <div class="tab-pane fade" id="scheduled" role="tabpanel" aria-labelledby="scheduled-tab">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Les notifications planifiées apparaîtront ici.
                    </div>
                </div>
                
                <!-- Modèles de notifications -->
                <div class="tab-pane fade" id="templates" role="tabpanel" aria-labelledby="templates-tab">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Les modèles de notifications seront disponibles ici.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Marquer toutes les notifications comme lues
        const markAllAsReadBtn = document.getElementById('markAllAsRead');
        if (markAllAsReadBtn) {
            markAllAsReadBtn.addEventListener('click', function() {
                if (confirm('Êtes-vous sûr de vouloir marquer toutes les notifications comme lues ?')) {
                    // Rediriger vers la route appropriée
                    window.location.href = '{{ route("secretaire.notifications.marquer-tout-comme-lu") }}';
                }
            });
        }
    });
</script>
@endsection
