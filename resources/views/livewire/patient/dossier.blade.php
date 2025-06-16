<div class="container mx-auto p-6 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">Dossier Médical</h1>

    @if($dossiers->isEmpty())
        <p>Aucun dossier médical trouvé.</p>
    @else
        <div class="space-y-4">
            @foreach($dossiers as $dossier)
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">Dossier #{{ $dossier->id }}</h2>
                    <p>{{ $dossier->description }}</p>
                    <p class="text-sm text-gray-500">Créé le {{ $dossier->created_at->format('d/m/Y') }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
