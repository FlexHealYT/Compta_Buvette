window.addEventListener("DOMContentLoaded", () => {
  if (localStorage.getItem("connected") === "True") {
    document.title = "Page d'administration";
    document.getElementById("selected").style.display = "none"
    document.getElementById("login-container").style.display = "none";
    document.getElementById("menu-container").style.display = "initial";
    loadMenu();

    const items = document.getElementsByClassName("item");
    for (let i = 0; i < items.length; i++) {
      const buttons = items[i].lastElementChild;
      const element = items[i].firstElementChild.innerHTML;

      const minusBtn = buttons.firstElementChild;
      const plusBtn = buttons.lastElementChild;

      if (element === "Sandwich") { // Menu de selection
        plusBtn.addEventListener("click", () => {
          Sandwich(true); // Menu de selection
        });

        minusBtn.addEventListener("click", () => {
          Sandwich(false); // Menu de selection
        });
      }

      if (element === "Soda") { // Menu de selection
        plusBtn.addEventListener("click", () => {
          Soda(true); // Menu de selection
        });

        minusBtn.addEventListener("click", () => {
          Soda(false); // Menu de selection
        });
      }

      if (element === "Café ou Thé") { // Menu de selection
        plusBtn.addEventListener("click", () => {
          CT(true); // Menu de selection
        });

        minusBtn.addEventListener("click", () => {
          CT(false); // Menu de selection
        });
      }

      if (element === "Crêpes Nutella ou Confiture") { // Menu de selection
        plusBtn.addEventListener("click", () => {
          Crepe(true); // Menu de selection
        });

        minusBtn.addEventListener("click", () => {
          Crepe(false); // Menu de selection
        });
      }

    }
  }
});


function addSandwich(type) { // Menu de selection
  if (counts["Sandwich"].count >= 0) { // Menu de selection
    counts["Sandwich"].count++; // Menu de selection
    document.getElementById("selected").style.display = "none";
    let menu = document.getElementById("selectedMenu");
    if (menu) {
      menu.remove();
    }
    document.getElementById("sandwichCount").innerHTML = // Menu de selection
      counts["Sandwich"].count; // Menu de selection
    updateTotal();
    if (sandwichData[type] !== undefined) {
      sandwichData[type]++;
    } else {
      sandwichData[type] = 0;
    }
  }
}


function addSoda(type) { // Menu de selection
  if (counts["Soda"].count >= 0) { // Menu de selection
    counts["Soda"].count++; // Menu de selection
    document.getElementById("selected").style.display = "none";
    let menu = document.getElementById("selectedMenu");
    if (menu) {
      menu.remove();
    }
    document.getElementById("sodaCount").innerHTML = // Menu de selection
      counts["Soda"].count; // Menu de selection
    updateTotal();
    if (sodaData[type] !== undefined) { // Menu de selection
      sodaData[type]++; // Menu de selection
    } else {
      sodaData[type] = 0; // Menu de selection
    }
  }
}

function addCT(type) { // Menu de selection
  if (counts["Café ou Thé"].count >= 0) { // Menu de selection
    counts["Café ou Thé"].count++; // Menu de selection
    document.getElementById("selected").style.display = "none";
    let menu = document.getElementById("selectedMenu");
    if (menu) {
      menu.remove();
    }
    document.getElementById("ctCount").innerHTML = // Menu de selection
      counts["Café ou Thé"].count; // Menu de selection
    updateTotal();
    if (ctData[type] !== undefined) { // Menu de selection
      ctData[type]++; // Menu de selection
    } else {
      ctData[type] = 0; // Menu de selection
    }
  }
}


function addCrepe(type) { // Menu de selection
  if (counts["Crêpes Nutella ou Confiture"].count >= 0) { // Menu de selection
    counts["Crêpes Nutella ou Confiture"].count++; // Menu de selection
    document.getElementById("selected").style.display = "none";
    let menu = document.getElementById("selectedMenu");
    if (menu) {
      menu.remove();
    }
    document.getElementById("crepeCount").innerHTML = // Menu de selection
      counts["Crêpes Nutella ou Confiture"].count; // Menu de selection
    updateTotal();
    if (crepeData[type] !== undefined) { // Menu de selection
      crepeData[type]++; // Menu de selection
    } else {
      crepeData[type] = 0; // Menu de selection
    }
  }
}

