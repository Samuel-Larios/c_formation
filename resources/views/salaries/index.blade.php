@extends('base_admin')

@section('title', 'Salary List')

@section('content')
    <div class="container">
        <h1>Salary List</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-filter"></i> Filter by Promotion</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('salaries.index') }}">
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
                                        <small class="text-white">Total Students with Salaries</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if ($promotionId)
            <!-- Salary Statistics -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3>Salary Statistics for Selected Promotion</h3>
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
                                    <h5 class="card-title">Students with Salaries</h5>
                                    <h2>{{ $studentsWithSalaries }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Students without Salaries</h5>
                                    <h2>{{ $studentsWithoutSalaries }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Salaries</h5>
                                    <h2>{{ $totalSalaries }}</h2>
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
                            <h5 class="card-title mb-0"><i class="fas fa-percentage"></i> Salary Target Achievement (30%)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <h5>Expected Students with Salaries</h5>
                                    <h3 class="text-primary">{{ $expectedStudentsWithSalaries }}</h3>
                                </div>
                                <div class="col-md-4">
                                    <h5>Actual Percentage</h5>
                                    <h3 class="{{ $isReached ? 'text-success' : 'text-danger' }}">{{ $actualPercentage }}%
                                    </h3>
                                </div>
                                <div class="col-md-4">
                                    <h5>Difference</h5>
                                    <h3 class="{{ $difference >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $difference }}%</h3>
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
                <h3>Salary Statistics Histogram</h3>
                <canvas id="salaryHistogram" width="400" height="200"></canvas>
            </div>
        @endif

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-filter"></i> Additional Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('salaries.index') }}" class="row g-3">
                    <input type="hidden" name="promotion" value="{{ request('promotion') }}">
                    <div class="col-md-3">
                        <label for="entreprise" class="form-label">Company</label>
                        <input type="text" name="entreprise" value="{{ request('entreprise') }}" class="form-control"
                            id="entreprise" placeholder="Filter by company">
                    </div>
                    <div class="col-md-3">
                        <label for="localisation" class="form-label">Location</label>
                        <input type="text" name="localisation" value="{{ request('localisation') }}"
                            class="form-control" id="localisation" placeholder="Filter by location">
                    </div>
                    <div class="col-md-3">
                        <label for="employeur" class="form-label">Employer</label>
                        <input type="text" name="employeur" value="{{ request('employeur') }}" class="form-control"
                            id="employeur" placeholder="Filter by employer">
                    </div>
                    <div class="col-md-3">
                        <label for="tel" class="form-label">Phone</label>
                        <input type="text" name="tel" value="{{ request('tel') }}" class="form-control"
                            id="tel" placeholder="Filter by phone">
                    </div>
                    <div class="col-md-3">
                        <label for="sexe" class="form-label">Gender</label>
                        <select name="sexe" class="form-control" id="sexe">
                            <option value="">All genders</option>
                            <option value="M" {{ request('sexe') == 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ request('sexe') == 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('salaries.index', ['promotion' => request('promotion')]) }}"
                            class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Export Button -->
        <div class="mb-3">
            <!-- Button to access the creation page -->
            <div class="mb-3">
                <a href="{{ route('salaries.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create a New Salary
                </a>

                <form method="POST" action="{{ route('salaries.export') }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="entreprise" value="{{ request('entreprise') }}">
                    <input type="hidden" name="localisation" value="{{ request('localisation') }}">
                    <input type="hidden" name="employeur" value="{{ request('employeur') }}">
                    <input type="hidden" name="tel" value="{{ request('tel') }}">
                    <input type="hidden" name="promotion" value="{{ request('promotion') }}">
                    <input type="hidden" name="sexe" value="{{ request('sexe') }}">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </button>
                </form>
            </div>

        </div>

        <!-- Salaries Table -->
        <div class="card">
            <div class="card-body">
                @if ($salaries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Company</th>
                                    <th>Location</th>
                                    <th>Employer</th>
                                    <th>Phone</th>
                                    <th>Student</th>
                                    <th>Gender</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salaries as $salary)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $salary->entreprise }}</td>
                                        <td>{{ $salary->localisation }}</td>
                                        <td>{{ $salary->employeur }}</td>
                                        <td>{{ $salary->tel }}</td>
                                        <td>{{ $salary->student->last_name }} {{ $salary->student->first_name }}</td>
                                        <td>{{ $salary->student->sexe }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('salaries.show', $salary->id) }}"
                                                    class="btn btn-outline-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('salaries.edit', $salary->id) }}"
                                                    class="btn btn-outline-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('salaries.destroy', $salary->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this salary?');"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $salaries->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> No salaries found.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts for Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if ($promotionId)
            // Histogram for salaries
            const histogramCtx = document.getElementById('salaryHistogram');
            if (histogramCtx) {
                const histogramData = @json($histogramData);
                const histogramChart = new Chart(histogramCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Students with Salaries', 'Students without Salaries'],
                        datasets: [{
                            label: 'Number of Students',
                            data: [histogramData.students_with_salaries, histogramData
                                .students_without_salaries
                            ],
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
                                text: 'Students with and without Salaries'
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
