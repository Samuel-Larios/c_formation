<?php

// Script simple pour créer un fichier CSV qui peut être ouvert dans Excel
$filename = 'test_students_import.csv';

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

// Données de test
$data = [
    ['', 'Jean', 'Dupont', 'M', 'Célibataire', 'Non', '1990-05-15', '0123456789', '0123456788', '', '', '', '', 'jean.dupont@example.com', '', 'Paris', 'Paris', 'Île-de-France', 'Paris', 'Centre-ville', 'Site Principal', 'PROM-2024-001'],
    ['', 'Marie', 'Martin', 'F', 'Mariée', 'Non', '1988-03-22', '0987654321', '0987654320', '', '', '', '', 'marie.martin@example.com', '', 'Lyon', 'Lyon', 'Auvergne-Rhône-Alpes', 'Lyon', 'Bellecour', 'Site Secondaire', 'PROM-2024-002'],
    ['', 'Pierre', 'Dubois', 'M', 'Célibataire', 'Oui', '1992-11-08', '0567891234', '0567891233', '', '', '', '', 'pierre.dubois@example.com', '', 'Marseille', 'Marseille', 'Provence-Alpes-Côte d\'Azur', 'Marseille', 'Vieux-Port', 'Site Principal', 'PROM-2024-001']
];

// Ouvrir le fichier en écriture
$file = fopen($filename, 'w');

// Écrire les en-têtes
fputcsv($file, $headers);

// Écrire les données
foreach ($data as $row) {
    fputcsv($file, $row);
}

// Fermer le fichier
fclose($file);

echo "Fichier CSV de test créé : $filename\n";
echo "Vous pouvez l'ouvrir avec Excel et le sauvegarder en .xlsx\n";
echo "\nDonnées utilisées dans le test :\n";
echo "- Sites : Site Principal, Site Secondaire\n";
echo "- Promotions : PROM-2024-001, PROM-2024-002\n";
