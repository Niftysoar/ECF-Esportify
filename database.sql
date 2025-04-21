-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('joueur', 'organisateur', 'admin') NOT NULL DEFAULT 'joueur',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Déchargement des données de la table `users`
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$oGNPCyTwR7mLiKbl.NfB1.s6nilcLQUT5bpVTIRlm5/7fr22jFjj2', 'admin', '2024-11-06 11:57:15'),
(2, 'orga1', 'orga1@example.com', '$2y$10$abcabcabcabcabcabcabcabcabcabcabcabcabcabca', 'organisateur', '2024-11-07 09:25:00'),
(3, 'joueur1', 'joueur1@example.com', '$2y$10$xyzxyzxyzxyzxyzxyzxyzxyzxyzxyzxyzxyzxyzxy', 'joueur', '2024-11-07 10:00:00');

-- Table des événements
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    start_time DATETIME NOT NULL,
    created_by INT NOT NULL,
    status ENUM('en_attente', 'valide', 'non_valide') DEFAULT 'en_attente',
    is_suspended BOOLEAN DEFAULT FALSE,
    can_start_from DATETIME,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Déchargement des données de la table `events`
INSERT INTO `events` (`id`, `title`, `description`, `start_time`, `created_by`, `status`, `is_suspended`, `can_start_from`) VALUES
(1, 'Tournoi Rocket League', 'Tournoi 2v2 sur Rocket League', '2025-04-20 20:00:00', 2, 'valide', FALSE, '2025-04-20 19:30:00'),
(2, 'Match FIFA', 'Petit match détente sur FIFA 23', '2025-04-25 18:00:00', 2, 'en_attente', FALSE, '2025-04-25 17:30:00');

-- Table des favoris (événements enregistrés par un joueur)
CREATE TABLE favorites (
    user_id INT,
    event_id INT,
    PRIMARY KEY (user_id, event_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Table des participations (avec statut pour savoir si accepté ou refusé)
CREATE TABLE participations (
    user_id INT,
    event_id INT,
    status ENUM('accepte', 'refuse', 'en_attente') DEFAULT 'en_attente',
    joined BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (user_id, event_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Table des scores
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    score INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);