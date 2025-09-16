<?php

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Créer un nouveau spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// En-têtes des colonnes
$headers = [
    'ID',
    'First Name',
    'Last Name',
    'Gender',
    'Marital Status',
    'Disability Status',
    'Date of Birth',
    'Contact',
    'Contact Pers1',
    'Contact Pers2',
    'Contact Pers3',
    'Contact Pers4',
    'Contact Pers5',
    'Email',
    'Password',
    'State of Origin',
    'State of Residence',
    'State',
    'LGA',
    'Community',
    'Site',
    'Promotion'
];

$sheet->fromArray($headers, null, 'A1');

// Données de test
$testData = [
    [
        '', // ID (vide pour nouvel étudiant)
        'Jean',
        'Dupont',
        'M',
        'Célibataire',
        'Non',
        '1990-05-15',
        '0123456789',
        '0123456788',
        '',
        '',
        '',
        '',
        'jean.dupont@example.com',
        '', // Mot de passe vide (sera généré)
        'Paris',
        'Paris',
        'Île-de-France',
        'Paris',
        'Centre-ville',
        'Site Principal', // Nom du site
        'PROM-2024-001' // Numéro de promotion
    ],
    [
        '',
        'Marie',
        'Martin',
        'F',
        'Mariée',
        'Non',
        '1988-03-22',
        '0987654321',
        '0987654320',
        '',
        '',
        '',
        '',
        'marie.martin@example.com',
        '',
        'Lyon',
        'Lyon',
        'Auvergne-Rhône-Alpes',
        'Lyon',
        'Bellecour',
        'Site Secondaire',
        'PROM-2024-002'
    ],
    [
        '',
        'Pierre',
        'Dubois',
        'M',
        'Célibataire',
        'Oui',
        '1992-11-08',
        '0567891234',
        '0567891233',
        '',
        '',
        '',
        '',
        'pierre.dubois@example.com',
        '',
        'Marseille',
        'Marseille',
        'Provence-Alpes-Côte d\'Azur',
        'Marseille',
        'Vieux-Port',
        'Site Principal',
        'PROM-2024-001'
    ]
];

$sheet->fromArray($testData, null, 'A2');

// Ajuster la largeur des colonnes
foreach (range('A', 'V') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Créer le writer et sauvegarder
$writer = new Xlsx($spreadsheet);
$filename = 'test_students_import.xlsx';
$writer->save($filename);

echo "Fichier de test créé : $filename\n";
echo "Données de test :\n";
echo "- Site Principal\n";
echo "- Site Secondaire\n";
echo "- PROM-2024-001\n";
echo "- PROM-2024-002\n";
