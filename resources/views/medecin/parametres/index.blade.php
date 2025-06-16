@extends('layouts.medecin')

@section('title', 'Paramètres du compte')

@push('styles')
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }
    .form-card:hover {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .form-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .form-body {
        padding: 2rem;
    }
    .form-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }
    .input-field {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .input-field:focus {
        background: white;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .btn-primary {
        background: #3b82f6;
        color: white;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }
    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Paramètres du compte</h1>
    </div>

            <div class="row g-4">
                <!-- Profil -->
                <div class="col-lg-8">
                    <div class="form-card mb-4">
                        <div class="form-header">
                            <h2 class="form-title">Informations personnelles</h2>
                        </div>
                        <div class="form-body">
                            <form action="{{ route('medecin.parametres.profil.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-4">
                                    <div class="col-md-3 text-center">
                                        <!-- Photo de profil -->
                                        <div class="mb-3">
                                            @if(auth()->user()->photo)
                                                <img src="{{ auth()->user()->photo }}" alt="{{ auth()->user()->nom }}" class="profile-img mb-2">
                                            @else
                                                <div class="profile-img mb-2 d-flex align-items-center justify-content-center bg-light text-primary">
                                                    <i class="bx bxs-user" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif
                                            <div class="mt-3">
                                                <input type="file" name="photo" class="form-control form-control-sm" id="photo">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-9">
                                        <!-- Informations personnelles -->
                                        <div class="row g-3">
                                            <div class="col-md-6 mb-3">
                                                <label for="nom" class="form-label">Nom</label>
                                                <input type="text" name="nom" id="nom" value="{{ auth()->user()->nom }}" class="form-control input-field">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="prenom" class="form-label">Prénom</label>
                                                <input type="text" name="prenom" id="prenom" value="{{ auth()->user()->prenom }}" class="form-control input-field">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Adresse email</label>
                                                <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="form-control input-field">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="telephone" class="form-label">Téléphone</label>
                                                <input type="tel" name="telephone" id="telephone" value="{{ auth()->user()->telephone }}" class="form-control input-field">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="adresse" class="form-label">Adresse</label>
                                                <textarea name="adresse" id="adresse" rows="3" class="form-control input-field">{{ auth()->user()->adresse }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bxs-save me-1"></i> Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Paramètres supplémentaires -->
                <div class="col-lg-4">
                    <!-- Mot de passe -->
                    <div class="form-card mb-4">
                        <div class="form-header">
                            <h2 class="form-title">Sécurité</h2>
                        </div>
                        <div class="form-body">
                            <form action="{{ route('medecin.parametres.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" id="current_password" class="form-control input-field">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control input-field">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                    <div class="form-text mt-1">Le mot de passe doit contenir au moins 8 caractères.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-field">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bxs-lock me-1"></i> Mettre à jour le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Préférences -->
                    <div class="form-card">
                        <div class="form-header">
                            <h2 class="form-title">Préférences</h2>
                        </div>
                        <div class="form-body">
                            <form action="{{ route('medecin.parametres.preferences.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label">Langue</label>
                                    <select name="langue" class="form-select input-field">
                                        <option value="fr" {{ auth()->user()->langue === 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="en" {{ auth()->user()->langue === 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fuseau horaire</label>
                                    <select name="fuseau_horaire" class="form-select input-field">
                                        <option value="Europe/Paris" {{ auth()->user()->fuseau_horaire === 'Europe/Paris' ? 'selected' : '' }}>Paris (UTC+1)</option>
                                        <option value="Europe/London" {{ auth()->user()->fuseau_horaire === 'Europe/London' ? 'selected' : '' }}>London (UTC+0)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mb-2">Notifications</label>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="notifications_email" id="notifications_email" class="form-check-input" {{ auth()->user()->notifications_email ? 'checked' : '' }}>
                                        <label for="notifications_email" class="form-check-label">Recevoir des notifications par email</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="notifications_sms" id="notifications_sms" class="form-check-input" {{ auth()->user()->notifications_sms ? 'checked' : '' }}>
                                        <label for="notifications_sms" class="form-check-label">Recevoir des notifications par SMS</label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bxs-cog me-1"></i> Enregistrer les préférences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
    // Fonction pour basculer l'affichage des mots de passe
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        });
    });
    
    // Animation des cartes
    document.querySelectorAll('.form-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>
@endpush