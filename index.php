<?php
session_start(); // ← INDISPENSABLE : démarre la session

// Inclure le fichier de config
include('pages/config.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_forward" />
    <title>Document</title>
    <!-- Import de Swiper JS -->
    <link rel="stylesheet" href="CSS/swiper-bundle.min.css">
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body class="background">
    <!-- Barre de navigation -->
    <header>
        <nav class="navbar">
            <a href="/"><img src="images/logo.png" alt="Esportify Logo" class="logo"></a>
            
            <ul class="nav-links">
                <li><a href="/lives">Lives</a></li>
                <li><a href="/about">À propos</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>

            <div class="nav-actions">
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="search-input" type="search" placeholder="Rechercher...">
                </div>
            
                <?php
                    if (isset($_SESSION['user_id'])) {
                        $username = htmlspecialchars($_SESSION['username']);
                        echo "
                            <a href='/dashboard' class='btn login'>$username</a>
                            <a href='/pages/logout.php' class='btn btn-highlight'>DECONNEXION</a>
                        ";
                    } else {
                        echo "
                            <a href='/signin' class='btn login'>SE CONNECTER</a>
                            <a href='/signup' class='btn btn-highlight'>S'INSCRIRE</a>
                        ";
                    }
                ?>
            </div>

            <div class="burger" id="burger">
                <i class="fa-solid fa-bars"></i>
            </div>

            <!-- Pour le responsive du header -->
            <div class="dropdown-menu">
                <div class="dropdown-links">
                    <li><a href="/lives">Lives</a></li>
                    <li><a href="/about">À propos</a></li>
                    <li><a href="/contact">Contact</a></li>
                </div>

                <div class="dropdown-search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="search-input" type="search" placeholder="Rechercher...">
                </div>

                <div class="dropdown-actions">
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        $username = htmlspecialchars($_SESSION['username']);
                        echo "
                            <a href='/dashboard' class='btn login'>$username</a>
                            <a href='/pages/logout.php' class='btn btn-highlight'>DECONNEXION</a>
                        ";
                    } else {
                        echo "
                            <a href='/signin' class='btn login'>SE CONNECTER</a>
                            <a href='/signup' class='btn btn-highlight'>S'INSCRIRE</a>
                        ";
                    }
                    ?>
                </div>
            </div>
        </nav>
    </header>
    
    <main id="main-page">

    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <a href="/"><img src="images/logo.png" alt="Esportify Logo" class="logo"></a>
                    <p class="slogan">Esportez vous bien !</p>
                </div>
                <div class="footer-section">
                    <h4>Analytiques</h4>
                    <ul>
                        <li><a href="#">Guides</a></li>
                        <li><a href="#">F.A.Q</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Plus</h4>
                    <ul>
                        <li><a href="#">Conditions d'utilisation</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contactez-nous</h4>
                    <p><a href="mailto:support@mail.com">Support@mail.com</a></p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-discord"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <p class="copyright">© 2025 Tous droits réservés.</p>
        </div>
    </footer>  

    <!-- Swiper.js for carousels -->
    <script src="Scripts/Swiper/swiper-bundle.min.js"></script>
    <script src="Scripts/Swiper/script.js"></script>

    <!-- Custom Router (module JS) -->
    <script type="module" src="Scripts/Routeur/Routeur.js"></script>

    <!-- Dropdown Menu -->
    <script src="Scripts/DropMenu/script.js"></script>

    <!-- Global site behavior -->
    <script src="Scripts/script.js"></script>

</body>
</html>