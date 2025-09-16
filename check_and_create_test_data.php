<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Promotion;
use App\Models\User;

echo "Vérification des données de test...\n\n";

// Vérifier les sites existants
echo "Sites existants :\n";
$sites = Site::all();
foreach ($sites as $site) {
    echo "- {$site->designation} (ID: {$site->id})\n";
}

// Créer les sites de test s'ils n'existent pas
$testSites = ['Site Principal', 'Site Secondaire'];
foreach ($testSites as $siteName) {
    $existingSite = Site::where('designation', $siteName)->first();
    if (!$existingSite) {
        $site = Site::create([
            'designation' => $siteName,
            'description' => 'Site de test pour import étudiants',
            'location' => 'Test Location',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✓ Site créé : {$siteName} (ID: {$site->id})\n";
    } else {
        echo "✓ Site existe déjà : {$siteName} (ID: {$existingSite->id})\n";
    }
}

echo "\nPromotions existantes :\n";
$promotions = Promotion::all();
foreach ($promotions as $promotion) {
    echo "- {$promotion->num_promotion} (ID: {$promotion->id}, Site: {$promotion->site->designation})\n";
}

// Créer les promotions de test
$testPromotions = [
    ['num_promotion' => 'PROM-2024-001', 'site_name' => 'Site Principal'],
    ['num_promotion' => 'PROM-2024-002', 'site_name' => 'Site Secondaire']
];

foreach ($testPromotions as $promoData) {
    $site = Site::where('designation', $promoData['site_name'])->first();
    if ($site) {
        $existingPromo = Promotion::where('num_promotion', $promoData['num_promotion'])->first();
        if (!$existingPromo) {
            $promotion = Promotion::create([
                'num_promotion' => $promoData['num_promotion'],
                'site_id' => $site->id,
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'description' => 'Promotion de test',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✓ Promotion créée : {$promoData['num_promotion']} (ID: {$promotion->id})\n";
        } else {
            echo "✓ Promotion existe déjà : {$promoData['num_promotion']} (ID: {$existingPromo->id})\n";
        }
    }
}

echo "\nDonnées de test prêtes pour l'import !\n";
echo "Vous pouvez maintenant :\n";
echo "1. Ouvrir test_students_import.csv avec Excel\n";
echo "2. Le sauvegarder en format .xlsx\n";
echo "3. L'importer via la page Export/Import des étudiants\n";
