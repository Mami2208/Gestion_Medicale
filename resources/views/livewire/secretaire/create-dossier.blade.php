<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Créer un dossier médical</h1>

    @if(session()->has('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 shadow-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="bg-white rounded-lg shadow-lg p-6">
        <!-- Section 1: Informations de base du dossier -->
        <div class="mb-8 border-b pb-4">
            <h2 class="text-2xl font-semibold mb-4 text-blue-800">Informations de base</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Choix entre patient existant ou nouveau patient -->
                <div class="col-span-2">
                    <div class="flex items-center space-x-4 mb-4">
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model="creation_patient" value="0" class="form-radio h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Sélectionner un patient existant</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model="creation_patient" value="1" class="form-radio h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Créer un nouveau patient</span>
                        </label>
                    </div>
                </div>
                
                <!-- Sélection d'un patient existant -->
                @if(!$creation_patient)
                <div class="col-span-2">
                    <label for="patient_id" class="block text-gray-700 font-semibold mb-2">Patient</label>
                    <select id="patient_id" wire:model="patient_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                        <option value="">Sélectionnez un patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">
                                {{ $patient->utilisateur->nom ?? '' }} {{ $patient->utilisateur->prenom ?? '' }}
                                @if(isset($patient->utilisateur->nom))
                                    ({{ $patient->date_naissance ? $patient->date_naissance->format('d/m/Y') : 'N/A' }})
                                @else
                                    (Patient #{{ $patient->id }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                @endif
                
                <!-- Création d'un nouveau patient -->
                @if($creation_patient)
                <div class="col-span-2">
                    <h3 class="text-lg font-semibold mb-3 text-blue-700">Informations du nouveau patient</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom</label>
                            <input type="text" id="nom" wire:model.defer="nom" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                            @error('nom') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom</label>
                            <input type="text" id="prenom" wire:model.defer="prenom" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                            @error('prenom') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="date_naissance" class="block text-gray-700 font-semibold mb-2">Date de naissance</label>
                            <input type="date" id="date_naissance" wire:model.defer="date_naissance" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                            @error('date_naissance') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="sexe" class="block text-gray-700 font-semibold mb-2">Sexe</label>
                            <select id="sexe" wire:model.defer="sexe" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                                <option value="">Sélectionnez</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                                <option value="Autre">Autre</option>
                            </select>
                            @error('sexe') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="telephone" class="block text-gray-700 font-semibold mb-2">Téléphone</label>
                            <input type="tel" id="telephone" wire:model.defer="telephone" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                            @error('telephone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" id="email" wire:model.defer="email" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="col-span-2">
                            <label for="adresse" class="block text-gray-700 font-semibold mb-2">Adresse</label>
                            <textarea id="adresse" wire:model.defer="adresse" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200"></textarea>
                            @error('adresse') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Numéro de dossier -->
                <div>
                    <label for="numero_dossier" class="block text-gray-700 font-semibold mb-2">Numéro de dossier</label>
                    <input type="text" id="numero_dossier" wire:model.defer="numero_dossier" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200" readonly>
                    @error('numero_dossier') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        
        <!-- Section 2: Informations médicales -->
        <div class="mb-8 border-b pb-4">
            <h2 class="text-2xl font-semibold mb-4 text-blue-800">Informations médicales essentielles</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="poids" class="block text-gray-700 font-semibold mb-2">Poids (kg)</label>
                    <input type="number" id="poids" wire:model.defer="poids" step="0.1" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                    @error('poids') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="taille" class="block text-gray-700 font-semibold mb-2">Taille (cm)</label>
                    <input type="number" id="taille" wire:model.defer="taille" step="0.1" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                    @error('taille') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="groupe_sanguin" class="block text-gray-700 font-semibold mb-2">Groupe sanguin</label>
                    <select id="groupe_sanguin" wire:model.defer="groupe_sanguin" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                        <option value="">Sélectionnez</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    @error('groupe_sanguin') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <!-- Allergies -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Allergies</label>
                @foreach($allergies as $index => $allergie)
                <div class="flex items-center mb-2">
                    <input type="text" wire:model.defer="allergies.{{ $index }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                    <button type="button" wire:click="removeAllergie({{ $index }})" class="ml-2 text-red-500 hover:text-red-700 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                @endforeach
                <button type="button" wire:click="addAllergie" class="mt-1 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition duration-200">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter une allergie
                </button>
            </div>
            
            <!-- Antécédents médicaux -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Antécédents médicaux</label>
                @foreach($antecedents_medicaux as $index => $antecedent)
                <div class="flex items-center mb-2">
                    <input type="text" wire:model.defer="antecedents_medicaux.{{ $index }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                    <button type="button" wire:click="removeAntecedent({{ $index }})" class="ml-2 text-red-500 hover:text-red-700 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                @endforeach
                <button type="button" wire:click="addAntecedent" class="mt-1 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition duration-200">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter un antécédent médical
                </button>
            </div>
        </div>
        
        <!-- Section 3: Informations complémentaires -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-800">Informations complémentaires</h2>
            
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-semibold mb-2">Description / Motif de consultation</label>
                <textarea id="description" wire:model.defer="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200"></textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="mb-4">
                <label for="observations" class="block text-gray-700 font-semibold mb-2">Observations médicales</label>
                <textarea id="observations" wire:model.defer="observations" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200"></textarea>
                @error('observations') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <!-- Traitements en cours -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Traitements en cours</label>
                @foreach($traitements_en_cours as $index => $traitement)
                <div class="flex items-center mb-2">
                    <input type="text" wire:model.defer="traitements_en_cours.{{ $index }}" class="flex-1 border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-200">
                    <button type="button" wire:click="removeTraitement({{ $index }})" class="ml-2 text-red-500 hover:text-red-700 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                @endforeach
                <button type="button" wire:click="addTraitement" class="mt-1 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition duration-200">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter un traitement
                </button>
            </div>
        </div>
        
        <div class="flex justify-end space-x-4">
            <button type="button" wire:click="$refresh" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 font-semibold">
                Annuler
            </button>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-semibold shadow-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Créer le dossier médical
                </div>
            </button>
        </div>
    </form>
</div>
