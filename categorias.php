<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías | The Art Nook</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body class="pagina-categoria">
    <?php include 'navbar.php'; ?>
    <div class="fondo-principal">
        <div class="ellipse-1"></div>
        <div class="hero">
            <div class="hero-content">
                <h1 class="hero-title">Mundos artísticos</h1>
                <p class="hero-subtitle">
                    Explora las diferentes formas de arte presentes en nuestra comunidad y sumérgete en las obras y artistas que dan vida a cada categoría.
                </p>
                <a href="#categorias-lista" class="hero-btn">Ver categorías</a>
            </div>
        </div>
        <div class="categorias-section-wrapper" style="position:relative;z-index:3;">
        <div id="categorias-lista" class="categorias-lista-grande">
            <a href="categoria.php?id=1" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/pintura.png" alt="Pintura">
                    <span class="categoria-nombre">Pintura</span>
                </div>
            </a>
            <a href="categoria.php?id=2" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/fotografia.png" alt="Fotografía">
                    <span class="categoria-nombre">Fotografía</span>
                </div>
            </a>
            <a href="categoria.php?id=3" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/escultura.png" alt="Escultura">
                    <span class="categoria-nombre">Escultura</span>
                </div>
            </a>
            <a href="categoria.php?id=4" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/arte-digital.png" alt="Arte Digital">
                    <span class="categoria-nombre">Arte Digital</span>
                </div>
            </a>
            <a href="categoria.php?id=5" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/ilustracion.png" alt="Ilustración">
                    <span class="categoria-nombre">Ilustración</span>
                </div>
            </a>
            <a href="categoria.php?id=6" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/ceramica.png" alt="Cerámica">
                    <span class="categoria-nombre">Cerámica</span>
                </div>
            </a>
            <a href="categoria.php?id=7" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/textil.png" alt="Textil">
                    <span class="categoria-nombre">Textil</span>
                </div>
            </a>
            <a href="categoria.php?id=8" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/joyeria.png" alt="Joyería">
                    <span class="categoria-nombre">Joyería</span>
                </div>
            </a>
            <a href="categoria.php?id=9" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/collage.png" alt="Collage">
                    <span class="categoria-nombre">Collage</span>
                </div>
            </a>
            <a href="categoria.php?id=10" class="categoria-card-link" style="text-decoration:none;color:inherit;">
                <div class="categoria-card categoria-card-grande">
                    <img src="uploads/categorias/libre.png" alt="Libre">
                    <span class="categoria-nombre">Libre</span>
                </div>
            </a>
        </div>
        </div>
    </div>
    <script src="js/hero-scroll.js"></script>
    <?php include 'footer.php'; ?>
    <script src="js/navbar-hamburguesa.js"></script>
</body>
</html> 