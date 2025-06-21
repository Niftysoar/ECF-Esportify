-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 16 mai 2025 à 13:38
-- Version PostgreSQL : 16
-- Version de PHP : 8.2.12

-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- START TRANSACTION;
-- SET time_zone = "+00:00";


-- /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
-- /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
-- /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
-- /*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `esports_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE events (
  id SERIAL PRIMARY KEY,
  title varchar(100) NOT NULL,
  description text DEFAULT NULL,
  start_time TIMESTAMP NOT NULL,
  created_by INTEGER NOT NULL,
  status TEXT CHECK (status IN ('en_attente','valide','non_valide')) DEFAULT 'en_attente',
  is_suspended BOOLEAN DEFAULT FALSE,
  can_start_from TIMESTAMP DEFAULT NULL,
  player_count INTEGER DEFAULT 0,
  end_date TIMESTAMP DEFAULT NULL
);

--
-- Déchargement des données de la table `events`
--

INSERT INTO events (id, title, description, start_time, created_by, status, is_suspended, can_start_from, player_count, end_date) VALUES
(1, 'Tournoi Rocket League', 'Tournoi 2v2 sur Rocket League', '2025-04-20 20:00:00', 2, 'valide', FALSE, '2025-04-20 19:30:00', 4, '2025-04-20 22:00:00'),
(2, 'Match FIFA', 'Petit match détente sur FIFA 23', '2025-04-25 18:00:00', 2, 'valide', FALSE, '2025-04-25 17:30:00', 2, '2025-04-25 19:00:00'),
(3, 'Battle Royale Fortnite', 'Tournoi solo sur Fortnite.', '2025-05-01 21:00:00', 2, 'valide', FALSE, '2025-05-01 20:30:00', 6, '2025-05-01 23:00:00'),
(10, 'Tournoi Fortnite Solo', 'Tournoi solo ouvert à tous les joueurs européens.', '2025-06-01 18:00:00', 2, 'en_attente', FALSE, '2025-06-01 17:30:00', 100, '2025-06-01 20:00:00'),
(11, 'Ligue Valorant - Phase 1', 'Compétition en 5v5, première phase éliminatoire.', '2025-06-03 16:00:00', 2, 'en_attente', FALSE, '2025-06-03 15:30:00', 10, '2025-06-03 19:00:00'),
(12, 'Match d''exhibition FIFA 25', 'Amical entre streamers FIFA.', '2025-06-05 21:00:00', 2, 'en_attente', FALSE, '2025-06-05 20:30:00', 2, '2025-06-05 22:00:00'),
(13, 'Tournoi Valorant', 'Tournoi compétitif en 5v5 sur Valorant, tous niveaux acceptés.', '2025-05-12 19:00:00', 2, 'en_attente', FALSE, '2025-05-12 18:30:00', 10, '2025-05-12 21:00:00'),
(14, 'LAN Mario Kart', 'Session fun sur Mario Kart 8 Deluxe. Venez avec votre bonne humeur !', '2025-05-15 17:30:00', 2, 'en_attente', FALSE, '2025-05-15 17:00:00', 8, '2025-05-15 19:00:00'),
(15, 'Tournoi Super Smash Bros. Ultimate', 'Affrontez d’autres joueurs dans un tournoi à élimination directe sur Switch !', '2025-05-10 18:00:00', 4, 'valide', FALSE, '2025-05-10 17:30:00', 16, '2025-05-10 20:30:00'),
(16, 'Session coop Zelda: Tears of the Kingdom', 'Explorez Hyrule à plusieurs dans une session de jeu libre et partages d’astuces.', '2025-05-11 14:00:00', 2, 'valide', FALSE, '2025-05-11 13:30:00', 4, '2025-05-11 16:00:00'),
(17, 'Course Time Trial - Mario Kart 8 Deluxe', 'Défiez les meilleurs chronos sur les circuits rétro et modernes.', '2025-05-13 20:00:00', 4, 'valide', FALSE, '2025-05-13 19:30:00', 12, '2025-05-13 21:30:00');

-- --------------------------------------------------------

--
-- Structure de la table `favorites`
--

CREATE TABLE favorites (
  user_id INTEGER NOT NULL,
  event_id INTEGER NOT NULL
);

--
-- Déchargement des données de la table `favorites`
--

INSERT INTO favorites (user_id, event_id) VALUES
(4, 1),
(4, 15),
(4, 17);

-- --------------------------------------------------------

--
-- Structure de la table `participations`
--

CREATE TABLE participations (
  user_id INTEGER NOT NULL,
  event_id INTEGER NOT NULL,
  status TEXT CHECK (status IN ('accepte','refuse','en_attente')) DEFAULT 'en_attente',
  joined BOOLEAN DEFAULT FALSE
);

--
-- Déchargement des données de la table `participations`
--

INSERT INTO participations (user_id, event_id, status, joined) VALUES
(4, 1, 'en_attente', FALSE),
(4, 15, 'en_attente', FALSE),
(4, 17, 'en_attente', FALSE);

-- --------------------------------------------------------

--
-- Structure de la table `scores`
--

CREATE TABLE scores (
  id SERIAL PRIMARY KEY,
  user_id INTEGER DEFAULT NULL,
  event_id INTEGER DEFAULT NULL,
  score INTEGER DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--
-- Déchargement des données de la table `scores`
--

INSERT INTO scores (id, user_id, event_id, score, created_at) VALUES
(1, 4, 1, 87, '2025-05-15 18:03:11');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  username varchar(50) NOT NULL UNIQUE,
  email varchar(100) NOT NULL UNIQUE,
  password varchar(255) NOT NULL,
  role TEXT CHECK (role IN ('joueur','organisateur','admin')) NOT NULL DEFAULT 'joueur',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--
-- Déchargement des données de la table `users`
--

INSERT INTO users (id, username, email, password, role, created_at) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$oGNPCyTwR7mLiKbl.NfB1.s6nilcLQUT5bpVTIRlm5/7fr22jFjj2', 'admin', '2024-11-06 11:57:15'),
(2, 'orga1', 'orga1@example.com', '$2y$10$abcabcabcabcabcabcabcabcabcabcabcabcabcabca', 'organisateur', '2024-11-07 09:25:00'),
(3, 'joueur1', 'joueur1@example.com', '$2y$10$xyzxyzxyzxyzxyzxyzxyzxyzxyzxyzxyzxyzxyzxy', 'joueur', '2024-11-07 10:00:00'),
(4, 'Niftysprites', 'berthomath@hotmail.com', '$2y$10$kS/i3kDrbRcnybNWVIvece2oQIWLTVlUCyWLumXjLCck1Ffly7fqm', 'admin', '2025-05-05 11:54:41');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `events`
--
CREATE INDEX idx_events_created_by ON events (created_by);

--
-- Index pour la table `favorites`
--
CREATE INDEX idx_favorites_event_id ON favorites (event_id);

--
-- Index pour la table `participations`
--
CREATE INDEX idx_participations_event_id ON participations (event_id);

--
-- Index pour la table `scores`
--
CREATE INDEX idx_scores_user_id ON scores (user_id);
CREATE INDEX idx_scores_event_id ON scores (event_id);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `events`
--
ALTER TABLE events
  ADD FOREIGN KEY (created_by) REFERENCES users(id);

--
-- Contraintes pour la table `favorites`
--
ALTER TABLE favorites
  ADD FOREIGN KEY (user_id) REFERENCES users(id),
  ADD FOREIGN KEY (event_id) REFERENCES events(id);

--
-- Contraintes pour la table `participations`
--
ALTER TABLE participations
  ADD FOREIGN KEY (user_id) REFERENCES users(id),
  ADD FOREIGN KEY (event_id) REFERENCES events(id);

--
-- Contraintes pour la table `scores`
--
ALTER TABLE scores
  ADD FOREIGN KEY (user_id) REFERENCES users(id),
  ADD FOREIGN KEY (event_id) REFERENCES events(id);
