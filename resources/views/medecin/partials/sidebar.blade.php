<aside class="fixed top-0 left-0 h-screen w-64 bg-gradient-to-b from-green-600 to-green-800 border-r border-gray-200 z-40 transition-transform duration-300 ease-in-out transform -translate-x-full lg:translate-x-0">
    <!-- Logo et titre -->
    <div class="h-16 flex items-center justify-center border-b border-green-700">
        <a href="{{ route('medecin.dashboard') }}" class="flex items-center space-x-2">
            <i class="fas fa-hospital text-white text-2xl"></i>
            <span class="text-xl font-bold text-white">DICOM Médical</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-4">
        <div class="space-y-2">
            <!-- Tableau de bord -->
            <a href="{{ route('medecin.dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.dashboard') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span>Tableau de bord</span>
            </a>

            <!-- Patients -->
            <a href="{{ route('medecin.patients.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.patients.*') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-users w-5"></i>
                <span>Patients</span>
            </a>

            <!-- Rendez-vous -->
            <a href="{{ route('medecin.rendez-vous.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.rendez-vous.*') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-calendar-alt w-5"></i>
                <span>Rendez-vous</span>
            </a>

            <!-- Consultations -->
            <a href="{{ route('medecin.consultations.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.consultations.*') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-stethoscope w-5"></i>
                <span>Consultations</span>
            </a>

            <!-- Dossiers médicaux -->
            <a href="{{ route('medecin.dossiers.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.dossiers.*') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-folder-medical w-5"></i>
                <span>Dossiers médicaux</span>
            </a>

            <!-- Délégations d'accès -->
            <a href="{{ route('medecin.delegations.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.delegations.*') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-share-alt w-5"></i>
                <span>Délégations d'accès</span>
            </a>

            <!-- Prescriptions -->
            <a href="{{ route('medecin.prescriptions.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.prescriptions.*') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-prescription w-5"></i>
                <span>Prescriptions</span>
            </a>

            <!-- Examens -->
            <a href="{{ route('medecin.examens.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.examens.*') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-vial w-5"></i>
                <span>Examens</span>
            </a>

            <!-- Statistiques -->
            <a href="{{ route('medecin.statistiques.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.statistiques.*') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-chart-bar w-5"></i>
                <span>Statistiques</span>
            </a>

            <!-- Paramètres -->
            <a href="{{ route('medecin.parametres.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('medecin.parametres.*') ? 'bg-white text-green-600 font-medium' : 'text-white hover:bg-green-700' }}">
                <i class="fas fa-cog w-5"></i>
                <span>Paramètres</span>
            </a>
        </div>
    </nav>

    <!-- Version -->
    <div class="absolute bottom-0 left-0 right-0 p-4 text-center text-sm text-white opacity-50">
        Version 1.0.0
    </div>
</aside>

@push('styles')
<style>
    @media (max-width: 1024px) {
        aside.show {
            transform: translateX(0);
        }
    }
</style>
@endpush 