@extends('base_student')
@section('title', 'Add an Employee')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Add an Employee</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('student.salaries.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="entreprise">Company</label>
                    <input type="text" name="entreprise" id="entreprise"
                           class="form-control @error('entreprise') is-invalid @enderror"
                           value="{{ old('entreprise') }}" required>
                    @error('entreprise')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="localisation">Location</label>
                    <input type="text" name="localisation" id="localisation"
                           class="form-control @error('localisation') is-invalid @enderror"
                           value="{{ old('localisation') }}" required>
                    @error('localisation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="employeur">Employer</label>
                    <input type="text" name="employeur" id="employeur"
                           class="form-control @error('employeur') is-invalid @enderror"
                           value="{{ old('employeur') }}" required>
                    @error('employeur')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="tel">Phone</label>
                    <input type="text" name="tel" id="tel"
                           class="form-control @error('tel') is-invalid @enderror"
                           value="{{ old('tel') }}" required>
                    @error('tel')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add
                </button>
                <a href="{{ route('student.salaries.index') }}" class="btn btn-secondary mt-3">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
