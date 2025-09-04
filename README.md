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
- `esports_db.sql` pour la base de données

---

### 3. Lancer le projet

```bash
docker-compose up --build
```

Cela crée :

- Un conteneur **Apache + PHP**.
- Un conteneur **MySQL** avec les tables de la BDD.
- Un conteneur **MongoDb** et **MongoDb Express** pour le NoSQL.
- Prépare un conteneur PostgreSQL (postgres) pour des extensions futures.

---

## 🌐 Accès aux services

| Service        | URL                          |
|----------------|-------------------------------|
| Site Web       | http://localhost:8000         |
| Sevice MongoDb       | http://localhost:8081         |
| Serveur PHP    | Port 8000 (redirigé vers Apache sur le conteneur)                        |
| Base de données   | MariaDB (MySQL compatible) sur localhost:3307                        |

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
