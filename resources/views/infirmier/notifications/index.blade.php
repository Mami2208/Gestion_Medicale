@extends('layouts.infirmier')

@section('title', 'Notifications')

@section('styles')
<style>
    .notification-card {
        transition: all 0.2s ease;
        border-left: 4px solid #28a745;
        margin-bottom: 12px;
    }
    
    .notification-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .notification-card.unread {
        background-color: rgba(40, 167, 69, 0.05);
    }
    
    .notification-card.high {
        border-left-color: #dc3545;
    }
    
    .notification-card.medium {
        border-left-color: #ffc107;
    }
    
    .notification-card.low {
        border-left-color: #17a2b8;
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .notification-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 8px;
    }
    
    .notification-dot.high {
        background-color: #dc3545;
    }
    
    .notification-dot.medium {
        background-color: #ffc107;
    }
    
    .notification-dot.low {
        background-color: #17a2b8;
    }
    
    .notification-dot.normal {
        background-color: #28a745;
    }
    
    .notification-timestamp {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .notification-actions {
        margin-top: 10px;
        display: flex;
        justify-content: flex-end;
    }
    
    .notification-actions .btn {
        margin-left: 5px;
    }
    
    .nav-pills .nav-link.active {
        background-color: #28a745;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">
            <i class="fas fa-bell me-2 text-success"></i>
            Notifications
        </h1>
        <p class="text-muted">Consultez vos notifications et messages</p>
    </div>
    <div>
        <div class="input-group">
            <input type="text" class="form-control" id="searchNotification" placeholder="Rechercher...">
            <button class="btn btn-success" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</div>

<!-- Statistiques des notifications -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                        <i class="fas fa-bell text-danger"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Non lues</p>
                        <h4 class="mb-0">{{ isset($notifications_non_lues) ? (is_object($notifications_non_lues) ? $notifications_non_lues->count() : count($notifications_non_lues)) : 0 }}</h4>
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
                        <i class="fas fa-calendar-day text-success"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Aujourd'hui</p>
                        <h4 class="mb-0">{{ isset($notifications_aujourdhui) ? (is_object($notifications_aujourdhui) ? $notifications_aujourdhui->count() : count($notifications_aujourdhui)) : 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-clipboard-list text-info"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Rendez-vous</p>
                        <h4 class="mb-0">{{ isset($notifications_rdv) ? (is_object($notifications_rdv) ? $notifications_rdv->count() : count($notifications_rdv)) : 0 }}</h4>
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
                        <i class="fas fa-exclamation-circle text-warning"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-muted">Urgentes</p>
                        <h4 class="mb-0">{{ isset($notifications_urgentes) ? (is_object($notifications_urgentes) ? $notifications_urgentes->count() : count($notifications_urgentes)) : 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions et filtres -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-md-auto mb-2 mb-md-0">
                        <button class="btn btn-sm btn-success me-2" id="markAllRead">
                            <i class="fas fa-check-double me-1"></i>Tout marquer comme lu
                        </button>
                        <button class="btn btn-sm btn-outline-danger" id="clearAll">
                            <i class="fas fa-trash me-1"></i>Effacer tout
                        </button>
                    </div>
                    <div class="col-md-auto ms-auto">
                        <div class="d-flex align-items-center">
                            <label class="me-2 text-nowrap"><i class="fas fa-filter text-success me-1"></i>Filtrer:</label>
                            <select class="form-select form-select-sm me-2" id="filterType">
                                <option value="">Tous types</option>
                                <option value="message">Messages</option>
                                <option value="alerte">Alertes</option>
                                <option value="rdv">Rendez-vous</option>
                                <option value="systeme">Système</option>
                            </select>
                            <select class="form-select form-select-sm" id="filterImportance">
                                <option value="">Toutes priorités</option>
                                <option value="high">Haute</option>
                                <option value="medium">Moyenne</option>
                                <option value="low">Basse</option>
                                <option value="normal">Normale</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Onglets des notifications -->
<ul class="nav nav-pills mb-3" id="notificationsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
            Toutes <span class="badge bg-light text-dark ms-1">{{ isset($notifications) ? (is_object($notifications) ? $notifications->count() : count($notifications)) : 0 }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab">
            Non lues <span class="badge bg-danger text-white ms-1">{{ isset($notifications_non_lues) ? (is_object($notifications_non_lues) ? $notifications_non_lues->count() : count($notifications_non_lues)) : 0 }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="urgent-tab" data-bs-toggle="tab" data-bs-target="#urgent" type="button" role="tab">
            Urgentes <span class="badge bg-warning text-dark ms-1">{{ isset($notifications_urgentes) ? (is_object($notifications_urgentes) ? $notifications_urgentes->count() : count($notifications_urgentes)) : 0 }}</span>
        </button>
    </li>
</ul>

<!-- Contenu des onglets -->
<div class="tab-content" id="notificationsTabsContent">
    <!-- Toutes les notifications -->
    <div class="tab-pane fade show active" id="all" role="tabpanel">
        @if(isset($notifications) && (is_object($notifications) ? $notifications->count() : count($notifications)) > 0)
            <div class="notification-list">
                @foreach($notifications as $notification)
                    @php
                        $importance = $notification->importance ?? 'normal';
                        $isUnread = $notification->read_at === null;
                        $type = $notification->type ?? 'message';
                        $typeIcon = $type === 'message' ? 'envelope' : ($type === 'alerte' ? 'exclamation-triangle' : ($type === 'rdv' ? 'calendar-check' : 'cog'));
                    @endphp
                    <div class="card notification-card {{ $isUnread ? 'unread' : '' }} {{ $importance }} notification-item" 
                         data-type="{{ $type }}" 
                         data-importance="{{ $importance }}">
                        <div class="card-body">
                            <div class="notification-header">
                                <div>
                                    <span class="notification-dot {{ $importance }}"></span>
                                    <strong>
                                        <i class="fas fa-{{ $typeIcon }} me-1"></i>
                                        {{ $notification->title ?? 'Notification' }}
                                    </strong>
                                    @if($isUnread)
                                        <span class="badge bg-danger ms-2">Nouveau</span>
                                    @endif
                                </div>
                                <div class="notification-timestamp">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ isset($notification->created_at) ? $notification->created_at->diffForHumans() : 'Date inconnue' }}
                                </div>
                            </div>
                            
                            <p class="mb-1">{{ $notification->message ?? 'Pas de contenu' }}</p>
                            
                            @if($notification->source ?? false)
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Source: {{ $notification->source }}
                                </div>
                            @endif
                            
                            <div class="notification-actions">
                                @if($isUnread)
                                    <button class="btn btn-sm btn-outline-success mark-read-btn" data-id="{{ $notification->id ?? 0 }}">
                                        <i class="fas fa-check me-1"></i>Marquer comme lu
                                    </button>
                                @endif
                                
                                @if($type === 'alerte')
                                    <a href="{{ route('infirmier.alertes.show', $notification->reference_id ?? 0) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Voir l'alerte
                                    </a>
                                @elseif($type === 'rdv')
                                    <a href="{{ route('infirmier.rendez_vous.show', $notification->reference_id ?? 0) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-calendar me-1"></i>Voir le rendez-vous
                                    </a>
                                @elseif($type === 'message')
                                    <button class="btn btn-sm btn-outline-primary view-message-btn" data-id="{{ $notification->id ?? 0 }}">
                                        <i class="fas fa-envelope-open me-1"></i>Lire le message
                                    </button>
                                @endif
                                
                                <button class="btn btn-sm btn-outline-danger delete-notification-btn" data-id="{{ $notification->id ?? 0 }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-bell-slash text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="mb-3">Aucune notification</h4>
                    <p>Vous n'avez pas de notifications pour le moment.</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Notifications non lues -->
    <div class="tab-pane fade" id="unread" role="tabpanel">
        @if(isset($notifications_non_lues) && (is_object($notifications_non_lues) ? $notifications_non_lues->count() : count($notifications_non_lues)) > 0)
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Vous avez {{ (is_object($notifications_non_lues) ? $notifications_non_lues->count() : count($notifications_non_lues)) }} notification(s) non lue(s).
            </div>
            <!-- Contenu similaire à l'onglet "Toutes" mais avec $notifications_non_lues -->
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-check-double text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="mb-3">Tout est à jour</h4>
                    <p>Vous avez lu toutes vos notifications. Bon travail !</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Notifications urgentes -->
    <div class="tab-pane fade" id="urgent" role="tabpanel">
        @if(isset($notifications_urgentes) && (is_object($notifications_urgentes) ? $notifications_urgentes->count() : count($notifications_urgentes)) > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle me-2"></i>
                Vous avez {{ (is_object($notifications_urgentes) ? $notifications_urgentes->count() : count($notifications_urgentes)) }} notification(s) urgente(s).
            </div>
            <!-- Contenu similaire à l'onglet "Toutes" mais avec $notifications_urgentes -->
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-thumbs-up text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="mb-3">Aucune urgence</h4>
                    <p>Vous n'avez pas de notifications urgentes pour le moment.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal pour afficher le détail d'un message -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageTitle">Détail du message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Expéditeur</label>
                    <div id="messageSender" class="fw-bold">-</div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Date</label>
                    <div id="messageDate" class="fw-bold">-</div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Contenu</label>
                    <div id="messageContent" class="border p-3 rounded bg-light">-</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="markReadInModal">Marquer comme lu</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour effacer toutes les notifications -->
<div class="modal fade" id="clearAllModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir effacer toutes vos notifications ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmClearAll">Effacer tout</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Recherche de notification
        $("#searchNotification").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".notification-item").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Filtres
        $("#filterType, #filterImportance").on("change", function() {
            applyFilters();
        });
        
        // Marquer tout comme lu
        $("#markAllRead").on("click", function() {
            // Ici, vous pouvez ajouter un appel AJAX pour marquer toutes les notifications comme lues
            
            // Pour l'instant, simulons visuellement le changement
            $(".notification-card.unread").removeClass("unread");
            $(".badge.bg-danger:contains('Nouveau')").remove();
            $(".mark-read-btn").remove();
            
            // Mise à jour des compteurs
            $(".badge:contains('Non lues')").text("0");
            
            // Message de confirmation
            showAlert("success", "Toutes les notifications ont été marquées comme lues.");
        });
        
        // Ouvrir le modal de confirmation pour effacer tout
        $("#clearAll").on("click", function() {
            $("#clearAllModal").modal("show");
        });
        
        // Confirmer l'effacement de toutes les notifications
        $("#confirmClearAll").on("click", function() {
            // Ici, vous pouvez ajouter un appel AJAX pour supprimer toutes les notifications
            
            // Pour l'instant, simulons visuellement le changement
            $(".notification-item").remove();
            $(".notification-list").append('<div class="card text-center py-5">' +
                '<div class="card-body">' +
                '<i class="fas fa-bell-slash text-success mb-3" style="font-size: 3rem;"></i>' +
                '<h4 class="mb-3">Aucune notification</h4>' +
                '<p>Vous n\'avez pas de notifications pour le moment.</p>' +
                '</div>' +
                '</div>');
            
            // Mise à jour des compteurs
            $(".badge").text("0");
            
            // Fermer le modal
            $("#clearAllModal").modal("hide");
            
            // Message de confirmation
            showAlert("success", "Toutes les notifications ont été effacées.");
        });
        
        // Marquer une notification comme lue
        $(document).on("click", ".mark-read-btn", function() {
            var btn = $(this);
            var id = btn.data("id");
            
            // Ici, vous pouvez ajouter un appel AJAX pour marquer la notification comme lue
            
            // Pour l'instant, simulons visuellement le changement
            btn.closest(".notification-card").removeClass("unread");
            btn.closest(".notification-card").find(".badge.bg-danger:contains('Nouveau')").remove();
            btn.remove();
            
            // Message de confirmation
            showAlert("success", "Notification marquée comme lue.");
        });
        
        // Supprimer une notification
        $(document).on("click", ".delete-notification-btn", function() {
            var btn = $(this);
            var id = btn.data("id");
            
            // Ici, vous pouvez ajouter un appel AJAX pour supprimer la notification
            
            // Pour l'instant, simulons visuellement le changement
            btn.closest(".notification-card").fadeOut(300, function() {
                $(this).remove();
                
                // Vérifier s'il reste des notifications
                if ($(".notification-item").length === 0) {
                    $(".notification-list").append('<div class="card text-center py-5">' +
                        '<div class="card-body">' +
                        '<i class="fas fa-bell-slash text-success mb-3" style="font-size: 3rem;"></i>' +
                        '<h4 class="mb-3">Aucune notification</h4>' +
                        '<p>Vous n\'avez pas de notifications pour le moment.</p>' +
                        '</div>' +
                        '</div>');
                }
            });
            
            // Message de confirmation
            showAlert("success", "Notification supprimée.");
        });
        
        // Voir le détail d'un message
        $(document).on("click", ".view-message-btn", function() {
            var id = $(this).data("id");
            
            // Ici, vous pouvez ajouter un appel AJAX pour récupérer les détails du message
            
            // Pour l'instant, utilisons des données fictives
            $("#messageTitle").text("Détail du message #" + id);
            $("#messageSender").text("Dr. Dupont (Médecin)");
            $("#messageDate").text("30/05/2025 à 14:30");
            $("#messageContent").html("Bonjour,<br><br>Merci de vérifier les signes vitaux du patient en chambre 203 avant 16h aujourd'hui.<br><br>Cordialement,<br>Dr. Dupont");
            
            // Afficher le modal
            $("#messageModal").modal("show");
        });
        
        // Marquer comme lu depuis le modal
        $("#markReadInModal").on("click", function() {
            // Fermer le modal
            $("#messageModal").modal("hide");
            
            // Message de confirmation
            showAlert("success", "Message marqué comme lu.");
        });
        
        function applyFilters() {
            var typeFilter = $("#filterType").val();
            var importanceFilter = $("#filterImportance").val();
            
            $(".notification-item").each(function() {
                var item = $(this);
                var typeMatch = true;
                var importanceMatch = true;
                
                if (typeFilter) {
                    typeMatch = item.data("type") === typeFilter;
                }
                
                if (importanceFilter) {
                    importanceMatch = item.data("importance") === importanceFilter;
                }
                
                item.toggle(typeMatch && importanceMatch);
            });
        }
        
        function showAlert(type, message) {
            var alertClass = type === "success" ? "alert-success" : (type === "warning" ? "alert-warning" : "alert-danger");
            var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert" id="tempAlert">' +
                message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>';
                
            // Supprimer les alertes précédentes
            $("#tempAlert").remove();
            
            // Ajouter la nouvelle alerte
            $("body").append(alertHtml);
            
            // Masquer l'alerte après 3 secondes
            setTimeout(function() {
                $("#tempAlert").alert('close');
            }, 3000);
        }
    });
</script>
@endsection
