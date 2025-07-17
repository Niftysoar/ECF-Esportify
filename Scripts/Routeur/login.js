if (window.location.pathname === "/signin") {
    console.log("🟢 Connexion : script actif sur /signin");

    // Observer les changements dans #main-page
    const observer = new MutationObserver(() => {
        const form = document.getElementById("login-form");

        if (form) {
            console.log("✅ Formulaire détecté, événement attaché");
            observer.disconnect(); // plus besoin d'observer

            form.addEventListener("submit", async (event) => {
                event.preventDefault();
                console.log("📤 Soumission interceptée");

                const username = document.getElementById("username").value.trim();
                const password = document.getElementById("password").value;

                try {
                    const response = await fetch("/pages/API/login.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ username, password }),
                    });

                    console.log("📥 Réponse brute :", response);

                    const result = await response.json();
                    console.log("📦 Réponse JSON :", result);

                    if (result.success) {
                        // Stocker les données dans les cookies
                        document.cookie = "accesstoken=" + result.token + "; path=/";
                        document.cookie = "role=" + result.role + "; path=/";

                        const destination = result.role === "admin" ? "/admin" : "/dashboard";
                        console.log("➡️ Redirection vers :", destination);

                        window.location.href = destination;
                    } else {
                        const errorDiv = document.getElementById("error-message");
                        if (errorDiv) {
                            errorDiv.innerText = result.error || "Erreur inconnue.";
                        }
                        console.warn("❌ Erreur de connexion :", result.error);
                    }

                } catch (err) {
                    console.error("🚨 Erreur réseau :", err);
                    const errorDiv = document.getElementById("error-message");
                    if (errorDiv) {
                        errorDiv.innerText = "Erreur réseau ou serveur.";
                    }
                }
            });
        } else {
            console.log("🔍 En attente du formulaire...");
        }
    });

    // Lance l'observation du DOM principal
    observer.observe(document.getElementById("main-page"), {
        childList: true,
        subtree: true,
    });
}