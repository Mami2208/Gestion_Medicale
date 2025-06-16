@extends('layouts.infirmier')

@section('title', 'Nouveau Traitement')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('infirmier.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('infirmier.patients.show', $patient->id) }}">{{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nouveau Traitement</li>
                </ol>
            </nav>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Nouveau Traitement</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('infirmier.traitements.store', $patient->id) }}" method="POST" id="traitementForm">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type_traitement" class="form-label">Type de traitement</label>
                                <select class="form-select @error('type_traitement') is-invalid @enderror" id="type_traitement" name="type_traitement" required>
                                    <option value="">Sélectionnez un type de traitement</option>
                                    @foreach(\App\Models\Traitement::TYPES as $value => $label)
                                        <option value="{{ $value }}" {{ old('type_traitement') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type_traitement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_debut" class="form-label">Date de début</label>
                                <input type="date" class="form-control @error('date_debut') is-invalid @enderror" id="date_debut" name="date_debut" value="{{ old('date_debut', now()->format('Y-m-d')) }}" required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_fin" class="form-label">Date de fin (optionnel)</label>
                                <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" name="date_fin" value="{{ old('date_fin') }}">
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Section Médicaments (affichée uniquement si le type est MEDICAMENT) -->
                        <div id="medicaments-section" style="display: none;">
                            <h5 class="mb-3">Médicaments</h5>
                            <div id="medicaments-container">
                                <!-- Les champs de médicaments seront ajoutés ici dynamiquement -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-medicament">
                                <i class="fas fa-plus"></i> Ajouter un médicament
                            </button>
                        </div>

                        <div class="mb-3">
                            <label for="observations" class="form-label">Observations (optionnel)</label>
                            <textarea class="form-control @error('observations') is-invalid @enderror" id="observations" name="observations" rows="2">{{ old('observations') }}</textarea>
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('infirmier.patients.show', $patient->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer le traitement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template pour l'ajout dynamique de médicaments -->
<template id="medicament-template">
    <div class="card mb-3 medicament-item">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Médicament</label>
                    <select class="form-select medicament-select" name="medicaments[0][id]" required>
                        <option value="">Sélectionnez un médicament</option>
                        @foreach($medicaments as $medicament)
                            <option value="{{ $medicament->id }}">{{ $medicament->nom }} @if($medicament->dosage) ({{ $medicament->dosage }}) @endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Posologie</label>
                    <input type="text" class="form-control" name="medicaments[0][posologie]" placeholder="Ex: 1 comprimé" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Fréquence</label>
                    <input type="text" class="form-control" name="medicaments[0][frequence]" placeholder="Ex: 3 fois/jour" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Durée (jours)</label>
                    <input type="number" class="form-control" name="medicaments[0][duree_jours]" min="1" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-medicament">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <label class="form-label">Instructions (optionnel)</label>
                    <input type="text" class="form-control" name="medicaments[0][instructions]" placeholder="Instructions particulières">
                </div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeTraitement = document.getElementById('type_traitement');
        const medicamentsSection = document.getElementById('medicaments-section');
        const medicamentsContainer = document.getElementById('medicaments-container');
        const addMedicamentBtn = document.getElementById('add-medicament');
        const medicamentTemplate = document.getElementById('medicament-template');
        let medicamentCount = 0;

        // Afficher/masquer la section des médicaments en fonction du type de traitement
        function toggleMedicamentsSection() {
            if (typeTraitement.value === 'MEDICAMENT') {
                medicamentsSection.style.display = 'block';
                if (medicamentCount === 0) {
                    addMedicament();
                }
            } else {
                medicamentsSection.style.display = 'none';
                medicamentsContainer.innerHTML = '';
                medicamentCount = 0;
            }
        }

        // Ajouter un nouveau champ de médicament
        function addMedicament() {
            const newMedicament = medicamentTemplate.content.cloneNode(true);
            const newIndex = medicamentCount;
            
            // Mettre à jour les noms des champs avec le nouvel index
            newMedicament.querySelectorAll('[name]').forEach(el => {
                const name = el.getAttribute('name').replace('[0]', `[${newIndex}]`);
                el.setAttribute('name', name);
            });
            
            medicamentsContainer.appendChild(newMedicament);
            medicamentCount++;
        }

        // Supprimer un champ de médicament
        function removeMedicament(button) {
            if (medicamentCount > 1) {
                button.closest('.medicament-item').remove();
                // Réorganiser les index
                const items = document.querySelectorAll('.medicament-item');
                items.forEach((item, index) => {
                    item.querySelectorAll('[name]').forEach(el => {
                        const name = el.getAttribute('name').replace(/\[\d+\]/, `[${index}]`);
                        el.setAttribute('name', name);
                    });
                });
                medicamentCount--;
            }
        }

        // Événements
        typeTraitement.addEventListener('change', toggleMedicamentsSection);
        
        addMedicamentBtn.addEventListener('click', addMedicament);
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-medicament') || e.target.closest('.remove-medicament')) {
                const button = e.target.classList.contains('remove-medicament') ? e.target : e.target.closest('.remove-medicament');
                removeMedicament(button);
            }
        });

        // Initialisation
        toggleMedicamentsSection();
    });
</script>
@endpush

@endsection
