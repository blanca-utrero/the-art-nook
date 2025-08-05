document.addEventListener('DOMContentLoaded', function() {
    const miniaturas = document.querySelectorAll('.miniatura-img');
    const imgPrincipal = document.getElementById('obra-img-principal');
    miniaturas.forEach(function(mini) {
        mini.addEventListener('click', function() {
            const url = this.getAttribute('data-img');
            if (imgPrincipal && url) {
                imgPrincipal.src = url;
            }
            miniaturas.forEach(m => m.classList.remove('activa'));
            this.classList.add('activa');
        });
    });
}); 