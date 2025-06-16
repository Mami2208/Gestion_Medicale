@extends('layouts.infirmier')

@section('title', 'Mon Profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Mon Profil</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Photo de profil" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <a href="{{ route('infirmier.profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Modifier le profil
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%;">Nom complet</th>
                                        <td>{{ $user->prenom }} {{ $user->nom }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Téléphone</th>
                                        <td>{{ $user->telephone ?? 'Non renseigné' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Adresse</th>
                                        <td>{{ $user->adresse ?? 'Non renseignée' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date de naissance</th>
                                        <td>{{ $user->date_naissance ? \Carbon\Carbon::parse($user->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date d'inscription</th>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
