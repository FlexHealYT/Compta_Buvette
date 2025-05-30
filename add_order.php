<?php
function addOrder($orderContent) {
    $filename = "orders.txt";
    $orderNumber = 1;
    
    // Lire le fichier existant pour trouver le dernier numéro
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (preg_match('/^(\d+)\s*-\s*/', $line, $matches)) {
                $currentNumber = (int)$matches[1];
                $orderNumber = max($orderNumber, $currentNumber + 1);
            }
        }
    }
    
    // Supprimer le numéro de commande s'il est déjà présent
    $orderContent = preg_replace('/^\d+\s*-\s*/', '', $orderContent);

    // Formater la nouvelle commande
    $newOrder = sprintf("%d - %s\n", $orderNumber, $orderContent);

    
    // Ajouter la commande au fichier
    file_put_contents($filename, $newOrder, FILE_APPEND);
    
    return $orderNumber;
}

// Traiter les paramètres GET
$orderContent = "";
foreach ($_GET as $item => $quantity) {
    if ($quantity > 0) {
        $item = str_replace('_', ' ', $item);
        $orderContent .= $item . " : " . $quantity . ', ';
    }
}

if (!empty($orderContent)) {
    addOrder(trim($orderContent));
}

// Afficher toutes les commandes
$allOrders = file_get_contents("orders.txt");
echo nl2br($allOrders);
?>