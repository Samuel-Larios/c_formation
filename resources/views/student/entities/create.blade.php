@extends('base_student')
@section('title', 'Create an Entity')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Create an Entity</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('student.entities.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="activity">Activity</label>
                    <input type="text" name="activity" id="activity"
                           class="form-control @error('activity') is-invalid @enderror"
                           value="{{ old('activity') }}" required>
                    @error('activity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-save"></i> Create
                </button>
                <a href="{{ route('student.entities.index') }}" class="btn btn-secondary mt-3">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
