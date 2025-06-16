@extends('layouts.app')

@section('styles')
<style>
    :root {
        --medical-primary: #2c3e50;
        --medical-secondary: #34495e;
        --medical-accent: #3498db;
        --medical-success: #27ae60;
        --medical-warning: #f1c40f;
        --medical-danger: #e74c3c;
    }

    .medical-section-header {
        background: linear-gradient(135deg, var(--medical-primary), var(--medical-secondary));
        color: white;
        padding: 1rem;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .medical-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .medical-input:focus,
    .medical-select:focus,
    .medical-textarea:focus {
        border-color: var(--medical-accent);
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    .medical-label {
        color: var(--medical-primary);
    }

    /* Styles pour les messages d'erreur */
    .medical-error {
        background-color: var(--medical-danger);
        color: white;
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-top: 0.25rem;
    }

    /* Style pour les champs en erreur */
    .medical-input.error,
    .medical-select.error,
    .medical-textarea.error {
        border-color: var(--medical-danger) !important;
        box-shadow: 0 0 0 0.25rem rgba(231, 76, 60, 0.25) !important;
    }

    /* Style pour les messages de succès */
    .medical-success {
        background-color: var(--medical-success);
        color: white;
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-top: 0.25rem;
    }

    /* Style pour les messages de warning */
    .medical-warning {
        background-color: var(--medical-warning);
        color: white;
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-top: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 pt-20">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto medical-card">
            <div class="p-6">
                <h1 class="text-2xl font-semibold text-blue-800 mb-6">Nouvel Examen Médical</h1>

                <!-- Messages globaux -->
                @if(session('success'))
                    <div class="medical-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="medical-error mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('medecin.examens.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Informations Patient -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="medical-section-header">
                            <h3 class="font-medium">Informations Patient</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <label for="patient_id" class="medical-label block mb-1">Patient</label>
                                <select id="patient_id" name="patient_id" class="medical-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('patient_id') error @enderror">
                                    <option value="">Sélectionnez un patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <div class="medical-error mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informations Examen -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="medical-section-header">
                            <h3 class="font-medium">Informations Examen</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <label for="type" class="medical-label block mb-1">Type d'examen</label>
                                <select id="type" name="type" class="medical-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('type') error @enderror">
                                    <option value="">Sélectionnez un type d'examen</option>
                                    <option value="consultation_generale" {{ old('type') == 'consultation_generale' ? 'selected' : '' }}>Consultation générale</option>
                                    <option value="examen_physique" {{ old('type') == 'examen_physique' ? 'selected' : '' }}>Examen physique</option>
                                    <option value="consultation_specialisee" {{ old('type') == 'consultation_specialisee' ? 'selected' : '' }}>Consultation spécialisée</option>
                                    <option value="examen_complementaire" {{ old('type') == 'examen_complementaire' ? 'selected' : '' }}>Examen complémentaire</option>
                                    <option value="examen_biochimique" {{ old('type') == 'examen_biochimique' ? 'selected' : '' }}>Examen biochimique</option>
                                    <option value="examen_radiologique" {{ old('type') == 'examen_radiologique' ? 'selected' : '' }}>Examen radiologique</option>
                                    <option value="examen_endoscopique" {{ old('type') == 'examen_endoscopique' ? 'selected' : '' }}>Examen endoscopique</option>
                                    <option value="examen_cardiologique" {{ old('type') == 'examen_cardiologique' ? 'selected' : '' }}>Examen cardiologique</option>
                                    <option value="examen_neurologique" {{ old('type') == 'examen_neurologique' ? 'selected' : '' }}>Examen neurologique</option>
                                    <option value="examen_ophtalmologique" {{ old('type') == 'examen_ophtalmologique' ? 'selected' : '' }}>Examen ophtalmologique</option>
                                    <option value="examen_orthopedique" {{ old('type') == 'examen_orthopedique' ? 'selected' : '' }}>Examen orthopédique</option>
                                    <option value="examen_dentaire" {{ old('type') == 'examen_dentaire' ? 'selected' : '' }}>Examen dentaire</option>
                                    <option value="examen_dermatologique" {{ old('type') == 'examen_dermatologique' ? 'selected' : '' }}>Examen dermatologique</option>
                                </select>
                                @error('type')
                                    <div class="medical-error mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="date" class="medical-label block mb-1">Date d'examen</label>
                                <input type="date" name="date" id="date" 
                                       value="{{ old('date') }}"
                                       class="medical-input mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('date') error @enderror">
                                @error('date')
                                    <div class="medical-error mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="statut" class="medical-label block mb-1">Statut</label>
                                <select id="statut" name="statut" class="medical-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('statut') error @enderror">
                                    <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="termine" {{ old('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                                    <option value="annule" {{ old('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                                </select>
                                @error('statut')
                                    <div class="medical-error mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="medical-label block mb-1">Description</label>
                                <textarea name="description" id="description" rows="3"
                                          class="medical-textarea mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('description') error @enderror"
                                          placeholder="Description de l'examen...">
                                    {{ old('description') }}
                                </textarea>
                                @error('description')
                                    <div class="medical-error mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="conclusion" class="medical-label block mb-1">Conclusion</label>
                                <textarea name="conclusion" id="conclusion" rows="3"
                                          class="medical-textarea mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('conclusion') error @enderror"
                                          placeholder="Conclusion de l'examen...">
                                    {{ old('conclusion') }}
                                </textarea>
                                @error('conclusion')
                                    <div class="medical-error mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('medecin.examens.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i> Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Créer l'examen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection