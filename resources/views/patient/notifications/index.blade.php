@extends('patient.layouts.app')

@section('title', 'Notifications')

@section('page_title', 'Mes notifications')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-bell me-2 text-success"></i>Notifications</h5>
                <div>
                    <button class="btn btn-outline-success btn-sm me-2">
                        <i class="fas fa-check-double me-1"></i>Tout marquer comme lu
                    </button>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item active" href="#">Toutes</a></li>
                            <li><a class="dropdown-item" href="#">Non lues</a></li>
                            <li><a class="dropdown-item" href="#">Rendez-vous</a></li>
                            <li><a class="dropdown-item" href="#">Résultats</a></li>
                            <li><a class="dropdown-item" href="#">Prescriptions</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <!-- Notification non lue - nouveau résultat -->
                    <div class="list-group-item list-group-item-action p-3 bg-light">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">
                                <span class="badge bg-success me-2">Nouveau</span>
                                Résultats disponibles
                            </h6>
                            <small class="text-muted">Il y a 2 heures</small>
                        </div>
                        <p class="mb-1">Vos résultats de radiographie pulmonaire sont maintenant disponibles.</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-success">
                                <i class="fas fa-file-medical me-1"></i>Dr. Dupont
                            </small>
                            <div>
                                <a href="{{ route('patient.resultats') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                                <button class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="fas fa-check me-1"></i>Marquer comme lu
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification non lue - rappel RDV -->
                    <div class="list-group-item list-group-item-action p-3 bg-light">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">
                                <span class="badge bg-success me-2">Nouveau</span>
                                Rappel de rendez-vous
                            </h6>
                            <small class="text-muted">Il y a 1 jour</small>
                        </div>
                        <p class="mb-1">Rappel : Vous avez un rendez-vous avec Dr. Martin le 30/06/2025 à 14h30.</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-success">
                                <i class="fas fa-calendar-check me-1"></i>Service de consultation
                            </small>
                            <div>
                                <button class="btn btn-sm btn-success">
                                    <i class="fas fa-calendar-alt me-1"></i>Ajouter au calendrier
                                </button>
                                <button class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="fas fa-check me-1"></i>Marquer comme lu
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification non lue - partage -->
                    <div class="list-group-item list-group-item-action p-3 bg-light">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">
                                <span class="badge bg-success me-2">Nouveau</span>
                                Dossier partagé
                            </h6>
                            <small class="text-muted">Il y a 2 jours</small>
                        </div>
                        <p class="mb-1">Dr. Dupont a consulté votre radiographie pulmonaire que vous avez partagée.</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-success">
                                <i class="fas fa-share-alt me-1"></i>Système de partage
                            </small>
                            <div>
                                <a href="{{ route('patient.partage') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-share-alt me-1"></i>Gérer les partages
                                </a>
                                <button class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="fas fa-check me-1"></i>Marquer comme lu
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification lue - renouvellement -->
                    <div class="list-group-item list-group-item-action p-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Renouvellement de prescription</h6>
                            <small class="text-muted">Il y a 1 semaine</small>
                        </div>
                        <p class="mb-1">Votre prescription pour Amlodipine 5mg et Metformine 500mg a été renouvelée par Dr. Martin.</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-success">
                                <i class="fas fa-prescription me-1"></i>Service de prescription
                            </small>
                            <div>
                                <a href="{{ route('patient.resultats') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-download me-1"></i>Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification lue - résultats -->
                    <div class="list-group-item list-group-item-action p-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Résultats disponibles</h6>
                            <small class="text-muted">Il y a 2 semaines</small>
                        </div>
                        <p class="mb-1">Vos résultats d'analyse de sang sont maintenant disponibles.</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-success">
                                <i class="fas fa-flask me-1"></i>Laboratoire Central
                            </small>
                            <div>
                                <a href="{{ route('patient.resultats') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification lue - consultation -->
                    <div class="list-group-item list-group-item-action p-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Compte-rendu de consultation</h6>
                            <small class="text-muted">Il y a 3 semaines</small>
                        </div>
                        <p class="mb-1">Le compte-rendu de votre consultation du 15/05/2025 avec Dr. Martin est disponible.</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-success">
                                <i class="fas fa-stethoscope me-1"></i>Dr. Martin
                            </small>
                            <div>
                                <a href="{{ route('patient.resultats') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-download me-1"></i>Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <nav aria-label="Pagination des notifications">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Suivant</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Paramètres de notification -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-cog me-2 text-success"></i>Paramètres de notification</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Notifications par email</h6>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email-rdv" checked>
                                    <label class="form-check-label" for="email-rdv">Rappel de rendez-vous</label>
                                </div>
                                <small class="text-muted">Recevoir un email 24h avant vos rendez-vous</small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email-result" checked>
                                    <label class="form-check-label" for="email-result">Nouveaux résultats disponibles</label>
                                </div>
                                <small class="text-muted">Recevoir un email lorsque de nouveaux résultats sont disponibles</small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email-prescription" checked>
                                    <label class="form-check-label" for="email-prescription">Renouvellement de prescription</label>
                                </div>
                                <small class="text-muted">Recevoir un email lorsqu'une prescription est renouvelée</small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email-news">
                                    <label class="form-check-label" for="email-news">Informations de santé</label>
                                </div>
                                <small class="text-muted">Recevoir des emails avec des conseils de santé personnalisés</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3">Notifications par SMS</h6>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sms-rdv" checked>
                                    <label class="form-check-label" for="sms-rdv">Rappel de rendez-vous</label>
                                </div>
                                <small class="text-muted">Recevoir un SMS 2h avant vos rendez-vous</small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sms-result">
                                    <label class="form-check-label" for="sms-result">Nouveaux résultats disponibles</label>
                                </div>
                                <small class="text-muted">Recevoir un SMS lorsque de nouveaux résultats sont disponibles</small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sms-prescription">
                                    <label class="form-check-label" for="sms-prescription">Renouvellement de prescription</label>
                                </div>
                                <small class="text-muted">Recevoir un SMS lorsqu'une prescription est renouvelée</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Enregistrer les préférences
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des boutons "Marquer comme lu"
        const markAsReadButtons = document.querySelectorAll('.btn-outline-secondary');
        markAsReadButtons.forEach(button => {
            button.addEventListener('click', function() {
                const notificationItem = this.closest('.list-group-item');
                notificationItem.classList.remove('bg-light');
                this.style.display = 'none';
                // Ici, vous pourriez ajouter un appel AJAX pour marquer la notification comme lue en base de données
            });
        });
        
        // Gestion du bouton "Tout marquer comme lu"
        const markAllAsReadButton = document.querySelector('.btn-outline-success');
        markAllAsReadButton.addEventListener('click', function() {
            const unreadNotifications = document.querySelectorAll('.list-group-item.bg-light');
            unreadNotifications.forEach(notification => {
                notification.classList.remove('bg-light');
                notification.querySelector('.btn-outline-secondary').style.display = 'none';
            });
            // Ici, vous pourriez ajouter un appel AJAX pour marquer toutes les notifications comme lues en base de données
        });
    });
</script>
@endpush
