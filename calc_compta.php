<?php
/**
 * Gestion des fichiers de comptabilité pour chaque item du menu
 * Crée ou met à jour les fichiers de comptabilité dans le dossier 'compta/'
 */

$menuFile = 'menu.json';
$menuItems = [];
if (file_exists($menuFile)) {
    $menuData = json_decode(file_get_contents($menuFile), true);
    $menu = array_keys($menuData);
    $menuItems = $menuData;
} else {
    // Fallback if menu.json does not exist (should not happen after initial creation)
    $menu = ["Eau", "Soda_Coca", "Soda_Oasis", "Soda_Ice_Tea", "Café", "Thé", "Hot-Dog_Ketchup", "Hot-Dog_Mayo", "Sandwich_Fromage", "Sandwich_Jambon", "Crêpe_Nutella", "Crêpe_Confiture", "Crêpes_sucre"];
    $menuItems = [
        "Eau" => 1,
        "Soda_Coca" => 2,
        "Soda_Oasis" => 2,
        "Soda_Ice_Tea" => 2,
        "Café" => 1,
        "Thé" => 0.5,
        "Hot-Dog_Ketchup" => 2.5,
        "Hot-Dog_Mayo" => 2.5,
        "Sandwich_Fromage" => 2.5,
        "Sandwich_Jambon" => 2.5,
        "Crêpe_Nutella" => 1.5,
        "Crêpe_Confiture" => 1.5,
        "Crêpes_sucre" => 1
    ];
}

// Initialisation ou mise à jour des fichiers de comptabilité
foreach ($menu as $plat) {
    $nomFichier = "compta/".$plat;
    if (!file_exists($nomFichier)){    
        file_put_contents($nomFichier, '0');
    }

    $content = file_get_contents($nomFichier);
    $intContent = intval(($content));  
    
    if (isset($_GET[$plat])) {
        $intContent += intval($_GET[$plat]);
    }
    file_put_contents($nomFichier, $intContent);
}

/**
 * Calcule le prix total pour un ou plusieurs items
 * 
 * @param mixed $items Un item unique ou un tableau d'items
 * @param mixed $prices Un prix unique ou un tableau de prix
 * @return string Prix total formaté avec 2 décimales
 */
function calcTotalPrice($items, $prices) {
    $totalPrice = 0.0;
    
    if (!is_array($items)) {
        // Cas d'un seul item
        $filename = 'compta/' . $items;
        if (file_exists($filename)) {
            $quantity = (int)file_get_contents($filename);
            $totalPrice = (float)$quantity * (float)$prices;
        }
    } else {
        // Cas de plusieurs items
        foreach ($items as $index => $item) {
            $filename = 'compta/' . $item;
            if (file_exists($filename)) {
                $quantity = (int)file_get_contents($filename);
                $price = (float)$prices[$index];
                $totalPrice += (float)$quantity * $price;
            }
        }
    }
    // Debug: Inspect $totalPrice before returning
    // var_dump('Before number_format in calcTotalPrice', $totalPrice);
    return number_format((float)$totalPrice, 2);
}

/**
 * Génère un tableau HTML stylisé pour afficher les items du menu et leurs prix
 * 
 * @param array $menuItemsArray Tableau associatif des items (nom => prix)
 * @param string $params Paramètres HTML additionnels pour le tableau (optionnel)
 * @return string HTML du tableau avec styles intégrés
 */
