<nav class="bg-blue-600 p-4 text-white">
    <div class="container mx-auto flex justify-between items-center">
        <a href="/" class="text-xl font-bold">MedPACS</a>
        <div class="space-x-4">
            @auth
                @can('upload-dicom')
                <a href="/upload" class="hover:text-blue-200">Upload DICOM</a>
                @endcan
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}">Connexion</a>
            @endauth
        </div>
    </div>
</nav>
