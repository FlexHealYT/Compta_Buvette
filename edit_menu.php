<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        .menu-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .menu-item input[type="text"],
        .menu-item input[type="number"] {
            flex-grow: 1;
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .menu-item button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 5px;
        }
        .menu-item .update-btn {
            background-color: #007bff;
            color: white;
        }
        .menu-item .update-btn:hover {
            background-color: #0056b3;
        }
        .menu-item .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .menu-item .delete-btn:hover {
            background-color: #c82333;
        }
        .add-form {
            display: flex;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .add-form input {
            margin-right: 10px;
        }
        .add-form button {
            background-color: #28a745;
            color: white;
        }
        .add-form button:hover {
            background-color: #218838;
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
        <h1>Modifier le Menu</h1>

        <?php
        $menuFile = 'menu.json';
        $menuItems = [];
        $message = '';

        // Charger le menu actuel
        if (file_exists($menuFile)) {
            $menuItems = json_decode(file_get_contents($menuFile), true);
            if ($menuItems === null) { // Gestion d'erreur si le JSON est mal formé
                $menuItems = [];
                $message = '<div class="message error">Erreur: Le fichier menu.json est mal formé.</div>';
            }
        } else {
            $message = '<div class="message error">Erreur: Le fichier menu.json est introuvable.</div>';
        }

        // Gérer les actions (ajouter, modifier, supprimer)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                $action = $_POST['action'];
                $itemName = isset($_POST['item_name']) ? trim($_POST['item_name']) : '';
                $itemPrice = isset($_POST['item_price']) ? floatval($_POST['item_price']) : 0.0;
                $oldItemName = isset($_POST['old_item_name']) ? trim($_POST['old_item_name']) : '';

                switch ($action) {
                    case 'add':
                        if (!empty($itemName) && !isset($menuItems[$itemName])) {
                            $menuItems[$itemName] = $itemPrice;
                            $message = '<div class="message success">Item "' . htmlspecialchars($itemName) . '" ajouté avec succès.</div>';
                        } else {
                            $message = '<div class="message error">Erreur: Le nom de l\'item est vide ou existe déjà.</div>';
                        }
                        break;
                    case 'update':
                        if (!empty($oldItemName) && isset($menuItems[$oldItemName])) {
                            if ($oldItemName !== $itemName) {
                                // Si le nom a changé, supprimer l'ancien et ajouter le nouveau
                                unset($menuItems[$oldItemName]);
                                $menuItems[$itemName] = $itemPrice;
                                $message = '<div class="message success">Item "' . htmlspecialchars($oldItemName) . '" mis à jour en "' . htmlspecialchars($itemName) . '".</div>';
                            } else {
                                // Si seul le prix a changé
                                $menuItems[$itemName] = $itemPrice;
                                $message = '<div class="message success">Prix de "' . htmlspecialchars($itemName) . '" mis à jour avec succès.</div>';
                            }
                        } else {
                            $message = '<div class="message error">Erreur: Item à modifier introuvable.</div>';
                        }
                        break;
                    case 'delete':
                        if (!empty($itemName) && isset($menuItems[$itemName])) {
                            unset($menuItems[$itemName]);
                            $message = '<div class="message success">Item "' . htmlspecialchars($itemName) . '" supprimé avec succès.</div>';
                        } else {
                            $message = '<div class="message error">Erreur: Item à supprimer introuvable.</div>';
                        }
                        break;
                }
                // Sauvegarder les modifications
                file_put_contents($menuFile, json_encode($menuItems, JSON_PRETTY_PRINT));
            }
        }
        ?>

        <?php echo $message; ?>

        <h2>Ajouter un nouvel item</h2>
        <form method="POST" class="add-form">
            <input type="hidden" name="action" value="add">
            <input type="text" name="item_name" placeholder="Nom de l'item" required>
            <input type="number" name="item_price" placeholder="Prix" step="0.01" min="0" required>
            <button type="submit">Ajouter</button>
        </form>

        <h2>Items existants</h2>
        <?php if (!empty($menuItems)): ?>
            <?php foreach ($menuItems as $name => $price): ?>
                <div class="menu-item">
                    <form method="POST" style="display: flex; flex-grow: 1;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="old_item_name" value="<?php echo htmlspecialchars($name); ?>">
                        <input type="text" name="item_name" value="<?php echo htmlspecialchars($name); ?>" required>
                        <input type="number" name="item_price" value="<?php echo htmlspecialchars($price); ?>" step="0.01" min="0" required>
                        <button type="submit" class="update-btn">Modifier</button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($name); ?>">
                        <button type="submit" class="delete-btn">Supprimer</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun item dans le menu pour le moment.</p>
        <?php endif; ?>
    </div>
</body>
</html> 