<?php
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

// Étape 2 : reconstruire les noms d’équipes cohérents
$equipes = [];
foreach ($rows as $ligne) {
    $club = $ligne['Club'];
    $categorie = $ligne['Categorie'];

    // Si une seule catégorie : nom = club, sinon club - catégorie
    $nomEquipe = (count($categoriesParClub[$club]) === 1) ? $club : "$club - $categorie";

    if (!isset($equipes[$nomEquipe])) {
        $equipes[$nomEquipe] = [];
    }
    $equipes[$nomEquipe][] = [
        'Nom' => $ligne['Nom'],
        'Prenom' => $ligne['Prenom']
    ];
}

// Récupération depuis l'URL
$equipeDemandee = $_GET['equipe'] ?? '';

// Récupération des membres
$membresEquipe = $equipes[$equipeDemandee] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($equipeDemandee ?: 'Equipe inconnue') ?></title>
    <link rel="stylesheet" href="style.css" />
    <script src="script.js"></script>
</head>
<body>
    <label class="theme-switch">
        <input type="checkbox" class="checkbox" />
        <div class="container">
            <div class="circle-container">
            <div class="sun-moon-container">
                <div class="moon">
                <div class="spot"></div>
                <div class="spot"></div>
                <div class="spot"></div>
                </div>
            </div>
            </div>
        </div>
    </label>

    <h1>
        <?php
        if ($membresEquipe) {
            echo "Bienvenue dans " . htmlspecialchars($equipeDemandee);
        } else {
            echo "Aucune équipe sélectionnée ou équipe inconnue.";
        }
        ?>
    </h1>
    <ul>
        <?php
        if ($membresEquipe) {
            foreach ($membresEquipe as $index => $membre) {
                $name = strtoupper(htmlspecialchars($membre['Nom']));
                echo "<li>";
                echo "<label for='checkbox-$index'>" . $name . " " . htmlspecialchars($membre['Prenom']) . "</label>";
                echo "<input type='checkbox' id='checkbox-$index' class='dropdown-checkbox'>";
                echo "<img src='src/fleche-vers-le-bas.png' alt='Flèche vers le bas' class='arrow-down'>";
                echo "<div class='dropdown-content'>";
                echo "Contenu du menu déroulant pour " . $name . " " . htmlspecialchars($membre['Prenom']);
                echo "</div>";
                echo "</li>";
            }
        }
        ?>
    </ul>
</body>
</html>
