@extends('base_admin')

@section('title', 'Add Job Creation')

@section('content')
<div class="container">
    <h1>Add Job Creation</h1>

    <form action="{{ route('job-creations.store') }}" method="POST">
        @csrf

        <!-- Champ pour sélectionner un étudiant -->
        <div class="form-group">
            <label for="student_id">Select Student:</label>
            <select name="student_id" id="student_id" class="form-control" required>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Champs pour les jobs -->
        <div id="job-fields">
            <div class="job-group">
                <div class="form-group">
                    <label for="nom">Name:</label>
                    <input type="text" name="jobs[0][nom]" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="tel">Phone:</label>
                    <input type="text" name="jobs[0][tel]" class="form-control" required>
                </div>
            </div>
        </div>

        <button type="button" id="add-job-field" class="btn btn-info">Add Another Job</button>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('jobcreations.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
    // Ajouter dynamiquement des champs pour les jobs
    document.getElementById('add-job-field').addEventListener('click', function() {
        const jobFields = document.getElementById('job-fields');
        const jobGroup = document.querySelector('.job-group').cloneNode(true);
        const index = document.querySelectorAll('.job-group').length;

        // Mettre à jour les noms des champs pour éviter les conflits
        jobGroup.querySelectorAll('input').forEach(input => {
            const name = input.getAttribute('name').replace(/\[\d\]/, `[${index}]`);
            input.setAttribute('name', name);
        });

        jobFields.appendChild(jobGroup);
    });
</script>
@endsection
