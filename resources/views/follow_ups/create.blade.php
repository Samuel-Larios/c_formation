@extends('base_admin')
@section('title', 'Add Follow-up')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add Follow-up</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('follow_ups.store') }}" method="POST">
                        @csrf

                        <!-- Farm Visits -->
                        <div class="form-group">
                            <label for="farm_visits">Farm Visits</label>
                            <select name="farm_visits" id="farm_visits" class="form-control" required>
                                <option value="">Select an option</option>
                                <option value="Visited">Yes</option>
                                <option value="Not visited">No</option>
                            </select>
                        </div>

                        <!-- Phone Contact -->
                        <div class="form-group">
                            <label for="phone_contact">Phone Contact</label>
                            <input type="text" name="phone_contact" id="phone_contact" class="form-control" required>
                        </div>

                        <!-- Sharing of Impact Stories -->
                        <div class="form-group">
                            <label for="sharing_of_impact_stories">Sharing of Impact Stories</label>
                            <input type="text" name="sharing_of_impact_stories" id="sharing_of_impact_stories" class="form-control" required>
                        </div>

                        <!-- Back-stopping -->
                        <div class="form-group">
                            <label for="back_stopping">Back-stopping</label>
                            <input type="text" name="back_stopping" id="back_stopping" class="form-control" required>
                        </div>

                        <!-- Promotion -->
                        <div class="form-group">
                            <label for="promotion_id">Promotion</label>
                            <select name="promotion_id" id="promotion_id" class="form-control" required>
                                <option value="">Select a promotion</option>
                                @foreach ($promotions as $promotion)
                                    <option value="{{ $promotion->id }}">{{ $promotion->num_promotion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Student -->
                        <div class="form-group">
                            <label for="student_id">Student</label>
                            <select name="student_id" id="student_id" class="form-control" required>
                                <option value="">Select a student</option>
                                <!-- Students will be dynamically loaded -->
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('follow_ups.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('promotion_id').addEventListener('change', function() {
        let promotionId = this.value;
        let studentSelect = document.getElementById('student_id');

        // Effacer la liste des étudiants
        studentSelect.innerHTML = '<option value="">Sélectionner un étudiant</option>';

        if (promotionId) {
            fetch(`/follow_ups/students/${promotionId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(student => {
                        let option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = student.last_name + ' ' + student.first_name;
                        studentSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des étudiants :', error));
        }
    });
</script>
@endsection
