<div class="container mx-auto p-6 max-w-6xl">
    <h1 class="text-3xl font-bold mb-6">Cas Anonymisés</h1>

    @if(session()->has('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <h2 class="text-xl font-semibold mb-4">Liste des cas anonymisés</h2>
            <ul class="space-y-2">
                @foreach($cases as $case)
                    <li>
                        <button wire:click="loadDicomFiles({{ $case->id }})" class="text-blue-600 hover:underline">
                            Cas #{{ $case->id }} - {{ $case->description }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="md:col-span-2">
            @if($selectedCaseId)
                <h2 class="text-xl font-semibold mb-4">Images DICOM du cas #{{ $selectedCaseId }}</h2>
                @if($dicomFiles->isEmpty())
                    <p>Aucune image DICOM trouvée pour ce cas.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($dicomFiles as $image)
                            <div class="bg-white p-4 rounded shadow">
                                <h3 class="font-semibold mb-2">Image #{{ $image->id }}</h3>
                                <img src="{{ asset('storage/' . $image->filepath) }}" alt="Image DICOM #{{ $image->id }}" class="w-full h-auto mb-2">
                                <button wire:click="downloadDicom({{ $image->id }})" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Télécharger</button>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <p>Sélectionnez un cas pour voir les images DICOM.</p>
            @endif
        </div>
    </div>
</div>
