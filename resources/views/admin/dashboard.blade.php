@extends('base')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Global statistics and information</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('students.index') }}" class="btn btn-label-info btn-round me-2">View Students</a>
                <a href="{{ route('students.create') }}" class="btn btn-primary btn-round">Add Student</a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Students</p>
                                    <h4 class="card-title">{{ $totalStudents }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Specializations</p>
                                    <h4 class="card-title">{{ $totalSpecializations }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Promotions</p>
                                    <h4 class="card-title">{{ $totalPromotions }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Sites</p>
                                    <h4 class="card-title">{{ $totalSites }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics Cards -->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Job Creations</p>
                                    <h4 class="card-title">{{ $totalJobCreations }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Salaries</p>
                                    <h4 class="card-title">{{ $totalSalaries }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Subventions</p>
                                    <h4 class="card-title">{{ $totalSubventions }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Evaluations</p>
                                    <h4 class="card-title">{{ $totalEvaluations }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-round">
                    <div class="card-body pb-0">
                        <div class="mb-4 mt-2">
                            <h4>Number of Users by Training center</h4>
                            <ul class="list-group">
                                @foreach($usersPerSite as $site)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $site->site_name }}
                                        <span class="badge bg-primary rounded-pill">{{ $site->total_users }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-primary card-round">
                    <div class="card-body pb-0">
                        <div class="mb-4 mt-2">
                            <h4>Number of Students by training center and Promotion</h4>
                            @foreach($studentsPerSitePerPromotion as $siteName => $promotions)
                                <div class="mb-3">
                                    <h5>{{ $siteName }}</h5>
                                    <ul class="list-group">
                                        @foreach($promotions as $promotion)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Promotion {{ $promotion->promotion_name }}
                                                <span class="badge bg-primary rounded-pill">{{ $promotion->total_students }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-primary card-round">
                    <div class="card-body pb-0">
                        <div class="mb-4 mt-2">
                            <h4>Number of Students by Specialization and training center</h4>
                            @foreach($studentsPerSpecializationPerSite as $siteName => $specializations)
                                <div class="mb-3">
                                    <h5>{{ $siteName }}</h5>
                                    <ul class="list-group">
                                        @foreach($specializations as $specialization)
                                            <li class="list-group-item d-flex justify-content-between align-items-center my-1">
                                                {{ $specialization->specialization_name }}
                                                <span class="badge bg-primary rounded-pill">{{ $specialization->total_students }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Charts and Tables -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Student Statistics</div>
                            <div class="card-tools">
                                <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                    <span class="btn-label">
                                        <i class="fa fa-pencil"></i>
                                    </span>
                                    Export
                                </a>
                                <a href="#" class="btn btn-label-info btn-round btn-sm">
                                    <span class="btn-label">
                                        <i class="fa fa-print"></i>
                                    </span>
                                    Print
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="min-height: 375px">
                            <canvas id="statisticsChart"></canvas>
                        </div>
                        <div id="myChartLegend"></div>
                    </div>
                </div>
            </div>

        </div>


        <!-- Site Statistics Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <h4 class="card-title">Training center Statistics</h4>
                            <div class="card-tools">
                                <button class="btn btn-icon btn-link btn-primary btn-xs">
                                    <span class="fa fa-angle-down"></span>
                                </button>
                                <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                                    <span class="fa fa-sync-alt"></span>
                                </button>
                                <button class="btn btn-icon btn-link btn-primary btn-xs">
                                    <span class="fa fa-times"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Training center</th>
                                        <th>Students</th>
                                        <th>Entities</th>
                                        <th>Job Creations</th>
                                        <th>Salaries</th>
                                        <th>Subventions</th>
                                        <th>Specializations</th>
                                        <th>Evaluations</th>
                                        <th>Follow-ups</th>
                                        <th>Business Statuses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siteStatistics as $site)
                                        <tr>
                                            <td>{{ $site->site_name }}</td>
                                            <td>{{ $site->total_students }}</td>
                                            <td>{{ $site->total_entities }}</td>
                                            <td>{{ $site->total_job_creations }}</td>
                                            <td>{{ $site->total_salaries }}</td>
                                            <td>{{ $site->total_subventions }}</td>
                                            <td>{{ $site->total_specializations }}</td>
                                            <td>{{ $site->total_evaluations }}</td>
                                            <td>{{ $site->total_follow_ups }}</td>
                                            <td>{{ $site->total_business_statuses }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students by Site Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <h4 class="card-title">Students by training center</h4>
                            <div class="card-tools">
                                <button class="btn btn-icon btn-link btn-primary btn-xs">
                                    <span class="fa fa-angle-down"></span>
                                </button>
                                <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                                    <span class="fa fa-sync-alt"></span>
                                </button>
                                <button class="btn btn-icon btn-link btn-primary btn-xs">
                                    <span class="fa fa-times"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Training center</th>
                                        <th class="text-end">Number of Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($studentsPerSite as $site)
                                        <tr>
                                            <td>{{ $site->designation }}</td>
                                            <td class="text-end">{{ $site->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students by State of Origin Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <h4 class="card-title">Students by State of Origin</h4>
                            <div class="card-tools">
                                <button class="btn btn-icon btn-link btn-primary btn-xs">
                                    <span class="fa fa-angle-down"></span>
                                </button>
                                <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                                    <span class="fa fa-sync-alt"></span>
                                </button>
                                <button class="btn btn-icon btn-link btn-primary btn-xs">
                                    <span class="fa fa-times"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>State of Origin</th>
                                        <th class="text-end">Number of Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($studentsPerStateOfOrigin as $state)
                                        <tr>
                                            <td>{{ $state->state_of_origin }}</td>
                                            <td class="text-end">{{ $state->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