function Sandwich(add) { // Menu de selection
  let menu_container = document.getElementById("selected");
  if (add) {
    if (document.getElementById("selectedMenu")) return;
    // On crée le menu
    menu_container.style.display = "initial";
    let menu = document.createElement("div");
    menu.id = "selectedMenu";
    menu.innerHTML = `
    <span class="close-button" onclick="Sandwich(false)">×</span>

    <h2>Quel sandwich voulez-vous ?</h2>
    <div class="sandwich">
      <div class="sandwich-item">
        <span>Jambon</span>
        <button onclick="addSandwich('Jambon')">+</button>
      </div>
      <div class="sandwich-item">
        <span>Fromage</span>
        <button onclick="addSandwich('Fromage')">+</button>
      </div>
  `; // Menu de selection
    menu_container.appendChild(menu);
  } else if (!add && counts["Sandwich"].count >= 0) { // Menu de selection
    let menu = document.getElementById("selectedMenu");
    if (menu) {
      menu_container.removeChild(menu);
      menu_container.style.display = "none";
    }
  }

  window.addEventListener("click", (e) => {
    const menu = document.getElementById("selectedMenu");
    const container = document.getElementById("selected");
    if (menu && container && e.target === container) {
      Sandwich(false); // Menu de selection
      container.style.display = "none";
    }
  });
}

function CT(add) { // Menu de selection
  let menu_container = document.getElementById("selected");
  if (add) {
    if (document.getElementById("selectedMenu")) return;
    // On crée le menu
    menu_container.style.display = "initial";
    let menu = document.createElement("div");
    menu.id = "selectedMenu";
    menu.innerHTML = `
    <span class="close-button" onclick="CT(false)">×</span>

    <h2>Café ou Thé ?</h2>
    <div class="ct">
      <div class="ct-item">
        <span>Café</span>
        <button onclick="addCT('Café')">+</button>
      </div>
      <div class="ct-item">
        <span>Thé</span>
        <button onclick="addCT('Thé')">+</button>
      </div>
  `; // Menu de selection
    menu_container.appendChild(menu);
  } else if (!add && counts["Café ou Thé"].count >= 0) { // Menu de selection
    let menu = document.getElementById("selectedMenu");
    if (menu) {
      menu_container.removeChild(menu);
      menu_container.style.display = "none";
    }
  }

  window.addEventListener("click", (e) => {
    const menu = document.getElementById("selectedMenu");
    const container = document.getElementById("selected");
    if (menu && container && e.target === container) {
      CT(false); // Menu de selection
      container.style.display = "none";
    }
  });
}

function Crepe(add) { // Menu de selection
  let menu_container = document.getElementById("selected");
  if (add) {
    if (document.getElementById("selectedMenu")) return;
    // On crée le menu
    menu_container.style.display = "initial";
    let menu = document.createElement("div");
    menu.id = "selectedMenu";
    menu.innerHTML = `
    <span class="close-button" onclick="Crepe(false)">×</span>

    <h2>Crêpes Nutella ou Confiture ?</h2>
    <div class="crepe">
      <div class="crepe-item">
        <span>Nutella</span>
        <button onclick="addCrepe('Nutella')">+</button>
      </div>
      <div class="crepe-item">
        <span>Confiture</span>
        <button onclick="addCrepe('Confiture')">+</button>
      </div>
  `; // Menu de selection
    menu_container.appendChild(menu);
  } else if (!add && counts["Crêpes Nutella ou Confiture"].count >= 0) { // Menu de selection
    let menu = document.getElementById("selectedMenu");
    if (menu) {
      menu_container.removeChild(menu);
      menu_container.style.display = "none";
    }
  }

  window.addEventListener("click", (e) => {
    const menu = document.getElementById("selectedMenu");
    const container = document.getElementById("selected");
    if (menu && container && e.target === container) {
      Crepe(false); // Menu de selection
      container.style.display = "none";
    }
  });
}


