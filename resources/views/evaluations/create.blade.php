@extends('base_admin')

@section('title', 'Add an Evaluation')

@section('content')
<div class="container">
    <h1>Add an Evaluation</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('evaluations.store') }}" method="POST">
        @csrf

        <!-- Field to select the promotion -->
        <div class="form-group">
            <label for="promotion_id">Promotion</label>
            <select name="promotion_id" id="promotion_id" class="form-control" required>
                <option value="">Select a promotion</option>
                @foreach ($promotions as $promotion)
                    <option value="{{ $promotion->id }}" {{ old('promotion_id') == $promotion->id ? 'selected' : '' }}>
                        {{ $promotion->num_promotion }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Field to select the student -->
        <div class="form-group">
            <label for="student_id">Student</label>
            <select name="student_id" id="student_id" class="form-control" required disabled>
                <option value="">Select a student</option>
            </select>
        </div>

        <!-- Field to select the subject -->
        <div class="form-group">
            <label for="matier_id">Subject</label>
            <select name="matier_id" id="matier_id" class="form-control" required>
                <option value="">Select a subject</option>
                @foreach ($matiers as $matier)
                    <option value="{{ $matier->id }}" {{ old('matier_id') == $matier->id ? 'selected' : '' }}>
                        {{ $matier->designation }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Field for the grade -->
        <div class="form-group">
            <label for="note">Grade</label>
            <input type="number" name="note" id="note" class="form-control" value="{{ old('note') }}" min="0" max="20" required>
        </div>

        <!-- Buttons -->
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('evaluations.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
    // Load students based on the selected promotion
    document.getElementById('promotion_id').addEventListener('change', function() {
        let promotionId = this.value;
        let studentSelect = document.getElementById('student_id');

        // Reset student list
        studentSelect.innerHTML = '<option value="">Select a student</option>';
        studentSelect.disabled = true;

        if (promotionId) {
            // Fetch students for the selected promotion
            fetch(`/evaluations/students-by-promotion/${promotionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(student => {
                            let option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = student.first_name + ' ' + student.last_name;
                            studentSelect.appendChild(option);
                        });
                        studentSelect.disabled = false;
                    }
                })
                .catch(error => console.error('Error loading students:', error));
        }
    });
</script>
@endsection
