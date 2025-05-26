@extends('base_student')
@section('title', 'Edit an Employee')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Edit an Employee</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('student.salaries.update', $salary) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="entreprise">Company</label>
                    <input type="text" name="entreprise" id="entreprise"
                           class="form-control @error('entreprise') is-invalid @enderror"
                           value="{{ old('entreprise', $salary->entreprise) }}" required>
                    @error('entreprise')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="localisation">Location</label>
                    <input type="text" name="localisation" id="localisation"
                           class="form-control @error('localisation') is-invalid @enderror"
                           value="{{ old('localisation', $salary->localisation) }}" required>
                    @error('localisation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="employeur">Employer</label>
                    <input type="text" name="employeur" id="employeur"
                           class="form-control @error('employeur') is-invalid @enderror"
                           value="{{ old('employeur', $salary->employeur) }}" required>
                    @error('employeur')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="tel">Phone</label>
                    <input type="text" name="tel" id="tel"
                           class="form-control @error('tel') is-invalid @enderror"
                           value="{{ old('tel', $salary->tel) }}" required>
                    @error('tel')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
