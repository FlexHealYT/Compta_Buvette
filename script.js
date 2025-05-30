function lireCsv(url, callback) {
  fetch(url)
    .then((response) => response.json())
    .then((data) => callback(data))
    .catch((error) => console.error("Erreur:", error));
}

const urlCsv = "lire_csv.php";

document.addEventListener("DOMContentLoaded", () => {
  const team_container = document.getElementById("team_container");

  if (team_container) {
    lireCsv(urlCsv, (equipes) => {
      for (const nomEquipe in equipes) {
        const bouton = document.createElement("button");
        bouton.textContent = nomEquipe;
        bouton.addEventListener("click", () => {
          window.location.href = `equipe.php?equipe=${encodeURIComponent(
            nomEquipe
          )}`;
        });
        team_container.appendChild(bouton);
      }
    });
  }

  const themeSwitch = document.querySelector(".checkbox");

  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark-theme") {
    document.body.classList.add("dark-theme");
    if (themeSwitch) {
      themeSwitch.checked = true;
    }
  } else {
    document.body.classList.remove("dark-theme");
    if (themeSwitch) {
      themeSwitch.checked = false;
    }
  }

  if (themeSwitch) {
    themeSwitch.addEventListener("change", function () {
      if (this.checked) {
        document.body.classList.add("dark-theme");
        localStorage.setItem("theme", "dark-theme");
      } else {
        document.body.classList.remove("dark-theme");
        localStorage.setItem("theme", "light-theme");
      }
    });
  }

  const arrows = document.querySelectorAll(".arrow-down");
  arrows.forEach((arrow) => {
    arrow.addEventListener("click", function () {
      const checkbox = this.previousElementSibling;
      checkbox.checked = !checkbox.checked;
    });
  });

  if (
    window.location.pathname.endsWith("/") ||
    window.location.pathname.endsWith("/index.html")
  ) {
    const admin_bouton = document.createElement("button");
    const admin_URL = "admin.html";
    admin_bouton.textContent = "🔒 Connexion à la page administrateur";
    admin_bouton.addEventListener("click", () => {
      window.location.href = admin_URL;
    });
    admin_bouton.id = "admin-link";
    admin_bouton.style.display = "block";
    admin_bouton.style.margin = "auto";
    document.body.appendChild(admin_bouton);  }
});
