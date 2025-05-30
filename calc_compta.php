<?php
$menu = ["Eau", "Soda_Coca", "Soda_Oasis", "Soda_Ice_Tea", "Café", "Thé", "Hot-Dog", "Sandwich_Fromage", "Sandwich_Jambon", "Crêpe_Nutella", "Crêpe_Confiture", "Crêpes_sucre"];

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
    echo $plat . ' : ' . $content . "<br>";
}
?>