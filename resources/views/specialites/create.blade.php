@extends('base_admin')
@section('title', 'Add Specialization')
@section('content')
<div class="container">
    <h2>Add Specialization</h2>

    <form action="{{ route('specialites.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="designation">Designation:</label>
            <input type="text" name="designation" id="designation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('specialites.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
