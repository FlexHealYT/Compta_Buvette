# Système de Gestion de Commandes

Ce projet est un système de gestion de commandes avec une interface d'administration, développé en PHP, HTML, CSS et JavaScript.

## Fonctionnalités

- Interface d'administration pour la gestion des commandes
- Système de suivi des commandes en temps réel
- Gestion des équipes via fichier CSV
- Interface utilisateur responsive
- Système de comptabilité intégré

## Prérequis

- Serveur web (XAMPP recommandé)
- PHP 7.0 ou supérieur
- Navigateur web moderne

## Installation

1. Clonez ce dépôt dans votre dossier htdocs de XAMPP :
   ```
   /xampp/htdocs/concours/
   ```

2. Assurez-vous que votre serveur Apache est en cours d'exécution

3. Accédez à l'application via votre navigateur :
   ```
   http://localhost/concours/
   ```

## Structure du Projet

- `admin.html` : Interface d'administration
- `order.php` : Gestion des commandes
- `equipe.php` : Gestion des équipes
- `compta/` : Dossier contenant les fichiers liés à la comptabilité
- `src/` : Dossier contenant les ressources du projet
- `error/` : Dossier de gestion des erreurs

## Configuration

Le fichier `variables.php` contient les paramètres de configuration du système. Assurez-vous de le configurer selon vos besoins.

## Utilisation

1. Accédez à l'interface d'administration via `admin.html`
2. Gérez les commandes via l'interface dédiée
3. Consultez les statistiques et la comptabilité dans les sections correspondantes

## Support

Pour toute question ou problème, veuillez contacter l'administrateur du système. 