function utilHtmlTable($menuItemsArray, $params = '') {
    $data = '
    <style>
        /* Variables */
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --primary-darker: #1a5276;
            --text-color: #222;
            --text-light: #f0f0f0;
            --bg-color: #f9f9f9;
            --bg-dark: #1a1a1a;
            --bg-darker: #2c3e50;
            --border-color: #ddd;
            --border-dark: #34495e;
            --shadow-light: rgba(0,0,0,0.1);
            --shadow-dark: rgba(0,0,0,0.3);
            --transition-speed: 0.3s;
        }

        /* Base styles */
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: var(--bg-color);
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        /* Dark theme */
        body.dark-theme {
            background-color: var(--bg-dark);
            color: var(--text-light);
        }

        /* Table styles */
        .menu-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px var(--shadow-light);
            transition: background-color var(--transition-speed), box-shadow var(--transition-speed);
        }

        body.dark-theme .menu-table {
            background-color: var(--bg-darker);
            box-shadow: 0 2px 4px var(--shadow-dark);
        }

        /* Table header */
        .menu-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            border-bottom: 2px solid var(--primary-dark);
            white-space: nowrap;
        }

        /* Table cells */
        .menu-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        body.dark-theme .menu-table td {
            border-bottom: 1px solid var(--border-dark);
        }

        /* Row hover effect */
        .menu-table tbody tr {
            transition: background-color var(--transition-speed), transform var(--transition-speed);
        }

        .menu-table tbody tr:hover {
            background-color: #f5f5f5;
            transform: translateX(5px);
        }

        .menu-table tbody tr:hover td {
            color: var(--primary-color);
        }

        body.dark-theme .menu-table tbody tr:hover {
            background-color: var(--border-dark);
        }

        /* Remove last row border */
        .menu-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Price and Quantity cells */
        .price-cell,
        .quantity-cell {
            text-align: right;
            font-family: "Courier New", monospace;
            font-weight: bold;
            color: var(--bg-darker);
            white-space: nowrap;
        }

        body.dark-theme .price-cell,
        body.dark-theme .quantity-cell {
            color: var(--text-light);
        }

        /* Item cells */
        .item-cell {
            font-weight: 500;
            color: var(--bg-darker);
        }

        body.dark-theme .item-cell {
            color: var(--text-light);
        }

        /* Category styles */
        .category-row {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 1.1em;
            border-top: 2px solid var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            transition: background-color var(--transition-speed);
        }

        body.dark-theme .category-row {
            background-color: var(--border-dark);
            border-top: 2px solid var(--primary-dark);
            border-bottom: 2px solid var(--primary-dark);
        }

        .category-row .item-cell {
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Total row styles */
        .total-row {
            background-color: var(--primary-color);
            font-weight: bold;
            font-size: 1.2em;
            border-top: 3px solid var(--primary-dark);
            transition: background-color var(--transition-speed);
        }

        body.dark-theme .total-row {
            background-color: var(--primary-dark);
            border-top: 3px solid var(--primary-darker);
        }

        .total-row .item-cell,
        .total-row .price-cell,
        .total-row .quantity-cell {
            color: white;
            background-color: transparent;
        }

        /* Theme switch */
        .theme-switch {
            position: fixed;
            top: 18px;
            right: 18px;
            z-index: 100;
            opacity: 0.7;
            scale: 0.8;
            transition: opacity var(--transition-speed), scale var(--transition-speed);
        }

        /* Back button */
        .back-btn-container {
            margin-bottom: 20px;
            text-align: left;
        }

        .back-btn {
            background-color: #6c757d;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        /* Other styles for general page layout */
        .container {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        body.dark-theme .container {
            background-color: var(--bg-darker);
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
    ';

    $data .= '<table class="menu-table" ' . $params . '><thead><tr>';
    $data .= '<th>Item</th><th>Prix unitaire</th><th>Quantité</th><th>Prix total</th>';
    $data .= '</tr></thead><tbody>';

    // Initialisation des totaux par catégorie et du total général
    $categorizedItems = [
        'Snacks' => [],
        'Desserts' => [],
        'Boissons' => []
    ];

    // Populate categorizedItems
    foreach ($menuItemsArray as $itemName => $price) {
        $lowerItemName = strtolower($itemName);
        if (str_contains($lowerItemName, 'hot-dog') || str_contains($lowerItemName, 'sandwich')) {
            $categorizedItems['Snacks'][$itemName] = $price;
        } elseif (str_contains($lowerItemName, 'crêpe')) {
            $categorizedItems['Desserts'][$itemName] = $price;
        } else { // Assuming everything else is a beverage
            $categorizedItems['Boissons'][$itemName] = $price;
        }
    }

    $categoryOrder = ['Snacks', 'Desserts', 'Boissons'];

    $allItems = [];
    $allPrices = [];

    foreach ($categoryOrder as $categoryName) {
        if (!empty($categorizedItems[$categoryName])) {
            // Add category header
            $data .= '<tr class="category-row"><td class="item-cell" colspan="4">' . htmlspecialchars($categoryName) . '</td></tr>';

            $categoryItemNames = [];
            $categoryPrices = [];

            foreach ($categorizedItems[$categoryName] as $itemName => $price) {
                $filename = 'compta/' . $itemName;
                $quantity = file_exists($filename) ? (int)file_get_contents($filename) : 0;

                $data .= '<tr data-item-id="' . str_replace(' ', '_', $itemName) . '" data-unit-price="' . $price . '">';
                $data .= '<td class="item-cell">' . str_replace('_', ' ', $itemName) . '</td>';
                // Debug: Inspect $price before number_format
                // var_dump('$price for unit price', $price);
                $data .= '<td class="price-cell">' . number_format((float)$price, 2) . '€</td>';
                $data .= '<td class="quantity-cell"><input type="number" class="quantity-input" value="' . $quantity . '" min="0"></td>';
                // Debug: Inspect arguments for calcTotalPrice
                // var_dump('Arguments for single item calcTotalPrice', $itemName, $price);
                $data .= '<td class="price-cell" id="total_' . str_replace(' ', '_', $itemName) . '">' . calcTotalPrice($itemName, (float)$price) . '€</td>';
                $data .= '</tr>';

                array_push($categoryItemNames, $itemName);
                array_push($categoryPrices, $price);
                array_push($allItems, $itemName);
                array_push($allPrices, $price);
            }

            // Add category total row
            $data .= '<tr class="total-row"><td class="item-cell">' . 'Total ' . htmlspecialchars($categoryName) . '</td><td colspan="2"></td>';
            // Debug: Inspect arguments for category total calcTotalPrice
            // var_dump('Arguments for category total calcTotalPrice', $categoryItemNames, $categoryPrices);
            $data .= '<td class="price-cell" id="total_' . strtolower($categoryName) . '">' . calcTotalPrice($categoryItemNames, $categoryPrices) . '€</td></tr>';
        }
    }

    // Affichage du total général
    $data .= '<tr class="total-row"><td class="item-cell">' . 'Total Général' . '</td><td colspan="2"></td>';
    // Debug: Inspect arguments for grand total calcTotalPrice
    // var_dump('Arguments for grand total calcTotalPrice', $allItems, $allPrices);
    $data .= '<td class="price-cell" id="total_total">' . calcTotalPrice($allItems, $allPrices) . '€</td></tr>';

    $data .= '</tbody></table>';

    return $data;
}

// Définition des items du menu et leurs prix
/*
$menuItems = [
    "Eau" => 1,
    "Soda_Coca" => 2,
    "Soda_Oasis" => 2,
    "Soda_Fanta" => 2,
    "Soda_Ice_Tea" => 2,
    "Café" => 1.5,
    "Thé" => 1.5,
    "Hot-Dog_Ketchup" => 3,
    "Hot-Dog_Mayo" => 3,
    "Sandwich_Fromage" => 4,
    "Sandwich_Jambon" => 4.5,
    "Crêpe_Nutella" => 3,
    "Crêpe_Confiture" => 2.5,
    "Crêpes_sucre" => 2
];
*/

// Affichage du tableau
// var_dump('Calling utilHtmlTable with', $menuItems);
echo utilHtmlTable($menuItems);
?>