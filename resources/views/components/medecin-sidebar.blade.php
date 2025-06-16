<div class="w-64 bg-gradient-to-b from-green-600 to-green-800 shadow-md h-screen p-4">
    <h2 class="text-xl font-bold mb-6 text-white">Menu Médecin</h2>
    <nav class="flex flex-col space-y-3">
        <a href="{{ route('medecin.dashboard') }}" class="text-white hover:bg-green-700 font-semibold px-3 py-2 rounded transition-colors">Tableau de bord</a>
        <a href="{{ route('medecin.dashboard') }}#appointments" class="text-white hover:bg-green-700 px-3 py-2 rounded transition-colors">Rendez-vous</a>
        {{-- <a href="{{ route('dicom.viewer', ['orthancId' => '']) }}" class="text-white hover:bg-green-700 px-3 py-2 rounded transition-colors">Images DICOM</a> --}}
        {{-- Upload link removed as per request --}}
        <a href="{{ route('medecin.dashboard') }}#treatments" class="text-white hover:bg-green-700 px-3 py-2 rounded transition-colors">Traitements</a>
        <a href="{{ route('medecin.dashboard') }}#notifications" class="text-white hover:bg-green-700 px-3 py-2 rounded transition-colors">Notifications</a>
    </nav>
</div>
