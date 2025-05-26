@extends('base_admin')
@section('title', 'Edit Specialization')

@section('content')
<div class="container">
    <h2>Edit Specialization</h2>

    <form action="{{ route('specialites.update', $specialite->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="designation">Designation:</label>
            <input type="text" name="designation" id="designation" class="form-control" value="{{ $specialite->designation }}" required>
        </div>

        <button type="submit" class="btn btn-warning">Update</button>
        <a href="{{ route('specialites.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
