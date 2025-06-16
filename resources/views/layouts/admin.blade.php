<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') - Gestion Médicale</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdown', () => ({
                open: false,
                toggle() {
                    this.open = !this.open;
                }
            }));
        });
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-primary text-white" style="background: linear-gradient(135deg, #43a047 0%, #1de9b6 100%);">
            <div class="p-4 flex items-center">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-10 mr-3">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <i class="fas fa-chart-line w-6"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <i class="fas fa-users w-6"></i>
                    <span>Gestion des utilisateurs</span>
                </a>
                <a href="{{ route('admin.logs') }}" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <i class="fas fa-history w-6"></i>
                    <span>Traçabilité & journaux</span>
                </a>
                <a href="{{ url('/admin/activity-logs-direct') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 ml-4">
                    <i class="fas fa-clipboard-list w-6"></i>
                    <span>Journaux d'activité</span>
                </a>
                <a href="{{ route('admin.security.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <i class="fas fa-shield-alt w-6"></i>
                    <span>Sécurité</span>
                </a>
                <a href="{{ route('admin.statistics.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span>Statistiques</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <i class="fas fa-cog w-6"></i>
                    <span>Configuration</span>
                </a>
                <a href="{{ route('admin.backups.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <i class="fas fa-database w-6"></i>
                    <span>Sauvegardes</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-4 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('title')</h2>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Barre de recherche -->
                        <form action="{{ route('admin.users.search') }}" method="GET" class="relative">
                            <div class="flex">
                                <input type="text" name="query" placeholder="Rechercher par nom ou prénom..." 
                                    class="w-64 rounded-l-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm"
                                    value="{{ request('query') }}">
                                <select name="role" class="rounded-r-md border-l border-gray-300 bg-gray-50 px-2 text-sm">
                                    <option value="">Tous les rôles</option>
                                    <option value="ADMIN" {{ request('role') == 'ADMIN' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="MEDECIN" {{ request('role') == 'MEDECIN' ? 'selected' : '' }}>Médecin</option>
                                    <option value="INFIRMIER" {{ request('role') == 'INFIRMIER' ? 'selected' : '' }}>Infirmier</option>
                                    <option value="SECRETAIRE" {{ request('role') == 'SECRETAIRE' ? 'selected' : '' }}>Secrétaire</option>
                                    <option value="PATIENT" {{ request('role') == 'PATIENT' ? 'selected' : '' }}>Patient</option>
                                </select>
                                <button type="submit" class="ml-2 bg-green-500 hover:bg-green-600 text-white rounded-md px-3 py-1">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Menu utilisateur -->
                        <div class="relative" x-data="dropdown">
                            <button @click="toggle()" type="button" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                                <span class="hidden md:inline">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                                <div class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center text-white">
                                    <span>{{ substr(Auth::user()->prenom, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}</span>
                                </div>
                            </button>
                            
                            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95">
                                <a href="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> Mon profil
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html> 