function Soda(add) { // Menu de selection
  let menu_container = document.getElementById("selected");
  if (add) {
    if (document.getElementById("selectedMenu")) return;
    // On crée le menu
    menu_container.style.display = "initial";
    let menu = document.createElement("div");
    menu.id = "selectedMenu";
    menu.innerHTML = `
    <span class="close-button" onclick="Soda(false)">×</span>

    <h2>Quel soda voulez-vous ?</h2>
    <div class="soda">
      <div class="soda-item">
        <span>Coca</span>
        <button onclick="addSoda('Coca')">+</button>
      </div>
      <div class="soda-item">
        <span>Oasis</span>
        <button onclick="addSoda('Oasis')">+</button>
      </div>
      <div class="soda-item">
        <span>Ice Tea</span>
        <button onclick="addSoda('Ice_Tea')">+</button>
      </div>
  `; // Menu de selection
    menu_container.appendChild(menu);
  } else if (!add && counts["Soda"].count >= 0) { // Menu de selection
    let menu = document.getElementById("selectedMenu");
    if (menu) {
      menu_container.removeChild(menu);
      menu_container.style.display = "none";
    }
  }

  window.addEventListener("click", (e) => {
    const menu = document.getElementById("selectedMenu");
    const container = document.getElementById("selected");
    if (menu && container && e.target === container) {
      Soda(false); // Menu de selection
      container.style.display = "none";
    }
  });
}

function logout() {
  localStorage.setItem("connected", "False");
  document.title = "Inscription";
  document.getElementById("login-container").style.display = "initial";
  document.getElementById("menu-container").style.display = "none";
}

function togglePassword(id) {
  let input = document.getElementById(id);
  input.type = input.type === "password" ? "text" : "password";
}

function check() {
  const inputPassword = document.getElementById("password").value;

  if (!inputPassword) {
    document.getElementById("incorrect").textContent =
      "Veuillez remplir tous les champs.";
    return;
  }

  // Récupération du mot de passe depuis le fichier PHP
  fetch("variables_reader.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      const validPassword = data.password;

      const isAlreadyConnected = localStorage.getItem("connected") === "True";

      if (inputPassword === validPassword || isAlreadyConnected) {
        localStorage.setItem("connected", "True");
        document.title = "Page d'administration";
        document.getElementById("login-container").style.display = "none";
        document.getElementById("menu-container").style.display = "initial";
        loadMenu(); // on génère dynamiquement le menu
      } else {
        localStorage.setItem("connected", "False");
        document.getElementById("incorrect").textContent =
          "Mot de passe incorrect.";
      }
    })
    .catch((error) => {
      console.error("Erreur lors du chargement du mot de passe :", error);
      document.getElementById("incorrect").textContent = "Erreur serveur.";
    });
}

// Menu dynamique
const menuData = {
  BOISSONS: {
    "Café ou Thé": 1.0,
    Eau: 1.0,
    Soda: 2.0,
  },
  SNACKS: {
    "Hot-Dog": 2.5,
    Sandwich: 2.5,
  },
  DESSERTS: {
    "Crêpes sucre": 1.0,
    "Crêpes Nutella ou Confiture": 1.5,
  },
};


const sandwichData = { Jambon: 0, Fromage: 0 }; // Menu de selection
const sodaData = { Oasis: 0, Coca: 0, "Ice_Tea": 0}; // Menu de selection
const ctData = { Café: 0, Thé: 0}; // Menu de selection
const crepeData = { Nutella: 0, Confiture: 0}; // Menu de selection

const counts = {};

function updateTotal() {
  let total = 0;
  for (const item in counts) {
    total += counts[item].count * counts[item].price;
  }
  document.getElementById("total").textContent = `Total : ${total
    .toFixed(2)
    .replace(".", ",")}€`;
}

function createItem(name, price) {
  counts[name] = { count: 0, price };

  const div = document.createElement("div");
  div.className = "item";

  const label = document.createElement("span");
  label.textContent = name;

  const buttons = document.createElement("div");
  buttons.className = "buttons";

  const minusBtn = document.createElement("button");
  minusBtn.textContent = "-";
  minusBtn.onclick = () => {
    if (counts[name].count > 0) {
      counts[name].count--;
      countDiv.textContent = counts[name].count;
      updateTotal();
    }
  };



 
  const plusBtn = document.createElement("button");
  plusBtn.textContent = "+";
  plusBtn.onclick = () => {
    if (name === "Sandwich"){ // Menu de selection
      Sandwich(true);
    }
    else if (name === "Soda"){ // Menu de selection
      Soda(true);
    }    
    else if (name === "Café ou Thé"){ // Menu de selection
      CT(true);
    }
    else if (name === "Crêpes Nutella ou Confiture"){ // Menu de selection
      Crepe(true);
    }
    else {
      counts[name].count++;
      countDiv.textContent = counts[name].count;
      updateTotal();
    }
  };

  const countDiv = document.createElement("div");
  countDiv.className = "count";
  countDiv.textContent = "0";

 
  if (name === "Sandwich") { // Menu de selection
    countDiv.id = "sandwichCount"; // Menu de selection
  }
  if (name === "Soda") { // Menu de selection
    countDiv.id = "sodaCount"; // Menu de selection
  }
  if (name === "Café ou Thé") { // Menu de selection
    countDiv.id = "ctCount"; // Menu de selection
  }
  if (name === "Crêpes Nutella ou Confiture") { // Menu de selection
    countDiv.id = "crepeCount"; // Menu de selection
  }

  buttons.appendChild(minusBtn);
  buttons.appendChild(countDiv);
  buttons.appendChild(plusBtn);

  div.appendChild(label);
  div.appendChild(buttons);
  return div;
}

