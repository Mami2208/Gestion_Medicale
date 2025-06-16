<div class="container mx-auto p-6 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">Images DICOM</h1>

    @if($images->isEmpty())
        <p>Aucune image DICOM trouvée.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($images as $image)
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">Image #{{ $image->id }}</h2>
                    <img src="{{ asset('storage/' . $image->filepath) }}" alt="Image DICOM #{{ $image->id }}" class="w-full h-auto">
                    <p class="text-sm text-gray-500">Date: {{ $image->created_at->format('d/m/Y') }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
