@extends('base_student')
@section('title', 'Edit a Job Creation')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Edit a Job Creation</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('student.job_creations.update', $jobCreation) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="nom">Name</label>
                    <input type="text" name="nom" id="nom"
                           class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom', $jobCreation->nom) }}" required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="tel">Phone</label>
                    <input type="text" name="tel" id="tel"
                           class="form-control @error('tel') is-invalid @enderror"
                           value="{{ old('tel', $jobCreation->tel) }}" required>
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
