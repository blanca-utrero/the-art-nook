// Scroll suave para botones hero con offset visual extra

document.addEventListener('DOMContentLoaded', function() {
  var body = document.body;
  var isHome = body.classList.contains('pagina-home');
  var isCategoria = body.classList.contains('pagina-categoria');
  document.querySelectorAll('a.hero-btn[href^="#"], a.hero-btn[href="#galeria-obras-lista"], a.hero-btn[href="#artistas-lista"], a.hero-btn[href="#categorias-lista"]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      var href = btn.getAttribute('href');
      if (href && href.startsWith('#')) {
        var target = document.querySelector(href);
        if(target) {
          e.preventDefault();
          var navbar = document.querySelector('.navbar');
          var navbarHeight = navbar ? navbar.offsetHeight : 0;
          var extraOffset = isHome ? 10 : (isCategoria ? 0 : 80); // Home y categorías: 30px, resto: 80px
          var top = target.getBoundingClientRect().top + window.scrollY - navbarHeight - extraOffset;
          window.scrollTo({top: top, behavior: 'smooth'});
        }
      }
    });
  });
}); 