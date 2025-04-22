# Esportify - Déploiement Local

Ce projet permet de créer un site web pour la gestion d'événements e-sport avec des fonctionnalités de connexion, de gestion de comptes utilisateurs et d'administration. Ce guide vous montre comment déployer ce projet en local.

## Prérequis

Avant de déployer le projet en local, vous devez avoir les éléments suivants installés :

* **XAMPP** ou **MAMP** : Pour gérer Apache et MySQL en local.
* **PHP** : Le langage de script côté serveur.
* **MySQL** : Le système de gestion de base de données.
* **Composer** (si nécessaire) : Pour la gestion des dépendances PHP.
* Un **éditeur de code** : Comme VS Code ou PHPStorm.

## Étapes de Déploiement

1.  **Cloner le repository**

    Si vous n'avez pas encore cloné le projet, utilisez la commande suivante dans votre terminal :

    ```bash
    git clone https://github.com/Niftysoar/ECF-Esportify.git
    
    ```

2.  **Placer les fichiers dans le dossier `htdocs`**

    Dans votre dossier d'installation XAMPP (par défaut `C:\xampp\htdocs` sous Windows ou `/Applications/XAMPP/htdocs/` sous macOS), créez un nouveau dossier pour votre projet (par exemple, `esport_website`). Ensuite, copiez tous les fichiers du projet cloné à l'intérieur de ce nouveau dossier.

3.  **Configurer la base de données**

    * Ouvrez phpMyAdmin en accédant à `http://localhost/phpmyadmin/` dans votre navigateur web.
    * Créez une nouvelle base de données. Choisissez le nom que vous souhaitez utiliser (par exemple, `esport_db`).
    * Si vous disposez d'un fichier `.sql` pour initialiser la base de données, sélectionnez la base de données que vous venez de créer dans phpMyAdmin et utilisez l'onglet "Importer" pour charger le fichier.

4.  **Configurer le fichier `config.php`**

    Localisez le fichier `config.php` dans les fichiers de votre projet. Ouvrez ce fichier avec votre éditeur de code et renseignez les informations de connexion à votre base de données MySQL. Assurez-vous que le nom d'hôte, le nom d'utilisateur, le mot de passe et le nom de la base de données correspondent à votre configuration locale.

    * **Note pour MAMP users :** Le nom d'utilisateur par défaut est souvent `root` et le mot de passe est également `root`.

5.  **Démarrer les services**

    * Ouvrez l'interface de contrôle de XAMPP ou MAMP.
    * Démarrez les services **Apache** et **MySQL**.
