@extends('base_admin')

@section('title', 'Create a Business Status')

@section('content')
<div class="container">
    <h1>Create a New Business Status</h1>

    <form action="{{ route('business_status.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="type_of_business">Type of Business:</label>
            <input type="text" name="type_of_business" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" class="form-control" required>
                <option value="">Select a status</option>
                <option value="Registered">Registered</option>
                <option value="Non registered">Non registered</option>
                <option value="Cooperative">Cooperative</option>
            </select>
        </div>

        <div class="form-group">
            <label for="student_id">Student:</label>
            <select name="student_id" class="form-control" required>
                <option value="">Select a student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->last_name }} {{ $student->first_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('business_status.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
