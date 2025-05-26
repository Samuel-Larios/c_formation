@extends('base_admin')

@section('title', 'Edit an Evaluation')

@section('content')
<div class="container">
    <h1>Edit an Evaluation</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('evaluations.update', $evaluation->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="student_id">Student</label>
            <select name="student_id" id="student_id" class="form-control" required>
                <option value="">Choose a student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" {{ $evaluation->student_id == $student->id ? 'selected' : '' }}>
                        {{ $student->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="matier_id">Subject</label>
            <select name="matier_id" id="matier_id" class="form-control" required>
                <option value="">Choose a subject</option>
                @foreach ($matiers as $matier)
                    <option value="{{ $matier->id }}" {{ $evaluation->matier_id == $matier->id ? 'selected' : '' }}>
                        {{ $matier->designation }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="note">Grade</label>
            <input type="number" name="note" id="note" class="form-control" value="{{ $evaluation->note }}" min="0" max="20" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
