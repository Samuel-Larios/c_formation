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
                        @if (isset($aggregateAcrossSites) && $aggregateAcrossSites)
                            <small class="text-warning"><i class="fas fa-info-circle"></i> Calculations aggregate students
                                from this promotion across all sites.</small>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('jobcreations.index') }}">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                <select name="promotion" id="promotion" class="form-select" onchange="this.form.submit()">
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

                        <!-- Header -->
                        <div class="card-header bg-success text-white d-flex align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i> Student Gender Distribution
                            </h5>
                        </div>

                        <!-- Body -->
                        <div class="card-body">

                            <!-- Section title -->
                            <h6 class="text-info mb-3">
                                <i class="fas fa-users me-2"></i> Selected Promotion
                            </h6>

                            <!-- Stats row -->
                            <div class="row text-center g-3">

                                <!-- Males -->
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded border h-100">
                                        <i class="fas fa-male fa-2x text-primary mb-2"></i>
                                        <h4 class="text-primary mb-1">{{ $studentGenderCounts['M'] ?? 0 }}</h4>
                                        <small class="text-muted">Males (M)</small>
                                    </div>
                                </div>

                                <!-- Females -->
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded border h-100">
                                        <i class="fas fa-female fa-2x text-danger mb-2"></i>
                                        <h4 class="text-danger mb-1">{{ $studentGenderCounts['F'] ?? 0 }}</h4>
                                        <small class="text-muted">Females (F)</small>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="col-4">
                                    <div class="p-3 rounded border h-100 text-white"
                                        style="background: linear-gradient(135deg, #28a745, #218838);">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <h4 class="mb-1">{{ $totalStudents }}</h4>
                                        <small>Total Students</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if ($promotionId)
            <!-- Job Creation Statistics -->
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="fw-bold text-center mb-4">Job Creation Statistics for Selected Promotion</h3>
                    <div class="row g-4">

                        <!-- Total Students -->
                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow border-0 h-100">
                                <div class="card-header bg-info text-white text-center fw-semibold">
                                    Total Students in Promotion
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="fw-bold">{{ $totalStudentsInPromotion }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Students with Jobs -->
                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow border-0 h-100">
                                <div class="card-header bg-success text-white text-center fw-semibold">
                                    Students with Jobs
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="fw-bold">{{ $studentsWithJobs }}</h2>
                                    @if (isset($studentsWithJobsByGender))
                                        <hr>
                                        <div class="d-flex justify-content-around">
                                            <div class="text-center">
                                                <i class="fas fa-male fa-2x text-primary mb-2"></i>
                                                <h6 class="mb-1">Men</h6>
                                                <span
                                                    class="badge bg-primary fs-6">{{ $studentsWithJobsByGender['M'] ?? 0 }}</span>
                                            </div>
                                            <div class="text-center">
                                                <i class="fas fa-female fa-2x text-danger mb-2"></i>
                                                <h6 class="mb-1">Women</h6>
                                                <span
                                                    class="badge bg-danger fs-6">{{ $studentsWithJobsByGender['F'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Students without Jobs -->
                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow border-0 h-100">
                                <div class="card-header bg-warning text-dark text-center fw-semibold">
                                    Students without Jobs
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="fw-bold">{{ $studentsWithoutJobs }}</h2>
                                    @if (isset($studentsWithoutJobsByGender))
                                        <hr>
                                        <div class="d-flex justify-content-around">
                                            <div class="text-center">
                                                <i class="fas fa-male fa-2x text-primary mb-2"></i>
                                                <h6 class="mb-1">Men</h6>
                                                <span
                                                    class="badge bg-primary fs-6">{{ $studentsWithoutJobsByGender['M'] ?? 0 }}</span>
                                            </div>
                                            <div class="text-center">
                                                <i class="fas fa-female fa-2x text-danger mb-2"></i>
                                                <h6 class="mb-1">Women</h6>
                                                <span
                                                    class="badge bg-danger fs-6">{{ $studentsWithoutJobsByGender['F'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Total Job Creations -->
                        <div class="col-md-3 col-sm-6">
                            <div class="card shadow border-0 h-100">
                                <div class="card-header text-white text-center fw-semibold"
                                    style="background: linear-gradient(135deg, #28a745, #218838);">
                                    Total Job Creations
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="fw-bold">{{ $totalJobCreations }}</h2>
                                    @if (isset($jobCreatorsByGender))
                                        <hr>
                                        <div class="d-flex justify-content-around">
                                            <div class="text-center">
                                                <i class="fas fa-male fa-2x text-primary mb-2"></i>
                                                <h6 class="mb-1">Men</h6>
                                                <span
                                                    class="badge bg-primary fs-6">{{ $jobCreatorsByGender['Homme'] ?? 0 }}</span>
                                            </div>
                                            <div class="text-center">
                                                <i class="fas fa-female fa-2x text-danger mb-2"></i>
                                                <h6 class="mb-1">Women</h6>
                                                <span
                                                    class="badge bg-danger fs-6">{{ $jobCreatorsByGender['Femme'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                    @endif
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

                        <!-- Header -->
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-percentage me-2"></i> Job Creation Target Achievement (70%)
                            </h5>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <div class="row text-center align-items-center">

                                <!-- Expected -->
                                <div class="col-md-4 border-end">
                                    <i class="fas fa-user-check fa-2x text-primary mb-2"></i>
                                    <h5 class="mb-1">Expected Students with Jobs</h5>
                                    <h3 class="text-primary fw-bold">{{ $expectedStudentsWithJobs }}</h3>
                                </div>

                                <!-- Actual -->
                                <div class="col-md-4 border-end">
                                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                                    <h5 class="mb-1">Actual Percentage</h5>
                                    <h3 class="fw-bold {{ $isReached ? 'text-success' : 'text-danger' }}">
                                        {{ $actualPercentage }}%
                                    </h3>
                                </div>

                                <!-- Difference -->
                                <div class="col-md-4">
                                    <i
                                        class="fas fa-exchange-alt fa-2x {{ $difference >= 0 ? 'text-success' : 'text-danger' }} mb-2"></i>
                                    <h5 class="mb-1">Difference</h5>
                                    <h3 class="fw-bold {{ $difference >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $difference }}%
                                    </h3>
                                    <small class="fw-semibold {{ $isReached ? 'text-success' : 'text-danger' }}">
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
        @if ($promotionId)
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
