@extends('base_admin')

@section('title', 'Liste des Évaluations')

@section('content')
<div class="container">
    <h1>Liste des Évaluations</h1>

    <!-- Lien pour ajouter une évaluation -->
    <a href="{{ route('evaluations.create') }}" class="btn btn-primary mb-3">Ajouter une Évaluation</a>

    <!-- Composant Livewire pour la recherche et le filtrage -->
    @livewire('evaluation-search')
</div>
@endsection