function loadMenu() {
  const container = document.getElementById("menu");
  container.innerHTML = ""; // reset si rechargement

  for (const category in menuData) {
    const h2 = document.createElement("h2");
    h2.textContent = category;
    container.appendChild(h2);

    for (const item in menuData[category]) {
      const itemElement = createItem(item, menuData[category][item]);
      container.appendChild(itemElement);
    }
  }

  const total = document.createElement("div");
  total.className = "total";
  total.id = "total";
  total.textContent = "Total : 0,00€";
  container.appendChild(total);

  const cross = document.createElement("img");
  cross.src = "src/cross.png";
  const tick = document.createElement("img");
  tick.src = "src/check.png";

  const cancel = document.createElement("button");
  cancel.className = "cancel";
  cancel.id = "cancel";
  cancel.onclick = () => {
    cancelOrder();
  };
  container.appendChild(cancel);
  cancel.appendChild(cross);

  const confirm = document.createElement("button");
  confirm.className = "confirm";
  confirm.id = "confirm";
  confirm.onclick = () => {
    confirmOrder();
  };
  container.appendChild(confirm);
  confirm.appendChild(tick);
}

function cancelOrder() {
  for (const item in counts) {
    counts[item].count = 0;
    updateTotal();
  }
  
  for (const item in sandwichData) { // Menu de selection
    sandwichData[item] = 0; // Menu de selection
  } 
  for (const item in sodaData) { // Menu de selection
    sodaData[item] = 0; // Menu de selection
  }
  for (const item in ctData) { // Menu de selection
    ctData[item] = 0; // Menu de selection
  }
  for (const item in crepeData) { // Menu de selection
    crepeData[item] = 0; // Menu de selection
  }

  const counters = document.getElementsByClassName("count");
  for (const counter of counters) {
    counter.innerHTML = "0";
  }
}

function confirmOrder() {
  let url = "calc_compta.php?";
  let request = '';
  
 
  // Ajouter les commandes normales
  for (const item in counts) {
    if (counts[item].count === 0) continue;
    if (item === "Sandwich") continue; // Menu de selection
    if (item === "Soda") continue; // Menu de selection
    if (item === "Café ou Thé") continue; // Menu de selection
    if (item === "Crêpes Nutella ou Confiture") continue; // Menu de selection
    const param = item.replace(/ /g, "_");
    request += `${param}=${counts[item].count}&`;
  }
  
 
  // Ajouter les sandwiches
  for (const item in sandwichData) { // Menu de selection
    if (sandwichData[item] === 0) continue; // Menu de selection
    request += `Sandwich_${item}=${sandwichData[item]}&`; // Menu de selection
  }

  for (const item in sodaData) { // Menu de selection
    if (sodaData[item] === 0) continue; // Menu de selection
    request += `Soda_${item}=${sodaData[item]}&`; // Menu de selection
  }

  for (const item in ctData) { // Menu de selection
    if (ctData[item] === 0) continue; // Menu de selection
    request += `${item}=${ctData[item]}&`; // Menu de selection
  }
  for (const item in crepeData) { // Menu de selection
    if (crepeData[item] === 0) continue; // Menu de selection
    request += `Crêpe_${item}=${crepeData[item]}&`; // Menu de selection
  }

  // Envoyer les commandes
  fetch(url + request);
  fetch('add_order.php?' + request.slice(0, -1)) // Enlever le dernier &
    .then(response => {
      if (!response.ok) {
        console.error('Erreur lors de l\'ajout de la commande');
      }
    })
    .catch(error => {
      console.error('Erreur:', error);
    });
    
  cancelOrder();
}
