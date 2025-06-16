@extends('layouts.app')

@section('title', 'Mes notifications')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Mes notifications</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ auth()->user()->role == 'ADMIN' ? route('admin.dashboard') : (auth()->user()->role == 'MEDECIN' ? route('medecin.dashboard') : (auth()->user()->role == 'SECRETAIRE' ? route('secretaire.dashboard') : (auth()->user()->role == 'INFIRMIER' ? route('infirmier.dashboard') : route('patient.dashboard')))) }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Notifications</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Toutes mes notifications</h5>
                    <div>
                        @if($notifications->count() > 0)
                            <form action="{{ route('notifications.read.all') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-check-double me-1"></i>Tout marquer comme lu
                                </button>
                            </form>
                            <form action="{{ route('notifications.delete.all') }}" method="POST" class="d-inline ms-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer toutes vos notifications ?')">
                                    <i class="fas fa-trash-alt me-1"></i>Tout supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if($notifications->count() > 0)
                        <div class="list-group notification-list">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action notification-item {{ $notification->lu ? 'read' : 'unread' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="notification-icon {{ $notification->type == 'danger' ? 'bg-danger' : ($notification->type == 'warning' ? 'bg-warning' : ($notification->type == 'success' ? 'bg-success' : 'bg-info')) }} text-white">
                                                <i class="fas {{ $notification->icone ?? ($notification->type == 'danger' ? 'fa-exclamation-circle' : ($notification->type == 'warning' ? 'fa-exclamation-triangle' : ($notification->type == 'success' ? 'fa-check-circle' : 'fa-info-circle'))) }}"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-1">{{ $notification->titre }}</h5>
                                                <p class="mb-1">{{ $notification->message }}</p>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="notification-actions">
                                            @if(!$notification->lu)
                                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-check me-1"></i>Marquer comme lu
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($notification->url)
                                                <a href="{{ $notification->url }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>Voir
                                                </a>
                                            @endif
                                            
                                            <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-bell-slash fa-4x text-muted"></i>
                            </div>
                            <h4>Aucune notification</h4>
                            <p class="text-muted">Vous n'avez pas de notifications pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-list .notification-item {
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}

.notification-list .notification-item.unread {
    border-left-color: #4e73df;
    background-color: rgba(78, 115, 223, 0.05);
}

.notification-list .notification-item .notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-list .notification-item:hover {
    background-color: #f8f9fc;
    transform: translateY(-2px);
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
}
</style>
@endsection
