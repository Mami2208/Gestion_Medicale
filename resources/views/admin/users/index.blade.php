@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@push('styles')
<style>
    .pagination-container nav {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
    .pagination-container nav > div {
        display: flex;
        align-items: center;
    }
    .pagination-container nav span.px-4 {
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
        border: 1px solid #e2e8f0;
        background-color: white;
        color: #4a5568;
        cursor: pointer;
    }
    .pagination-container nav span.px-4:hover {
        background-color: #edf2f7;
    }
    .pagination-container nav span.bg-blue-50 {
        background-color: #4299e1;
        color: white;
        border-color: #4299e1;
    }
    .pagination-container nav a {
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
        border: 1px solid #e2e8f0;
        background-color: white;
        color: #4a5568;
        cursor: pointer;
        text-decoration: none;
    }
    .pagination-container nav a:hover {
        background-color: #edf2f7;
    }
</style>
@endpush

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Liste des utilisateurs</h2>
            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Nouvel utilisateur
            </a>
        </div>
    </div>

    <div class="p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N°</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de création</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $index => $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $user->nom }} {{ $user->prenom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $user->formatted_role }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'MEDECIN')
                                    {{ $user->medecin->matricule ?? '' }}
                                @elseif($user->role === 'INFIRMIER')
                                    {{ $user->infirmier->matricule ?? '' }}
                                @elseif($user->role === 'SECRETAIRE')
                                    {{ $user->secretaire_medical->matricule ?? '' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="pagination-container">
                    {{ $users->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection