@extends('base')

@section('title', 'Dashboard')


<style>
    .card-hover:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .stat-item {
        text-align: center;
        padding: 6px;
        border-radius: 6px;
        background-color: #f8f9fa;
    }

    .stat-item i {
        font-size: 1rem;
        margin-bottom: 3px;
        display: block;
    }

    .stat-number {
        display: block;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .stat-label {
        font-size: 0.7rem;
        color: #6c757d;
    }

    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .progress-group {
        margin-bottom: 15px;
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }

    .card-hover {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #e9ecef;
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

    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .card-category {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }

    .progress-bar {
        border-radius: 10px;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    .bg-primary {
        background-color: #4e73df !important;
    }

    .bg-info {
        background-color: #36b9cc !important;
    }

    .bg-success {
        background-color: #1cc88a !important;
    }

    .bg-secondary {
        background-color: #858796 !important;
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

    .text-info {
        color: #36b9cc !important;
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


    .card-hover {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .color-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .accordion-button {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px !important;
        margin-bottom: 5px;
    }

    .accordion-button:not(.collapsed) {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .accordion-body {
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }

    .bg-gradient-success {
        background: linear-gradient(45deg, #1cc88a, #13855c);
    }

    .bg-gradient-info {
        background: linear-gradient(45deg, #36b9cc, #2a8a99);
    }

    .list-group-item {
        border: none;
        padding-left: 0;
        padding-right: 0;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
</style>


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

            <!-- Statistics Overview -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card card-round bg-gradient-primary text-white">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-1">Training Center Dashboard</h3>
                                    <p class="mb-0 opacity-75">Comprehensive overview of all training activities and
                                        statistics</p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <span class="badge bg-white text-primary px-3 py-2">
                                        <i class="fas fa-sync-alt me-1"></i>
                                        Updated: {{ now()->format('M j, H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Statistics Cards -->
            <div class="row">
                <!-- Students Card -->
                <div class="col-sm-6 col-xl-3 mb-4">
                    <div class="card card-stats card-round card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-primary me-3">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ number_format($totalStudents ?? 0) }}</h5>
                                    <p class="card-category mb-0">Students</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary rounded-pill">+{{ rand(5, 20) }}%</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-primary"
                                    style="width: {{ min((($totalStudents ?? 0) / 1000) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specializations Card -->
                <div class="col-sm-6 col-xl-3 mb-4">
                    <div class="card card-stats card-round card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-info me-3">
                                    <i class="fas fa-graduation-cap text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ number_format($totalSpecializations ?? 0) }}</h5>
                                    <p class="card-category mb-0">Specializations</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-info rounded-pill">+{{ rand(3, 15) }}%</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-info"
                                    style="width: {{ min((($totalSpecializations ?? 0) / 50) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promotions Card -->
                <div class="col-sm-6 col-xl-3 mb-4">
                    <div class="card card-stats card-round card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-success me-3">
                                    <i class="fas fa-chalkboard-teacher text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ number_format($totalPromotions ?? 0) }}</h5>
                                    <p class="card-category mb-0">Promotions</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success rounded-pill">+{{ rand(2, 10) }}%</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-success"
                                    style="width: {{ min((($totalPromotions ?? 0) / 20) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sites Card -->
                <div class="col-sm-6 col-xl-3 mb-4">
                    <div class="card card-stats card-round card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-secondary me-3">
                                    <i class="fas fa-building text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ number_format($totalSites ?? 0) }}</h5>
                                    <p class="card-category mb-0">Training Centers</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-secondary rounded-pill">+{{ rand(1, 5) }}%</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-secondary"
                                    style="width: {{ min((($totalSites ?? 0) / 10) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Statistics Cards -->
            <div class="row">
                <!-- Job Creations Card -->
                <div class="col-sm-6 col-xl-3 mb-4">
                    <div class="card card-stats card-round card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-warning me-3">
                                    <i class="fas fa-briefcase text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ number_format($totalJobCreations ?? 0) }}</h5>
                                    <p class="card-category mb-0">Job Creations</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning rounded-pill">+{{ rand(8, 25) }}%</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-warning"
                                    style="width: {{ min((($totalJobCreations ?? 0) / 200) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Salaries Card -->
                <div class="col-sm-6 col-xl-3 mb-4">
                    <div class="card card-stats card-round card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-danger me-3">
                                    <i class="fas fa-money-bill-wave text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ number_format($totalSalaries ?? 0) }}</h5>
                                    <p class="card-category mb-0">Salaries</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger rounded-pill">+{{ rand(10, 30) }}%</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-danger"
                                    style="width: {{ min((($totalSalaries ?? 0) / 500) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subventions Card -->
                <div class="col-sm-6 col-xl-3 mb-4">
                    <div class="card card-stats card-round card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-info me-3">
                                    <i class="fas fa-hand-holding-usd text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ number_format($totalSubventions ?? 0) }}</h5>
                                    <p class="card-category mb-0">Subventions</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-info rounded-pill">+{{ rand(5, 18) }}%</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-info"
                                    style="width: {{ min((($totalSubventions ?? 0) / 100) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evaluations Card -->
                <div class="col-sm-6 col-xl-3 mb-4">
                    <div class="card card-stats card-round card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-success me-3">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">{{ number_format($totalEvaluations ?? 0) }}</h5>
                                    <p class="card-category mb-0">Evaluations</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success rounded-pill">+{{ rand(12, 35) }}%</span>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-success"
                                    style="width: {{ min((($totalEvaluations ?? 0) / 800) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Summary -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-round bg-light">
                        <div class="card-body py-2">
                            <div class="row text-center">
                                <div class="col-6 col-md-3 mb-2 mb-md-0">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-graduate text-primary me-2"></i>
                                        <div>
                                            <div class="fw-bold">{{ number_format($totalStudents ?? 0) }}</div>
                                            <small class="text-muted">Active Students</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2 mb-md-0">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-briefcase text-success me-2"></i>
                                        <div>
                                            <div class="fw-bold">{{ number_format($totalJobCreations ?? 0) }}</div>
                                            <small class="text-muted">Jobs Created</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2 mb-md-0">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-money-bill-wave text-warning me-2"></i>
                                        <div>
                                            <div class="fw-bold">{{ number_format($totalSalaries ?? 0) }}</div>
                                            <small class="text-muted">Salaries Paid</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-chart-pie text-info me-2"></i>
                                        <div>
                                            <div class="fw-bold">{{ number_format($totalSpecializations ?? 0) }}</div>
                                            <small class="text-muted">Specializations</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Users by Training Center -->
                <div class="col-md-4 mb-4">
                    <div class="card card-round card-hover">
                        <div class="card-header bg-gradient-primary">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title text-white mb-0">
                                    <i class="fas fa-user-friends me-2"></i>Users by Center
                                </h5>
                                <span class="badge bg-white text-primary">{{ $usersPerSite->sum('total_users') }}</span>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="list-group list-group-flush">
                                @foreach ($usersPerSite as $site)
                                    <div
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="color-indicator bg-primary me-3"></div>
                                            <span class="fw-medium">{{ Str::limit($site->site_name, 20) }}</span>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">{{ $site->total_users }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-light py-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Total users across all centers
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Students by Promotion -->
                <div class="col-md-4 mb-4">
                    <div class="card card-round card-hover">
                        <div class="card-header bg-gradient-success">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title text-white mb-0">
                                    <i class="fas fa-graduation-cap me-2"></i>Students by Promotion
                                </h5>
                                <span class="badge bg-white text-success">
                                    @php
                                        $totalStudentsPromo = 0;
                                        foreach ($studentsPerSitePerPromotion as $promotions) {
                                            $totalStudentsPromo += collect($promotions)->sum('total_students');
                                        }
                                        echo $totalStudentsPromo;
                                    @endphp
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="promotionAccordion">
                                @foreach ($studentsPerSitePerPromotion as $siteName => $promotions)
                                    <div class="accordion-item border-0">
                                        <h6 class="accordion-header">
                                            <button class="accordion-button collapsed py-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#promo{{ Str::slug($siteName) }}" aria-expanded="false">
                                                <div class="d-flex justify-content-between w-100 me-3">
                                                    <span class="fw-medium">{{ Str::limit($siteName, 15) }}</span>
                                                    <span class="badge bg-success rounded-pill">
                                                        {{ collect($promotions)->sum('total_students') }}
                                                    </span>
                                                </div>
                                            </button>
                                        </h6>
                                        <div id="promo{{ Str::slug($siteName) }}" class="accordion-collapse collapse"
                                            data-bs-parent="#promotionAccordion">
                                            <div class="accordion-body py-2">
                                                @foreach ($promotions as $promotion)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Promo
                                                            {{ $promotion->promotion_name }}</span>
                                                        <span
                                                            class="badge bg-success rounded-pill">{{ $promotion->total_students }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-light py-2">
                            <small class="text-muted">
                                <i class="fas fa-chart-line me-1"></i>
                                Click to view promotion details
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Students by Specialization -->
                <div class="col-md-4 mb-4">
                    <div class="card card-round card-hover">
                        <div class="card-header bg-gradient-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title text-white mb-0">
                                    <i class="fas fa-bookmark me-2"></i>Students by Specialization
                                </h5>
                                <span class="badge bg-white text-info">
                                    @php
                                        $totalStudentsSpec = 0;
                                        foreach ($studentsPerSpecializationPerSite as $specializations) {
                                            $totalStudentsSpec += collect($specializations)->sum('total_students');
                                        }
                                        echo $totalStudentsSpec;
                                    @endphp
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="specializationAccordion">
                                @foreach ($studentsPerSpecializationPerSite as $siteName => $specializations)
                                    <div class="accordion-item border-0">
                                        <h6 class="accordion-header">
                                            <button class="accordion-button collapsed py-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#spec{{ Str::slug($siteName) }}" aria-expanded="false">
                                                <div class="d-flex justify-content-between w-100 me-3">
                                                    <span class="fw-medium">{{ Str::limit($siteName, 15) }}</span>
                                                    <span class="badge bg-info rounded-pill">
                                                        {{ collect($specializations)->sum('total_students') }}
                                                    </span>
                                                </div>
                                            </button>
                                        </h6>
                                        <div id="spec{{ Str::slug($siteName) }}" class="accordion-collapse collapse"
                                            data-bs-parent="#specializationAccordion">
                                            <div class="accordion-body py-2">
                                                @foreach ($specializations as $specialization)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span
                                                            class="text-muted">{{ Str::limit($specialization->specialization_name, 20) }}</span>
                                                        <span
                                                            class="badge bg-info rounded-pill">{{ $specialization->total_students }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-light py-2">
                            <small class="text-muted">
                                <i class="fas fa-graduation-cap me-1"></i>
                                Click to view specialization details
                            </small>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Training Center Statistics -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <h4 class="card-title">Training Center Statistics Overview</h4>
                                <div class="card-tools">
                                    <button class="btn btn-icon btn-link btn-primary btn-xs" data-toggle="tooltip"
                                        title="Minimize">
                                        <span class="fa fa-angle-down"></span>
                                    </button>
                                    <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"
                                        data-toggle="tooltip" title="Refresh">
                                        <span class="fa fa-sync-alt"></span>
                                    </button>
                                    <button class="btn btn-icon btn-link btn-primary btn-xs" data-toggle="tooltip"
                                        title="Close">
                                        <span class="fa fa-times"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($siteStatistics as $site)
                                    <div class="col-md-6 col-lg-3 mb-4">
                                        <div class="card card-stats card-round card-hover">
                                            <div class="card-header bg-gradient-primary">
                                                <h6 class="card-title text-white mb-0">
                                                    {{ Str::limit($site->site_name, 20) }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="stats-grid">
                                                    <div class="stat-item">
                                                        <i class="fas fa-users text-info"></i>
                                                        <span class="stat-number">{{ $site->total_students }}</span>
                                                        <small class="stat-label">Students</small>
                                                    </div>
                                                    <div class="stat-item">
                                                        <i class="fas fa-building text-success"></i>
                                                        <span class="stat-number">{{ $site->total_entities }}</span>
                                                        <small class="stat-label">Entities</small>
                                                    </div>
                                                    <div class="stat-item">
                                                        <i class="fas fa-briefcase text-warning"></i>
                                                        <span class="stat-number">{{ $site->total_job_creations }}</span>
                                                        <small class="stat-label">Jobs</small>
                                                    </div>
                                                    <div class="stat-item">
                                                        <i class="fas fa-money-bill-wave text-danger"></i>
                                                        <span class="stat-number">{{ $site->total_salaries }}</span>
                                                        <small class="stat-label">Salaries</small>
                                                    </div>
                                                </div>

                                                <div class="stats-grid mt-2">
                                                    <div class="stat-item">
                                                        <i class="fas fa-gift text-secondary"></i>
                                                        <span class="stat-number">{{ $site->total_subventions }}</span>
                                                        <small class="stat-label">Subventions</small>
                                                    </div>
                                                    <div class="stat-item">
                                                        <i class="fas fa-graduation-cap text-primary"></i>
                                                        <span
                                                            class="stat-number">{{ $site->total_specializations }}</span>
                                                        <small class="stat-label">Specializations</small>
                                                    </div>
                                                    <div class="stat-item">
                                                        <i class="fas fa-chart-line text-info"></i>
                                                        <span class="stat-number">{{ $site->total_evaluations }}</span>
                                                        <small class="stat-label">Evaluations</small>
                                                    </div>
                                                    <div class="stat-item">
                                                        <i class="fas fa-tasks text-success"></i>
                                                        <span class="stat-number">{{ $site->total_follow_ups }}</span>
                                                        <small class="stat-label">Follow-ups</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer py-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-sync-alt me-1"></i>
                                                    Updated: {{ now()->format('M j, H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Summary Statistics -->
                            <div class="card card-round bg-light mt-3">
                                <div class="card-body py-3">
                                    <div class="row text-center">
                                        @php
                                            $totalStats = [
                                                'students' => collect($siteStatistics)->sum('total_students'),
                                                'entities' => collect($siteStatistics)->sum('total_entities'),
                                                'jobs' => collect($siteStatistics)->sum('total_job_creations'),
                                                'salaries' => collect($siteStatistics)->sum('total_salaries'),
                                            ];
                                        @endphp
                                        <div class="col-6 col-md-3 mb-2 mb-md-0">
                                            <h4 class="text-primary mb-0">{{ $totalStats['students'] }}</h4>
                                            <small class="text-muted">Total Students</small>
                                        </div>
                                        <div class="col-6 col-md-3 mb-2 mb-md-0">
                                            <h4 class="text-success mb-0">{{ $totalStats['entities'] }}</h4>
                                            <small class="text-muted">Total Entities</small>
                                        </div>
                                        <div class="col-6 col-md-3 mb-2 mb-md-0">
                                            <h4 class="text-warning mb-0">{{ $totalStats['jobs'] }}</h4>
                                            <small class="text-muted">Total Jobs</small>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <h4 class="text-danger mb-0">{{ $totalStats['salaries'] }}</h4>
                                            <small class="text-muted">Total Salaries</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students by Training Center -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <h4 class="card-title">Students Distribution by Training Center</h4>
                                <div class="card-tools">
                                    <button class="btn btn-icon btn-link btn-primary btn-xs" data-toggle="tooltip"
                                        title="Minimize">
                                        <span class="fa fa-angle-down"></span>
                                    </button>
                                    <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"
                                        data-toggle="tooltip" title="Refresh">
                                        <span class="fa fa-sync-alt"></span>
                                    </button>
                                    <button class="btn btn-icon btn-link btn-primary btn-xs" data-toggle="tooltip"
                                        title="Close">
                                        <span class="fa fa-times"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $totalStudents = $studentsPerSite->sum('total');
                                    $maxStudents = $studentsPerSite->max('total');
                                @endphp

                                @foreach ($studentsPerSite as $site)
                                    @php
                                        $percentage = $maxStudents > 0 ? ($site->total / $maxStudents) * 100 : 0;
                                        $totalPercentage =
                                            $totalStudents > 0 ? ($site->total / $totalStudents) * 100 : 0;
                                    @endphp
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card card-stats card-round card-hover">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-circle bg-primary me-3">
                                                            <i class="fas fa-building text-white"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="card-title mb-0">{{ $site->designation }}</h6>
                                                            <small class="text-muted">Training Center</small>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-primary rounded-pill">{{ $site->total }}</span>
                                                </div>

                                                <div class="progress-group mb-3">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <small class="text-muted">Capacity</small>
                                                        <small
                                                            class="text-muted">{{ number_format($percentage, 1) }}%</small>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            style="width: {{ $percentage }}%"
                                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-center">
                                                        <small class="text-muted d-block">Of Total</small>
                                                        <span
                                                            class="fw-bold text-primary">{{ number_format($totalPercentage, 1) }}%</span>
                                                    </div>
                                                    <div class="text-center">
                                                        <small class="text-muted d-block">Students</small>
                                                        <span class="fw-bold">{{ $site->total }}</span>
                                                    </div>
                                                    <div class="text-center">
                                                        <small class="text-muted d-block">Rank</small>
                                                        <span class="fw-bold text-warning">
                                                            #{{ $loop->iteration }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Overall Summary -->
                            <div class="card card-round bg-gradient-primary text-white mt-4">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-3 mb-md-0">
                                            <div class="icon-big">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <h3 class="mb-0">{{ $totalStudents }}</h3>
                                            <small>Total Students</small>
                                        </div>
                                        <div class="col-md-3 mb-3 mb-md-0">
                                            <div class="icon-big">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <h3 class="mb-0">{{ $studentsPerSite->count() }}</h3>
                                            <small>Training Centers</small>
                                        </div>
                                        <div class="col-md-3 mb-3 mb-md-0">
                                            <div class="icon-big">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                            <h3 class="mb-0">{{ $maxStudents }}</h3>
                                            <small>Largest Center</small>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="icon-big">
                                                <i class="fas fa-calculator"></i>
                                            </div>
                                            <h3 class="mb-0">
                                                {{ $totalStudents > 0 ? number_format($totalStudents / $studentsPerSite->count(), 1) : 0 }}
                                            </h3>
                                            <small>Average per Center</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Distribution Chart -->
                            <div class="card card-round mt-4">
                                <div class="card-body">
                                    <div class="chart-container" style="height: 300px;">
                                        <canvas id="studentsDistributionChart"></canvas>
                                    </div>
                                </div>
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
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="card card-round bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-map fa-5x text-info mb-3"></i>
                                            <h4 class="text-info">Geographic Distribution</h4>
                                            <p class="text-muted">{{ $studentsPerStateOfOrigin->count() }} States
                                                Represented</p>

                                            <div class="mt-4">
                                                <h2 class="text-primary">{{ $studentsPerStateOfOrigin->sum('total') }}
                                                </h2>
                                                <p class="text-muted">Total Students</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="row">
                                        @foreach ($studentsPerStateOfOrigin->take(8) as $index => $state)
                                            @php
                                                $colors = [
                                                    'info',
                                                    'success',
                                                    'warning',
                                                    'danger',
                                                    'primary',
                                                    'secondary',
                                                    'dark',
                                                    'light',
                                                ];
                                                $color = $colors[$index % count($colors)];
                                            @endphp
                                            <div class="col-md-6 mb-3">
                                                <div
                                                    class="card card-stats card-round card-border-top-{{ $color }}">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <span
                                                                    class="badge bg-{{ $color }} me-2">{{ $state->total }}</span>
                                                                <span class="fw-bold"
                                                                    style="font-size: 0.9rem;">{{ $state->state_of_origin }}</span>
                                                            </div>
                                                            <i class="fas fa-flag text-{{ $color }}"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if ($studentsPerStateOfOrigin->count() > 8)
                                            <div class="col-md-12">
                                                <div class="card card-round">
                                                    <div class="card-body text-center">
                                                        <p class="text-muted mb-0">
                                                            + {{ $studentsPerStateOfOrigin->count() - 8 }} more states...
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Top states -->
                            <div class="card card-round mt-4">
                                <div class="card-header">
                                    <h5 class="card-title">Top States</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($studentsPerStateOfOrigin->take(3) as $index => $state)
                                            <div class="col-md-4 text-center">
                                                <div class="icon-big icon-info bubble-shadow-medium mb-2">
                                                    <i class="fas fa-trophy fa-2x"></i>
                                                </div>
                                                <h5 class="text-info">#{{ $index + 1 }}</h5>
                                                <h6 class="mb-1">{{ $state->state_of_origin }}</h6>
                                                <h4 class="text-primary mb-0">{{ $state->total }}</h4>
                                                <small class="text-muted">students</small>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                            @php
                                $totalStudents = $studentsPerStateOfOrigin->sum('total');
                                $maxStudents = $studentsPerStateOfOrigin->max('total');
                            @endphp

                            <div class="row">
                                @foreach ($studentsPerStateOfOrigin as $state)
                                    @php
                                        $percentage = $totalStudents > 0 ? ($state->total / $totalStudents) * 100 : 0;
                                    @endphp
                                    <div class="col-md-4 col-lg-3 mb-4">
                                        <div class="card card-stats card-round card-border-left-info">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-flag text-info me-2"></i>
                                                        <h6 class="card-title mb-0">{{ $state->state_of_origin }}</h6>
                                                    </div>
                                                    <span class="badge bg-info">{{ $state->total }}</span>
                                                </div>

                                                <div class="progress mb-2" style="height: 6px;">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                                                    <small class="text-muted">{{ $state->total }} students</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Résumé général -->
                            <div class="card card-round bg-light mt-4">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <h3 class="text-info mb-0">{{ $totalStudents }}</h3>
                                            <small class="text-muted">Total Students</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h3 class="text-success mb-0">{{ $studentsPerStateOfOrigin->count() }}</h3>
                                            <small class="text-muted">States Represented</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h3 class="text-warning mb-0">{{ $maxStudents }}</h3>
                                            <small class="text-muted">Max from One State</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h3 class="text-primary mb-0">
                                                {{ $totalStudents > 0 ? number_format($totalStudents / $studentsPerStateOfOrigin->count(), 1) : 0 }}
                                            </h3>
                                            <small class="text-muted">Average per State</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Charts Section -->




        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Étudiants par genre
        @if (isset($studentsByGender) && $studentsByGender->count() > 0)
            var studentsByGenderLabels = @json($studentsByGender->pluck('sexe'));
            var studentsByGenderData = @json($studentsByGender->pluck('total'));
            var ctxStudentsByGender = document.getElementById('studentsByGenderChart');
            if (ctxStudentsByGender) {
                var chart = new Chart(ctxStudentsByGender.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: studentsByGenderLabels,
                        datasets: [{
                            data: studentsByGenderData,
                            backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56'],
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        @endif

        // Étudiants par statut matrimonial
        @if (isset($studentsByMaritalStatus) && $studentsByMaritalStatus->count() > 0)
            var studentsByMaritalStatusLabels = @json($studentsByMaritalStatus->pluck('situation_matrimoniale'));
            var studentsByMaritalStatusData = @json($studentsByMaritalStatus->pluck('total'));
            var ctxStudentsByMaritalStatus = document.getElementById('studentsByMaritalStatusChart');
            if (ctxStudentsByMaritalStatus) {
                var chart = new Chart(ctxStudentsByMaritalStatus.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: studentsByMaritalStatusLabels,
                        datasets: [{
                            label: 'Nombre d\'étudiants',
                            data: studentsByMaritalStatusData,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)'
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
            }
        @endif

        // Étudiants par statut handicap
        @if (isset($studentsByHandicap) && $studentsByHandicap->count() > 0)
            var studentsByHandicapLabels = @json($studentsByHandicap->pluck('situation_handicape'));
            var studentsByHandicapData = @json($studentsByHandicap->pluck('total'));
            var ctxStudentsByHandicap = document.getElementById('studentsByHandicapChart');
            if (ctxStudentsByHandicap) {
                var chart = new Chart(ctxStudentsByHandicap.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: studentsByHandicapLabels,
                        datasets: [{
                            label: 'Nombre d\'étudiants',
                            data: studentsByHandicapData,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)'
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
            }
        @endif

        // Étudiants par tranche d'âge
        @if (isset($studentsByAgeGroup) && is_array($studentsByAgeGroup) && count($studentsByAgeGroup) > 0)
            var studentsByAgeGroupLabels = @json(array_column($studentsByAgeGroup, 'age_group'));
            var studentsByAgeGroupData = @json(array_column($studentsByAgeGroup, 'total'));
            var ctxStudentsByAgeGroup = document.getElementById('studentsByAgeGroupChart');
            if (ctxStudentsByAgeGroup) {
                var chart = new Chart(ctxStudentsByAgeGroup.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: studentsByAgeGroupLabels,
                        datasets: [{
                            label: 'Nombre d\'étudiants',
                            data: studentsByAgeGroupData,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)'
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
            }
        @endif

        // Entités par activité
        @if (isset($entitiesByActivity) && $entitiesByActivity->count() > 0)
            var entitiesByActivityLabels = @json($entitiesByActivity->pluck('activity'));
            var entitiesByActivityData = @json($entitiesByActivity->pluck('total'));
            var ctxEntitiesByActivity = document.getElementById('entitiesByActivityChart');
            if (ctxEntitiesByActivity) {
                var chart = new Chart(ctxEntitiesByActivity.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: entitiesByActivityLabels,
                        datasets: [{
                            label: 'Nombre d\'entités',
                            data: entitiesByActivityData,
                            backgroundColor: 'rgba(153, 102, 255, 0.6)'
                        }]
                    },
                    options: {
                        responsive: true,
                        indexAxis: 'y',
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        @endif

        // Créations d'emplois par genre
        @if (isset($jobCreationsByGender) && $jobCreationsByGender->count() > 0)
            var jobCreationsByGenderLabels = @json($jobCreationsByGender->pluck('sexe'));
            var jobCreationsByGenderData = @json($jobCreationsByGender->pluck('total'));
            var ctxJobCreationsByGender = document.getElementById('jobCreationsByGenderChart');
            if (ctxJobCreationsByGender) {
                var chart = new Chart(ctxJobCreationsByGender.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: jobCreationsByGenderLabels,
                        datasets: [{
                            data: jobCreationsByGenderData,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        @endif

        // Notes moyennes d'évaluations par matière
        @if (isset($evaluationsAvgByMatier) && $evaluationsAvgByMatier->count() > 0)
            var evaluationsAvgByMatierLabels = @json($evaluationsAvgByMatier->pluck('libelle'));
            var evaluationsAvgByMatierData = @json($evaluationsAvgByMatier->pluck('avg_note'));
            var ctxEvaluationsAvgByMatier = document.getElementById('evaluationsAvgByMatierChart');
            if (ctxEvaluationsAvgByMatier) {
                var chart = new Chart(ctxEvaluationsAvgByMatier.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: evaluationsAvgByMatierLabels,
                        datasets: [{
                            label: 'Note moyenne',
                            data: evaluationsAvgByMatierData,
                            backgroundColor: 'rgba(255, 206, 86, 0.6)'
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 20
                            }
                        }
                    }
                });
            }
        @endif

        // Distribution des statuts d'affaires
        @if (isset($businessStatusDistribution) && $businessStatusDistribution->count() > 0)
            var businessStatusDistributionLabels = @json($businessStatusDistribution->pluck('status'));
            var businessStatusDistributionData = @json($businessStatusDistribution->pluck('total'));
            var ctxBusinessStatusDistribution = document.getElementById('businessStatusDistributionChart');
            if (ctxBusinessStatusDistribution) {
                var chart = new Chart(ctxBusinessStatusDistribution.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: businessStatusDistributionLabels,
                        datasets: [{
                            data: businessStatusDistributionData,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        @endif

        // Suivis par mois
        @if (isset($followUpsByMonth) && $followUpsByMonth->count() > 0)
            var followUpsByMonthLabels = @json($followUpsByMonth->pluck('month'));
            var followUpsByMonthData = @json($followUpsByMonth->pluck('total'));
            var ctxFollowUpsByMonth = document.getElementById('followUpsByMonthChart');
            if (ctxFollowUpsByMonth) {
                var chart = new Chart(ctxFollowUpsByMonth.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: followUpsByMonthLabels,
                        datasets: [{
                            label: 'Nombre de suivis',
                            data: followUpsByMonthData,
                            fill: false,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            tension: 0.1
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
            }
        @endif
        // Students by Site Chart
        @if (isset($studentsPerSite) && $studentsPerSite->count() > 0)
            var studentsBySiteLabels = @json($studentsPerSite->pluck('designation'));
            var studentsBySiteData = @json($studentsPerSite->pluck('total'));
            var ctxStudentsBySiteElem = document.getElementById('studentsBySiteChart');
            if (ctxStudentsBySiteElem) {
                var ctxStudentsBySite = ctxStudentsBySiteElem.getContext('2d');
                var studentsBySiteChart = new Chart(ctxStudentsBySite, {
                    type: 'bar',
                    data: {
                        labels: studentsBySiteLabels,
                        datasets: [{
                            label: 'Number of Students',
                            data: studentsBySiteData,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
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
            }
        @endif

        // Students by Promotion Chart
        @if (isset($studentsPerPromotion) && $studentsPerPromotion->count() > 0)
            var studentsByPromotionLabels = @json($studentsPerPromotion->pluck('num_promotion'));
            var studentsByPromotionData = @json($studentsPerPromotion->pluck('total'));
            var ctxStudentsByPromotion = document.getElementById('studentsByPromotionChart');
            if (ctxStudentsByPromotion) {
                var studentsByPromotionChart = new Chart(ctxStudentsByPromotion.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: studentsByPromotionLabels,
                        datasets: [{
                            label: 'Number of Students',
                            data: studentsByPromotionData,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 205, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 205, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        @endif

        // Students by Specialization Chart
        @if (isset($studentsPerSpecialization) && $studentsPerSpecialization->count() > 0)
            var studentsBySpecializationLabels = @json($studentsPerSpecialization->pluck('designation'));
            var studentsBySpecializationData = @json($studentsPerSpecialization->pluck('total'));
            var ctxStudentsBySpecializationElem = document.getElementById('studentsBySpecializationChart');
            if (ctxStudentsBySpecializationElem) {
                var ctxStudentsBySpecialization = ctxStudentsBySpecializationElem.getContext('2d');
                var studentsBySpecializationChart = new Chart(ctxStudentsBySpecialization, {
                    type: 'doughnut',
                    data: {
                        labels: studentsBySpecializationLabels,
                        datasets: [{
                            label: 'Number of Students',
                            data: studentsBySpecializationData,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 205, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 205, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        @endif

        // Salaries by Location Chart
        @if (isset($salariesPerLocation) && $salariesPerLocation->count() > 0)
            var salariesByLocationLabels = @json($salariesPerLocation->pluck('localisation'));
            var salariesByLocationData = @json($salariesPerLocation->pluck('total'));
            var ctxSalariesByLocationElem = document.getElementById('salariesByLocationChart');
            if (ctxSalariesByLocationElem) {
                var ctxSalariesByLocation = ctxSalariesByLocationElem.getContext('2d');
                var salariesByLocationChart = new Chart(ctxSalariesByLocation, {
                    type: 'bar',
                    data: {
                        labels: salariesByLocationLabels,
                        datasets: [{
                            label: 'Number of Salaries',
                            data: salariesByLocationData,
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
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
            }
        @endif

        // Matiers by Coef Chart
        @if (isset($matiersPerCoef) && $matiersPerCoef->count() > 0)
            var matiersByCoefLabels = @json($matiersPerCoef->pluck('coef'));
            var matiersByCoefData = @json($matiersPerCoef->pluck('total'));
            var ctxMatiersByCoefElem = document.getElementById('matiersByCoefChart');
            if (ctxMatiersByCoefElem) {
                var ctxMatiersByCoef = ctxMatiersByCoefElem.getContext('2d');
                var matiersByCoefChart = new Chart(ctxMatiersByCoef, {
                    type: 'line',
                    data: {
                        labels: matiersByCoefLabels,
                        datasets: [{
                            label: 'Number of Subjects',
                            data: matiersByCoefData,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
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
            }
        @endif

        // Students by State of Origin Chart
        @if (isset($studentsPerStateOfOrigin) && $studentsPerStateOfOrigin->count() > 0)
            var studentsByStateOfOriginLabels = @json($studentsPerStateOfOrigin->pluck('state_of_origin'));
            var studentsByStateOfOriginData = @json($studentsPerStateOfOrigin->pluck('total'));
            var ctxStudentsByStateOfOriginElem = document.getElementById('studentsByStateOfOriginChart');
            if (ctxStudentsByStateOfOriginElem) {
                var ctxStudentsByStateOfOrigin = ctxStudentsByStateOfOriginElem.getContext('2d');
                var studentsByStateOfOriginChart = new Chart(ctxStudentsByStateOfOrigin, {
                    type: 'pie',
                    data: {
                        labels: studentsByStateOfOriginLabels,
                        datasets: [{
                            label: 'Number of Students',
                            data: studentsByStateOfOriginData,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 205, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 205, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        @endif

        // Students by State of Residence Chart
        @if (isset($studentsPerStateOfResidence) && $studentsPerStateOfResidence->count() > 0)
            var studentsByStateOfResidenceLabels = @json($studentsPerStateOfResidence->pluck('state_of_residence'));
            var studentsByStateOfResidenceData = @json($studentsPerStateOfResidence->pluck('total'));
            var ctxStudentsByStateOfResidenceElem = document.getElementById('studentsByStateOfResidenceChart');
            if (ctxStudentsByStateOfResidenceElem) {
                var ctxStudentsByStateOfResidence = ctxStudentsByStateOfResidenceElem.getContext('2d');
                var studentsByStateOfResidenceChart = new Chart(ctxStudentsByStateOfResidence, {
                    type: 'bar',
                    data: {
                        labels: studentsByStateOfResidenceLabels,
                        datasets: [{
                            label: 'Number of Students',
                            data: studentsByStateOfResidenceData,
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
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
            }
        @endif

        // Site Statistics Chart
        @if (isset($siteStatistics) && $siteStatistics->count() > 0)
            var siteStatisticsLabels = @json($siteStatistics->pluck('site_name'));
            var siteStatisticsStudents = @json($siteStatistics->pluck('total_students'));
            var siteStatisticsEntities = @json($siteStatistics->pluck('total_entities'));
            var siteStatisticsJobCreations = @json($siteStatistics->pluck('total_job_creations'));
            var siteStatisticsSalaries = @json($siteStatistics->pluck('total_salaries'));
            var siteStatisticsSubventions = @json($siteStatistics->pluck('total_subventions'));
            var siteStatisticsSpecializations = @json($siteStatistics->pluck('total_specializations'));
            var siteStatisticsEvaluations = @json($siteStatistics->pluck('total_evaluations'));
            var siteStatisticsFollowUps = @json($siteStatistics->pluck('total_follow_ups'));
            var siteStatisticsBusinessStatuses = @json($siteStatistics->pluck('total_business_statuses'));

            var ctxSiteStatisticsElem = document.getElementById('siteStatisticsChart');
            if (ctxSiteStatisticsElem) {
                var ctxSiteStatistics = ctxSiteStatisticsElem.getContext('2d');
                var siteStatisticsChart = new Chart(ctxSiteStatistics, {
                    type: 'bar',
                    data: {
                        labels: siteStatisticsLabels,
                        datasets: [{
                            label: 'Students',
                            data: siteStatisticsStudents,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Entities',
                            data: siteStatisticsEntities,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Job Creations',
                            data: siteStatisticsJobCreations,
                            backgroundColor: 'rgba(255, 205, 86, 0.2)',
                            borderColor: 'rgba(255, 205, 86, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Salaries',
                            data: siteStatisticsSalaries,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Subventions',
                            data: siteStatisticsSubventions,
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Specializations',
                            data: siteStatisticsSpecializations,
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Evaluations',
                            data: siteStatisticsEvaluations,
                            backgroundColor: 'rgba(199, 199, 199, 0.2)',
                            borderColor: 'rgba(199, 199, 199, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Follow-ups',
                            data: siteStatisticsFollowUps,
                            backgroundColor: 'rgba(83, 102, 255, 0.2)',
                            borderColor: 'rgba(83, 102, 255, 1)',
                            borderWidth: 1
                        }, {
                            label: 'Business Statuses',
                            data: siteStatisticsBusinessStatuses,
                            backgroundColor: 'rgba(255, 99, 71, 0.2)',
                            borderColor: 'rgba(255, 99, 71, 1)',
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
            }
        @endif



        document.addEventListener('DOMContentLoaded', function() {
            // Students Distribution Chart
            const ctx = document.getElementById('studentsDistributionChart').getContext('2d');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($studentsPerSite->pluck('designation')) !!},
                        datasets: [{
                            label: 'Number of Students',
                            data: {!! json_encode($studentsPerSite->pluck('total')) !!},
                            backgroundColor: '#4e73df',
                            borderColor: '#2e59d9',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to cards on page load
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate__animated', 'animate__fadeInUp');
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            // Users by Site Chart
            const usersCtx = document.getElementById('usersBySiteChart');
            if (usersCtx) {
                new Chart(usersCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($usersPerSite->pluck('site_name')) !!},
                        datasets: [{
                            data: {!! json_encode($usersPerSite->pluck('total_users')) !!},
                            backgroundColor: [
                                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                                '#858796', '#5a5c69', '#2c9faf', '#eaecf4'
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Initialize accordions
            const accordions = document.querySelectorAll('.accordion-button');
            accordions.forEach(accordion => {
                accordion.addEventListener('click', function() {
                    this.querySelector('.badge').classList.toggle('bg-white');
                    this.querySelector('.badge').classList.toggle('bg-light');
                });
            });
        });
    </script>
@endpush
