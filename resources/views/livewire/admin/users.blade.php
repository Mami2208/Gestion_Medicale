<div class="container mx-auto p-6 max-w-6xl">
    <h1 class="text-3xl font-bold mb-6">Gestion des Utilisateurs</h1>

    <table class="min-w-full bg-white border border-gray-300 rounded">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Nom</th>
                <th class="py-2 px-4 border-b">Prénom</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Rôle</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                <td class="py-2 px-4 border-b">{{ $user->nom }}</td>
                <td class="py-2 px-4 border-b">{{ $user->prenom }}</td>
                <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                <td class="py-2 px-4 border-b">{{ $user->role }}</td>
                <td class="py-2 px-4 border-b">
                    <!-- Actions like edit, delete can be added here -->
                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Supprimer</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
