<?php

require_once 'vendor/autoload.php';

use App\Models\Site;
use App\Models\Promotion;
use App\Models\Student;

try {
    // Vérifier les données existantes
    echo 'Sites: ' . Site::count() . PHP_EOL;
    echo 'Promotions: ' . Promotion::count() . PHP_EOL;
    echo 'Students: ' . Student::count() . PHP_EOL;

    // Afficher quelques exemples
    if (Site::count() > 0) {
        $site = Site::first();
        echo 'Premier site: ' . $site->designation . PHP_EOL;
    }
    if (Promotion::count() > 0) {
        $promotion = Promotion::first();
        echo 'Première promotion: ' . $promotion->num_promotion . PHP_EOL;
    }
    if (Student::count() > 0) {
        $student = Student::first();
        echo 'Premier étudiant: ' . $student->first_name . ' ' . $student->last_name . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Erreur: ' . $e->getMessage() . PHP_EOL;
}
