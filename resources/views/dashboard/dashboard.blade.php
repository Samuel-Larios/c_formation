@extends('base_admin')

@section('title', 'Dashboard')


<style>
    .card-hover {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #4e73df;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2e59d9;
    }

    .card-category {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        height: 4px;
    }

    .progress-bar {
        border-radius: 10px;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    .bg-gradient-dark {
        background: linear-gradient(45deg, #5a5c69, #373840);
    }

    .bg-primary {
        background-color: #4e73df !important;
    }

    .bg-success {
        background-color: #1cc88a !important;
    }

    .bg-warning {
        background-color: #f6c23e !important;
    }

    .bg-danger {
        background-color: #e74a3b !important;
    }

    .text-primary {
        color: #4e73df !important;
    }

    .text-success {
        color: #1cc88a !important;
    }

    .text-warning {
        color: #f6c23e !important;
    }

    .text-danger {
        color: #e74a3b !important;
    }
</style>

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Dashboard</h1>

        <div class="row">
            <!-- Total Students -->
            <div class="col-md-3 mb-4">
                <div class="card card-stats card-round card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-primary me-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">{{ number_format($totalStudents) }}</h5>
                                <p class="card-category mb-0">Total Students</p>
                            </div>
                            {{-- <div class="text-end">
                                <span class="badge bg-primary rounded-pill">+{{ rand(5, 20) }}%</span>
                            </div> --}}
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-primary"
                                style="width: {{ min(($totalStudents / 1000) * 100, 100) }}%"></div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-building me-1"></i>
                                Across {{ $studentsPerSite->count() }} centers
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Promotions -->
            <div class="col-md-3 mb-4">
                <div class="card card-stats card-round card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-success me-3">
                                <i class="fas fa-graduation-cap text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">{{ number_format($totalPromotions) }}</h5>
                                <p class="card-category mb-0">Total Promotions</p>
                            </div>
                            {{-- <div class="text-end">
                                <span class="badge bg-success rounded-pill">+{{ rand(2, 10) }}%</span>
                            </div> --}}
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ min(($totalPromotions / 20) * 100, 100) }}%"></div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-chalkboard me-1"></i>
                                Active learning programs
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Specializations -->
            <div class="col-md-3 mb-4">
                <div class="card card-stats card-round card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-warning me-3">
                                <i class="fas fa-bookmark text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">{{ number_format($totalSpecializations) }}</h5>
                                <p class="card-category mb-0">Specializations</p>
                            </div>
                            {{-- <div class="text-end">
                                <span class="badge bg-warning rounded-pill">+{{ rand(3, 15) }}%</span>
                            </div> --}}
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-warning"
                                style="width: {{ min(($totalSpecializations / 50) * 100, 100) }}%"></div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-tags me-1"></i>
                                Diverse skill areas
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Entities -->
            <div class="col-md-3 mb-4">
                <div class="card card-stats card-round card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-danger me-3">
                                <i class="fas fa-building text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">{{ number_format($totalEntities) }}</h5>
                                <p class="card-category mb-0">Business Entities</p>
                            </div>
                            {{-- <div class="text-end">
                                <span class="badge bg-danger rounded-pill">+{{ rand(8, 25) }}%</span>
                            </div> --}}
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-danger"
                                style="width: {{ min(($totalEntities / 100) * 100, 100) }}%"></div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-briefcase me-1"></i>
                                Entrepreneurial ventures
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Summary Row -->
        <div class="row">
            <div class="col-12">
                <div class="card card-round bg-gradient-dark text-white">
                    <div class="card-body py-3">
                        <div class="row text-center">
                            <div class="col-6 col-md-3 mb-2 mb-md-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user-graduate fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">{{ number_format($totalStudents) }}</h4>
                                        <small>Students Trained</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-2 mb-md-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-chalkboard-teacher fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">{{ number_format($totalPromotions) }}</h4>
                                        <small>Promotions</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-2 mb-md-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-graduation-cap fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">{{ number_format($totalSpecializations) }}</h4>
                                        <small>Specializations</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-city fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">{{ number_format($totalEntities) }}</h4>
                                        <small>Business Entities</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-6">
                <canvas id="studentsBySiteChart"></canvas>
                <p class="text-center mt-2">Number of students per site</p>
            </div>
            <div class="col-md-6">
                <canvas id="studentsByPromotionChart"></canvas>
                <p class="text-center mt-2">Distribution of students by promotion</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <canvas id="studentsBySpecializationChart"></canvas>
                <p class="text-center mt-2">Distribution of students by specialization</p>
            </div>
            <div class="col-md-6">
                <canvas id="salariesByLocationChart"></canvas>
                <p class="text-center mt-2">Number of salaries by location</p>
            </div>
        </div>

        <!-- New section: Charts on student data -->
        <div class="row mt-4">
            <div class="col-md-6">
                <canvas id="studentsBySexeChart"></canvas>
                <p class="text-center mt-2">Distribution of students by gender</p>
            </div>
            <div class="col-md-6">
                <canvas id="averageAgeBySiteChart"></canvas>
                <p class="text-center mt-2">Average age of students per site</p>
            </div>
        </div>

        <!-- Promotion Summary Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-round">
                    <div class="card-header bg-gradient-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title text-white mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Promotion Summary
                            </h4>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white text-primary me-2">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    {{ $totalPromotionsSite }} Promotions
                                </span>
                                <span class="badge bg-white text-primary">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $totalStudentsInPromotionsSite }} Students
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($promotionSummary->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th><i class="fas fa-graduation-cap me-1"></i>Promotion</th>
                                            <th><i class="fas fa-users me-1"></i>Total Students</th>
                                            <th><i class="fas fa-chart-line me-1"></i>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($promotionSummary as $promotion)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-circle bg-primary me-3">
                                                            <i class="fas fa-graduation-cap text-white"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-bold">{{ $promotion->num_promotion }}</h6>
                                                            <small class="text-muted">Active Promotion</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-success rounded-pill me-2">
                                                            {{ $promotion->total_students }}
                                                        </span>
                                                        <small class="text-muted">students enrolled</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($promotion->total_students > 0)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Active
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>Empty
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr class="fw-bold">
                                            <td>
                                                <i class="fas fa-sigma me-1"></i>Total
                                            </td>
                                            <td>
                                                {{ $totalStudentsInPromotionsSite }} students
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $totalPromotionsSite }} promotions</small>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Summary Cards -->
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="card card-stats card-round bg-light">
                                        <div class="card-body text-center">
                                            <div class="icon-big text-primary mb-2">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <h4 class="text-primary mb-0">{{ $totalPromotionsSite }}</h4>
                                            <small class="text-muted">Total Promotions</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card card-stats card-round bg-light">
                                        <div class="card-body text-center">
                                            <div class="icon-big text-success mb-2">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <h4 class="text-success mb-0">{{ $totalStudentsInPromotionsSite }}</h4>
                                            <small class="text-muted">Total Students</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card card-stats card-round bg-light">
                                        <div class="card-body text-center">
                                            <div class="icon-big text-info mb-2">
                                                <i class="fas fa-chart-pie"></i>
                                            </div>
                                            <h4 class="text-info mb-0">{{ $promotionSummary->where('total_students', '>', 0)->count() }}</h4>
                                            <small class="text-muted">Active Promotions</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Promotion Data Available</h5>
                                <p class="text-muted">There are no promotions configured for your site yet.</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-sync-alt me-1"></i>
                                Updated: {{ now()->format('M j, Y H:i') }}
                            </small>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2" onclick="window.location.reload()">
                                    <i class="fas fa-sync-alt me-1"></i>Refresh
                                </button>
                                <a href="{{ route('promotions.index') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>Manage Promotions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotion Distribution Chart -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-round">
                    <div class="card-header bg-gradient-info">
                        <h4 class="card-title text-white mb-0">
                            <i class="fas fa-chart-line me-2"></i>Promotion Distribution Chart
                        </h4>
                    </div>
                    <div class="card-body" style="height: 400px; position: relative;">
                        <canvas id="promotionDistributionChart" style="max-height: 300px;"></canvas>
                        <p class="text-center mt-2">Student distribution across promotions</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctxSite = document.getElementById('studentsBySiteChart').getContext('2d');
            var studentsBySiteChart = new Chart(ctxSite, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($studentsBySiteLabels) !!},
                    datasets: [{
                        label: 'Students by Site',
                        data: {!! json_encode($studentsBySiteData) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var ctxPromotion = document.getElementById('studentsByPromotionChart').getContext('2d');
            var studentsByPromotionChart = new Chart(ctxPromotion, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($studentsByPromotionLabels) !!},
                    datasets: [{
                        label: 'Students by Promotion',
                        data: {!! json_encode($studentsByPromotionData) !!},
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });

            var ctxSpecialization = document.getElementById('studentsBySpecializationChart').getContext('2d');
            var studentsBySpecializationChart = new Chart(ctxSpecialization, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($studentsBySpecializationLabels) !!},
                    datasets: [{
                        label: 'Students by Specialization',
                        data: {!! json_encode($studentsBySpecializationData) !!},
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });

            var ctxSalaries = document.getElementById('salariesByLocationChart').getContext('2d');
            var salariesByLocationChart = new Chart(ctxSalaries, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($salariesByLocationLabels) !!},
                    datasets: [{
                        label: 'Salaries by Location',
                        data: {!! json_encode($salariesByLocationData) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.7)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // New charts
            var ctxSexe = document.getElementById('studentsBySexeChart').getContext('2d');
            var studentsBySexeChart = new Chart(ctxSexe, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($studentsBySexeLabels) !!},
                    datasets: [{
                        label: 'Students by Gender',
                        data: {!! json_encode($studentsBySexeData) !!},
                        backgroundColor: [
                            '#FF6384', '#36A2EB'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });

            var ctxAge = document.getElementById('averageAgeBySiteChart').getContext('2d');
            var averageAgeBySiteChart = new Chart(ctxAge, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($averageAgeBySiteLabels) !!},
                    datasets: [{
                        label: 'Average Age by Site',
                        data: {!! json_encode($averageAgeBySiteData) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Promotion Distribution Chart
            var ctxPromotionDist = document.getElementById('promotionDistributionChart').getContext('2d');
            var promotionDistributionChart = new Chart(ctxPromotionDist, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($promotionSummary->pluck('num_promotion')) !!},
                    datasets: [{
                        label: 'Students per Promotion',
                        data: {!! json_encode($promotionSummary->pluck('total_students')) !!},
                        backgroundColor: 'rgba(78, 115, 223, 0.7)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    maxHeight: 300,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' students';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    layout: {
                        padding: {
                            top: 10,
                            bottom: 10
                        }
                    }
                }
            });
        });




        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to cards on page load
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate__animated', 'animate__fadeInUp');
            });
        });
    </script>
@endsection
