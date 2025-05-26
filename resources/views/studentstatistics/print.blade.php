<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression des Statistiques de l'Étudiant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        @media print {
            button {
                display: none;
            }
            body {
                font-size: 14px;
            }
            h2, h4 {
                color: #000;
            }
        }
    </style>
</head>
<body>

    <div class="container mt-4">
        <h2 class="text-center">Détails de l'Étudiant</h2>
        <hr>

        <!-- Informations de l'étudiant -->
        <table class="table table-bordered">
            <tr>
                <th>Nom :</th>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <th>Email :</th>
                <td>{{ $student->email }}</td>
            </tr>
            <tr>
                <th>Training center :</th>
                <td>{{ $student->site->nom ?? 'Non défini' }}</td>
            </tr>
            <tr>
                <th>Promotion :</th>
                <td>{{ $student->promotions->pluck('nom')->implode(', ') }}</td>
            </tr>
        </table>

        <!-- Spécialités -->
        <h4>Spécialités</h4>
        <ul>
            @foreach ($student->specializations as $specialization)
                <li>{{ $specialization->specialite->designation }}</li>
            @endforeach
        </ul>

        <!-- Évaluations -->
        <h4>Évaluations</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($student->evaluations as $evaluation)
                    <tr>
                        <td>{{ $evaluation->matier->designation }}</td>
                        <td>{{ $evaluation->note }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Job Creations -->
        <h4>Créations d'Emploi</h4>
        <ul>
            @foreach ($student->jobCreations as $job)
                <li>{{ $job->description }}</li>
            @endforeach
        </ul>

        <!-- Salaires -->
        <h4>Salaires</h4>
        <ul>
            @foreach ($student->salaries as $salary)
                <li>{{ $salary->amount }} FCFA</li>
            @endforeach
        </ul>

        <!-- Subventions -->
        <h4>Subventions</h4>
        <ul>
            @foreach ($student->subventions as $subvention)
                <li>{{ $subvention->amount }} FCFA</li>
            @endforeach
        </ul>

        <!-- Suivis -->
        <h4>Suivis</h4>
        <ul>
            @foreach ($student->followUps as $followUp)
                <li>{{ $followUp->details }}</li>
            @endforeach
        </ul>

        <!-- Statut des entreprises -->
        <h4>Statut des Entreprises</h4>
        <ul>
            @foreach ($student->businessStatuses as $status)
                <li>{{ $status->status }}</li>
            @endforeach
        </ul>

        <!-- Entités -->
        <h4>Entités</h4>
        <ul>
            @foreach ($student->entities as $entity)
                <li>{{ $entity->name }}</li>
            @endforeach
        </ul>

        <!-- Bouton d'impression -->
        <div class="text-center mt-4">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
    </div>

</body>
</html>
