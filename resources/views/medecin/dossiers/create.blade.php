@extends('medecin.layouts.app')


@php
    \Log::info('Affichage du formulaire de création de dossier');
    if ($errors->any()) {
        \Log::error('Erreurs de validation dans le formulaire', ['errors' => $errors->all()]);
    }
@endphp

@section('title', 'Créer un dossier médical')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Créer un nouveau patient et son dossier médical</h1>
        <p class="mt-1 text-sm text-gray-500">Remplissez les informations pour créer un nouveau patient et son dossier médical</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('medecin.dossiers.store') }}" method="POST" class="p-6" id="patient-form">
            @csrf
            <div id="form-errors" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Erreur !</strong>
                <span class="block sm:inline" id="error-message"></span>
            </div>

            <div class="space-y-6">
                <!-- Informations personnelles du patient -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informations personnelles</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Prénom -->
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('prenom') border-red-500 @enderror">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('nom') border-red-500 @enderror">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone <span class="text-red-500">*</span></label>
                            <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('telephone') border-red-500 @enderror">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Date de naissance -->
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700">Date de naissance <span class="text-red-500">*</span></label>
                            <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('date_naissance') border-red-500 @enderror">
                            @error('date_naissance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Genre -->
                        <div>
                            <label for="genre" class="block text-sm font-medium text-gray-700">Genre <span class="text-red-500">*</span></label>
                            <select name="genre" id="genre" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('genre') border-red-500 @enderror">
                                <option value="">Sélectionnez un genre</option>
                                <option value="M" {{ old('genre') == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('genre') == 'F' ? 'selected' : '' }}>Féminin</option>
                                <option value="A" {{ old('genre') == 'A' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('genre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="mb-4">
                        <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <textarea name="adresse" id="adresse" rows="2"
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('adresse') border-red-500 @enderror">{{ old('adresse') }}</textarea>
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe temporaire -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe temporaire <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Informations médicales -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informations médicales</h2>
                    
                    <!-- Statut -->
                    <div class="mb-4">
                        <label for="statut" class="block text-sm font-medium text-gray-700">Statut <span class="text-red-500">*</span></label>
                        <select name="statut" id="statut" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('statut') border-red-500 @enderror">
                            @foreach(\App\Models\Dossier::statuts() as $value => $label)
                                <option value="{{ $value }}" {{ old('statut', 'ACTIF') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Groupe sanguin -->
                    <div class="mb-4">
                        <label for="groupe_sanguin" class="block text-sm font-medium text-gray-700">Groupe sanguin</label>
                        <input type="text" name="groupe_sanguin" id="groupe_sanguin" value="{{ old('groupe_sanguin') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('groupe_sanguin') border-red-500 @enderror"
                               placeholder="Ex: A+">
                        @error('groupe_sanguin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Taille -->
                    <div class="mb-4">
                        <label for="taille" class="block text-sm font-medium text-gray-700">Taille (cm)</label>
                        <input type="number" name="taille" id="taille" step="0.01" min="0" value="{{ old('taille') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('taille') border-red-500 @enderror">
                        @error('taille')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Poids -->
                    <div class="mb-4">
                        <label for="poids" class="block text-sm font-medium text-gray-700">Poids (kg)</label>
                        <input type="number" name="poids" id="poids" step="0.1" min="0" value="{{ old('poids') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('poids') border-red-500 @enderror">
                        @error('poids')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Antécédents médicaux -->
                    <div class="mb-4">
                        <label for="antecedents_medicaux" class="block text-sm font-medium text-gray-700">Antécédents médicaux</label>
                        <textarea name="antecedents_medicaux" id="antecedents_medicaux" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('antecedents_medicaux') border-red-500 @enderror">{{ old('antecedents_medicaux') }}</textarea>
                        @error('antecedents_medicaux')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Allergies -->
                    <div class="mb-4">
                        <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies</label>
                        <textarea name="allergies" id="allergies" rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('allergies') border-red-500 @enderror">{{ old('allergies') }}</textarea>
                        @error('allergies')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observations -->
                    <div class="mb-4">
                        <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                        <textarea name="observations" id="observations" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('observations') border-red-500 @enderror">{{ old('observations') }}</textarea>
                        @error('observations')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('medecin.dossiers.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Annuler</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Créer le dossier médical</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Fonction utilitaire pour afficher les erreurs
function showError(message, details = null) {
    console.error('Erreur:', message, details);
    
    const errorDiv = document.getElementById('form-errors');
    const errorMessage = document.getElementById('error-message');
    
    if (!errorDiv || !errorMessage) {
        console.error('Éléments d\'erreur non trouvés dans le DOM');
        return;
    }
    
    // Afficher le message d'erreur principal
    errorMessage.textContent = message;
    
    // Afficher les détails si disponibles
    const errorDetails = document.createElement('div');
    errorDetails.className = 'mt-2 text-sm';
    
    if (details) {
        if (typeof details === 'object') {
            // Afficher les erreurs de validation
            if (details.errors) {
                const errorList = document.createElement('ul');
                errorList.className = 'list-disc pl-5';
                
                for (const [field, errors] of Object.entries(details.errors)) {
                    errors.forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = `${field}: ${error}`;
                        errorList.appendChild(li);
                    });
                }
                errorDetails.appendChild(errorList);
            } else {
                // Afficher d'autres détails d'erreur
                for (const [key, value] of Object.entries(details)) {
                    if (key !== 'message') {
                        const p = document.createElement('p');
                        p.textContent = `${key}: ${JSON.stringify(value)}`;
                        errorDetails.appendChild(p);
                    }
                }
            }
        } else {
            errorDetails.textContent = details;
        }
    }
    
    // Ajouter les détails au message d'erreur
    errorMessage.appendChild(errorDetails);
    
    // Afficher la div d'erreur
    errorDiv.classList.remove('hidden');
    
    // Faire défiler jusqu'aux erreurs
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Gestionnaire d'erreurs global
window.onerror = function(message, source, lineno, colno, error) {
    console.error('Erreur globale:', {
        message: message,
        source: source,
        lineno: lineno,
        colno: colno,
        error: error
    });
    
    showError('Une erreur JavaScript est survenue', {
        message: message,
        source: source,
        line: lineno,
        column: colno,
        error: error ? error.stack : 'Pas de détails supplémentaires'
    });
    
    // Empêcher l'exécution du gestionnaire d'erreurs par défaut
    return true;
};

// Gestionnaire d'erreurs non capturées des promesses
window.addEventListener('unhandledrejection', function(event) {
    console.error('Erreur de promesse non gérée:', event.reason);
    
    showError('Une erreur est survenue dans une opération asynchrone', {
        reason: event.reason ? event.reason.toString() : 'Raison inconnue',
        stack: event.reason && event.reason.stack ? event.reason.stack : 'Pas de stack trace disponible'
    });
});

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM chargé, initialisation du formulaire...');
        
        const form = document.getElementById('patient-form');
        if (!form) {
            console.error('Formulaire non trouvé');
            showError('Le formulaire n\'a pas pu être initialisé correctement');
            return;
        }
        
        const errorDiv = document.getElementById('form-errors');
        const errorMessage = document.getElementById('error-message');
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton ? submitButton.innerHTML : '';

        if (!submitButton) {
            console.error('Bouton de soumission non trouvé');
            return;
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            console.log('Soumission du formulaire...');
            
            try {
                // Afficher l'indicateur de chargement
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
                
                // Cacher les erreurs précédentes
                if (errorDiv) errorDiv.classList.add('hidden');
                if (errorMessage) errorMessage.innerHTML = '';
                
                // Récupérer les données du formulaire
                const formData = new FormData(form);
                
                // Afficher les données du formulaire dans la console
                console.log('Données du formulaire:');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value);
                }
                
                // Envoyer la requête AJAX
                console.log('Envoi de la requête AJAX à:', form.action);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: formData
                });
                
                console.log('Réponse reçue, statut:', response.status, response.statusText);
                
                let data;
                const responseText = await response.text();
                try {
                    data = responseText ? JSON.parse(responseText) : {};
                } catch (e) {
                    console.error('Erreur de parsing JSON:', e, 'Réponse:', responseText);
                    throw {
                        message: 'Erreur de format de la réponse du serveur',
                        status: response.status,
                        statusText: response.statusText,
                        responseText: responseText,
                        error: e.toString()
                    };
                }
                
                if (!response.ok) {
                    console.error('Erreur HTTP:', response.status, response.statusText, data);
                    throw data || {
                        message: `Erreur ${response.status}: ${response.statusText}`,
                        status: response.status,
                        statusText: response.statusText,
                        responseText: responseText
                    };
                }
                
                // Succès : rediriger ou afficher un message de succès
                console.log('Succès, réponse:', data);
                
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.message) {
                    // Afficher un message de succès
                    showSuccess(data.message);
                    // Rediriger après 2 secondes
                    setTimeout(() => {
                        window.location.href = '{{ route("medecin.dossiers.index") }}';
                    }, 2000);
                } else {
                    // Redirection par défaut
                    window.location.href = '{{ route("medecin.dossiers.index") }}';
                }
                
            } catch (error) {
                console.error('Erreur lors de la soumission du formulaire:', error);
                
                // Réactiver le bouton
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                
                // Afficher l'erreur
                showError(
                    error.message || 'Une erreur est survenue lors de la soumission du formulaire',
                    error.errors || error
                );
                
                // Faire défiler jusqu'aux erreurs
                if (errorDiv) {
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });
    
    // Fonction pour afficher un message de succès
    function showSuccess(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
        successDiv.role = 'alert';
        successDiv.innerHTML = `
            <strong class="font-bold">Succès !</strong>
            <span class="block sm:inline">${message}</span>
        `;
        
        // Insérer le message en haut du formulaire
        const form = document.getElementById('patient-form');
        if (form && form.firstChild) {
            form.insertBefore(successDiv, form.firstChild);
        }
        
        // Faire défiler jusqu'au message
        successDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Supprimer le message après 5 secondes
        setTimeout(() => {
            successDiv.remove();
        }, 5000);
    }
</script>
@endpush
@endsection