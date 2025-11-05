@extends('base')
@section('title', 'Follow-ups Statistics')
@section('description', 'Follow-ups Statistics')
@section('keywords', 'Statistics, Follow-ups')

@section('content')
    <div class="container">
        <h1>Follow-ups Statistics</h1>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Filter Follow-ups</h3>
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
                <h3>Follow-ups Results</h3>
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
                filterFollowUps();
            });

            // Export button click
            $('#exportBtn').click(function() {
                exportFollowUps();
            });

            // Initial load
            filterFollowUps();
        });

        function filterFollowUps() {
            let formData = $('#filterForm').serialize();

            $.post('/student-statistics/filter-follow-ups', formData)
                .done(function(data) {
                    displayResults(data);
                })
                .fail(function() {
                    alert('Error filtering follow-ups');
                });
        }

        function exportFollowUps() {
            let formData = $('#filterForm').serialize();
            window.location.href = '/student-statistics/export-follow-ups?' + formData;
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
                html += '<th>Farm Visits</th>';
                html += '<th>Phone Contact</th>';
                html += '<th>Sharing of Impact Stories</th>';
                html += '<th>Back Stopping</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';

                data.data.forEach(function(followUp) {
                    html += '<tr>';
                    html += '<td>' + (followUp.student ? followUp.student.first_name + ' ' + followUp.student
                        .last_name : '') + '</td>';
                    html += '<td>' + (followUp.student && followUp.student.site ? followUp.student.site
                        .designation : '') + '</td>';
                    html += '<td>' + (followUp.student && followUp.student.promotions && followUp.student.promotions
                        .length > 0 ? followUp.student.promotions[0].num_promotion : '') + '</td>';
                    html += '<td>' + (followUp.farm_visits || '') + '</td>';
                    html += '<td>' + (followUp.phone_contact || '') + '</td>';
                    html += '<td>' + (followUp.sharing_of_impact_stories || '') + '</td>';
                    html += '<td>' + (followUp.back_stopping || '') + '</td>';
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
                html += '<h5 class="text-muted">No Follow-ups Found</h5>';
                html += '<p class="text-muted">There are no follow-ups matching the selected filters.</p>';
                html += '</div>';
            }

            $('#resultsTable').html(html);
        }
    </script>
@endsection
