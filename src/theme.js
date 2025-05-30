// Fonction pour basculer le thème
function toggleTheme() {
    document.body.classList.toggle('dark-theme');
    const themeLabel = document.querySelector('.theme-switch label');
    themeLabel.textContent = document.body.classList.contains('dark-theme') ? '☀️' : '🌙';
    
    // Sauvegarder la préférence dans le localStorage
    localStorage.setItem('darkTheme', document.body.classList.contains('dark-theme'));
}

// Fonction pour initialiser le thème
function initTheme() {
    // Vérifier si une préférence est sauvegardée
    const darkTheme = localStorage.getItem('darkTheme') === 'true';
    
    // Appliquer le thème sauvegardé
    if (darkTheme) {
        document.body.classList.add('dark-theme');
        const themeLabel = document.querySelector('.theme-switch label');
        if (themeLabel) {
            themeLabel.textContent = '☀️';
        }
    }
}

// Initialiser le thème au chargement de la page
document.addEventListener('DOMContentLoaded', initTheme); 