<nav class="p-3 text-black ml-64" style="background-color: rgba(128, 128, 128, 0.05);">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-4">
            @auth
                <span class="font-bold">Bienvenue, {{ Auth::user()->name ?? 'Utilisateur'}}!</span>
            @endauth
        </div>
        <div class="flex items-center space-x-4">
            @auth
                <div class="w-8 h-8 rounded-full bg-white text-teal-600 flex items-center justify-center mr-3">
                    <span class="font-bold">{{ Auth::user()->initials ?? 'U' }}</span>
                </div>
            @else
                <a href="{{ route('login') }}">Connexion</a>
            @endauth
        </div>
    </div>
</nav>
