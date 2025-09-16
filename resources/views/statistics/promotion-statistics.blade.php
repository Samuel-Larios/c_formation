@extends('base_admin')

@section('title', 'Promotion Statistics')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <h3 class="fw-bold mb-3"><i class="fas fa-chart-line me-2"></i>Promotion Statistics</h3>
            <p class="text-muted">Analyze promotion enrollments and compare with expected numbers</p>
        </div>

        <div class="row">
            <!-- Selection Form -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filters</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('promotion.statistics') }}">
                            <div class="mb-3">
                                <label for="promotion" class="form-label fw-semibold">Select a promotion:</label>
                                <select name="promotion" id="promotion" class="form-select" onchange="this.form.submit()">
                                    <option value="">Choose a promotion</option>
                                    @foreach ($promotions as $promotion)
                                        <option value="{{ $promotion->id }}"
                                            {{ request('promotion') == $promotion->id ? 'selected' : '' }}>
                                            {{ $promotion->num_promotion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="expected" class="form-label fw-semibold">Expected number of students:</label>
                                <input type="number" name="expected" id="expected" class="form-control"
                                    value="{{ request('expected', 0) }}" min="0" onchange="this.form.submit()">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="col-lg-8">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <div class="text-primary mb-2">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <h4 class="card-title">{{ $currentStudentsCount }}</h4>
                                <p class="card-text text-muted">Actual Students</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <div class="text-success mb-2">
                                    <i class="fas fa-target fa-2x"></i>
                                </div>
                                <h4 class="card-title">{{ $expectedStudents }}</h4>
                                <p class="card-text text-muted">Expected Students</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Comparison Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Comparison for Selected Promotion
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="comparisonChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Last 5 Promotions Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-chart-line me-2"></i>Last 5 Promotions Trend</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="lastFiveChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution Chart -->
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-venus-mars me-2"></i>Gender Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="genderChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts for Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Comparison chart
            const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
            const comparisonData = {
                labels: ['Actual Students', 'Expected Students'],
                datasets: [{
                    label: 'Number of students',
                    data: [{{ $currentStudentsCount }}, {{ $expectedStudents }}],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 5,
                    borderSkipped: false,
                }]
            };
            new Chart(comparisonCtx, {
                type: 'bar',
                data: comparisonData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Chart for the last 5 promotions
            const lastFiveCtx = document.getElementById('lastFiveChart').getContext('2d');
            const lastFiveData = @json($lastFiveData);
            const lastFiveLabels = lastFiveData.map(item => item.promotion);
            const lastFiveCounts = lastFiveData.map(item => item.student_count);
            const lineData = {
                labels: lastFiveLabels,
                datasets: [{
                    label: 'Number of students',
                    data: lastFiveCounts,
                    fill: true,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    tension: 0.4
                }]
            };
            new Chart(lastFiveCtx, {
                type: 'line',
                data: lineData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Gender distribution chart
            const genderCtx = document.getElementById('genderChart').getContext('2d');
            fetch('/api/promotion-gender-distribution?promotion_id={{ $selectedPromotionId ?? '' }}')
                .then(response => response.json())
                .then(data => {
                    const genderData = {
                        labels: ['Male', 'Female'],
                        datasets: [{
                            label: 'Number of Students',
                            data: [data.male || 0, data.female || 0],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 99, 132, 0.8)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 2,
                            borderRadius: 5,
                            borderSkipped: false,
                        }]
                    };
                    new Chart(genderCtx, {
                        type: 'bar',
                        data: genderData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0,0,0,0.1)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching gender distribution data:', error);
                });
        </script>
    </div>
@endsection
