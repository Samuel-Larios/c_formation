@extends('base_admin')
@section('title', 'Add a Grant')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add a Grant</div>

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

                    <form action="{{ route('subventions.store') }}" method="POST">
                        @csrf

                        <!-- Start-up Kits field -->
                        <div class="form-group">
                            <label for="start_up_kits">Start-up Kits</label>
                            <select name="start_up_kits" id="start_up_kits" class="form-control">
                                <option value="">Select an option</option>
                                <option value="Grant receive">Received</option>
                                <option value="Grant not receive">Not Received</option>
                            </select>
                        </div>

                        <!-- Grants field -->
                        <div class="form-group">
                            <label for="grants">Grants</label>
                            <select name="grants" id="grants" class="form-control">
                                <option value="">Select an option</option>
                                <option value="Grant receive">Received</option>
                                <option value="Grant not receive">Not Received</option>
                            </select>
                        </div>

                        <!-- Loan field -->
                        <div class="form-group">
                            <label for="loan">Loan</label>
                            <select name="loan" id="loan" class="form-control">
                                <option value="">Select an option</option>
                                <option value="Loan receive">Received</option>
                                <option value="Loan not receive">Not Received</option>
                            </select>
                        </div>

                        <!-- Start-up Kits Items Received field -->
                        <div class="form-group">
                            <label for="start_up_kits_items_received">Start-up Kits Items Received</label>
                            <textarea name="start_up_kits_items_received" id="start_up_kits_items_received" class="form-control" rows="3" placeholder="Enter items received"></textarea>
                        </div>

                        <!-- State of Farm Location field -->
                        <div class="form-group">
                            <label for="state_of_farm_location">State of Farm Location</label>
                            <textarea name="state_of_farm_location" id="state_of_farm_location" class="form-control" rows="3" placeholder="Enter state or location"></textarea>
                        </div>

                        <!-- Promotion field -->
                        <div class="form-group">
                            <label for="promotion_id">Promotion</label>
                            <select name="promotion_id" id="promotion_id" class="form-control">
                                <option value="">Select a promotion</option>
                                @foreach ($promotions as $promotion)
                                    <option value="{{ $promotion->id }}">{{ $promotion->num_promotion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Students field -->
                        <div class="form-group">
                            <label for="student_id">Students</label>
                            <select name="student_id[]" id="student_id" class="form-control" multiple>
                                <!-- Students will be dynamically loaded -->
                            </select>
                        </div>

                        <!-- Date field (optional) -->
                        <div class="form-group">
                            <label for="date">Date (optional)</label>
                            <input type="date" name="date" id="date" class="form-control">
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('subventions.index') }}" class="btn btn-secondary">Back</a>
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

        // Clear the student list
        studentSelect.innerHTML = '<option value="">Select one or more students</option>';

        if (promotionId) {
            fetch(`/subventions/students/${promotionId}`)
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
