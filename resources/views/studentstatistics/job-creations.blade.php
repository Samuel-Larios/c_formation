@extends('base')
@section('title', 'Job Creations Statistics')
@section('description', 'Job Creations Statistics')
@section('keywords', 'Statistics, Job Creations')

@section('content')
    <div class="container">
        <h1>Job Creations Statistics</h1>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Filter Job Creations</h3>
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
                <h3>Job Creations Results</h3>
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
                filterJobCreations();
            });

            // Export button click
            $('#exportBtn').click(function() {
                exportJobCreations();
            });

            // Initial load
            filterJobCreations();
        });

        function filterJobCreations() {
            let formData = $('#filterForm').serialize();

            $.post('/student-statistics/filter-job-creations', formData)
                .done(function(data) {
                    displayResults(data);
                })
                .fail(function() {
                    alert('Error filtering job creations');
                });
        }

        function exportJobCreations() {
            let formData = $('#filterForm').serialize();
            window.location.href = '/student-statistics/export-job-creations?' + formData;
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
                html += '<th>Job Name</th>';
                html += '<th>Phone</th>';
                html += '<th>Gender</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';

                data.data.forEach(function(jobCreation) {
                    html += '<tr>';
                    html += '<td>' + (jobCreation.student ? jobCreation.student.first_name + ' ' + jobCreation
                        .student.last_name : '') + '</td>';
                    html += '<td>' + (jobCreation.student && jobCreation.student.site ? jobCreation.student.site
                        .designation : '') + '</td>';
                    html += '<td>' + (jobCreation.student && jobCreation.student.promotions && jobCreation.student
                        .promotions.length > 0 ? jobCreation.student.promotions[0].num_promotion : '') + '</td>';
                    html += '<td>' + (jobCreation.nom || '') + '</td>';
                    html += '<td>' + (jobCreation.tel || '') + '</td>';
                    html += '<td>' + (jobCreation.sexe || '') + '</td>';
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
                html += '<h5 class="text-muted">No Job Creations Found</h5>';
                html += '<p class="text-muted">There are no job creations matching the selected filters.</p>';
                html += '</div>';
            }

            $('#resultsTable').html(html);
        }
    </script>
@endsection
