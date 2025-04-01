document.addEventListener("DOMContentLoaded", function () {
    var backToTop = document.getElementById("backToTop");

    if (backToTop) {
        // Écoute le scroll
        window.addEventListener("scroll", function () {
            if (window.scrollY > 300) {
                backToTop.style.display = "block"; // Affiche le bouton
            } else {
                backToTop.style.display = "none"; // Cache le bouton
            }
        });

        // Action au clic pour remonter
        backToTop.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
});