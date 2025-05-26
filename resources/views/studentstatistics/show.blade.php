<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Étudiant</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styles personnalisés */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #212529;
            padding-top: 20px;
            background-color: #f8f9fa;
        }

        .print-container {
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .header-section {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .section-title {
            color: #0d6efd;
            margin-top: 30px;
            margin-bottom: 20px;
            padding-bottom: 5px;
            border-bottom: 1px solid #dee2e6;
        }

        .card-section {
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 500;
        }

        /* Styles d'impression */
        @media print {
            body {
                padding: 0;
                background-color: white;
                font-size: 12pt;
            }

            .print-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            .section-title {
                page-break-after: avoid;
            }

            .card-section {
                page-break-inside: avoid;
                border: none;
                margin-bottom: 15px;
            }

            .table {
                page-break-inside: avoid;
            }

            @page {
                size: A4;
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <div class="container print-container">
        <!-- Action Buttons - Hidden in print -->
        <div class="no-print mb-4 d-flex justify-content-between">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer-fill"></i> Print
            </button>
            <button onclick="window.history.back()" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </button>
        </div>

        <!-- Header -->
        <div class="header-section text-center">
            <h1 class="display-5 fw-bold">Student Details</h1>
            <h2 class="text-muted">{{ $student->first_name }} {{ $student->last_name }}</h2>
            {{-- <p class="text-muted">ID: {{ $student->id }}</p> --}}
        </div>


        <!-- Informations personnelles -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">Personal Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>First Name:</strong> {{ $student->first_name }}</p>
                            <p><strong>Last Name:</strong> {{ $student->last_name }}</p>
                            <p><strong>Gender:</strong> {{ $student->sexe }}</p>
                            <p><strong>Marital Status:</strong> {{ $student->situation_matrimoniale ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date of Birth:</strong> {{ $student->date_naissance ? $student->date_naissance->format('d/m/Y') : 'Not provided' }}</p>
                            <p><strong>Contact:</strong> {{ $student->contact ?? 'Not provided' }}</p>
                            <p><strong>Email:</strong> {{ $student->email ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>State of Origin:</strong> {{ $student->state_of_origin }}</p>
                            <p><strong>State of Residence:</strong> {{ $student->state_of_residence }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Location:</strong>
                                {{ $student->state ?? '' }} /
                                {{ $student->lga ?? '' }} /
                                {{ $student->community ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Spécialisations -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">Specializations</h3>
                </div>
                <div class="card-body">
                    @if($specialites->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($specialites as $specialite)
                                <li class="list-group-item">{{ $specialite->designation }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No specialization registered</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Matières et Notes -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">Subjects and Grades</h3>
                </div>
                <div class="card-body">
                    @if($evaluations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evaluations as $evaluation)
                                        <tr>
                                            <td>{{ $evaluation->designation }}</td>
                                            <td>{{ $evaluation->note }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No evaluations recorded</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Emplois Créés -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">Created Jobs</h3>
                </div>
                <div class="card-body">
                    @if($jobCreations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobCreations as $job)
                                        <tr>
                                            <td>{{ $job->nom }}</td>
                                            <td>{{ $job->tel }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No created jobs recorded</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Salaires -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">Salaries</h3>
                </div>
                <div class="card-body">
                    @if($salaries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Company</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salaries as $salary)
                                        <tr>
                                            <td>{{ $salary->entreprise }}</td>
                                            <td>{{ $salary->localisation }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No employees recorded</p>
                    @endif
                </div>
            </div>
        </div>


        <!-- Subventions -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">Subsidies</h3>
                </div>
                <div class="card-body">
                    @if($subventions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Start-Up Kits</th>
                                        <th>Grants</th>
                                        <th>Loan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subventions as $subvention)
                                        <tr>
                                            <td>{{ $subvention->start_up_kits }}</td>
                                            <td>{{ $subvention->grants }}</td>
                                            <td>{{ $subvention->loan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No subsidies recorded</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Suivi -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">Follow-Up</h3>
                </div>
                <div class="card-body">
                    @if($followUps->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Farm Visits</th>
                                        <th>Phone Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($followUps as $followUp)
                                        <tr>
                                            <td>{{ $followUp->farm_visits }}</td>
                                            <td>{{ $followUp->phone_contact }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No follow-up recorded</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Statut des Entreprises -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">Business Status</h3>
                </div>
                <div class="card-body">
                    @if($businessStatuses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type of Business</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($businessStatuses as $businessStatus)
                                        <tr>
                                            <td>{{ $businessStatus->type_of_business }}</td>
                                            <td>{{ $businessStatus->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No business status recorded</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Entités -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">Entities</h3>
                </div>
                <div class="card-body">
                    @if($entities->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($entities as $entity)
                                <li class="list-group-item">{{ $entity->activity }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No entities recorded</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
