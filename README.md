# 🎮 Esportify — Déploiement Local avec Docker

**Esportify** est une plateforme web pour la gestion d’événements e-sport, incluant la participation, la création d’événements, l'administration et le suivi des scores.

---

## ⚙️ Prérequis

Assurez-vous d'avoir installé :

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Git](https://git-scm.com/)
- Un éditeur de code (recommandé : VS Code)

---

## 🚀 Installation & Lancement

### 1. Cloner le dépôt

```bash
git clone https://github.com/Niftysoar/ECF-Esportify.git
cd ECF-Esportify
```

---

### 2. Structure attendue

À la racine du projet, vous devez avoir :

- `Dockerfile`
- `docker-compose.yml`
- `config.php` (voir ci-dessous)
- Un dossier `docker/sql/` contenant `esportify_db.sql` pour la base

---

### 3. Exemple de `config.php`

```php
<?php
$host = 'mysql';
$dbname = 'esportify_db';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
```

---

### 4. Lancer le projet

```bash
docker-compose up --build
```

Cela crée :

- Un conteneur **Apache + PHP**
- Un conteneur **MySQL** avec les tables de la BDD
- Un conteneur **phpMyAdmin** (optionnel)

---

## 🌐 Accès aux services

| Service        | URL                          |
|----------------|-------------------------------|
| Site Web       | http://localhost:8000         |
| phpMyAdmin     | http://localhost:8080         |
| Utilisateur    | `root`                        |
| Mot de passe   | `root`                        |
| Host MySQL     | `mysql` (nom du service Docker) |

---

## 🧪 Tester l'application

1. S'inscrire via `/signup`
2. Se connecter via `/signin`
3. Créer ou rejoindre un événement via `/dashboard`
4. En tant qu’admin, valider les événements via `/admin`

---

## 🛠️ Commandes utiles

| Action                    | Commande                                 |
|---------------------------|------------------------------------------|
| Lancer les conteneurs     | `docker-compose up --build`              |
| Stopper les conteneurs    | `docker-compose down`                    |
| Rebuild + forcer recréation | `docker-compose up --build --force-recreate` |

---

## 🧾 Notes

- Pour importer la BDD manuellement, utilisez phpMyAdmin et chargez `docker/sql/esportify_db.sql`.
- Les images et scripts sont servis via Apache dans `/public` ou selon ton arborescence.

---

📬 Pour toute question ou bug, contactez l’auteur du dépôt ou ouvrez une *issue* sur GitHub.
