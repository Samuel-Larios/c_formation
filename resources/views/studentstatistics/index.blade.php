@extends('base')
@section('title', 'Student Statistics')
@section('description', 'Student Statistics')
@section('keywords', 'Statistics, Students')

@section('content')
<div class="container">
    <h1>Student Statistics</h1>

    <form id="selectionForm">
        @csrf
        <div class="form-group">
            <label for="site_id">Site:</label>
            <select name="site_id" id="site_id" class="form-control">
                <option value="">Select a training center</option>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}">{{ $site->designation }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="promotion_id">Promotion:</label>
            <select name="promotion_id" id="promotion_id" class="form-control" disabled>
                <option value="">Select a promotion</option>
            </select>
        </div>

        <div class="form-group">
            <label for="student_id">Student:</label>
            <select name="student_id" id="student_id" class="form-control" disabled>
                <option value="">Select a student</option>
            </select>
        </div>

        <button type="button" id="showDetails" class="btn btn-primary" disabled>Show Details</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Handling site change
    $('#site_id').change(function() {
        let siteId = $(this).val();
        if (siteId) {
            $.get('/get-promotions/' + siteId)
                .done(function(data) {
                    $('#promotion_id').empty().append('<option value="">Select a promotion</option>');
                    $.each(data, function(index, promo) {
                        $('#promotion_id').append($('<option>', {
                            value: promo.id,
                            text: promo.text
                        }));
                    });
                    $('#promotion_id').prop('disabled', false);
                })
                .fail(function() {
                    console.error('Error loading promotions');
                    alert('Error loading promotions');
                });
        } else {
            resetPromotionsAndStudents();
        }
    });

    // Handling promotion change
    $('#promotion_id').change(function() {
        let promotionId = $(this).val();
        if (promotionId) {
            $.get('/get-students/' + promotionId)
                .done(function(data) {
                    $('#student_id').empty().append('<option value="">Select a student</option>');
                    $.each(data, function(index, student) {
                        $('#student_id').append($('<option>', {
                            value: student.id,
                            text: student.text
                        }));
                    });
                    $('#student_id').prop('disabled', false);
                    $('#showDetails').prop('disabled', false);
                })
                .fail(function() {
                    console.error('Error loading students');
                    alert('Error loading students');
                });
        } else {
            resetStudents();
        }
    });

    // Handling Show Details button
    $('#showDetails').click(function() {
        let studentId = $('#student_id').val();
        if (studentId) {
            window.location.href = '/studentstatistics/' + studentId;
        } else {
            alert('Please select a student');
        }
    });

    // Utility functions
    function resetPromotionsAndStudents() {
        $('#promotion_id').empty().append('<option value="">Select a promotion</option>').prop('disabled', true);
        resetStudents();
    }

    function resetStudents() {
        $('#student_id').empty().append('<option value="">Select a student</option>').prop('disabled', true);
        $('#showDetails').prop('disabled', true);
    }
});
</script>
@endsection
