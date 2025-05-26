@extends('base_admin')

@section('title', 'Edit Business Status')

@section('content')
<div class="container">
    <h1>Edit Business Status</h1>

    <form action="{{ route('business_status.update', $businessStatus->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="type_of_business">Type of Business:</label>
            <input type="text" name="type_of_business" class="form-control" value="{{ $businessStatus->type_of_business }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" class="form-control" required>
                <option value="">Select a status</option>
                <option value="Registered" {{ $businessStatus->status == 'Registered' ? 'selected' : '' }}>Registered</option>
                <option value="Non registered" {{ $businessStatus->status == 'Non registered' ? 'selected' : '' }}>Non registered</option>
            </select>
        </div>

        <div class="form-group">
            <label for="student_id">Student:</label>
            <select name="student_id" class="form-control" required>
                <option value="">Select a student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" {{ $businessStatus->student_id == $student->id ? 'selected' : '' }}>
                        {{ $student->last_name }} {{ $student->first_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-warning">Update</button>
        <a href="{{ route('business_status.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
