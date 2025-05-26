@extends('base_admin')
@section('title', 'Filtered Students')
@section('content')
<div class="container">
    <h1>Filtered Students</h1>

    <!-- Bouton pour retourner au formulaire -->
    <div class="mb-3">
        <a href="{{ route('students.filter.form') }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i> Back to Filter
        </a>
    </div>

    <!-- Affichage des résultats -->
    <div class="card">
        <div class="card-header">
            Results
            @if($promotionId)
                - Promotion: {{ $promotions->find($promotionId)->num_promotion ?? '' }}
            @endif
            @if($specializationId)
                - Specialization: {{ $specializations->find($specializationId)->designation ?? '' }}
            @endif
        </div>
        <div class="card-body">
            <table class="table">
                <!-- Table content similar to your original code -->
            </table>
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
