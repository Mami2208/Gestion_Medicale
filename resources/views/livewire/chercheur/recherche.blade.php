<div class="container mx-auto p-6 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">Recherche de cas</h1>

    <input type="text" wire:model.debounce.500ms="searchTerm" placeholder="Rechercher par pathologie, modalité, patient..." class="w-full border border-gray-300 rounded px-3 py-2 mb-4" />

    @if(empty($searchTerm))
        <p>Entrez un terme de recherche pour commencer.</p>
    @elseif($results->isEmpty())
        <p>Aucun résultat trouvé.</p>
    @else
        <div class="space-y-4">
            @foreach($results as $dossier)
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">Dossier #{{ $dossier->id }}</h2>
                    <p>Description: {{ $dossier->description }}</p>
                    <p>Patient: {{ $dossier->patient->utilisateur->nom ?? 'N/A' }} {{ $dossier->patient->utilisateur->prenom ?? '' }}</p>
                    <p>Date: {{ $dossier->created_at->format('d/m/Y') }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
