<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Personnaliser les Couleurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="color"] {
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 4px;
            margin-bottom: 15px;
            cursor: pointer;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .back-btn-container {
            margin-bottom: 20px;
            text-align: center;
        }
        .back-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="back-btn-container">
        <button class="back-btn" onclick="window.location.href='admin.html'">
            ← Retour
        </button>
    </div>

    <div class="container">
        <h1>Personnaliser les Couleurs des Items</h1>

        <?php
        $colorsFile = 'colors.json';
        $colors = [];
        $message = '';

        // Charger les couleurs actuelles
        if (file_exists($colorsFile)) {
            $colors = json_decode(file_get_contents($colorsFile), true);
        } else {
            // Couleurs par défaut si le fichier n'existe pas
            $colors = [
                'crepes' => '#add8e6',
                'drinks' => '#ffb6c1',
                'hotdogs_sandwiches' => '#90ee90'
            ];
        }

        // Gérer la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newColors = [
                'crepes' => $_POST['crepes_color'],
                'drinks' => $_POST['drinks_color'],
                'hotdogs_sandwiches' => $_POST['hotdogs_sandwiches_color']
            ];

            if (json_encode($newColors)) {
                file_put_contents($colorsFile, json_encode($newColors, JSON_PRETTY_PRINT));
                $colors = $newColors; // Mettre à jour les couleurs affichées
                $message = "<div class=\"message success\">Couleurs mises à jour avec succès !</div>";
            } else {
                $message = "<div class=\"message error\">Erreur lors de l'enregistrement des couleurs.</div>";
            }
        }
        ?>

        <?php echo $message; ?>

        <form method="POST">
            <label for="crepes_color">Couleur des Crêpes :</label>
            <input type="color" id="crepes_color" name="crepes_color" value="<?php echo htmlspecialchars($colors['crepes']); ?>">

            <label for="drinks_color">Couleur des Boissons / Café / Thé :</label>
            <input type="color" id="drinks_color" name="drinks_color" value="<?php echo htmlspecialchars($colors['drinks']); ?>">

            <label for="hotdogs_sandwiches_color">Couleur des Hot-dogs / Sandwichs :</label>
            <input type="color" id="hotdogs_sandwiches_color" name="hotdogs_sandwiches_color" value="<?php echo htmlspecialchars($colors['hotdogs_sandwiches']); ?>">

            <button type="submit">Enregistrer les Couleurs</button>
        </form>
    </div>
</body>
</html> 