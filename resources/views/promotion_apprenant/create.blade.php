@extends('base_admin')

@section('title', 'Add Promotion-Student Record')

@section('content')
<div class="container">
    <h2>Add Promotion-Student Record</h2>

    <!-- Affichage des erreurs de validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire d'ajout -->
    <form action="{{ route('promotion_apprenant.store') }}" method="POST">
        @csrf

        <!-- Sélection de la promotion -->
        <div class="form-group">
            <label for="promotion_id">Promotion:</label>
            <select name="promotion_id" id="promotion_id" class="form-control" required>
                <option value="">Choose a promotion</option>
                @foreach ($promotions as $promotion)
                    <option value="{{ $promotion->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $promotion->num_promotion }}</option>
                @endforeach
            </select>
        </div>

        <!-- Sélection des étudiants (dynamique) -->
        <div class="form-group">
            <label for="students">Students:</label>
            <select name="students[]" id="students" class="form-control" multiple required>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) key to select multiple students.</small>
        </div>

        <!-- Boutons de soumission et de retour -->
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('promotion_apprenant.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<!-- Intégration de jQuery et Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialisation de Select2 pour le champ des étudiants
        $('#students').select2({
            placeholder: "Choose students",
            allowClear: true
        });

        // Écouteur d'événement pour le changement de promotion
        $('#promotion_id').on('change', function() {
            const promotionId = $(this).val(); // Récupérer l'ID de la promotion sélectionnée

            if (promotionId) {
                // Requête AJAX pour récupérer les étudiants disponibles pour cette promotion
                $.ajax({
                    url: "{{ route('get_students_by_promotion') }}",
                    type: "GET",
                    data: { promotion_id: promotionId },
                    success: function(response) {
                        // Vider la liste actuelle des étudiants
                        $('#students').empty();

                        // Ajouter les nouveaux étudiants à la liste
                        if (response.length > 0) {
                            $.each(response, function(index, student) {
                                $('#students').append(
                                    `<option value="${student.id}">${student.first_name} ${student.last_name}</option>`
                                );
                            });
                        } else {
                            $('#students').append(
                                `<option value="">No students available</option>`
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                // Si aucune promotion n'est sélectionnée, vider la liste des étudiants
                $('#students').empty();
            }
        });
    });
</script>
@endsection
