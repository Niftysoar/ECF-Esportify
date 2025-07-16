<?php
// events.php

session_start();
require_once('../config.php');

// Initialisation des filtres
$filter_player_count = isset($_GET['player_count']) ? $_GET['player_count'] : '';
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';
$filter_username = isset($_GET['username']) ? $_GET['username'] : '';

// Affichage d'une erreur s’il y en a en session
$error = null;
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

try {
    // Construction de la requête avec filtres
    $sql = "SELECT events.*, users.username
            FROM events
            JOIN users ON events.created_by = users.id
            WHERE events.status = 'valide'";

    if ($filter_player_count) {
        $sql .= " AND events.player_count >= :player_count";
    }
    if ($filter_date) {
        $sql .= " AND DATE(events.start_time) = :date";
    }
    if ($filter_username) {
        $sql .= " AND users.username LIKE :username";
    }

    // On limite à 3 événements récents
    $sql .= " ORDER BY start_time DESC LIMIT 3";

    $stmt = $pdo->prepare($sql);

    // Binding des paramètres pour la requête préparée
    if ($filter_player_count) {
        $stmt->bindParam(':player_count', $filter_player_count, PDO::PARAM_INT);
    }
    if ($filter_date) {
        $stmt->bindParam(':date', $filter_date, PDO::PARAM_STR);
    }
    if ($filter_username) {
        $stmt->bindParam(':username', $filter_username, PDO::PARAM_STR);
    }

    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si la requête est une requête AJAX, retourner les données en JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($events);
        exit; // Arrêter l'exécution ici pour les requêtes AJAX
    }
} catch (PDOException $e) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    } else {
        die("Erreur lors de la récupération des événements : " . $e->getMessage());
    }
}
?>
  <!-- Section principale -->
    <section class="hero">
        <div class="hero-content">
            <h1>
                LE <span class="highlight">GAME CHANGER</span> <br> DE L’E-SPORT !
            </h1>
            <p>
                Fondée le 17 mars 2021, est spécialisée dans l'organisation d'événements e-sportifs. 
                En rassemblant joueurs et passionnés de jeux vidéo.
            </p>
            <div class="hero-stat">
              <span class="stat-number">+200,000</span>
              <span class="stat-text">événements en 7 jours</span>
            </div>
        </div>
        <div class="hero-image">
            <img src="assets/images/gamebg.jpg" alt="Manette gaming">
        </div>
    </section>

    <!-- Logos partenaires -->
    <section class="partners">
        <img src="assets/images/brand_logo01.png" alt="Partenaire 1">
        <img src="assets/images/brand_logo02.png" alt="Partenaire 2">
        <img src="assets/images/brand_logo03.png" alt="Partenaire 3">
        <img src="assets/images/brand_logo04.png" alt="Partenaire 4">
        <img src="assets/images/brand_logo05.png" alt="Partenaire 5">
    </section>

    <div class="Trait"></div>

    <!-- Section à propos -->
    <section class="about">
        <div class="about-image">
            <img src="assets/images/controller.jpg" alt="Manette gaming">
        </div>
        <div class="about-content">
            <h2><span class="highlight">A PROPOS</span> DE NOUS</h2>
            <p>Fondée le 17 mars 2021, Esportify révolutionne l'industrie de l'esport. Spécialiste dans l'organisation de compétitions de jeux vidéo dynamiques, nous créons des événements mémorables qui rassemblent des joueurs de tous horizons.</p>
            <p>Très vite, nous nous sommes imposés comme un acteur majeur de la scène “esportive”, nous bâtissant ainsi une réputation d’excellence et d’innovation.</p>
            <p>En créant un environnement concurrentiel florissant, la société continue de fédérer les passionnés de jeux vidéo et de propulser le monde de l'esport vers de nouveaux sommets.</p>
            <a href="/about" class="btn btn-highlight">EN SAVOIR PLUS</a>
        </div>
    </section>

    <section id="guides">
        <div class="container">
          <h2>LISEZ NOS GUIDES POUR UN MEILLEUR JEU</h2>
      
          <!-- Swiper Container -->
          <div class="card-wrapper swiper">
            <ul class="card-list swiper-wrapper">
      
              <!-- Slide 1 -->
              <li class="card-item swiper-slide">
                <a href="#" class="card-link">
                  <img src="assets/images/setup.jpg" alt="Card image" class="card-image">
                  <p class="badge">Setup</p>
                  <h2 class="card-title">Lorem ipsum dolor sit amet consectetur adipisicing elit.</h2>
                </a>
              </li>
      
              <!-- Slide 2 -->
              <li class="card-item swiper-slide">
                <a href="#" class="card-link">
                  <img src="assets/images/live_match.jpg" alt="Card image" class="card-image">
                  <p class="badge">Developer</p>
                  <h2 class="card-title">Lorem ipsum dolor sit amet consectetur adipisicing elit.</h2>
                </a>
              </li>
      
              <!-- Slide 3 -->
              <li class="card-item swiper-slide">
                <a href="#" class="card-link">
                  <img src="assets/images/marketer.jpg" alt="Card image" class="card-image">
                  <p class="badge">Marketer</p>
                  <h2 class="card-title">Lorem ipsum dolor sit amet consectetur adipisicing elit.</h2>
                </a>
              </li>
      
              <!-- Slide 4 -->
              <li class="card-item swiper-slide">
                <a href="#" class="card-link">
                  <img src="assets/images/gamer.jpg" alt="Card image" class="card-image">
                  <p class="badge">Gamer</p>
                  <h2 class="card-title">Lorem ipsum dolor sit amet consectetur adipisicing elit.</h2>
                </a>
              </li>
      
              <!-- Slide 5 -->
              <li class="card-item swiper-slide">
                <a href="#" class="card-link">
                  <img src="assets/images/editor.jpg" alt="Card image" class="card-image">
                  <p class="badge">Editor</p>
                  <h2 class="card-title">Lorem ipsum dolor sit amet consectetur adipisicing elit.</h2>
                </a>
              </li>
      
            </ul>
      
            <!-- Navigation + Pagination -->
            <div class="swiper-pagination"></div>
            <div class="swiper-slide-button swiper-button-prev"></div>
            <div class="swiper-slide-button swiper-button-next"></div>
          </div>
        </div>
      </section>

      <h2 class="section-title">📅 Derniers événements</h2>

      <div class="event-list" id="event-list">
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                    <div class="event-meta">
                        <p><strong>Organisé par :</strong> <?php echo htmlspecialchars($event['username']); ?></p>
                        <p><strong><i class="fa-solid fa-user-group"></i></strong> <?php echo htmlspecialchars($event['player_count']); ?></p>
                        <p><strong><i class="fa-regular fa-calendar"></i></strong> Le <?php echo date('d/m/Y', strtotime($event['start_time'])); ?> de <?php echo date('H:i', strtotime($event['start_time'])); ?> à <?php echo date('H:i', strtotime($event['end_date'])); ?></p>
                    </div>
                    <div class="event-actions">
                        <a href="/pages/event/join_event.php?event_id=<?= $event['id'] ?>" class="btn btn-highlight">Rejoindre</a>
                        <a href="chat_event.php?event_id=<?= $event['id'] ?>" class="button">Chat</a>
                        <a href="/pages/auth/dashboard.php?add_favorite=<?= $event['id']; ?>" class="button">Favoris</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-event">Aucun événement à afficher pour le moment.</p>
        <?php endif; ?>
    </div>

    <div class="banner">
        <div class="banner-content">
            <h2>REJOIGNEZ-NOUS ET DECOUVREZ LE MONDE DE L’ESPORTS !</h2>
            <a href="/signup" class="btn btn-highlight">REJOINDRE !</a>          
        </div>
    </div>