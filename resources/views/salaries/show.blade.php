@extends('base_admin')

@section('title', 'Salary Details')

@section('styles')
<style>
    @media print {
        /* Masquer tous les éléments sauf les informations du salarié */
        body * {
            visibility: hidden; /* Masquer tout par défaut */
        }

        .printable-content, .printable-content * {
            visibility: visible; /* Afficher uniquement le contenu à imprimer */
        }

        .printable-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        /* Supprimer les marges et les espaces inutiles */
        body {
            margin: 0;
            padding: 0;
        }

        h1, .card-title, .card-text {
            margin: 0;
            padding: 0;
        }

        .card {
            border: none;
            box-shadow: none;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1>Salary Details</h1>

    <!-- Bouton pour imprimer -->
    <div class="text-end mb-3">
        <button onclick="window.print()" class="btn btn-success">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <!-- Contenu à imprimer -->
    <div class="printable-content">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Company: {{ $salary->entreprise }}</h5>
                <p class="card-text"><strong>Location:</strong> {{ $salary->localisation }}</p>
                <p class="card-text"><strong>Employer:</strong> {{ $salary->employeur }}</p>
                <p class="card-text"><strong>Phone:</strong> {{ $salary->tel }}</p>
                <p class="card-text">
                    <strong>Student:</strong>
                    @if($salary->student)
                        {{ $salary->student->name }}
                    @else
                        No student
                    @endif
                </p>
            </div>
        </div>
    </div>

    <a href="{{ route('salaries.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
