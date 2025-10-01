<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Vérification des données de promotion ===\n\n";

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

echo "\n=== Vérification des étudiants par promotion ===\n";

// Pour chaque promotion, compter les étudiants
foreach ($promotions as $promotion) {
    $studentCount = DB::table('promotion_apprenant')
        ->where('promotion_id', $promotion->id)
        ->count();

    echo "Promotion {$promotion->num_promotion}: {$studentCount} étudiants\n";

    // Distribution par genre des étudiants
    $genderCounts = DB::table('promotion_apprenant')
        ->join('students', 'promotion_apprenant.student_id', '=', 'students.id')
        ->where('promotion_apprenant.promotion_id', $promotion->id)
        ->selectRaw('students.sexe, COUNT(*) as count')
        ->groupBy('students.sexe')
        ->get();

    echo "  Distribution par genre: ";
    foreach ($genderCounts as $gender) {
        echo "{$gender->sexe}: {$gender->count} ";
    }
    echo "\n";
}

echo "\n=== Test de la requête du contrôleur ===\n";

// Simuler la requête du contrôleur pour une promotion
if ($promotions->count() > 0) {
    $testPromotionId = $promotions->first()->id;

    echo "Test pour la promotion ID: {$testPromotionId}\n";

    // Étudiants de la promotion
    $students = DB::table('promotion_apprenant')
        ->join('students', 'promotion_apprenant.student_id', '=', 'students.id')
        ->where('promotion_apprenant.promotion_id', $testPromotionId)
        ->select('students.*')
        ->get();

    echo "Étudiants trouvés: " . $students->count() . "\n";

    // Distribution par genre des étudiants
    $studentGenderCounts = DB::table('promotion_apprenant')
        ->join('students', 'promotion_apprenant.student_id', '=', 'students.id')
        ->where('promotion_apprenant.promotion_id', $testPromotionId)
        ->selectRaw('students.sexe, COUNT(*) as count')
        ->groupBy('students.sexe')
        ->pluck('count', 'sexe')
        ->toArray();

    echo "Distribution par genre des étudiants: " . json_encode($studentGenderCounts) . "\n";

    // Job creations pour ces étudiants
    $jobCreations = DB::table('job_creations')
        ->join('students', 'job_creations.student_id', '=', 'students.id')
        ->join('promotion_apprenant', 'students.id', '=', 'promotion_apprenant.student_id')
        ->where('promotion_apprenant.promotion_id', $testPromotionId)
        ->select('job_creations.*')
        ->get();

    echo "Job creations trouvés: " . $jobCreations->count() . "\n";

    // Distribution par genre des job creations
    $jobGenderCounts = DB::table('job_creations')
        ->join('students', 'job_creations.student_id', '=', 'students.id')
        ->join('promotion_apprenant', 'students.id', '=', 'promotion_apprenant.student_id')
        ->where('promotion_apprenant.promotion_id', $testPromotionId)
        ->selectRaw('job_creations.sexe, COUNT(*) as count')
        ->groupBy('job_creations.sexe')
        ->pluck('count', 'sexe')
        ->toArray();

    echo "Distribution par genre des employeurs: " . json_encode($jobGenderCounts) . "\n";
}

echo "\n=== Fin du test ===\n";
