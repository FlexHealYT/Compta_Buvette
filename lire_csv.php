<?php
header('Content-Type: application/json');

function lireCsv($fichier) {
    if (!file_exists($fichier)) return [];
    $data = [];
    if (($handle = fopen($fichier, "r")) !== false) {
        $headers = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== false) {
            $data[] = array_combine($headers, $row);
        }
        fclose($handle);
    }
    return $data;
}

$rows = lireCsv('equipes.csv');

// Étape 1 : recenser les catégories par club
$categoriesParClub = [];
foreach ($rows as $ligne) {
    $club = $ligne['Club'];
    $categorie = $ligne['Categorie'];
    if (!isset($categoriesParClub[$club])) {
        $categoriesParClub[$club] = [];
    }
    if (!in_array($categorie, $categoriesParClub[$club])) {
        $categoriesParClub[$club][] = $categorie;
    }
}

// Étape 2 : construire les équipes
$equipes = [];
foreach ($rows as $ligne) {
    $club = $ligne['Club'];
    $categorie = $ligne['Categorie'];
    
    // Si une seule catégorie, on n’affiche que le club
    $nomEquipe = (count($categoriesParClub[$club]) === 1) ? $club : "$club - $categorie";

    if (!isset($equipes[$nomEquipe])) {
        $equipes[$nomEquipe] = [];
    }
    $equipes[$nomEquipe][] = [
        'Nom' => $ligne['Nom'],
        'Prenom' => $ligne['Prenom']
    ];
}

echo json_encode($equipes);
