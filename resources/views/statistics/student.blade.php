@extends('base')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h1>Statistiques</h1>

    <!-- Formulaire pour choisir un étudiant -->
    <form id="filterForm" action="{{ route('student.show') }}" method="GET" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="site_id">Training center :</label>
            <select name="site_id" id="site_id" class="form-control" onchange="loadPromotions(this.value)">
                <option value="">Sélectionner un training center</option>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}">{{ $site->designation }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="promotion_id">Promotion :</label>
            <select name="promotion_id" id="promotion_id" class="form-control" onchange="loadStudents(this.value)" disabled>
                <option value="">Sélectionner une promotion</option>
            </select>
        </div>

        <div class="form-group">
            <label for="student_id">Étudiant :</label>
            <select name="student_id" id="student_id" class="form-control" disabled>
                <option value="">Sélectionner un étudiant</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" disabled id="showButton">Afficher les détails</button>
    </form>
</div>

<!-- Script pour gérer les mises à jour dynamiques -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function loadPromotions(siteId) {
        if (siteId) {
            $.ajax({
                url: '/get-promotions/' + siteId,
                type: 'GET',
                success: function(data) {
                    $('#promotion_id').html(data).prop('disabled', false);
                    $('#student_id').html('<option value="">Sélectionner un étudiant</option>').prop('disabled', true);
                    $('#showButton').prop('disabled', true);
                }
            });
        } else {
            $('#promotion_id').html('<option value="">Sélectionner une promotion</option>').prop('disabled', true);
            $('#student_id').html('<option value="">Sélectionner un étudiant</option>').prop('disabled', true);
            $('#showButton').prop('disabled', true);
        }
    }

    function loadStudents(promotionId) {
        if (promotionId) {
            $.ajax({
                url: '/get-students/' + promotionId,
                type: 'GET',
                success: function(data) {
                    $('#student_id').html(data).prop('disabled', false);
                    $('#showButton').prop('disabled', false);
                }
            });
        } else {
            $('#student_id').html('<option value="">Sélectionner un étudiant</option>').prop('disabled', true);
            $('#showButton').prop('disabled', true);
        }
    }
</script>
@endsection
