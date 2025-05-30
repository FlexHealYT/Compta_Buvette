<?php
$menu = ["Eau", "Soda_Coca", "Soda_Oasis", "Soda_Fanta", "Soda_Ice_Tea", "Café", "Thé", "Hot-Dog", "Sandwich_Fromage", "Sandwich_Jambon", "Crêpe_Nutella", "Crêpe_Confiture", "Crêpes_sucre"];

foreach ($menu as $plat) {
    $nomFichier = "compta/".$plat;
    file_put_contents($nomFichier, '');
}

file_put_contents("orders.txt", '');
file_put_contents("finishOrders.txt", '');

?>