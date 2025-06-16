<nav class="bg-green-50 border-b border-green-200 fixed w-full z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <img class="h-8 w-auto" src="{{ asset('images/logo.svg') }}" alt="Logo">
                        <span class="ml-2 text-xl font-bold text-gray-800">Gestion Médicale</span>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <button type="button" id="sidebar-toggle" class="ml-4 lg:hidden px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Search -->
                <div class="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end">
                    <div class="max-w-lg w-full lg:max-w-xs">
                        <label for="search" class="sr-only">Rechercher</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input id="search" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Rechercher..." type="search">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center">
                <!-- Notifications -->
                <div class="relative">
                    @php
                        $user = auth()->user();
                        $unreadCount = $user->unreadNotifications->count();
                        $notifications = $user->roleNotifications()->orderBy('created_at', 'desc')->limit(5)->get();
                    @endphp
                    
                    <button type="button" id="notifications-dropdown-button" class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <span class="sr-only">Voir les notifications</span>
                        <i class="fas fa-bell"></i>
                        @if($unreadCount > 0)
                            <span class="absolute top-0 right-0 block h-4 w-4 rounded-full bg-red-500 ring-2 ring-white text-xs text-white text-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </button>
                    
                    <!-- Dropdown menu des notifications -->
                    <div id="notifications-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu">
                        <div class="py-2 px-4 bg-gray-100 border-b border-gray-200">
                            <h3 class="text-sm font-medium text-gray-700">Notifications</h3>
                        </div>
                        
                        @if($notifications->count() > 0)
                            <div class="max-h-96 overflow-y-auto">
                                @foreach($notifications as $notification)
                                    <a href="{{ $notification->url ?? route('notifications.index') }}" class="block px-4 py-3 hover:bg-gray-50 transition duration-150 ease-in-out {{ $notification->lu ? '' : 'bg-blue-50' }}">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $notification->type == 'danger' ? 'bg-red-100 text-red-500' : ($notification->type == 'warning' ? 'bg-yellow-100 text-yellow-500' : ($notification->type == 'success' ? 'bg-green-100 text-green-500' : 'bg-blue-100 text-blue-500')) }}">
                                                    <i class="fas {{ $notification->icone ?? ($notification->type == 'danger' ? 'fa-exclamation-circle' : ($notification->type == 'warning' ? 'fa-exclamation-triangle' : ($notification->type == 'success' ? 'fa-check-circle' : 'fa-info-circle'))) }}"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 {{ $notification->lu ? '' : 'font-bold' }}">
                                                    {{ Str::limit($notification->titre, 35) }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ Str::limit($notification->message, 60) }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('notifications.index') }}" class="block text-center py-2 text-sm font-medium text-blue-600 bg-gray-50 hover:bg-gray-100 border-t border-gray-200">
                                Voir toutes les notifications
                            </a>
                        @else
                            <div class="py-6 text-center">
                                <p class="text-sm text-gray-500">Aucune notification</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Messages -->
                <button type="button" class="ml-3 p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <span class="sr-only">Voir les messages</span>
                    <i class="fas fa-envelope"></i>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                </button>

                <!-- Profile dropdown -->
                <div class="ml-3 relative">
                    <div>
                        <button type="button" id="user-menu-button" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Ouvrir le menu utilisateur</span>
                            <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->photo ?? asset('images/default-avatar.png') }}" alt="{{ auth()->user()->nom }}">
                        </button>
                    </div>

                    <!-- Dropdown menu -->
                    <div id="user-menu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        <a href="{{ route('medecin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                            <i class="fas fa-user mr-2"></i>
                            Mon profil
                        </a>
                        <a href="{{ route('medecin.parametres.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                            <i class="fas fa-cog mr-2"></i>
                            Paramètres
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    // Toggle user menu
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');

    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }
    
    // Toggle notifications dropdown
    const notificationsButton = document.getElementById('notifications-dropdown-button');
    const notificationsDropdown = document.getElementById('notifications-dropdown');

    if (notificationsButton && notificationsDropdown) {
        notificationsButton.addEventListener('click', function(event) {
            event.stopPropagation();
            notificationsDropdown.classList.toggle('hidden');
            // Si le menu utilisateur est ouvert, le fermer
            if (userMenu && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
            }
        });

        // Close notifications dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!notificationsButton.contains(event.target) && !notificationsDropdown.contains(event.target)) {
                notificationsDropdown.classList.add('hidden');
            }
        });
        
        // Empêcher la propagation des clics à l'intérieur du dropdown
        notificationsDropdown.addEventListener('click', function(event) {
            // Ne pas fermer le dropdown si on clique sur un élément qui n'est pas un lien
            if (!event.target.closest('a')) {
                event.stopPropagation();
            }
        });
    }
</script>
@endpush
