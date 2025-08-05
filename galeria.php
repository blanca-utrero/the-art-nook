<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería | The Art Nook</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body class="pagina-galeria">
    <?php include 'navbar.php'; ?>
    <div class="fondo-principal">
        <div class="ellipse-1"></div>
        <div class="hero">
            <div class="hero-content">
                <h1 class="hero-title">Arte para descubrir</h1>
                <p class="hero-subtitle">
                    Una selección única de arte para todos los gustos y estilos. Explora, disfruta y déjate sorprender.
                </p>
                <a href="#galeria-obras-lista" class="hero-btn">Ver Galería</a>
            </div>
        </div>
        <div id="galeria-obras-lista" class="galeria-obras-lista" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; width: 100%; max-width: 1240px; margin: 48px auto 0 auto;">
            <?php
            require_once 'includes/db.php';
            $sql = "SELECT o.id, o.titulo, o.descripcion, o.fecha_creacion, img.url, u.nombre, u.apellidos
                    FROM obras o
                    LEFT JOIN imagenes_obras img ON o.id = img.obra_id AND img.orden = 1
                    LEFT JOIN usuarios u ON o.usuario_id = u.id
                    ORDER BY o.fecha_creacion DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $obras = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach($obras as $obra): ?>
                <a href="obra.php?id=<?= $obra['id'] ?>" style="text-decoration: none; color: inherit;">
                    <div class="tarjeta-obra">
                        <div class="obra-foto" style="background-image: url('<?= htmlspecialchars($obra['url']) ?>');"></div>
                        <div class="obra-info">
                            <div class="obra-titulo"><?= htmlspecialchars($obra['titulo']) ?></div>
                            <div class="obra-artista"><?= htmlspecialchars($obra['nombre'] . ' ' . $obra['apellidos']) ?></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
    // Scroll suave compensando la altura de la navbar
    document.querySelectorAll('a.hero-btn[href^="#galeria-lista"], a.hero-btn[href="#galeria-lista"]').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        var target = document.getElementById('galeria-lista');
        if(target) {
          var navbar = document.querySelector('.navbar');
          var offset = navbar ? navbar.offsetHeight + 30 : 120;
          var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
          window.scrollTo({top: top, behavior: 'smooth'});
        }
      });
    });
    </script>
    <?php include 'footer.php'; ?>
    <script src="js/navbar-hamburguesa.js"></script>
</body>
</html> 