@extends('medecin.layouts.app')

@section('title', 'Modifier la prescription')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Modifier la prescription</h1>
        <p class="mt-1 text-sm text-gray-500">Modifiez les détails de la prescription</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('medecin.prescriptions.update', $prescription) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Informations sur le patient -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du patient</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Nom complet</label>
                            <input type="text" class="form-control bg-gray-100" 
                                   value="{{ $prescription->dossierMedical->patient->utilisateur->nom }} {{ $prescription->dossierMedical->patient->utilisateur->prenom }}" 
                                   disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date de naissance</label>
                            <input type="text" class="form-control bg-gray-100" 
                                   value="{{ $prescription->dossierMedical->patient->utilisateur->date_naissance ? \Carbon\Carbon::parse($prescription->dossierMedical->patient->utilisateur->date_naissance)->format('d/m/Y') : 'Non spécifié' }}" 
                                   disabled>
                        </div>
                    </div>
                </div>

                <!-- Informations sur le traitement -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations sur le traitement</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="type_traitement" class="form-label">Type de traitement <span class="text-red-500">*</span></label>
                            <select name="type_traitement" id="type_traitement" class="form-control" required>
                                @foreach(\App\Models\Traitement::TYPES as $key => $label)
                                    <option value="{{ $key }}" {{ old('type_traitement', $prescription->traitement->type_traitement ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_traitement')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description_traitement" class="form-label">Description du traitement <span class="text-red-500">*</span></label>
                            <input type="text" name="description_traitement" id="description_traitement" 
                                   class="form-control @error('description_traitement') is-invalid @enderror" 
                                   value="{{ old('description_traitement', $prescription->traitement->description ?? '') }}" required>
                            @error('description_traitement')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Détails de la prescription -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Détails de la prescription</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="medicament" class="form-label">Médicament <span class="text-red-500">*</span></label>
                            <input type="text" name="medicament" id="medicament" 
                                   class="form-control @error('medicament') is-invalid @enderror" 
                                   value="{{ old('medicament', $prescription->medicament) }}" required>
                            @error('medicament')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="posologie" class="form-label">Posologie <span class="text-red-500">*</span></label>
                            <input type="text" name="posologie" id="posologie" 
                                   class="form-control @error('posologie') is-invalid @enderror" 
                                   value="{{ old('posologie', $prescription->posologie) }}" required>
                            @error('posologie')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="frequence" class="form-label">Fréquence <span class="text-red-500">*</span></label>
                            <input type="text" name="frequence" id="frequence" 
                                   class="form-control @error('frequence') is-invalid @enderror" 
                                   value="{{ old('frequence', $prescription->frequence) }}" required>
                            @error('frequence')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="duree_jours" class="form-label">Durée (en jours) <span class="text-red-500">*</span></label>
                            <input type="number" name="duree_jours" id="duree_jours" min="1" 
                                   class="form-control @error('duree_jours') is-invalid @enderror" 
                                   value="{{ old('duree_jours', $prescription->duree_jours) }}" required>
                            @error('duree_jours')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="form-group">
                    <label for="instructions" class="form-label">Instructions <span class="text-red-500">*</span></label>
                    <textarea name="instructions" id="instructions" rows="4" 
                              class="form-control @error('instructions') is-invalid @enderror" required>{{ old('instructions', $prescription->instructions) }}</textarea>
                    @error('instructions')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de prescription -->
                <div class="form-group">
                    <label for="date_prescription" class="form-label">Date de prescription <span class="text-red-500">*</span></label>
                    <input type="date" name="date_prescription" id="date_prescription" 
                           class="form-control @error('date_prescription') is-invalid @enderror" 
                           value="{{ old('date_prescription', $prescription->date_prescription ? \Carbon\Carbon::parse($prescription->date_prescription)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                    @error('date_prescription')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('medecin.prescriptions.show', $prescription) }}" class="btn btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Mettre à jour la prescription
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mettre à jour la date de fin en fonction de la durée
        const datePrescription = document.getElementById('date_prescription');
        const dureeJours = document.getElementById('duree_jours');
        
        function updateEndDate() {
            if (datePrescription.value && dureeJours.value) {
                const startDate = new Date(datePrescription.value);
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + parseInt(dureeJours.value));
                
                // Formater la date au format YYYY-MM-DD
                const formattedDate = endDate.toISOString().split('T')[0];
                // Si vous avez un champ date_fin, vous pouvez le mettre à jour ici
                // document.getElementById('date_fin').value = formattedDate;
            }
        }
        
        // Écouter les changements sur la date de prescription et la durée
        datePrescription.addEventListener('change', updateEndDate);
        dureeJours.addEventListener('change', updateEndDate);
        
        // Mettre à jour la date de fin au chargement de la page
        updateEndDate();
    });
</script>
@endpush
