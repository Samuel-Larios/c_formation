@extends('base_admin')

@section('title', 'Edit Job Creation')

@section('content')
<div class="container">
    <h1>Edit Job Creation</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('job-creations.update', $jobCreation->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Sélectionner un étudiant -->
        <div class="form-group">
            <label for="student_id">Select Student:</label>
            <select name="student_id" id="student_id" class="form-control" required>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" {{ $student->id == $jobCreation->student_id ? 'selected' : '' }}>
                        {{ $student->first_name }} {{ $student->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Modifier les informations du job -->
        <div class="form-group">
            <label for="nom">Name:</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $jobCreation->nom) }}" required>
        </div>

        <div class="form-group">
            <label for="tel">Phone:</label>
            <input type="text" name="tel" id="tel" class="form-control" value="{{ old('tel', $jobCreation->tel) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('jobcreations.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
