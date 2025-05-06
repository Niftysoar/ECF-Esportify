import Route from "./Route.js";

// Définir ici vos routes
export const allRoutes = [
    new Route("/", "Accueil", "/pages/home.html", []),
    new Route("/about", "À propos", "/pages/about.html", []),
    new Route("/lives", "Lives", "/pages/events.php", []),
    new Route("/contact", "Contact", "/pages/contact.html", []),
    new Route("/signin", "Connexion", "/pages/auth/signin.php", []),
    new Route("/signup", "Inscription", "/pages/auth/signup.php", []),
    new Route("/dashboard", "Mon compte", "/pages/auth/dashboard.php", []),
    new Route("/admin", "Administration", "/pages/auth/admin_dashboard.php", ['admin']),
];

// Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "Esportify";