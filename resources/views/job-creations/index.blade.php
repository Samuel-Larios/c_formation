@extends('base_admin')

@section('title', 'Job Creation Statistics')

@section('content')
    <div class="container">
        <h1>Job Creation Statistics</h1>

        <div class="mb-3">
            <a href="{{ route('job-creations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Job Creation
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-filter"></i> Filter by Promotion</h5>
                        @if(isset($aggregateAcrossSites) && $aggregateAcrossSites)
                            <small class="text-warning"><i class="fas fa-info-circle"></i> Les calculs agrègent les étudiants de cette promotion sur tous les sites.</small>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('jobcreations.index') }}">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                <select name="promotion" id="promotion" class="form-select"
                                    onchange="this.form.submit()">
                                    <option value="">All promotions</option>
                                    @foreach ($promotions as $promotion)
                                        <option value="{{ $promotion->num_promotion }}"
                                            {{ isset($promotionId) && $promotionId == $promotion->num_promotion ? 'selected' : '' }}>
                                            {{ $promotion->num_promotion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if (isset($studentGenderCounts) && count($studentGenderCounts) > 0)
                <div class="col-md-6">
                    <div class="card border-success shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-chart-pie"></i> Student Gender Distribution</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="text-info mb-3"><i class="fas fa-users"></i> Selected Promotion</h6>
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded border">
                                        <i class="fas fa-male fa-2x text-primary mb-2"></i>
                                        <h4 class="text-primary">{{ $studentGenderCounts['M'] ?? 0 }}</h4>
                                        <small class="text-muted">Males (M)</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded border">
                                        <i class="fas fa-female fa-2x text-danger mb-2"></i>
                                        <h4 class="text-danger">{{ $studentGenderCounts['F'] ?? 0 }}</h4>
                                        <small class="text-muted">Females (F)</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-success rounded border">
                                        <i class="fas fa-users fa-2x text-white mb-2"></i>
                                        <h4 class="text-white">{{ $totalStudents }}</h4>
                                        <small class="text-white">Total Students with Jobs</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if($promotionId)
        <!-- Job Creation Statistics -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h3>Job Creation Statistics for Selected Promotion</h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">Total Students in Promotion</h5>
                                <h2>{{ $totalStudentsInPromotion }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">Students with Jobs</h5>
                                <h2>{{ $studentsWithJobs }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">Students without Jobs</h5>
                                <h2>{{ $studentsWithoutJobs }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">Total Job Creations</h5>
                                <h2>{{ $totalJobCreations }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Percentage Achievement -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-info shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-percentage"></i> Job Creation Target Achievement (70%)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h5>Expected Students with Jobs</h5>
                                <h3 class="text-primary">{{ $expectedStudentsWithJobs }}</h3>
                            </div>
                            <div class="col-md-4">
                                <h5>Actual Percentage</h5>
                                <h3 class="{{ $isReached ? 'text-success' : 'text-danger' }}">{{ $actualPercentage }}%</h3>
                            </div>
                            <div class="col-md-4">
                                <h5>Difference</h5>
                                <h3 class="{{ $difference >= 0 ? 'text-success' : 'text-danger' }}">{{ $difference }}%</h3>
                                <small class="{{ $isReached ? 'text-success' : 'text-danger' }}">
                                    {{ $isReached ? 'Target Reached' : 'Target Not Reached' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histogram -->
        <div class="mb-4">
            <h3>Job Creation Histogram</h3>
            <canvas id="jobCreationHistogram" width="400" height="200"></canvas>
        </div>
        @endif

        <!-- Student Summary Table -->
        <div class="card border-info shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0"><i class="fas fa-users"></i> Student Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-user me-1"></i>Student</th>
                                <th><i class="fas fa-briefcase me-1"></i>Employers</th>
                                <th><i class="fas fa-users me-1"></i>Number of People Working With</th>
                                <th><i class="fas fa-venus-mars me-1"></i>Gender</th>
                                <th><i class="fas fa-cogs me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($studentJobCounts as $studentJob)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle bg-info me-3">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $studentJob->student->first_name ?? 'N/A' }}
                                                    {{ $studentJob->student->last_name ?? 'N/A' }}</h6>
                                                <small class="text-muted">Student</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap">
                                            @foreach (explode(',', $studentJob->employers) as $employerData)
                                                @php
                                                    $parts = explode(':', $employerData);
                                                    $jobId = $parts[0];
                                                    $employerName = $parts[1];
                                                @endphp
                                                <div class="d-flex align-items-center me-2 mb-1">
                                                    <span class="badge bg-primary me-1">{{ trim($employerName) }}</span>
                                                    <a href="{{ route('job-creations.edit', $jobId) }}"
                                                        class="btn btn-sm btn-warning">Edit</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-success rounded-pill fs-6">{{ $studentJob->job_count }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-info rounded-pill fs-6">{{ $studentJob->student->sexe ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        {{-- <a href="{{ route('job-creations.create', ['student_id' => $studentJob->student->id]) }}" class="btn btn-sm btn-primary me-2">Add Job</a> --}}
                                        <a href="{{ route('jobcreations.index', ['student_id' => $studentJob->student->id]) }}"
                                            class="btn btn-sm btn-secondary">View Jobs</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Data Available</h5>
                                        <p class="text-muted">No job creations found for the selected criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $studentJobCounts->links('pagination::bootstrap-5') }}
        </div>

    </div>

    <!-- Scripts for Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if($promotionId)
        // Histogram for job creations
        const histogramCtx = document.getElementById('jobCreationHistogram');
        if (histogramCtx) {
            const histogramData = @json($histogramData);
            const histogramChart = new Chart(histogramCtx, {
                type: 'bar',
                data: {
                    labels: ['Students with Jobs', 'Students without Jobs'],
                    datasets: [{
                        label: 'Number of Students',
                        data: [histogramData.students_with_jobs, histogramData.students_without_jobs],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(220, 53, 69, 0.8)'
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Students with and without Job Creations'
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Students'
                            }
                        }
                    }
                }
            });
        }
        @endif
    </script>
@endsection
