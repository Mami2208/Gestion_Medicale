@extends('secretaire.layouts.app')

@section('title', 'Créer une notification')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .notification-template {
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .notification-template:hover {
        border-color: #3490dc;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .notification-template.selected {
        border-color: #3490dc;
        background-color: rgba(52, 144, 220, 0.05);
    }
    
    .notification-preview {
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        padding: 20px;
        background-color: #f8f9fa;
        margin-top: 20px;
    }
    
    .tab-content {
        padding-top: 20px;
    }
    
    .recipient-tag {
        display: inline-block;
        background-color: #e2e8f0;
        border-radius: 15px;
        padding: 5px 10px;
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    .recipient-tag .close {
        margin-left: 5px;
        cursor: pointer;
    }
    
    .schedule-options {
        margin-top: 15px;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-bell text-primary me-2"></i> Créer une notification
                </h4>
                <a href="{{ route('secretaire.notifications.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
    
    <form action="{{ route('secretaire.notifications.store') }}" method="POST" id="notificationForm">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <!-- Onglets -->
                <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                            <i class="fas fa-edit me-1"></i> Général
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="recipients-tab" data-bs-toggle="tab" data-bs-target="#recipients" type="button" role="tab" aria-controls="recipients" aria-selected="false">
                            <i class="fas fa-users me-1"></i> Destinataires
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab" aria-controls="schedule" aria-selected="false">
                            <i class="fas fa-clock me-1"></i> Planification
                        </button>
                    </li>
                </ul>
                
                <!-- Contenu des onglets -->
                <div class="tab-content" id="notificationTabsContent">
                    <!-- Onglet Général -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notificationType" class="form-label">Type de notification</label>
                                    <select class="form-select" id="notificationType" name="type" required>
                                        <option value="">Sélectionner un type...</option>
                                        <option value="RENDEZ_VOUS">Rappel de rendez-vous</option>
                                        <option value="DOCUMENT">Document à signer</option>
                                        <option value="RESULTAT">Résultats disponibles</option>
                                        <option value="INFO">Information générale</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notificationTitle" class="form-label">Titre</label>
                                    <input type="text" class="form-control" id="notificationTitle" name="title" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notificationMessage" class="form-label">Message</label>
                                    <textarea class="form-control" id="notificationMessage" name="message" rows="5" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notificationPriority" class="form-label">Priorité</label>
                                    <select class="form-select" id="notificationPriority" name="priority">
                                        <option value="normal">Normale</option>
                                        <option value="high">Haute</option>
                                        <option value="low">Basse</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Onglet Destinataires -->
                    <div class="tab-pane fade" id="recipients" role="tabpanel" aria-labelledby="recipients-tab">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Mode d'envoi</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recipient_mode" id="specificRecipients" value="specific" checked>
                                        <label class="form-check-label" for="specificRecipients">
                                            Destinataires spécifiques
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recipient_mode" id="filterRecipients" value="filter">
                                        <label class="form-check-label" for="filterRecipients">
                                            Par filtres
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Destinataires spécifiques -->
                                <div id="specificRecipientsSection" class="mb-3">
                                    <label for="recipientSearch" class="form-label">Rechercher un patient</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="recipientSearch" placeholder="Nom, prénom ou numéro de dossier">
                                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    
                                    <div id="searchResults" class="list-group mt-2 d-none">
                                        <!-- Les résultats de recherche seront insérés ici via JavaScript -->
                                    </div>
                                    
                                    <div class="mt-3">
                                        <label class="form-label">Destinataires sélectionnés</label>
                                        <div id="selectedRecipients" class="mt-2">
                                            <!-- Les destinataires sélectionnés seront affichés ici -->
                                            <p class="text-muted" id="noRecipientsMessage">Aucun destinataire sélectionné</p>
                                        </div>
                                        <input type="hidden" name="recipient_ids" id="recipientIdsInput">
                                    </div>
                                </div>
                                
                                <!-- Filtres -->
                                <div id="filterRecipientsSection" class="mb-3 d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="filterMedecin" class="form-label">Médecin</label>
                                                <select class="form-select" id="filterMedecin" name="filter_medecin_id">
                                                    <option value="">Tous les médecins</option>
                                                    @foreach(\App\Models\Medecin::with('utilisateur')->get() as $medecin)
                                                        <option value="{{ $medecin->id }}">Dr. {{ $medecin->utilisateur->prenom }} {{ $medecin->utilisateur->nom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="filterRendezVous" class="form-label">Rendez-vous</label>
                                                <select class="form-select" id="filterRendezVous" name="filter_rendez_vous">
                                                    <option value="">Tous les patients</option>
                                                    <option value="today">Rendez-vous aujourd'hui</option>
                                                    <option value="tomorrow">Rendez-vous demain</option>
                                                    <option value="week">Rendez-vous cette semaine</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Onglet Planification -->
                    <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Quand envoyer ?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="schedule_type" id="sendNow" value="now" checked>
                                        <label class="form-check-label" for="sendNow">
                                            Envoyer maintenant
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="schedule_type" id="sendLater" value="later">
                                        <label class="form-check-label" for="sendLater">
                                            Planifier pour plus tard
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="schedule_type" id="sendRelative" value="relative">
                                        <label class="form-check-label" for="sendRelative">
                                            Relatif à un événement
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Options d'envoi plus tard -->
                                <div id="sendLaterOptions" class="schedule-options d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="scheduledDate" class="form-label">Date</label>
                                                <input type="text" class="form-control" id="scheduledDate" name="scheduled_date" placeholder="JJ/MM/AAAA">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="scheduledTime" class="form-label">Heure</label>
                                                <input type="text" class="form-control" id="scheduledTime" name="scheduled_time" placeholder="HH:MM">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Options relatives à un événement -->
                                <div id="sendRelativeOptions" class="schedule-options d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="relativeEvent" class="form-label">Événement</label>
                                                <select class="form-select" id="relativeEvent" name="relative_event">
                                                    <option value="rendez_vous">Rendez-vous</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="relativeTiming" class="form-label">Timing</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="relativeValue" name="relative_value" min="1" value="1">
                                                    <select class="form-select" id="relativeUnit" name="relative_unit">
                                                        <option value="hours">Heures</option>
                                                        <option value="days" selected>Jours</option>
                                                        <option value="weeks">Semaines</option>
                                                    </select>
                                                    <select class="form-select" id="relativeBefore" name="relative_before">
                                                        <option value="before">Avant</option>
                                                        <option value="after">Après</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notificationChannel" class="form-label">Canaux de notification</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="channels[]" id="channelApp" value="app" checked>
                                        <label class="form-check-label" for="channelApp">
                                            Application
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="channels[]" id="channelEmail" value="email">
                                        <label class="form-check-label" for="channelEmail">
                                            Email
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="channels[]" id="channelSms" value="sms">
                                        <label class="form-check-label" for="channelSms">
                                            SMS
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('secretaire.notifications.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <div>
                        <button type="button" class="btn btn-primary me-2" id="previewNotification">
                            <i class="fas fa-eye me-1"></i> Aperçu
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-1"></i> Envoyer
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Modèles de notification -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Modèles de notification</h5>
                    </div>
                    <div class="card-body">
                        <div class="notification-template" data-type="RENDEZ_VOUS" data-title="Rappel de rendez-vous" data-message="Nous vous rappelons votre rendez-vous prévu le [DATE] à [HEURE] avec le Dr. [MEDECIN]. Merci de vous présenter 15 minutes avant l'heure de votre rendez-vous.">
                            <h6><i class="fas fa-calendar-alt text-primary me-2"></i> Rappel de rendez-vous</h6>
                            <p class="text-muted mb-0 small">Notification standard pour rappeler aux patients leur prochain rendez-vous</p>
                        </div>
                        
                        <div class="notification-template" data-type="DOCUMENT" data-title="Document à signer" data-message="Vous avez un document qui nécessite votre signature. Veuillez vous connecter à votre espace patient ou passer à l'accueil lors de votre prochaine visite pour le signer.">
                            <h6><i class="fas fa-file-signature text-danger me-2"></i> Document à signer</h6>
                            <p class="text-muted mb-0 small">Notification pour les documents nécessitant une signature</p>
                        </div>
                        
                        <div class="notification-template" data-type="RESULTAT" data-title="Résultats disponibles" data-message="Vos résultats d'examen sont maintenant disponibles. Veuillez consulter votre espace patient ou contacter votre médecin pour en discuter.">
                            <h6><i class="fas fa-flask text-success me-2"></i> Résultats disponibles</h6>
                            <p class="text-muted mb-0 small">Informer les patients de la disponibilité de leurs résultats</p>
                        </div>
                        
                        <div class="notification-template" data-type="INFO" data-title="Information importante" data-message="Nous souhaitons vous informer que [MESSAGE]. Pour plus d'informations, n'hésitez pas à nous contacter.">
                            <h6><i class="fas fa-info-circle text-info me-2"></i> Information générale</h6>
                            <p class="text-muted mb-0 small">Notification générale pour communiquer des informations</p>
                        </div>
                    </div>
                </div>
                
                <!-- Aperçu de la notification -->
                <div class="card border-0 shadow-sm d-none" id="previewCard">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Aperçu de la notification</h5>
                    </div>
                    <div class="card-body">
                        <div class="notification-preview">
                            <h6 id="previewTitle">Titre de la notification</h6>
                            <p id="previewMessage" class="mb-2">Contenu de la notification...</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i> <span id="previewDate">{{ date('d/m/Y H:i') }}</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation des datepickers
        flatpickr("#scheduledDate", {
            locale: "fr",
            dateFormat: "d/m/Y",
            minDate: "today"
        });
        
        flatpickr("#scheduledTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
        
        // Gestion des modèles de notification
        const templates = document.querySelectorAll('.notification-template');
        templates.forEach(template => {
            template.addEventListener('click', function() {
                const type = this.dataset.type;
                const title = this.dataset.title;
                const message = this.dataset.message;
                
                document.getElementById('notificationType').value = type;
                document.getElementById('notificationTitle').value = title;
                document.getElementById('notificationMessage').value = message;
                
                // Retirer la classe selected de tous les modèles
                templates.forEach(t => t.classList.remove('selected'));
                
                // Ajouter la classe selected au modèle cliqué
                this.classList.add('selected');
                
                // Mettre à jour l'aperçu
                updatePreview();
            });
        });
        
        // Gestion des modes de destinataires
        const specificRecipientsRadio = document.getElementById('specificRecipients');
        const filterRecipientsRadio = document.getElementById('filterRecipients');
        const specificRecipientsSection = document.getElementById('specificRecipientsSection');
        const filterRecipientsSection = document.getElementById('filterRecipientsSection');
        
        specificRecipientsRadio.addEventListener('change', function() {
            if (this.checked) {
                specificRecipientsSection.classList.remove('d-none');
                filterRecipientsSection.classList.add('d-none');
            }
        });
        
        filterRecipientsRadio.addEventListener('change', function() {
            if (this.checked) {
                specificRecipientsSection.classList.add('d-none');
                filterRecipientsSection.classList.remove('d-none');
            }
        });
        
        // Gestion des options de planification
        const sendNowRadio = document.getElementById('sendNow');
        const sendLaterRadio = document.getElementById('sendLater');
        const sendRelativeRadio = document.getElementById('sendRelative');
        const sendLaterOptions = document.getElementById('sendLaterOptions');
        const sendRelativeOptions = document.getElementById('sendRelativeOptions');
        
        sendNowRadio.addEventListener('change', function() {
            if (this.checked) {
                sendLaterOptions.classList.add('d-none');
                sendRelativeOptions.classList.add('d-none');
            }
        });
        
        sendLaterRadio.addEventListener('change', function() {
            if (this.checked) {
                sendLaterOptions.classList.remove('d-none');
                sendRelativeOptions.classList.add('d-none');
            }
        });
        
        sendRelativeRadio.addEventListener('change', function() {
            if (this.checked) {
                sendLaterOptions.classList.add('d-none');
                sendRelativeOptions.classList.remove('d-none');
            }
        });
        
        // Gestion de la recherche des destinataires
        const searchInput = document.getElementById('recipientSearch');
        const searchButton = document.getElementById('searchButton');
        const searchResults = document.getElementById('searchResults');
        const selectedRecipients = document.getElementById('selectedRecipients');
        const noRecipientsMessage = document.getElementById('noRecipientsMessage');
        const recipientIdsInput = document.getElementById('recipientIdsInput');
        
        // Simuler une recherche (à remplacer par un appel AJAX réel)
        searchButton.addEventListener('click', function() {
            const searchTerm = searchInput.value.trim();
            if (searchTerm.length < 2) {
                alert('Veuillez saisir au moins 2 caractères pour la recherche');
                return;
            }
            
            // Simulation de résultats
            searchResults.classList.remove('d-none');
            searchResults.innerHTML = `
                <a href="#" class="list-group-item list-group-item-action" data-id="1" data-name="Martin Dupont">
                    Martin Dupont <small class="text-muted">- #12345</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action" data-id="2" data-name="Sophie Martin">
                    Sophie Martin <small class="text-muted">- #12346</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action" data-id="3" data-name="Jean Durand">
                    Jean Durand <small class="text-muted">- #12347</small>
                </a>
            `;
            
            // Ajouter les événements de clic aux résultats
            const resultItems = searchResults.querySelectorAll('.list-group-item');
            resultItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    addRecipient(id, name);
                    searchResults.classList.add('d-none');
                    searchInput.value = '';
                });
            });
        });
        
        // Ajouter un destinataire
        function addRecipient(id, name) {
            // Cacher le message "aucun destinataire"
            noRecipientsMessage.classList.add('d-none');
            
            // Vérifier si le destinataire existe déjà
            if (document.querySelector(`.recipient-tag[data-id="${id}"]`)) {
                return;
            }
            
            const tag = document.createElement('div');
            tag.className = 'recipient-tag';
            tag.dataset.id = id;
            tag.innerHTML = `
                ${name}
                <span class="close" data-id="${id}">&times;</span>
            `;
            
            selectedRecipients.appendChild(tag);
            
            // Mettre à jour l'input caché
            updateRecipientIds();
            
            // Ajouter l'événement de suppression
            tag.querySelector('.close').addEventListener('click', function() {
                tag.remove();
                updateRecipientIds();
                
                // Afficher le message "aucun destinataire" si nécessaire
                if (selectedRecipients.querySelectorAll('.recipient-tag').length === 0) {
                    noRecipientsMessage.classList.remove('d-none');
                }
            });
        }
        
        // Mettre à jour l'input caché avec les IDs des destinataires
        function updateRecipientIds() {
            const tags = selectedRecipients.querySelectorAll('.recipient-tag');
            const ids = Array.from(tags).map(tag => tag.dataset.id);
            recipientIdsInput.value = ids.join(',');
        }
        
        // Bouton d'aperçu
        const previewButton = document.getElementById('previewNotification');
        const previewCard = document.getElementById('previewCard');
        const previewTitle = document.getElementById('previewTitle');
        const previewMessage = document.getElementById('previewMessage');
        const previewDate = document.getElementById('previewDate');
        
        previewButton.addEventListener('click', function() {
            updatePreview();
            previewCard.classList.remove('d-none');
        });
        
        // Mettre à jour l'aperçu
        function updatePreview() {
            const title = document.getElementById('notificationTitle').value || 'Titre de la notification';
            const message = document.getElementById('notificationMessage').value || 'Contenu de la notification...';
            
            previewTitle.textContent = title;
            previewMessage.textContent = message;
            
            // Mise à jour de la date selon le type d'envoi
            if (document.getElementById('sendNow').checked) {
                previewDate.textContent = '{{ date("d/m/Y H:i") }}';
            } else if (document.getElementById('sendLater').checked) {
                const date = document.getElementById('scheduledDate').value;
                const time = document.getElementById('scheduledTime').value;
                if (date && time) {
                    previewDate.textContent = `${date} ${time}`;
                }
            } else {
                previewDate.textContent = 'Planifié (relatif à un événement)';
            }
        }
        
        // Mettre à jour l'aperçu quand les champs changent
        document.getElementById('notificationTitle').addEventListener('input', updatePreview);
        document.getElementById('notificationMessage').addEventListener('input', updatePreview);
    });
</script>
@endsection
