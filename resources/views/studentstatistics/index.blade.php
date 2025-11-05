@extends('base')
@section('title', 'Student Statistics')
@section('description', 'Student Statistics')
@section('keywords', 'Statistics, Students')

@section('content')
    <div class="container">
        <h1>Student Statistics</h1>

        <!-- Navigation Links -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Available Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('student.statistics.subventions') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-money-bill-wave"></i> Subventions
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('student.statistics.salaries') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-dollar-sign"></i> Salaries
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('student.statistics.job-creations') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-briefcase"></i> Job Creations
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('student.statistics.state-of-origin') }}"
                                    class="btn btn-warning btn-block">
                                    <i class="fas fa-map-marker-alt"></i> State of Origin
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('student.statistics.follow-ups') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-users"></i> Follow-ups
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('student.statistics.entrepreneurs') }}" class="btn btn-dark btn-block">
                                    <i class="fas fa-lightbulb"></i> Entrepreneurs
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('student.statistics.handicap-situations') }}"
                                    class="btn btn-danger btn-block">
                                    <i class="fas fa-wheelchair"></i> Handicap Situations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Export Students to Excel</h3>
            </div>
            <div class="card-body">
                <form id="exportForm" method="GET" action="{{ route('student.statistics.export.filtered') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label for="export_site_id">Site:</label>
                            <select name="site_id" id="export_site_id" class="form-control">
                                <option value="">All Sites</option>
                                @foreach ($sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->designation }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="export_promotion_id">Promotion:</label>
                            <select name="promotion_id" id="export_promotion_id" class="form-control">
                                <option value="">All Promotions</option>
                                @php
                                    $promotions = \App\Models\Promotion::select('num_promotion')
                                        ->distinct()
                                        ->orderBy('num_promotion')
                                        ->get();
                                @endphp
                                @foreach ($promotions as $promotion)
                                    <option value="{{ $promotion->num_promotion }}">{{ $promotion->num_promotion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="export_specialite_id">Specialization:</label>
                            <select name="specialite_id" id="export_specialite_id" class="form-control">
                                <option value="">All Specializations</option>
                                @foreach (\App\Models\Specialite::all() as $specialite)
                                    <option value="{{ $specialite->id }}">{{ $specialite->designation }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="export_sexe">Gender:</label>
                            <select name="sexe" id="export_sexe" class="form-control">
                                <option value="">All</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-file-excel"></i> Export to Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Subventions Statistics Link -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Subventions Statistics</h3>
            </div>
            <div class="card-body">
                <p>Filter and export subventions data by site and promotion.</p>
                <a href="{{ route('student.statistics.subventions') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar"></i> View Subventions Statistics
                </a>
            </div>
        </div>

        <!-- Salaries Statistics Link -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Salaries Statistics</h3>
            </div>
            <div class="card-body">
                <p>Filter and export salaries data by site and promotion.</p>
                <a href="{{ route('student.statistics.salaries') }}" class="btn btn-success">
                    <i class="fas fa-money-bill-wave"></i> View Salaries Statistics
                </a>
            </div>
        </div>

        <!-- Job Creations Statistics Link -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Job Creations Statistics</h3>
            </div>
            <div class="card-body">
                <p>Filter and export job creations data by site and promotion.</p>
                <a href="{{ route('student.statistics.job-creations') }}" class="btn btn-warning">
                    <i class="fas fa-briefcase"></i> View Job Creations Statistics
                </a>
            </div>
        </div>

        <!-- State of Origin Statistics Link -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Students by State of Origin Statistics</h3>
            </div>
            <div class="card-body">
                <p>Filter and export students data by state of origin, site and promotion.</p>
                <a href="{{ route('student.statistics.state-of-origin') }}" class="btn btn-info">
                    <i class="fas fa-map-marker-alt"></i> View State of Origin Statistics
                </a>
            </div>
        </div>

        <!-- Follow-ups Statistics Link -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Follow-ups Statistics</h3>
            </div>
            <div class="card-body">
                <p>Filter and export follow-ups data by site, promotion and student.</p>
                <a href="{{ route('student.statistics.follow-ups') }}" class="btn btn-secondary">
                    <i class="fas fa-user-check"></i> View Follow-ups Statistics
                </a>
            </div>
        </div>

        <!-- Entrepreneurs Statistics Link -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Entrepreneurs Statistics</h3>
            </div>
            <div class="card-body">
                <p>Filter and export students without employer (entrepreneurs) by site, promotion and student.</p>
                <a href="{{ route('student.statistics.entrepreneurs') }}" class="btn btn-primary">
                    <i class="fas fa-building"></i> View Entrepreneurs Statistics
                </a>
            </div>
        </div>

        <!-- Handicap Situations Statistics Link -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Handicap Situations Statistics</h3>
            </div>
            <div class="card-body">
                <p>Filter and export students with handicap situations by site, promotion, student and handicap type.</p>
                <a href="{{ route('student.statistics.handicap-situations') }}" class="btn btn-warning">
                    <i class="fas fa-wheelchair"></i> View Handicap Situations Statistics
                </a>
            </div>
        </div>

        <!-- Student Details Form -->
        {{-- <div class="card">
            <div class="card-header">
                <h3>Student Details</h3>
            </div>
            <div class="card-body">
                <form id="selectionForm">
                    @csrf
                    <div class="form-group">
                        <label for="site_id">Site:</label>
                        <select name="site_id" id="site_id" class="form-control">
                            <option value="">Select a training center</option>
                            @foreach ($sites as $site)
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
        </div> --}}
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handling site change for export form
            $('#export_site_id').change(function() {
                let siteId = $(this).val();
                if (siteId) {
                    $.get('/get-promotions/' + siteId)
                        .done(function(data) {
                            $('#export_promotion_id').empty().append(
                                '<option value="">All Promotions</option>');
                            $.each(data, function(index, promo) {
                                $('#export_promotion_id').append($('<option>', {
                                    value: promo.id,
                                    text: promo.text
                                }));
                            });
                            $('#export_promotion_id').prop('disabled', false);
                        })
                        .fail(function() {
                            console.error('Error loading promotions');
                            alert('Error loading promotions');
                        });
                } else {
                    // When "All Sites" is selected, keep all unique promotions available
                    $('#export_promotion_id').empty().append(
                        '<option value="">All Promotions</option>');
                    @php
                        $promotions = \App\Models\Promotion::select('num_promotion')->distinct()->orderBy('num_promotion')->get();
                    @endphp
                    @foreach ($promotions as $promotion)
                        $('#export_promotion_id').append(
                            '<option value="{{ $promotion->num_promotion }}">{{ $promotion->num_promotion }}</option>'
                        );
                    @endforeach
                }
            });

            // Handling site change for details form
            $('#site_id').change(function() {
                let siteId = $(this).val();
                if (siteId) {
                    $.get('/get-promotions/' + siteId)
                        .done(function(data) {
                            $('#promotion_id').empty().append(
                                '<option value="">Select a promotion</option>');
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

            // Handling promotion change for details form
            $('#promotion_id').change(function() {
                let promotionId = $(this).val();
                if (promotionId) {
                    $.get('/get-students/' + promotionId)
                        .done(function(data) {
                            $('#student_id').empty().append(
                                '<option value="">Select a student</option>');
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
                $('#promotion_id').empty().append('<option value="">Select a promotion</option>').prop('disabled',
                    true);
                resetStudents();
            }

            function resetStudents() {
                $('#student_id').empty().append('<option value="">Select a student</option>').prop('disabled',
                    true);
                $('#showDetails').prop('disabled', true);
            }
        });
    </script>
@endsection
