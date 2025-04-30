<div class="w-64 bg-white shadow-md h-screen p-4">
    <h2 class="text-xl font-bold mb-6">Menu Médecin</h2>
    <nav class="flex flex-col space-y-3">
        <a href="{{ route('medecin.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-semibold">Tableau de bord</a>
        <a href="{{ route('medecin.dashboard') }}#appointments" class="text-gray-700 hover:text-blue-600">Rendez-vous</a>
        {{-- <a href="{{ route('dicom.viewer', ['orthancId' => '']) }}" class="text-gray-700 hover:text-blue-600">Images DICOM</a> --}}
        {{-- Upload link removed as per request --}}
        <a href="{{ route('medecin.dashboard') }}#treatments" class="text-gray-700 hover:text-blue-600">Traitements</a>
        <a href="{{ route('medecin.dashboard') }}#notifications" class="text-gray-700 hover:text-blue-600">Notifications</a>
    </nav>
</div>
