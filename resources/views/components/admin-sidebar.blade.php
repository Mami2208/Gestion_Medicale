<div class="fixed top-0 left-0 w-64 bg-teal-600 shadow-md h-screen p-4">
    <h2 class="fixed top-10 text-4xl font-bold mb-6 text-white">MedPACS</h2>

    <!-- Menu de navigation principal -->
    <nav class="fixed top-20 flex flex-col space-y-3 mt-6 w-60 pr-4">
        <a href="{{ route('admin.dashboard') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-teal-700 font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-teal-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Tableau de bord</a>
        <a href="{{ route('admin.medecins.index') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-teal-700 font-semibold {{ request()->routeIs('admin.medecins.*') ? 'bg-teal-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Gérer les médecins</a>
        <a href="{{ route('admin.secretaires.index') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-teal-700 font-semibold {{ request()->routeIs('admin.secretaires.*') ? 'bg-teal-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Gérer les secrétaires</a>
        <a href="{{ route('admin.secretaires.index') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-teal-700 font-semibold {{ request()->routeIs('admin.secretaires.*') ? 'bg-teal-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Gérer les hôpitaux</a>
        <a href="{{ route('admin.secretaires.index') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-teal-700 font-semibold {{ request()->routeIs('admin.secretaires.*') ? 'bg-teal-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Voir les logs</a>
    </nav>

    <!-- Section de déconnexion collée tout en bas -->
    <div class="absolute bottom-0 left-0 w-full border-t border-teal-500 pt-3 pb-2">
        <div class="flex items-center mb-3 px-3">
            <div class="w-8 h-8 rounded-full bg-white text-teal-600 flex items-center justify-center mr-3">
                <span class="font-bold">{{ Auth::user()->initials ?? 'U' }}</span>
            </div>
            <div class="text-white">
                <p class="font-semibold">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                <p class="text-xs text-teal-200">{{ Auth::user()->role ?? 'Administrateur' }}</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-2 rounded-md text-white hover:bg-red-600 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Déconnexion
            </button>
        </form>
    </div>
</div>