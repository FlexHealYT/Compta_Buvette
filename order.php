<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes</title>
    <link rel="stylesheet" href="order.css">
    <script src="src/theme.js"></script>
    <script src="script.js"></script>
</head>
<body>
    <div class="theme-switch">
        <input type="checkbox" id="theme-toggle" onchange="toggleTheme()">
        <label for="theme-toggle">🌙</label>
    </div>

    <div class="back-btn-container">
        <button class="back-btn" onclick="window.location.href='admin.html'">
            ← Retour
        </button>
    </div>

    <div class="container">
        <h1>Commandes</h1>
        <div class="audio-controls">
            <button class="play-button" onclick="playAudio()">Lancer l'audio</button>
            <div class="sound-toggle" onclick="toggleSound()">
                <input type="checkbox" id="soundToggle" checked>
                <span class="sound-icon" id="soundIcon">🔊</span>
            </div>
        </div>
    </div>

    <audio id="myAudio" style="display: none;">
        <source src="src/notif.mp3" type="audio/mpeg">
        Votre navigateur ne supporte pas l'élément audio.
    </audio>

    <div id="order_container">Aucune commande pour le moment.
    <?php
        $nomFichier = "orders.txt";
        if (file_exists($nomFichier)) {
            $content = file_get_contents($nomFichier);
            echo nl2br($content);
        } else {
            echo "Aucune commande pour le moment.";
        }
        ?>
    </div>

    <div id="selected" style="display: none;"></div>

    <script>
    let audio = document.getElementById("myAudio");
    let soundIcon = document.getElementById("soundIcon");
    let soundToggle = document.getElementById("soundToggle");
    let orders = "";
    let customColors = {}; // Variable pour stocker les couleurs personnalisées

    // Charger les couleurs personnalisées au démarrage
    async function loadCustomColors() {
        try {
            const response = await fetch('colors.json');
            if (response.ok) {
                customColors = await response.json();
            } else {
                console.error('Erreur lors du chargement des couleurs personnalisées.');
            }
        } catch (error) {
            console.error('Erreur réseau lors du chargement des couleurs:', error);
        }
    }

    // Appeler loadCustomColors au chargement de la page
    document.addEventListener('DOMContentLoaded', loadCustomColors);

    async function updateOrders() {
        try {
            const response = await fetch("orders.txt");
            if (!response.ok) throw new Error("Fichier introuvable");
            const new_orders = await response.text();

            const finish_orders_response = await fetch('get_finish_orders.php');
            if (!finish_orders_response.ok) throw new Error("Erreur lors de la récupération des commandes terminées");
            const finish_orders = await finish_orders_response.text();

            const finish_orders_array = finish_orders.split(',').map(id => parseInt(id.trim())).filter(id => !isNaN(id));

            if (new_orders !== orders) {
                const container = document.getElementById("order_container");
                container.innerHTML = ""; // Vider le conteneur
                const ordersArray = new_orders.split(/\n/);
                ordersArray.forEach((order) => {
                    if (order.trim()) { // Ne pas ajouter les lignes vides
                        const match = order.match(/^(\d+)\s*-\s*(.*)/);
                        if (match) {
                            const [_, orderNumber, orderContent] = match;
                            const orderId = parseInt(orderNumber);
                            if (!finish_orders_array.includes(orderId)) {
                                container.innerHTML += `<p id="order-${orderNumber}">${order.slice(0, -1)} <button class='order-buttons' id='button-${orderNumber}' onclick=viewOrder(${orderNumber})>Voir commande</button></p>`;
                            }
                        }
                    }
                });
                playAudio();
                orders = new_orders;
            }
        } catch (error) {
            console.error("Erreur lors de la vérification des commandes:", error);
            document.getElementById("order_container").innerHTML = "Aucune commande pour le moment.";
        }
    }

    setInterval(updateOrders, 100);

    function viewOrder(orderID) {
        fetch(`get_order.php?id=${orderID}`)
            .then(response => response.text())
            .then(data => {
                data = data.slice(data.indexOf('-') + 2);
                data = data.split(", ");

                // Function to get item category for sorting
                function getItemCategory(item) {
                    item = item.toLowerCase();
                    if (item.includes('hot-dog') || item.includes('sandwich')) return 1; // Snacks first
                    if (item.includes('crêpe')) return 2; // Desserts second
                    if (item.includes('eau') || item.includes('soda') || item.includes('café') || item.includes('thé')) return 3; // Beverages last
                    return 4; // Other items at the end
                }

                // Sort the items by category
                data.sort((a, b) => {
                    const categoryA = getItemCategory(a);
                    const categoryB = getItemCategory(b);
                    return categoryA - categoryB;
                });

                function getItemColor(item) {
                    item = item.toLowerCase();
                    if (item.includes('crêpe')) return customColors.crepes || '#add8e6';
                    if (item.includes('eau') || item.includes('soda') || item.includes('café') || item.includes('thé')) return customColors.drinks || '#ffb6c1';
                    if (item.includes('hot-dog') || item.includes('sandwich')) return customColors.hotdogs_sandwiches || '#90ee90';
                    return '';
                }

                let selected = document.getElementById("selected");
                if (document.getElementById("selectedOrder")) return;
                // On crée le menu
                selected.style.display = "initial";
                let order = document.createElement("div");
                order.id = "selectedOrder";
                order.innerHTML = `
                <span class="close-button" onclick="closeOrder()">×</span>

                <h2>Voici la commande n°${orderID}</h2>
                <div id="item-container"></div>
                <button id="finishOrder" onclick=finishOrder(${orderID})>Rendre commande</button>
                `;
                selected.appendChild(order);

                data.forEach(item => {
                    const div = document.createElement("div");
                    div.style.padding = "10px";
                    div.style.margin = "8px";
                    div.style.borderRadius = "15px"; // Coins plus arrondis
                    div.style.boxShadow = "2px 8px 12px rgba(0, 0, 0, 0.4)"; // Ombre plus prononcée et décalée
                    div.style.backgroundColor = getItemColor(item);
                    div.style.color = "black"; // Couleur de texte noire
                    div.style.textAlign = "center"; // Centrer le texte
                    div.textContent = item;
                    document.getElementById("item-container").appendChild(div);
                });

                window.addEventListener("click", (e) => {
                    const menu = document.getElementById("selectedOrder");
                    const container = document.getElementById("selected");
                    if (menu && container && e.target === container) {
                    closeOrder();
                    container.style.display = "none";
                    }
                });
                })
            .catch(error => console.error('Erreur:', error));
    }

    function closeOrder() {
        let menu = document.getElementById("selectedOrder");
        if (menu) {
            document.getElementById("selected").removeChild(menu);
            document.getElementById("selected").style.display = "none";
        }
    }

    function finishOrder(orderID) {
        document.getElementById("order_container").removeChild(document.getElementById(`order-${orderID}`))
        fetch(`get_finish_orders.php?id=${orderID}`)
        closeOrder()
    }

    function playAudio() {
        if (!soundToggle.checked) return;
        audio.play().catch(function(error) {
            console.log("Erreur de lecture audio:", error);
        });
    }

    function toggleSound() {
        soundToggle.checked = !soundToggle.checked;
        audio.volume = soundToggle.checked ? 0.5 : 0;
        soundIcon.textContent = soundToggle.checked ? "🔊" : "🔇";
    }

    function toggleTheme() {
        document.body.classList.toggle('dark-theme');
        const themeLabel = document.querySelector('.theme-switch label');
        themeLabel.textContent = document.body.classList.contains('dark-theme') ? '☀️' : '🌙';
    }

    window.onload = function() {
        audio.volume = 0.5;
        soundIcon.textContent = "🔊";
        updateOrders(); // Charger au démarrage
    }
</script>
</body>
</html> 