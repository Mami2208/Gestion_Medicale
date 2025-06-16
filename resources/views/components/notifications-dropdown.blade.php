@php
    $user = auth()->user();
    $unreadCount = $user->unreadNotificationsCount();
    $notifications = $user->roleNotifications()->orderBy('created_at', 'desc')->limit(5)->get();
@endphp

<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        @if($unreadCount > 0)
            <span class="badge bg-danger badge-counter">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown" style="min-width: 350px;">
        <h6 class="dropdown-header">
            Centre de notifications
        </h6>
        
        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
                <a class="dropdown-item d-flex align-items-center {{ $notification->lu ? '' : 'bg-light' }}" href="{{ $notification->url ?? route('notifications.index') }}">
                    <div class="me-3">
                        <div class="icon-circle {{ $notification->type == 'danger' ? 'bg-danger' : ($notification->type == 'warning' ? 'bg-warning' : ($notification->type == 'success' ? 'bg-success' : 'bg-info')) }}">
                            <i class="fas {{ $notification->icone ?? ($notification->type == 'danger' ? 'fa-exclamation-circle' : ($notification->type == 'warning' ? 'fa-exclamation-triangle' : ($notification->type == 'success' ? 'fa-check-circle' : 'fa-info-circle'))) }} text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                        <span class="{{ $notification->lu ? '' : 'fw-bold' }}">{{ Str::limit($notification->titre, 35) }}</span>
                    </div>
                </a>
            @endforeach
            
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.index') }}">Voir toutes les notifications</a>
        @else
            <div class="dropdown-item text-center">
                <span class="text-muted">Aucune notification</span>
            </div>
        @endif
    </div>
</li>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.badge-counter {
    position: absolute;
    transform: scale(0.7);
    transform-origin: top right;
    right: 0.25rem;
    margin-top: -0.25rem;
}
</style>
