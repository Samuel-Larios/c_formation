@extends('base_student')
@section('title', 'Add a Job Creation')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Add a Job Creation</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('student.job_creations.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="nom">Name</label>
                    <input type="text" name="nom" id="nom"
                           class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom') }}" required>
                    @error('nom')
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
                <a href="{{ route('student.job_creations.index') }}" class="btn btn-secondary mt-3">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
