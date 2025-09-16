@extends('base_admin')
@section('title', 'Student List')
<style>
    @media print {

        /* Hide unnecessary elements */
        body * {
            visibility: hidden;
        }

        .table,
        .table * {
            visibility: visible;
        }

        .table {
            position: absolute;
            left: 0;
            top: 50px;
            /* Adjust position to leave space for the title */
            width: 100%;
        }

        /* Display dynamic title */
        .print-title {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        /* Hide Site and Actions columns */
        .no-print {
            display: none;
        }

        /* Hide buttons and form */
        .btn,
        .form-select,
        .pagination,
        .alert {
            display: none;
        }


        /* Style général du tableau */
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* En-tête du tableau */
        .table-dark {
            background: linear-gradient(135deg, #343a40, #495057);
            color: white;
        }

        .table-dark th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        /* Cellules du tableau */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .table td {
            padding: 12px 15px;
            vertical-align: top;
            border-bottom: 1px solid #e9ecef;
        }

        /* Style des séparateurs */
        hr {
            margin: 8px 0;
            border: 0;
            border-top: 1px dashed #dee2e6;
        }

        /* Style des badges */
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.85em;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529;
        }

        /* Style des liens */
        a {
            color: #4e73df;
            text-decoration: none;
            transition: color 0.2s;
        }

        a:hover {
            color: #2e59d9;
            text-decoration: underline;
        }

        /* Style des boutons d'action */
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Mise en valeur des labels */
        strong {
            color: #495057;
            font-weight: 600;
        }

        /* Style alterné des lignes */
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        /* Style pour les valeurs manquantes */
        .text-muted {
            color: #6c757d !important;
            font-style: italic;
        }

        /* Espacement des éléments */
        .mb-1 {
            margin-bottom: 8px;
        }
    }
</style>

@section('content')
    <div class="container">
        <h1>Student List</h1>
        <a href="{{ route('students.create') }}" class="btn btn-primary">Add Student</a>



        <!-- Print button -->
        <div class="text-end mb-3">
            <button onclick="window.print()" class="btn btn-success">
                <i class="fas fa-print"></i> Print List
            </button>
        </div>
        <!-- Search form for specific student -->
        <form action="{{ route('students.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="row mb-3">
                    <label for="search" class="form-label">Search Students:</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>


        <!-- Filter form by promotion -->
        <form action="{{ route('students.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label for="promotion_id" class="form-label">Filter by Promotion:</label>
                    <select name="promotion_id" id="promotion_id" class="form-select">
                        <option value="">All Students</option>
                        @foreach ($promotions as $promotion)
                            <option value="{{ $promotion->id }}"
                                {{ request('promotion_id') == $promotion->id ? 'selected' : '' }}>
                                {{ $promotion->num_promotion }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filter form by gender and promotion -->
        <form action="{{ route('students.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="sexe" class="form-label">Filter by Gender:</label>
                    <select name="sexe" id="sexe" class="form-select">
                        <option value="">All Genders</option>
                        <option value="M" {{ request('sexe') == 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ request('sexe') == 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="promotion_id" class="form-label">Filter by Promotion:</label>
                    <select name="promotion_id" id="promotion_id" class="form-select">
                        <option value="">All Promotions</option>
                        @foreach ($promotions as $promotion)
                            <option value="{{ $promotion->id }}"
                                {{ request('promotion_id') == $promotion->id ? 'selected' : '' }}>
                                {{ $promotion->num_promotion }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <!-- NEW: Filter by Promotion and State of Origin -->
        <form action="{{ route('students.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="promotion_id" class="form-label">Promotion:</label>
                    <select name="promotion_id" id="promotion_id" class="form-select" required>
                        <option value="">Select a promotion</option>
                        @foreach ($promotions as $promotion)
                            <option value="{{ $promotion->id }}"
                                {{ request('promotion_id') == $promotion->id ? 'selected' : '' }}>
                                {{ $promotion->num_promotion }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="state_of_origin" class="form-label">State of Origin:</label>
                    <select name="state_of_origin" id="state_of_origin" class="form-select">
                        <option value="">All States</option>
                        @foreach ($statesOfOrigin as $state)
                            <option value="{{ $state }}"
                                {{ request('state_of_origin') == $state ? 'selected' : '' }}>
                                {{ $state }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <!-- Filter form by speciality and promotion -->
        <form action="{{ route('students.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="specialite_id" class="form-label">Filter by Speciality:</label>
                    <select name="specialite_id" id="specialite_id" class="form-select">
                        <option value="">All Specialities</option>
                        @foreach ($specialites as $specialite)
                            <option value="{{ $specialite->id }}"
                                {{ request('specialite_id') == $specialite->id ? 'selected' : '' }}>
                                {{ $specialite->designation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="promotion_id" class="form-label">Filter by Promotion:</label>
                    <select name="promotion_id" id="promotion_id" class="form-select">
                        <option value="">Students Not in Promotion</option>
                        @foreach ($promotions as $promotion)
                            <option value="{{ $promotion->id }}"
                                {{ request('promotion_id') == $promotion->id ? 'selected' : '' }}>
                                {{ $promotion->num_promotion }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <!-- Dynamic title for printing -->
        <div class="print-title" style="display: none;">
            <h2>Student List</h2>
            @if ($selectedSexe)
                <p>Gender: {{ $selectedSexe == 'M' ? 'Male' : 'Female' }}</p>
            @endif
            @if ($selectedPromotionName)
                <p>Promotion: {{ $selectedPromotionName }}</p>
            @endif
            @if ($selectedState)
                <p>State of Origin: {{ $selectedState }}</p>
            @endif
            @if ($selectedSpecialiteName)
                <p>Speciality: {{ $selectedSpecialiteName }}</p>
            @endif
        </div>


        {{-- <!-- Export Students Form -->
        <div class="card mb-4 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #36b9cc, #1cc88a);">
                <h5 class="mb-0">Export Students</h5>
            </div>
            <div class="card-body bg-light">
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form id="exportForm" action="{{ route('students.export') }}" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <label for="promotion_id_export" class="form-label">Promotion</label>
                        <select name="promotion_id" id="promotion_id_export" class="form-select" required>
                            <option value="">Select a promotion</option>
                            @foreach ($promotions as $promotion)
                                <option value="{{ $promotion->id }}">{{ $promotion->num_promotion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 align-self-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Import Students Form -->
        <div class="card mb-4 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #f6c23e, #e74a3b);">
                <h5 class="mb-0">Import Students</h5>
            </div>
            <div class="card-body bg-light">
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data"
                    class="row g-3">
                    @csrf
                    <div class="col-md-8">
                        <label for="file" class="form-label">Excel File (.xlsx, .xls)</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls"
                            required>
                    </div>
                    <div class="col-md-4 align-self-end">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
 --}}


        {{-- <div class="card mb-4 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #4e73df, #1cc88a);">
                <h5 class="mb-0">Gender Statistics (All Students)</h5>
            </div>
            <div class="card-body bg-light">
                @php
                    $malePercentageAll =
                        $totalAllStudents > 0 ? round(($maleCountAll / $totalAllStudents) * 100, 2) : 0;
                    $femalePercentageAll =
                        $totalAllStudents > 0 ? round(($femaleCountAll / $totalAllStudents) * 100, 2) : 0;
                @endphp

                <table class="table table-bordered text-center"
                    style="background-color: white; border-radius: 8px; overflow: hidden;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-primary">Gender</th>
                            <th class="text-success">Count</th>
                            <th class="text-info">Percentage</th>
                            <th class="text-secondary">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <span class="badge bg-primary px-3 py-2">Male</span>
                            </td>
                            <td class="text-success fw-bold">{{ $maleCountAll }}</td>
                            <td class="text-info fw-bold">{{ $malePercentageAll }}%</td>
                            <td>
                                <div class="progress" style="height: 20px; border-radius: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ $malePercentageAll }}%;"
                                        aria-valuenow="{{ $malePercentageAll }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $malePercentageAll }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="badge px-3 py-2 text-white" style="background-color: #e83e8c;">Female</span>
                            </td>
                            <td class="text-success fw-bold">{{ $femaleCountAll }}</td>
                            <td class="text-info fw-bold">{{ $femalePercentageAll }}%</td>
                            <td>
                                <div class="progress" style="height: 20px; border-radius: 10px;">
                                    <div class="progress-bar"
                                        style="background-color: #e83e8c; width: {{ $femalePercentageAll }}%;"
                                        role="progressbar" aria-valuenow="{{ $femalePercentageAll }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ $femalePercentageAll }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="table-active">
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalAllStudents }}</strong></td>
                            <td><strong>100%</strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> --}}

        <div class="card mb-4 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #4e73df, #1cc88a);">
                <h5 class="mb-0">Gender Statistics
                    @if ($selectedPromotionName || $selectedSpecialiteName || $selectedState || $selectedSexe)
                        (Filtered Results)
                    @else
                        (All Students)
                    @endif
                </h5>
            </div>
            <div class="card-body bg-light">
                @php
                    $malePercentage = $totalStudents > 0 ? round(($maleCount / $totalStudents) * 100, 2) : 0;
                    $femalePercentage = $totalStudents > 0 ? round(($femaleCount / $totalStudents) * 100, 2) : 0;
                @endphp

                <table class="table table-bordered text-center"
                    style="background-color: white; border-radius: 8px; overflow: hidden;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-primary">Gender</th>
                            <th class="text-success">Count</th>
                            <th class="text-info">Percentage</th>
                            <th class="text-secondary">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <span class="badge bg-primary px-3 py-2">Male</span>
                            </td>
                            <td class="text-success fw-bold">{{ $maleCount }}</td>
                            <td class="text-info fw-bold">{{ $malePercentage }}%</td>
                            <td>
                                <div class="progress" style="height: 20px; border-radius: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ $malePercentage }}%;" aria-valuenow="{{ $malePercentage }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                        {{ $malePercentage }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <td>
                            <span class="badge px-3 py-2 text-white" style="background-color: #e83e8c;">Female</span>
                        </td>
                        <td class="text-success fw-bold">{{ $femaleCount }}</td>
                        <td class="text-info fw-bold">{{ $femalePercentage }}%</td>
                        <td>
                            <div class="progress" style="height: 20px; border-radius: 10px;">
                                <div class="progress-bar"
                                    style="background-color: #e83e8c; width: {{ $femalePercentage }}%;"
                                    role="progressbar" aria-valuenow="{{ $femalePercentage }}" aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{ $femalePercentage }}%
                                </div>
                            </div>
                        </td>
                        </tr>
                        <tr class="table-active">
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $totalStudents }}</strong></td>
                            <td><strong>100%</strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                @if ($selectedPromotionName || $selectedSpecialiteName || $selectedState || $selectedSexe)
                   <div class="text-center mt-3">
    <div class="row justify-content-center">
        <div class="col-auto mb-2">
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary w-100">
                <i class="fas fa-sync-alt me-2"></i>Reset to All Students
            </a>
        </div>
        <div class="col-auto mb-2">
            <form action="{{ route('students.export') }}" method="GET" class="d-inline">
                <input type="hidden" name="promotion_id" value="{{ request('promotion_id') }}">
                <input type="hidden" name="sexe" value="{{ request('sexe') }}">
                <input type="hidden" name="specialite_id" value="{{ request('specialite_id') }}">
                <input type="hidden" name="state_of_origin" value="{{ request('state_of_origin') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="language" value="en">
                <input type="hidden" name="format" value="excel">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-file-excel me-2"></i>Export to Excel
                </button>
            </form>
        </div>
    </div>
</div>
                @endif
            </div>
        </div>

        <!-- Student table -->
        <table class="table mt-3 table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Gender / FLS / Birthday</th>
                    <th>Speciality / Farm Address</th>
                    <th>Email / Phone number</th>
                    <th class="no-print">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>
                            <hr>
                            <div style="font-weight: 600; color: #2e59d9;">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </div>
                            <hr>
                            @if ($student->situation_handicape)
                                Handicape Situation <span class="badge bg-secondary mt-1">
                                    {{ $student->situation_handicape }}
                                </span>
                            @endif
                            <hr>
                        </td>

                        <td>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span><strong>Gender:</strong> {{ $student->sexe }}</span>
                            </div>
                            <hr>

                            <div>
                                <strong>FLS:</strong> {{ $student->state }}
                            </div>
                            <hr>

                            <div>
                                <strong>Birthday:</strong>
                                @if ($student->date_naissance)
                                    {{ \Carbon\Carbon::parse($student->date_naissance)->format('d M Y') }} (Age:
                                    {{ \Carbon\Carbon::parse($student->date_naissance)->age }} years)
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                            <hr>

                            @if ($student->businessStatuses->isNotEmpty())
                                @foreach ($student->businessStatuses as $business)
                                    <div class="mb-1">
                                        <strong>Business:</strong> {{ $business->type_of_business }}
                                        <span
                                            class="badge {{ $business->status == 'Active' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $business->status }}
                                        </span>
                                    </div>
                                    @if (!$loop->last)
                                        <hr>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-muted">No business info</span>
                            @endif
                            <hr>
                        </td>

                        <td>
                            <hr>
                            <div>
                                <strong>Specializations:</strong>
                                @forelse($student->specializations as $specialization)
                                    <span class="badge bg-primary">
                                        {{ $specialization->specialite->designation }}
                                    </span>
                                @empty
                                    <span class="text-muted">N/A</span>
                                @endforelse
                            </div>
                            <hr>

                            <div>
                                <strong>Value Chain:</strong>
                                @forelse($student->entities as $entity)
                                    <span class="badge bg-info text-white">
                                        {{ $entity->activity }}
                                    </span>
                                @empty
                                    <span class="text-muted">N/A</span>
                                @endforelse
                            </div>
                            <hr>

                            <div>
                                <strong>Farm Address:</strong>
                                {{ $student->community ?: 'N/A' }}
                            </div>
                            <hr>

                            <div>
                                <strong>State of Origin:</strong>
                                {{ $student->state_of_origin ?: 'N/A' }}
                            </div>
                            <hr>
                        </td>

                        <td>
                            <hr>
                            <div>
                                <strong>Email:</strong>
                                @if ($student->email)
                                    <a href="mailto:{{ $student->email }}" class="text-break">
                                        {{ $student->email }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                            <hr>

                            <div>
                                <strong>Phone:</strong>
                                @if ($student->contact)
                                    <a href="tel:{{ $student->contact }}">
                                        {{ $student->contact }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                            <hr>

                            <div>
                                <strong>LGA:</strong>
                                {{ $student->lga ?: 'N/A' }}
                            </div>
                            <hr>

                            <div>
                                <strong>Marital Status:</strong>
                                {{ $student->situation_matrimoniale ?: 'N/A' }}
                            </div>
                            <hr>
                        </td>

                        <td class="no-print">
                            <hr>
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('students.edit', $student->id) }}"
                                    class="btn btn-warning btn-sm d-flex align-items-center justify-content-center gap-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('students.show', $student->id) }}"
                                    class="btn btn-info btn-sm d-flex align-items-center justify-content-center gap-1">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-1"
                                        onclick="return confirm('Delete this student?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </div>
                            <hr>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>





        <div class="print-title" style="display: none;">
            <h2>Student List</h2>
            @if ($selectedPromotionName)
                <p>Promotion: {{ $selectedPromotionName }}</p>
            @endif
            @if ($selectedState)
                <p>State of Origin: {{ $selectedState }}</p>
            @endif
            @if ($selectedSpecialiteName)
                <p>Speciality: {{ $selectedSpecialiteName }}</p>
            @endif
        </div>



        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>



        @section('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const promotionSelect = document.getElementById('promotion_id');
                    const stateSelect = document.getElementById('state_of_origin');

                    // Disable state select until promotion is chosen
                    stateSelect.disabled = !promotionSelect.value;

                    promotionSelect.addEventListener('change', function() {
                        stateSelect.disabled = !this.value;
                        if (!this.value) {
                            stateSelect.value = '';
                        }
                    });
                });
            </script>
        @endsection
    @endsection
