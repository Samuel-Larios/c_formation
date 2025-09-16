<?php
require_once 'vendor/autoload.php';

use App\Models\Student;
use App\Models\Site;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;

// Test de l'import avec un fichier XLS simple
echo "<h1>Test d'import XLS</h1>";

// Vérifier les extensions
echo "<h2>Extensions PHP :</h2>";
echo "ZipArchive: " . (class_exists('ZipArchive') ? 'Disponible' : 'Non disponible') . "<br>";
echo "PHPExcel: " . (class_exists('PHPExcel') ? 'Disponible' : 'Non disponible') . "<br>";

// Vérifier les données de test
echo "<h2>Données de test :</h2>";
$sites = Site::take(3)->get();
$promotions = Promotion::take(3)->get();

echo "<h3>Sites disponibles :</h3>";
foreach ($sites as $site) {
    echo "- {$site->designation} (ID: {$site->id})<br>";
}

echo "<h3>Promotions disponibles :</h3>";
foreach ($promotions as $promotion) {
    echo "- {$promotion->num_promotion} (ID: {$promotion->id})<br>";
}

echo "<h2>Instructions :</h2>";
echo "<ol>";
echo "<li>Créez un fichier Excel (.xls) avec les colonnes suivantes :</li>";
echo "<ul>";
echo "<li>ID (laisser vide)</li>";
echo "<li>Prénom</li>";
echo "<li>Nom</li>";
echo "<li>Sexe (M ou F)</li>";
echo "<li>Situation Matrimoniale</li>";
echo "<li>Situation Handicapé</li>";
echo "<li>Date de Naissance (AAAA-MM-JJ)</li>";
echo "<li>Contact</li>";
echo "<li>Email</li>";
echo "<li>État d'origine</li>";
echo "<li>État de résidence</li>";
echo "<li>Site (nom du site)</li>";
echo "<li>Promotion (numéro de promotion)</li>";
echo "</ul>";
echo "<li>Utilisez les noms de sites et numéros de promotion listés ci-dessus</li>";
echo "<li>Importez le fichier via l'interface d'administration</li>";
echo "</ol>";

echo "<h2>Exemple de données :</h2>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Prénom</th><th>Nom</th><th>Sexe</th><th>Email</th><th>Site</th><th>Promotion</th></tr>";
echo "<tr><td></td><td>Jean</td><td>Dupont</td><td>M</td><td>jean@example.com</td><td>{$sites->first()->designation}</td><td>{$promotions->first()->num_promotion}</td></tr>";
echo "<tr><td></td><td>Marie</td><td>Martin</td><td>F</td><td>marie@example.com</td><td>{$sites->first()->designation}</td><td>{$promotions->first()->num_promotion}</td></tr>";
echo "</table>";
?>
