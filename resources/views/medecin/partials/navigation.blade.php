<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Search Bar -->
                <div class="flex-1 flex items-center">
                    <div class="w-full max-w-lg lg:max-w-xs">
                        <label for="search" class="sr-only">Rechercher</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input id="search" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Rechercher..." type="search">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <!-- Notifications -->
                <div class="ml-4 flex items-center md:ml-6">
                    <button class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="sr-only">Voir les notifications</span>
                        <i class="fas fa-bell"></i>
                    </button>

                    <!-- Profile dropdown -->
                    <div class="ml-3 relative">
                        <div>
                            <button type="button" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button">
                                <span class="sr-only">Ouvrir le menu utilisateur</span>
                                <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->photo ?? asset('images/default-avatar.png') }}" alt="{{ auth()->user()->nom }}">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav> 