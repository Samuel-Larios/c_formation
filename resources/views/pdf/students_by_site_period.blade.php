<!DOCTYPE html>
<html>
<head>
    <title>Statistiques des Étudiants</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; }
        .info { margin: 20px 0; }
        .count { font-size: 24px; font-weight: bold; text-align: center; margin: 30px 0; }
        .footer { margin-top: 50px; font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport des étudiants inscrits</div>
        <div class="subtitle">Généré le {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="info">
        <p><strong>Site:</strong> {{ $site->designation }} - {{ $site->emplacement }}</p>
        <p><strong>Période:</strong> du {{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }}</p>
    </div>

    <div class="count">
        Nombre d'étudiants inscrits: {{ $count }}
    </div>

    <div class="footer">
        © {{ date('Y') }} - Tous droits réservés
    </div>
</body>
</html>
