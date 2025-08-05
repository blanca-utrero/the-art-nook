function ajustarFondoPrincipal() {
    var fondo = document.querySelector('.fondo-principal');
    var ellipse = document.querySelector('.ellipse-1');
    if (fondo && ellipse) {
        var minHeight = Math.max(ellipse.offsetTop + ellipse.offsetHeight, fondo.scrollHeight);
        fondo.style.minHeight = minHeight + 'px';
    }
}
window.addEventListener('load', ajustarFondoPrincipal);
window.addEventListener('resize', ajustarFondoPrincipal); 