@extends('base')
@section('title', 'State of Origin Statistics')
@section('description', 'State of Origin Statistics')
@section('keywords', 'Statistics, State of Origin')

@section('content')
    <div class="container">
        <h1>State of Origin Statistics</h1>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Filter Students by State of Origin</h3>
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
                            <label for="state_of_origin">State of Origin:</label>
                            <select name="state_of_origin" id="state_of_origin" class="form-control">
                                <option value="">All States</option>
                                @foreach ($statesOfOrigin as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>&nbsp;</label><br>
                            <button type="button" id="filterBtn" class="btn btn-primary btn-block">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button type="button" id="exportBtn" class="btn btn-success">
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
                <h3>Students Results</h3>
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
                filterStudents();
            });

            // Export button click
            $('#exportBtn').click(function() {
                exportStudents();
            });

            // Initial load
            filterStudents();
        });

        function filterStudents() {
            let formData = $('#filterForm').serialize();

            $.post('/student-statistics/filter-state-of-origin', formData)
                .done(function(data) {
                    displayResults(data);
                })
                .fail(function() {
                    alert('Error filtering students');
                });
        }

        function exportStudents() {
            let formData = $('#filterForm').serialize();
            window.location.href = '/student-statistics/export-state-of-origin?' + formData;
        }

        function displayResults(data) {
            let html = '';

            if (data.data && data.data.length > 0) {
                html += '<div class="table-responsive">';
                html += '<table class="table table-striped table-hover">';
                html += '<thead class="table-dark">';
                html += '<tr>';
                html += '<th>First Name</th>';
                html += '<th>Last Name</th>';
                html += '<th>Gender</th>';
                html += '<th>Site</th>';
                html += '<th>Promotion</th>';
                html += '<th>State of Origin</th>';
                html += '<th>State of Residence</th>';
                html += '<th>LGA</th>';
                html += '<th>Community</th>';
                html += '<th>Contact</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';

                data.data.forEach(function(student) {
                    html += '<tr>';
                    html += '<td>' + (student.first_name || '') + '</td>';
                    html += '<td>' + (student.last_name || '') + '</td>';
                    html += '<td>' + (student.sexe || '') + '</td>';
                    html += '<td>' + (student.site ? student.site.designation : '') + '</td>';
                    html += '<td>' + (student.promotions && student.promotions.length > 0 ? student.promotions[0]
                        .num_promotion : '') + '</td>';
                    html += '<td>' + (student.state_of_origin || '') + '</td>';
                    html += '<td>' + (student.state_of_residence || '') + '</td>';
                    html += '<td>' + (student.lga || '') + '</td>';
                    html += '<td>' + (student.community || '') + '</td>';
                    html += '<td>' + (student.contact || '') + '</td>';
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
                html += '<h5 class="text-muted">No Students Found</h5>';
                html += '<p class="text-muted">There are no students matching the selected filters.</p>';
                html += '</div>';
            }

            $('#resultsTable').html(html);
        }
    </script>
@endsection
