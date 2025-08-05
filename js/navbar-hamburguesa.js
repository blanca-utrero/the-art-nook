document.addEventListener('DOMContentLoaded', function() {
    var hamburger = document.querySelector('.hamburger');
    var navbar = document.querySelector('.navbar');
    if (hamburger && navbar) {
        hamburger.addEventListener('click', function() {
            var isOpen = navbar.classList.toggle('open');
            hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        // Cerrar menú al hacer click fuera
        document.addEventListener('click', function(e) {
            if (navbar.classList.contains('open') && !navbar.contains(e.target)) {
                navbar.classList.remove('open');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        });
        // Cerrar menú al pulsar un enlace
        navbar.querySelectorAll('.navbar-links a').forEach(function(link) {
            link.addEventListener('click', function() {
                navbar.classList.remove('open');
                hamburger.setAttribute('aria-expanded', 'false');
            });
        });
    }
}); 