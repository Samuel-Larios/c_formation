@extends('base')
@section('title', 'Entrepreneurs Statistics')
@section('description', 'Entrepreneurs Statistics')
@section('keywords', 'Statistics, Entrepreneurs')

@section('content')
    <div class="container">
        <h1>Entrepreneurs Statistics</h1>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Filter Entrepreneurs</h3>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label for="site_id">Site:</label>
                            <select name="site_id" id="site_id" class="form-control">
                                <option value="">All Sites</option>
                                @foreach ($sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->designation }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="promotion_id">Promotion:</label>
                            <select name="promotion_id" id="promotion_id" class="form-control">
                                <option value="">All Promotions</option>
                                @php
                                    $promotions = \App\Models\Promotion::select('num_promotion')
                                        ->distinct()
                                        ->orderBy('num_promotion')
                                        ->get();
                                @endphp
                                @foreach ($promotions as $promotion)
                                    <option value="{{ $promotion->num_promotion }}">{{ $promotion->num_promotion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="student_id">Student:</label>
                            <select name="student_id" id="student_id" class="form-control">
                                <option value="">All Students</option>
                                <!-- Students will be loaded dynamically if needed -->
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>&nbsp;</label><br>
                            <button type="button" id="filterBtn" class="btn btn-primary btn-block">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>

                        <div class="col-md-3">
                            <label>&nbsp;</label><br>
                            <button type="button" id="exportBtn" class="btn btn-success btn-block">
                                <i class="fas fa-file-excel"></i> Export to Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Table -->
        <div class="card">
            <div class="card-header">
                <h3>Entrepreneurs Results</h3>
            </div>
            <div class="card-body">
                <div id="resultsTable">
                    <!-- Results will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Filter button click
            $('#filterBtn').click(function() {
                filterEntrepreneurs();
            });

            // Export button click
            $('#exportBtn').click(function() {
                exportEntrepreneurs();
            });

            // Initial load
            filterEntrepreneurs();
        });

        function filterEntrepreneurs() {
            let formData = $('#filterForm').serialize();

            $.post('/student-statistics/filter-entrepreneurs', formData)
                .done(function(data) {
                    displayResults(data);
                })
                .fail(function() {
                    alert('Error filtering entrepreneurs');
                });
        }

        function exportEntrepreneurs() {
            let formData = $('#filterForm').serialize();
            window.location.href = '/student-statistics/export-entrepreneurs?' + formData;
        }

        function displayResults(data) {
            let html = '';

            if (data.data && data.data.length > 0) {
                html += '<div class="table-responsive">';
                html += '<table class="table table-striped table-hover">';
                html += '<thead class="table-dark">';
                html += '<tr>';
                html += '<th>Student</th>';
                html += '<th>Site</th>';
                html += '<th>Promotion</th>';
                html += '<th>Sexe</th>';
                html += '<th>State of Origin</th>';
                html += '<th>Situation Handicap</th>';
                html += '<th>Status</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';

                data.data.forEach(function(student) {
                    let statusIcon = student.is_entrepreneur ?
                        '<i class="fas fa-check-circle text-success" title="Entrepreneur"></i>' :
                        '<i class="fas fa-times-circle text-danger" title="Has Employer"></i>';

                    html += '<tr>';
                    html += '<td>' + (student.first_name + ' ' + student.last_name) + '</td>';
                    html += '<td>' + (student.site ? student.site.designation : '') + '</td>';
                    html += '<td>' + (student.promotions && student.promotions.length > 0 ? student.promotions[0]
                        .num_promotion : '') + '</td>';
                    html += '<td>' + student.sexe + '</td>';
                    html += '<td>' + (student.state_of_origin || '') + '</td>';
                    html += '<td>' + (student.situation_handicape || '') + '</td>';
                    html += '<td>' + statusIcon + ' ' + (student.is_entrepreneur ? 'Entrepreneur' :
                        'Has Employer') + '</td>';
                    html += '</tr>';
                });

                html += '</tbody>';
                html += '</table>';
                html += '</div>';

                // Pagination
                if (data.links) {
                    html += '<div class="d-flex justify-content-center mt-3">';
                    html += data.links;
                    html += '</div>';
                }
            } else {
                html = '<div class="text-center py-5">';
                html += '<i class="fas fa-info-circle fa-3x text-muted mb-3"></i>';
                html += '<h5 class="text-muted">No Entrepreneurs Found</h5>';
                html += '<p class="text-muted">There are no entrepreneurs matching the selected filters.</p>';
                html += '</div>';
            }

            $('#resultsTable').html(html);
        }
    </script>
@endsection
