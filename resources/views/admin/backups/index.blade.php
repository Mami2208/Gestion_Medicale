@extends('layouts.admin')

@section('title', 'Sauvegardes du système')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h1 class="text-xl font-semibold text-gray-800">Sauvegardes du système</h1>
        
        <form action="{{ route('admin.backups.create') }}" method="POST" class="flex">
            @csrf
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                Créer une sauvegarde
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-6 mt-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 mx-6 mt-4" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="p-6">
        @if(count($backups) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nom du fichier
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date de création
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Taille
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($backups as $backup)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $backup['filename'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $backup['date'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($backup['size'] / 1048576, 2) }} MB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.backups.download', $backup['filename']) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-download"></i>
                                </a>
                                
                                <form action="{{ route('admin.backups.delete', $backup['filename']) }}" method="POST"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-database text-5xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Aucune sauvegarde disponible</h3>
            <p class="mt-1 text-sm text-gray-500">
                Créez votre première sauvegarde en cliquant sur le bouton "Créer une sauvegarde"
            </p>
        </div>
        @endif
    </div>
    
    <div class="p-6 border-t border-gray-200 bg-gray-50">
        <h2 class="text-lg font-semibold mb-4">Informations sur les sauvegardes</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="font-medium text-gray-800 mb-2">Ce qui est inclus dans la sauvegarde</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Base de données complète (patients, rendez-vous, dossiers, etc.)</li>
                    <li>Fichiers uploadés par les utilisateurs</li>
                    <li>Images médicales et autres documents</li>
                </ul>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="font-medium text-gray-800 mb-2">Bonnes pratiques</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Effectuez des sauvegardes régulières (hebdomadaires)</li>
                    <li>Stockez les sauvegardes téléchargées dans un endroit sécurisé</li>
                    <li>Conservez plusieurs versions de sauvegardes</li>
                    <li>Testez périodiquement la restauration des sauvegardes</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
