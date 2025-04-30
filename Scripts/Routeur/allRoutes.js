import Route from "./Route.js";

//Définir ici vos routes
export const allRoutes = [
    new Route("/", "Accueil", "/pages/home.html", []),
    new Route("/about", "A propos", "/pages/about.html", []),
    new Route("/lives", "Lives", "/pages/events.php", []),
    new Route("/contact", "Contact", "/pages/contact.html", []),
    new Route("/signin", "Connexion", "/pages/signin.php", []),
    new Route("/signup", "Inscription", "/pages/signup.php", [], "/js/auth/signup.js"),
    new Route("/dashboard", "Mon compte", "/pages/dashboard.php", []),
];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "Esportify";