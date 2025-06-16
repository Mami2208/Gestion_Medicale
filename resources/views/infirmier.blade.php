@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Infirmier Dashboard</h1>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <h2 class="text-xl font-semibold mb-2">Liste des Patients</h2>
            @livewire('infirmier.patients')
        </div>
        <div>
            <h2 class="text-xl font-semibold mb-2">Historique de Soins et Constantes Vitales</h2>
            @livewire('infirmier.soins')
        </div>
    </div>
</div>
@endsection
