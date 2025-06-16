@extends('layouts.admin')

@section('title', 'Journaux d\'activité')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h4 class="text-xl font-semibold text-gray-800">Journaux d'activité</h4>
                </div>
                <div class="p-6 bg-white">
                    <!-- Filtres de recherche -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <h5 class="text-lg font-medium text-gray-700 mb-3">Filtres de recherche</h5>
                        <form action="{{ route('admin.activity-logs.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Utilisateur</label>
                                <select name="user_id" id="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                                    <option value="">Tous</option>
                                    @foreach(App\Models\Utilisateur::orderBy('nom')->get() as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->nom }} {{ $user->prenom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                                <input type="text" name="action" id="action" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" value="{{ request('action') }}">
                            </div>
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                                    <option value="">Tous</option>
                                    <option value="auth" {{ request('type') == 'auth' ? 'selected' : '' }}>Authentification</option>
                                    <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                                    <option value="patient" {{ request('type') == 'patient' ? 'selected' : '' }}>Patient</option>
                                    <option value="dossier" {{ request('type') == 'dossier' ? 'selected' : '' }}>Dossier médical</option>
                                    <option value="rdv" {{ request('type') == 'rdv' ? 'selected' : '' }}>Rendez-vous</option>
                                </select>
                            </div>
                            <div>
                                <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                                <input type="date" name="date_start" id="date_start" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" value="{{ request('date_start') }}">
                            </div>
                            <div>
                                <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                                <input type="date" name="date_end" id="date_end" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" value="{{ request('date_end') }}">
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors">Filtrer</button>
                                <a href="{{ route('admin.activity-logs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors">Réinitialiser</a>
                            </div>
                        </form>
                    </div>

                    <!-- Tableau des journaux -->
                    <div class="mt-6 overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Utilisateur</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Action</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Adresse IP</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $log->user ? $log->user->nom.' '.$log->user->prenom : 'Système' }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @php
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            if ($log->type == 'auth') {
                                                $badgeClass = 'bg-blue-100 text-blue-800';
                                            } elseif ($log->type == 'user') {
                                                $badgeClass = 'bg-purple-100 text-purple-800';
                                            } elseif ($log->type == 'patient') {
                                                $badgeClass = 'bg-green-100 text-green-800';
                                            } elseif ($log->type == 'dossier') {
                                                $badgeClass = 'bg-yellow-100 text-yellow-800';
                                            } elseif ($log->type == 'rdv') {
                                                $badgeClass = 'bg-red-100 text-red-800';
                                            }
                                        @endphp
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $log->type }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $log->action }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $log->description }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $log->ip_address }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-gray-500 italic">Aucun journal d'activité trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex justify-center">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
        gap: 0.25rem;
    }
    .pagination > div > span,
    .pagination > div > a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        height: 2rem;
        padding: 0 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
    }
    .pagination > div > span {
        background-color: #3b82f6;
        color: white;
    }
    .pagination > div > a {
        background-color: #f9fafb;
        color: #374151;
        border: 1px solid #d1d5db;
    }
    .pagination > div > a:hover {
        background-color: #f3f4f6;
        color: #1f2937;
    }
</style>
@endpush
