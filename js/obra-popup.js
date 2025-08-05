document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btn-contactar-artista');
    const popup = document.getElementById('popup-contacto');
    const cerrar = document.getElementById('cerrar-popup-contacto');

    btn.addEventListener('click', function() {
        popup.style.display = 'block';
        setTimeout(() => popup.classList.add('activo'), 10);
    });
    cerrar.addEventListener('click', function() {
        popup.classList.remove('activo');
        setTimeout(() => popup.style.display = 'none', 200);
    });
    // Cerrar al hacer clic fuera
    window.addEventListener('mousedown', function(e) {
        if (popup.style.display === 'block' && !popup.querySelector('.popup-contacto-content').contains(e.target) && e.target !== btn) {
            popup.classList.remove('activo');
            setTimeout(() => popup.style.display = 'none', 200);
        }
    });
}); 