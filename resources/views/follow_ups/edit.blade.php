@extends('base_admin')
@section('title', 'Edit Follow-up')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Follow-up</div>

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

                    <form action="{{ route('follow_ups.update', $followUp->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Farm Visits -->
                        <div class="form-group">
                            <label for="farm_visits">Farm Visits</label>
                            <select name="farm_visits" id="farm_visits" class="form-control" required>
                                <option value="">Select an option</option>
                                <option value="Visit" {{ $followUp->farm_visits == 'Visit' ? 'selected' : '' }}>Visit</option>
                                <option value="No visit" {{ $followUp->farm_visits == 'No visit' ? 'selected' : '' }}>No Visit</option>
                            </select>
                        </div>

                        <!-- Phone Contact -->
                        <div class="form-group">
                            <label for="phone_contact">Phone Contact</label>
                            <input type="text" name="phone_contact" id="phone_contact" class="form-control" value="{{ $followUp->phone_contact }}" required>
                        </div>

                        <!-- Sharing of Impact Stories -->
                        <div class="form-group">
                            <label for="sharing_of_impact_stories">Sharing of Impact Stories</label>
                            <input type="text" name="sharing_of_impact_stories" id="sharing_of_impact_stories" class="form-control" value="{{ $followUp->sharing_of_impact_stories }}" required>
                        </div>

                        <!-- Back-stopping -->
                        <div class="form-group">
                            <label for="back_stopping">Back-stopping</label>
                            <input type="text" name="back_stopping" id="back_stopping" class="form-control" value="{{ $followUp->back_stopping }}" required>
                        </div>

                        <!-- Promotion -->
                        <div class="form-group">
                            <label for="promotion_id">Promotion</label>
                            <select name="promotion_id" id="promotion_id" class="form-control" required>
                                <option value="">Select a promotion</option>
                                @foreach ($promotions as $promotion)
                                    <option value="{{ $promotion->id }}" {{ $followUp->student->promotions->contains($promotion->id) ? 'selected' : '' }}>
                                        {{ $promotion->num_promotion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Student -->
                        <div class="form-group">
                            <label for="student_id">Student</label>
                            <select name="student_id" id="student_id" class="form-control" required>
                                <option value="">Select a student</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" {{ $followUp->student_id == $student->id ? 'selected' : '' }}>
                                        {{ $student->last_name }} {{ $student->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update</button>
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

        // Clear student list
        studentSelect.innerHTML = '<option value="">Select a student</option>';

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
                .catch(error => console.error('Error loading students:', error));
        }
    });
</script>
@endsection
