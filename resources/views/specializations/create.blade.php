@extends('base_admin')

@section('title', 'Add Specialization')

@section('content')
<div class="container">
    <h2 class="mt-3">Add students to a specialization</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('specializations.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Specialization:</label>
            <select name="specialite_id" id="specialite_id" class="form-control" required>
                <option value="">Choose a specialization</option>
                @foreach ($specialites as $specialite)
                    <option value="{{ $specialite->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $specialite->designation }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Students:</label>
            <select name="student_ids[]" id="student_ids" class="form-control" multiple required>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) key to select multiple students.</small>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('specializations.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<!-- Intégration de jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Écouteur d'événement pour le changement de spécialisation
        $('#specialite_id').on('change', function() {
            const specialiteId = $(this).val(); // Récupérer l'ID de la spécialisation sélectionnée

            if (specialiteId) {
                // Requête AJAX pour récupérer les étudiants disponibles pour cette spécialisation
                $.ajax({
                    url: "{{ route('get_students_by_specialization') }}",
                    type: "GET",
                    data: { specialite_id: specialiteId },
                    success: function(response) {
                        // Vider la liste actuelle des étudiants
                        $('#student_ids').empty();

                        // Ajouter les nouveaux étudiants à la liste
                        if (response.length > 0) {
                            $.each(response, function(index, student) {
                                $('#student_ids').append(
                                    `<option value="${student.id}">${student.first_name} ${student.last_name}</option>`
                                );
                            });
                        } else {
                            $('#student_ids').append(
                                `<option value="">No students available</option>`
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                // Si aucune spécialisation n'est sélectionnée, vider la liste des étudiants
                $('#student_ids').empty();
            }
        });

        // Charger les étudiants pour la spécialisation sélectionnée par défaut au chargement de la page
        $('#specialite_id').trigger('change');
    });
</script>
@endsection
