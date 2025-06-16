@extends('medecin.layouts.app')

@section('title', 'Imagerie médicale')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Imagerie médicale</h1>
            <p class="mt-1 text-sm text-gray-500">Gérez les images médicales de vos patients</p>
        </div>
        <a href="{{ route('medecin.imagerie.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Nouvelle image
        </a>
    </div>

    <!-- Liste des images -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Patient
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type d'image
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Format
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($imageries as $imagerie)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $imagerie->dossierMedical->patient->utilisateur->nom ?? 'N/A' }}
                                            {{ $imagerie->dossierMedical->patient->utilisateur->prenom ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $imagerie->type_image ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $imagerie->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $imagerie->is_dicom ? 'DICOM' : 'Standard' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('medecin.imagerie.show', $imagerie) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($imagerie->is_dicom)
                                    <a href="{{ route('medecin.imagerie.viewer', $imagerie) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-image"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Aucune image médicale trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $imageries->links() }}
        </div>
    </div>
</div>
@endsection 