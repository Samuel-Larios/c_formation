@extends('base_admin')

@section('title', 'Create an Entity')

@section('content')
<div class="container">
    <h2 class="mt-3 mb-4">Add a New Entity</h2>

    <!-- Affichage des erreurs de validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire de création -->
    <form action="{{ route('entities.store') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf

        <!-- Section: Activity -->
        <div class="mb-4">
            <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Activity</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="activity" class="form-label">Activity:</label>
                    <input type="text" name="activity" class="form-control" required value="{{ old('activity') }}">
                </div>
            </div>
        </div>

        <!-- Section: Student Selection -->
        <div class="mb-4">
            <h5 class="mb-3"><i class="fas fa-users me-2"></i>Student Selection</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="student_id" class="form-label">Student:</label>
                    <select name="student_id" id="student_id" class="form-select" required>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-success me-md-2">
                <i class="fas fa-save me-2"></i>Save
            </button>
            <a href="{{ route('entities.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </form>
</div>
@endsection
