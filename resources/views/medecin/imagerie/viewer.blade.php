@extends('layouts.medecin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visualiseur DICOM - {{ $imagerie->type }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('medecin.imagerie.show', $imagerie) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <iframe src="{{ $viewerUrl }}" 
                            style="width: 100%; height: 80vh; border: none;"
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 