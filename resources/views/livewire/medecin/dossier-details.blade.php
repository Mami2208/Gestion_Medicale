<div>
    @if($dossier)
        <h3 class="text-lg font-semibold mb-2">Dossier médical de {{ $dossier->patient->nom ?? '' }} {{ $dossier->patient->prenom ?? '' }}</h3>
        <p>{{ $dossier->description ?? 'Aucune description disponible' }}</p>

        <h4 class="mt-4 font-semibold">Images DICOM associées</h4>
        @if($dicomImages->isEmpty())
            <p>Aucune image DICOM disponible pour ce patient.</p>
        @else
            <ul class="list-disc list-inside">
                @foreach($dicomImages as $image)
                    <li>
                        <a href="{{ url('/view/' . $image->dicom->orthanc_id) }}" target="_blank" class="text-blue-600 hover:underline">
                            Étude du {{ $image->dicom->study_date ?? 'date inconnue' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    @else
        <p>Sélectionnez un patient pour voir les détails du dossier médical.</p>
    @endif
</div>
