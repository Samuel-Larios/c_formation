@extends('base')
@section('title', 'Salary Statistics')
@section('description', 'Salary Statistics')
@section('keywords', 'Statistics, Salaries')

@section('content')
    <div class="container">
        <h1>Salary Statistics</h1>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Filter Salaries</h3>
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
                <h3>Salary Results</h3>
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
                filterSalaries();
            });

            // Export button click
            $('#exportBtn').click(function() {
                exportSalaries();
            });

            // Initial load
            filterSalaries();
        });

        function filterSalaries() {
            let formData = $('#filterForm').serialize();

            $.post('/student-statistics/filter-salaries', formData)
                .done(function(data) {
                    displayResults(data);
                })
                .fail(function() {
                    alert('Error filtering salaries');
                });
        }

        function exportSalaries() {
            let formData = $('#filterForm').serialize();
            window.location.href = '/student-statistics/export-salaries?' + formData;
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
                html += '<th>Company</th>';
                html += '<th>Location</th>';
                html += '<th>Employer</th>';
                html += '<th>Phone</th>';
                html += '<th>Follow-up</th>';
                html += '<th>Visit</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';

                data.data.forEach(function(salary) {
                    html += '<tr>';
                    html += '<td>' + (salary.student ? salary.student.first_name + ' ' + salary.student.last_name :
                        '') + '</td>';
                    html += '<td>' + (salary.student && salary.student.site ? salary.student.site.designation :
                        '') + '</td>';
                    html += '<td>' + (salary.student && salary.student.promotions && salary.student.promotions
                        .length > 0 ? salary.student.promotions[0].num_promotion : '') + '</td>';
                    html += '<td>' + (salary.entreprise || '') + '</td>';
                    html += '<td>' + (salary.localisation || '') + '</td>';
                    html += '<td>' + (salary.employeur || '') + '</td>';
                    html += '<td>' + (salary.tel || '') + '</td>';
                    html += '<td>' + (salary.suivit || '') + '</td>';
                    html += '<td>' + (salary.visite || '') + '</td>';
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
                html += '<h5 class="text-muted">No Salaries Found</h5>';
                html += '<p class="text-muted">There are no salaries matching the selected filters.</p>';
                html += '</div>';
            }

            $('#resultsTable').html(html);
        }
    </script>
@endsection
