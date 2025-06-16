<div>
    <label for="specialite" class="block font-medium text-sm text-gray-700">Filtrer par spécialité</label>
    <select wire:model="specialiteId" id="specialite" class="form-select mt-1 block w-full">
        <option value="">-- Sélectionnez une spécialité --</option>
        @foreach($specialites as $specialite)
            <option value="{{ $specialite->id }}">{{ $specialite->nom }}</option>
        @endforeach
    </select>

    <div class="mt-4">
        @if($patients->isEmpty())
            <p>Aucun patient trouvé pour cette spécialité.</p>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach($patients as $patient)
                    <li class="py-2 cursor-pointer hover:bg-gray-100" wire:click="$emit('patientSelected', {{ $patient->id }})">
                        {{ $patient->nom }} {{ $patient->prenom }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
