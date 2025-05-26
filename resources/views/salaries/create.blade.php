@extends('base_admin')

@section('title', 'Create a Salary')

@section('content')
<div class="container">
    <h1>Create a New Salary</h1>

    <form action="{{ route('salaries.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="entreprise">Company:</label>
            <input type="text" name="entreprise" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="localisation">Location:</label>
            <input type="text" name="localisation" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="employeur">Employer:</label>
            <input type="text" name="employeur" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="tel">Phone:</label>
            <input type="text" name="tel" class="form-control" required>
        </div>

        {{-- <div class="form-group">
            <label for="promotion_id">Promotion:</label>
            <select name="promotion_id" id="promotion_id" class="form-control" required>
                @foreach ($promotions as $promotion)
                    <option value="{{ $promotion->id }}">{{ $promotion->num_promotion }}</option>
                @endforeach
            </select>
        </div> --}}

        <div class="form-group">
            <label for="student_id">Student:</label>
            <select name="student_id" id="student_id" class="form-control" required>
                @if ($students->count() > 0)
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->last_name }} {{ $student->first_name }}</option>
                    @endforeach
                @else
                    <option value="">No student available</option>
                @endif
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('salaries.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#promotion_id').change(function() {
            var promotionId = $(this).val();

            if (promotionId) {
                $.ajax({
                    url: "{{ route('get_students_by_promotion') }}",
                    type: "GET",
                    data: { promotion_id: promotionId },
                    success: function(response) {
                        $('#student_id').empty();
                        if (response.length > 0) {
                            $.each(response, function(key, student) {
                                $('#student_id').append('<option value="' + student.id + '">' + student.last_name + ' ' + student.first_name + '</option>');
                            });
                        } else {
                            $('#student_id').append('<option value="">No student available</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            } else {
                $('#student_id').empty().append('<option value="">Please select a promotion first</option>');
            }
        });

        // Trigger change on page load if a promotion is already selected
        var selectedPromotionId = $('#promotion_id').val();
        if (selectedPromotionId) {
            $('#promotion_id').trigger('change');
        }
    });
</script>
@endsection
