@extends('base_admin')
@section('title', 'Edit Grant')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Grant</div>

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

                    <form action="{{ route('subventions.update', $subvention->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Start-up Kits field -->
                        <div class="form-group">
                            <label for="start_up_kits">Start-up Kits</label>
                            <select name="start_up_kits" id="start_up_kits" class="form-control">
                                <option value="">Select an option</option>
                                <option value="grant_receive" {{ $subvention->start_up_kits == 'grant_receive' ? 'selected' : '' }}>Grant Receive</option>
                                <option value="grant_not_receive" {{ $subvention->start_up_kits == 'grant_not_receive' ? 'selected' : '' }}>Grant Not Receive</option>
                            </select>
                        </div>

                        <!-- Grants field -->
                        <div class="form-group">
                            <label for="grants">Grants</label>
                            <select name="grants" id="grants" class="form-control">
                                <option value="">Select an option</option>
                                <option value="grant_receive" {{ $subvention->grants == 'grant_receive' ? 'selected' : '' }}>Grant Receive</option>
                                <option value="grant_not_receive" {{ $subvention->grants == 'grant_not_receive' ? 'selected' : '' }}>Grant Not Receive</option>
                            </select>
                        </div>

                        <!-- Loan field -->
                        <div class="form-group">
                            <label for="loan">Loan</label>
                            <select name="loan" id="loan" class="form-control">
                                <option value="">Select an option</option>
                                <option value="grant_receive" {{ $subvention->loan == 'grant_receive' ? 'selected' : '' }}>Grant Receive</option>
                                <option value="grant_not_receive" {{ $subvention->loan == 'grant_not_receive' ? 'selected' : '' }}>Grant Not Receive</option>
                            </select>
                        </div>

                        <!-- Promotion field -->
                        <div class="form-group">
                            <label for="promotion_id">Promotion</label>
                            <select name="promotion_id" id="promotion_id" class="form-control">
                                <option value="">Select a promotion</option>
                                @foreach ($promotions as $promotion)
                                    <option value="{{ $promotion->id }}" {{ $subvention->student->promotions->contains($promotion->id) ? 'selected' : '' }}>
                                        {{ $promotion->num_promotion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Students field -->
                        <div class="form-group">
                            <label for="student_id">Students</label>
                            <select name="student_id" id="student_id" class="form-control">
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" {{ $subvention->student_id == $student->id ? 'selected' : '' }}>
                                        {{ $student->last_name }} {{ $student->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date field -->
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ $subvention->date }}">
                        </div>

                        <!-- Submit button -->
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

        // Clear the student list
        studentSelect.innerHTML = '<option value="">Select a student</option>';

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
