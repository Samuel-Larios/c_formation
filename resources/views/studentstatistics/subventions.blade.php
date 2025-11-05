@extends('base')
@section('title', 'Subventions Statistics')
@section('description', 'Subventions Statistics')
@section('keywords', 'Statistics, Subventions')

@section('content')
    <div class="container">
        <h1>Subventions Statistics</h1>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Filter Subventions</h3>
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
                <h3>Subventions Results</h3>
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
                filterSubventions();
            });

            // Export button click
            $('#exportBtn').click(function() {
                exportSubventions();
            });

            // Initial load
            filterSubventions();
        });

        function filterSubventions() {
            let formData = $('#filterForm').serialize();

            $.post('/student-statistics/filter-subventions', formData)
                .done(function(data) {
                    displayResults(data);
                })
                .fail(function() {
                    alert('Error filtering subventions');
                });
        }

        function exportSubventions() {
            let formData = $('#filterForm').serialize();
            window.location.href = '/student-statistics/export-subventions?' + formData;
        }

        function displayResults(data) {
            let html = '';

            if (data.data && data.data.length > 0) {
                html += '<div class="table-responsive">';
                html += '<table class="table table-striped table-hover">';
                html += '<thead class="table-dark">';
                html += '<tr>';
                html += '<th>Student Name</th>';
                html += '<th>Site</th>';
                html += '<th>Promotion</th>';
                html += '<th>Start Up Kits</th>';
                html += '<th>Grants</th>';
                html += '<th>Loan</th>';
                html += '<th>Date</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';

                data.data.forEach(function(subvention) {
                    html += '<tr>';
                    html += '<td>' + (subvention.student ? subvention.student.first_name + ' ' + subvention.student
                        .last_name : '') + '</td>';
                    html += '<td>' + (subvention.student && subvention.student.site ? subvention.student.site
                        .designation : '') + '</td>';
                    html += '<td>' + (subvention.student && subvention.student.promotions && subvention.student
                        .promotions.length > 0 ? subvention.student.promotions[0].num_promotion : '') + '</td>';
                    html += '<td>' + (subvention.start_up_kits || '') + '</td>';
                    html += '<td>' + (subvention.grants || '') + '</td>';
                    html += '<td>' + (subvention.loan || '') + '</td>';
                    html += '<td>' + (subvention.date || '') + '</td>';
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
                html += '<h5 class="text-muted">No Subventions Found</h5>';
                html += '<p class="text-muted">There are no subventions matching the selected filters.</p>';
                html += '</div>';
            }

            $('#resultsTable').html(html);
        }
    </script>
@endsection
