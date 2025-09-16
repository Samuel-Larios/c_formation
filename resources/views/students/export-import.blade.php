@extends('base_admin')
@section('title', 'Export/Import Students')
@section('content')
<div class="container">
    <h1>Export/Import Students</h1>
    <p>Manage student data export and import while handling foreign key relationships.</p>

    <!-- Export Students Form -->
    <div class="card mb-4 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #36b9cc, #1cc88a);">
            <h5 class="mb-0">Export Students</h5>
        </div>
        <div class="card-body bg-light">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form id="exportForm" action="{{ route('students.export') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label for="promotion_id_export" class="form-label">Promotion</label>
                    <select name="promotion_id" id="promotion_id_export" class="form-select" required>
                        <option value="">Select a promotion</option>
                        @foreach ($promotions as $promotion)
                            <option value="{{ $promotion->id }}">{{ $promotion->num_promotion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="format" class="form-label">Format</label>
                    <select name="format" id="format" class="form-select" required>
                        <option value="excel">Excel</option>
                        <option value="word">Word</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="language" class="form-label">Langue</label>
                    <select name="language" id="language" class="form-select" required>
                        <option value="fr">Français</option>
                        <option value="en">English</option>
                    </select>
                </div>
                <div class="col-md-3 align-self-end">
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erreur d'importation :</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Excel Import -->
            <div class="mb-3">
                <h6>Import depuis Excel (.xlsx, .xls)</h6>
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-9">
                        <label for="file" class="form-label">Fichier Excel (.xlsx, .xls)</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
                        <small class="form-text text-muted">
                            Le fichier doit contenir les colonnes avec les noms des entités liées (ex: nom du site, numéro de promotion).
                        </small>
                        <small class="form-text text-warning">
                            <strong>Note :</strong> Si vous rencontrez des erreurs avec les fichiers .xlsx, utilisez le format .xls (Excel 97-2003) ou <a href="{{ url('check_php_extensions.php') }}" target="_blank">vérifiez les extensions PHP</a>.
                        </small>
                        <small class="form-text text-info">
                            <strong>Configuration requise :</strong> L'extension PHP 'zip' doit être activée pour les fichiers .xlsx.
                        </small>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-file-excel"></i> Importer Excel
                        </button>
                    </div>
                </form>
            </div>

            <hr>

            <!-- CSV Import (Alternative) -->
            <div>
                <h6>Import depuis CSV (Alternative - sans extension requise)</h6>
                <form action="{{ route('students.import.csv') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-9">
                        <label for="csv_file" class="form-label">Fichier CSV (.csv)</label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv,.txt" required>
                        <small class="form-text text-muted">
                            Format CSV simple avec séparateur virgule. Même structure que le fichier Excel.
                        </small>
                        <small class="form-text text-success">
                            <strong>Avantage :</strong> Fonctionne sans configuration PHP supplémentaire.
                        </small>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-file-csv"></i> Importer CSV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="card">
        <div class="card-header">
            <h5>Instructions d'import</h5>
        </div>
        <div class="card-body">
            <h6>Format du fichier Excel :</h6>
            <ul>
                <li><strong>ID</strong> : Laisser vide pour les nouveaux étudiants</li>
                <li><strong>Prénom</strong> : Prénom de l'étudiant</li>
                <li><strong>Nom</strong> : Nom de l'étudiant</li>
                <li><strong>Sexe</strong> : M ou F</li>
                <li><strong>Situation Matrimoniale</strong> : Situation matrimoniale</li>
                <li><strong>Situation Handicapé</strong> : Situation de handicap</li>
                <li><strong>Date de Naissance</strong> : Format AAAA-MM-JJ</li>
                <li><strong>Contact</strong> : Numéro de téléphone principal</li>
                <li><strong>Contact Pers1-5</strong> : Contacts supplémentaires</li>
                <li><strong>Email</strong> : Adresse email</li>
                <li><strong>Mot de passe</strong> : Laisser vide pour génération automatique</li>
                <li><strong>État d'origine</strong> : État d'origine</li>
                <li><strong>État de résidence</strong> : État de résidence</li>
                <li><strong>État</strong> : État</li>
                <li><strong>LGA</strong> : Zone gouvernementale locale</li>
                <li><strong>Communauté</strong> : Communauté</li>
                <li><strong>Site</strong> : Nom du site (sera automatiquement recherché)</li>
                <li><strong>Promotion</strong> : Numéro de promotion (sera automatiquement recherché)</li>
            </ul>
            <p><strong>Note :</strong> Les clés étrangères (Site, Promotion) seront automatiquement résolues par nom.</p>
            <p><strong>Test :</strong> <a href="{{ url('test_import_xls.php') }}" target="_blank">Voir un exemple de fichier d'import</a></p>
        </div>
    </div>
</div>
@endsection
