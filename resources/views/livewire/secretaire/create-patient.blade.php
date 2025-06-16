<div class="container mx-auto p-6 max-w-lg">
    <h1 class="text-3xl font-bold mb-6">Créer un compte patient</h1>

    @if(session()->has('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="nom" class="block text-gray-700 font-bold mb-2">Nom</label>
            <input type="text" id="nom" wire:model.defer="nom" required class="w-full border border-gray-300 rounded px-3 py-2">
            @error('nom') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="prenom" class="block text-gray-700 font-bold mb-2">Prénom</label>
            <input type="text" id="prenom" wire:model.defer="prenom" required class="w-full border border-gray-300 rounded px-3 py-2">
            @error('prenom') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input type="email" id="email" wire:model.defer="email" required class="w-full border border-gray-300 rounded px-3 py-2">
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-bold mb-2">Mot de passe</label>
            <input type="password" id="password" wire:model.defer="password" required class="w-full border border-gray-300 rounded px-3 py-2">
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" wire:model.defer="password_confirmation" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer le compte</button>
    </form>
</div>
