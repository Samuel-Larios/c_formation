@extends('base_admin')

@section('title', 'Entity Details')

@section('content')
<div class="container">
    <h2 class="mt-3 mb-4">Entity Details</h2>

    <!-- Carte pour afficher les détails -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Activity: {{ $entity->activity }}</h5>
            <p class="card-text">Student: {{ $entity->student->first_name }} {{ $entity->student->last_name }}</p>
            <p class="card-text">Training center: {{ $entity->student->site->designation }}</p>
        </div>
    </div>

    <!-- Bouton de retour -->
    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
        <a href="{{ route('entities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>
@endsection
