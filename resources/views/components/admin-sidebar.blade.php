<div class="fixed top-0 left-0 w-64 bg-gradient-to-b from-green-600 to-green-800 shadow-md min-h-screen flex flex-col">
    <!-- En-tête -->
    <div class="p-4">
        <h2 class="text-4xl font-bold text-white">MedPACS</h2>
    </div>

    <!-- Menu de navigation principal -->
    <nav class="flex-1 px-4 py-2 overflow-y-auto">
        <div class="space-y-3">
    <a href="{{ route('admin.dashboard') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-green-700 font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-green-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Tableau de bord</a>
    <a href="{{ route('admin.medecins.index') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-green-700 font-semibold {{ request()->routeIs('admin.medecins.*') ? 'bg-green-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Gérer les médecins</a>
    <a href="{{ route('admin.secretaires.index') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-green-700 font-semibold {{ request()->routeIs('admin.secretaires.*') ? 'bg-green-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Gérer les secrétaires</a>
    <a href="{{ route('admin.infirmiers.index') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-green-700 font-semibold {{ request()->routeIs('admin.infirmiers.*') ? 'bg-green-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Gérer les infirmiers</a>
    <a href="{{ route('admin.hopitaux.index') }}" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-green-700 font-semibold {{ request()->routeIs('admin.hopitaux.*') ? 'bg-green-700 text-white border-l-4 border-white pl-2' : 'text-white' }}">Gérer les hôpitaux</a>
    <a href="#" class="w-full px-3 py-2 rounded-md transition duration-200 hover:bg-green-700 font-semibold text-white">Voir les logs</a>
        </div>
    </nav>

    <!-- Bouton de déconnexion -->
    <div class="sticky bottom-0 p-4 border-t border-green-700 bg-gradient-to-b from-green-600 to-green-800">
    <form method="POST" action="{{ route('logout') }}" class="w-full">
        @csrf
            <button type="submit" class="w-full flex items-center px-4 py-2 text-white hover:bg-green-700 rounded-lg transition duration-200">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span class="ml-3">Déconnexion</span>
        </button>
    </form>
</div>
</div>
