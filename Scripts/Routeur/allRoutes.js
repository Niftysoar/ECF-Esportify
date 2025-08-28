import Route from "./Route.js";

// Définir ici vos routes
export const allRoutes = [
    new Route("/", "Accueil", "/pages/home.php", []),
    new Route("/about", "À propos", "/pages/about.html", []),
    new Route("/lives", "Lives", "/pages/event/events.php", []),
    new Route("/createevent", "Créer un événement", "/pages/auth/organisateur/create_event.php", []),
    new Route("/editevent", "Modifier un événement", "/pages/auth/organisateur/edit_event.php", []),
    new Route("/contact", "Contact", "/pages/contact.php", []),
    new Route("/messages", "Messages reçus", "/pages/auth/admin/message.php", []),
    new Route("/signin", "Connexion", "/pages/auth/signin.php", []),
    new Route("/signup", "Inscription", "/pages/auth/signup.php", []),
    new Route("/dashboard", "Mon compte", "/pages/auth/dashboard.php", []),
    new Route("/orga", "Organisation", "/pages/auth/organisateur/my_events.php", []),
    new Route("/userregister", "Utilisateurs inscrits", "/pages/auth/organisateur/manage_registrations.php", []),
    new Route("/admin", "Administration", "/pages/auth/admin/admin_dashboard.php", []),
    new Route("/usermanager", "Gestion utilisateurs", "/pages/auth/admin/users.php", []),
    new Route("/eventmanager", "Gestion événements", "/pages/auth/admin/admin_events.php", []),
];

// Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "Esportify";