import Route from "./Route.js";

//Définir ici vos routes
export const allRoutes = [
    new Route("/", "Accueil", "/pages/home.html", []),
    new Route("/about", "A propos", "/pages/about.html", []),
    new Route("/lives", "Lives", "/pages/lives.html", []),
    new Route("/contact", "Contact", "/pages/contact.html", []),
    new Route("/signin", "Connexion", "/pages/auth/signin.html", []),
    new Route("/signup", "Inscription", "/pages/auth/signup.php", [], "/js/auth/signup.js"),
    new Route("/account", "Mon compte", "/pages/auth/account.html", ["client", "admin"]),
    new Route("/editPassword", "Changement de mot de passe", "/pages/auth/editPassword.html", ["client", "admin"]),
];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "Esportify";