<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Debug - Vérification des données ===\n\n";

// Vérifier les promotions
$promotions = DB::table('promotions')->get();
echo "Promotions trouvées: " . $promotions->count() . "\n";
foreach ($promotions as $promotion) {
    echo "- ID: {$promotion->id}, Num: {$promotion->num_promotion}, Site ID: {$promotion->site_id}\n";
}

echo "\n=== Vérification des associations promotion_apprenant ===\n";

// Vérifier les associations
$associations = DB::table('promotion_apprenant')->get();
echo "Associations trouvées: " . $associations->count() . "\n";
foreach ($associations as $assoc) {
    echo "- Promotion ID: {$assoc->promotion_id}, Student ID: {$assoc->student_id}, Site ID: {$assoc->site_id}\n";
}

echo "\n=== Vérification des étudiants ===\n";

// Vérifier les étudiants
$students = DB::table('students')->get();
echo "Étudiants trouvés: " . $students->count() . "\n";
foreach ($students as $student) {
    echo "- ID: {$student->id}, Nom: {$student->first_name} {$student->last_name}, Sexe: {$student->sexe}, Site ID: {$student->site_id}\n";
}

echo "\n=== Test de la requête corrigée ===\n";

// Test de la requête pour une promotion spécifique
if ($promotions->count() > 0) {
    $testPromotionId = $promotions->first()->id;
    $testSiteId = $promotions->first()->site_id;

    echo "Test pour Promotion ID: {$testPromotionId}, Site ID: {$testSiteId}\n";

    // Requête corrigée pour compter les étudiants par genre
    $studentGenderCounts = DB::table('promotion_apprenant')
        ->join('students', 'promotion_apprenant.student_id', '=', 'students.id')
        ->where('promotion_apprenant.promotion_id', $testPromotionId)
        ->where('promotion_apprenant.site_id', $testSiteId)
        ->selectRaw('students.sexe, COUNT(*) as count')
        ->groupBy('students.sexe')
        ->get();

    echo "Résultats de la requête:\n";
    foreach ($studentGenderCounts as $result) {
        echo "- Sexe: {$result->sexe}, Count: {$result->count}\n";
    }

    if ($studentGenderCounts->count() == 0) {
        echo "Aucun résultat trouvé. Vérifiez les données dans la table promotion_apprenant.\n";
    }
}

echo "\n=== Fin du debug ===\n";
