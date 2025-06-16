<div class="container mx-auto p-6 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">Suivi des Rendez-vous</h1>

    @if($rdvs->isEmpty())
        <p>Aucun rendez-vous trouvé.</p>
    @else
        <div class="space-y-4">
            @foreach($rdvs as $rdv)
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">Rendez-vous #{{ $rdv->id }}</h2>
                    <p>Date: {{ \Carbon\Carbon::parse($rdv->date)->format('d/m/Y H:i') }}</p>
                    <p>Statut: {{ $rdv->statut ?? 'Non spécifié' }}</p>
                    <p>Commentaires: {{ $rdv->commentaires ?? 'Aucun' }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
