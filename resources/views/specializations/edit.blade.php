@extends('base_admin')

@section('title', 'Edit Specialization')

@section('content')
<div class="container">
    <h2 class="mt-3">Edit Specialization</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('specializations.update', $specialization->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Specialization:</label>
            <select name="specialite_id" class="form-control" required>
                <option value="">Choose a specialization</option>
                @foreach ($specialites as $specialite)
                    <option value="{{ $specialite->id }}" {{ $specialization->specialite_id == $specialite->id ? 'selected' : '' }}>{{ $specialite->designation }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Student:</label>
            <select name="student_id" class="form-control" required>
                <option value="">Choose a student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" {{ $specialization->student_id == $student->id ? 'selected' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <a href="{{ route('specializations.index') }}" class="btn btn-secondary mt-3">Back</a>

    </form>
</div>
@endsection
