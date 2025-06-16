@extends('medecin.layouts.app')

@section('title', 'Nouvelle prescription')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Nouvelle prescription</h1>
        <p class="mt-1 text-sm text-gray-500">Créez une nouvelle prescription pour un patient</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('medecin.prescriptions.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Sélection du patient -->
                <div class="form-group">
                    <label for="patient_id" class="form-label">Patient <span class="text-red-500">*</span></label>
                    <select name="patient_id" id="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required>
                        <option value="">Sélectionnez un patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }} - {{ $patient->utilisateur->date_naissance ? \Carbon\Carbon::parse($patient->utilisateur->date_naissance)->format('d/m/Y') : 'Non spécifié' }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Informations sur le traitement -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations sur le traitement</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="type_traitement" class="form-label">Type de traitement <span class="text-red-500">*</span></label>
                            <select name="type_traitement" id="type_traitement" class="form-control" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="MEDICAMENT" {{ old('type_traitement') == 'MEDICAMENT' ? 'selected' : '' }}>Médicament</option>
                                <option value="KINESITHERAPIE" {{ old('type_traitement') == 'KINESITHERAPIE' ? 'selected' : '' }}>Kinésithérapie</option>
                                <option value="PANSEMENT" {{ old('type_traitement') == 'PANSEMENT' ? 'selected' : '' }}>Pansement</option>
                                <option value="SOINS_INFIRMIER" {{ old('type_traitement') == 'SOINS_INFIRMIER' ? 'selected' : '' }}>Soins infirmiers</option>
                                <option value="AUTRE" {{ old('type_traitement') == 'AUTRE' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('type_traitement')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description_traitement" class="form-label">Description du traitement <span class="text-red-500">*</span></label>
                            <input type="text" name="description_traitement" id="description_traitement" 
                                   class="form-control @error('description_traitement') is-invalid @enderror" 
                                   value="{{ old('description_traitement') }}" required>
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
                                   value="{{ old('medicament') }}" required>
                            @error('medicament')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="posologie" class="form-label">Posologie <span class="text-red-500">*</span></label>
                            <input type="text" name="posologie" id="posologie" 
                                   class="form-control @error('posologie') is-invalid @enderror" 
                                   value="{{ old('posologie') }}" required>
                            @error('posologie')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="frequence" class="form-label">Fréquence <span class="text-red-500">*</span></label>
                            <input type="text" name="frequence" id="frequence" 
                                   class="form-control @error('frequence') is-invalid @enderror" 
                                   value="{{ old('frequence') }}" required>
                            @error('frequence')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="duree_jours" class="form-label">Durée (en jours) <span class="text-red-500">*</span></label>
                            <input type="number" name="duree_jours" id="duree_jours" min="1" 
                                   class="form-control @error('duree_jours') is-invalid @enderror" 
                                   value="{{ old('duree_jours', 7) }}" required>
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
                              class="form-control @error('instructions') is-invalid @enderror" required>{{ old('instructions') }}</textarea>
                    @error('instructions')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de prescription -->
                <div class="form-group">
                    <label for="date_prescription" class="form-label">Date de prescription <span class="text-red-500">*</span></label>
                    <input type="date" name="date_prescription" id="date_prescription" 
                           class="form-control @error('date_prescription') is-invalid @enderror" 
                           value="{{ old('date_prescription', now()->format('Y-m-d')) }}" required>
                    @error('date_prescription')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('medecin.prescriptions.index') }}" class="btn btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Créer la prescription
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