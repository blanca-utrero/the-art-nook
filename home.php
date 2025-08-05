<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | The Art Nook</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="fondo-principal">
        <div class="ellipse-1"></div>
        <div class="hero">
            <div class="hero-content">
                <h1 class="hero-title">The Art Nook</h1>
                <p class="hero-subtitle">
                    Un refugio creativo donde artistas emergentes y amantes del arte se encuentran para compartir, descubrir y conectar a través de sus obras.
                </p>
                <button onclick="document.querySelector('#obras-destacadas').scrollIntoView({behavior: 'smooth'})" class="hero-btn">Explorar ahora</button>
            </div>
        </div>
        <section class="destacadas-section">
            <div class="destacadas-header">
                <h2 class="destacadas-title" id="obras-destacadas">Obras destacadas</h2>
                <p class="destacadas-subtitle">Una pequeña ventana al universo creativo de nuestra comunidad</p>
            </div>
            <?php include 'obras_destacadas.php'; ?>
            <div style="width:100%;display:flex;justify-content:center;margin-top:32px;">
                <a href="galeria.php" class="hero-btn">Ir a la Galería</a>
            </div>
        </section>
        <section class="artistas-section">
          <div class="artistas-header">
            <h2 class="artistas-title">Conoce a los artistas</h2>
            <p class="artistas-subtitle">Aquí, cada artista encuentra un lugar donde ser visto, escuchado y valorado.</p>
          </div>
          <div class="artistas-destacados-lista">
            <?php
            require_once 'includes/db.php';
            // Selecciona los 3 últimos artistas con al menos 3 obras
            $sql = "SELECT u.id, u.nombre, u.apellidos, u.estado, u.foto
                    FROM usuarios u
                    WHERE (SELECT COUNT(*) FROM obras o WHERE o.usuario_id = u.id) >= 3
                    ORDER BY (SELECT MAX(fecha_creacion) FROM obras o WHERE o.usuario_id = u.id) DESC
                    LIMIT 2";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($artistas as $artista):
                // Obtener las 3 últimas obras del artista
                $stmtObras = $pdo->prepare("SELECT img.url
                    FROM obras o
                    LEFT JOIN imagenes_obras img ON o.id = img.obra_id AND img.orden = 1
                    WHERE o.usuario_id = ? ORDER BY o.fecha_creacion DESC LIMIT 3");
                $stmtObras->execute([$artista['id']]);
                $obras = $stmtObras->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <a href="artista.php?id=<?= $artista['id'] ?>" style="text-decoration:none;color:inherit;display:block;height:100%;">
            <div class="tarjeta-artista">
              <div class="artista-header">
                <img class="artista-foto" src="<?= htmlspecialchars($artista['foto']) ?>" alt="Foto de <?= htmlspecialchars($artista['nombre']) ?>">
                <div class="artista-info">
                  <div class="artista-nombre"><?= htmlspecialchars($artista['nombre'] . ' ' . $artista['apellidos']) ?></div>
                  <div class="artista-estado"><?= htmlspecialchars($artista['estado']) ?></div>
                </div>
              </div>
              <div class="artista-linea"></div>
              <div class="artista-obras">
                <?php foreach ($obras as $obra): ?>
                  <div class="artista-obra-foto" style="background-image: url('<?= htmlspecialchars($obra['url']) ?>');"></div>
                <?php endforeach; ?>
              </div>
            </div>
            </a>
            <?php endforeach; ?>
          </div>
          <div class="artistas-cta">
            <a href="artistas.php" class="hero-btn">Sigue descubriendo talento</a>
          </div>
        </section>
        <div class="categorias-section-wrapper">
        <section class="categorias-section">
          <div class="categorias-header">
            <h2 class="artistas-title">Inspírate a tu manera</h2>
            <p class="artistas-subtitle">
              Explora las distintas formas de expresión que habitan en nuestra comunidad artística y encuentra la que resuena contigo.
            </p>
          </div>
          <div class="categorias-lista">
            <a href="categoria.php?id=1" class="categoria-card-link" style="text-decoration:none;color:inherit;">
              <div class="categoria-card">
                <img src="uploads/categorias/pintura.png" alt="Pintura">
                <span class="categoria-nombre">Pintura</span>
              </div>
            </a>
            <a href="categoria.php?id=2" class="categoria-card-link" style="text-decoration:none;color:inherit;">
              <div class="categoria-card">
                <img src="uploads/categorias/fotografia.png" alt="Fotografía">
                <span class="categoria-nombre">Fotografía</span>
              </div>
            </a>
            <a href="categoria.php?id=3" class="categoria-card-link" style="text-decoration:none;color:inherit;">
              <div class="categoria-card">
                <img src="uploads/categorias/ilustracion.png" alt="Ilustración">
                <span class="categoria-nombre">Ilustración</span>
              </div>
            </a>
            <a href="categoria.php?id=4" class="categoria-card-link" style="text-decoration:none;color:inherit;">
              <div class="categoria-card">
                <img src="uploads/categorias/ceramica.png" alt="Cerámica">
                <span class="categoria-nombre">Cerámica</span>
              </div>
            </a>
            <a href="categoria.php?id=5" class="categoria-card-link" style="text-decoration:none;color:inherit;">
              <div class="categoria-card">
                <img src="uploads/categorias/arte-digital.png" alt="Arte digital">
                <span class="categoria-nombre">Arte digital</span>
              </div>
            </a>
            <a href="categoria.php?id=6" class="categoria-card-link" style="text-decoration:none;color:inherit;">
              <div class="categoria-card">
                <img src="uploads/categorias/joyeria.png" alt="Joyería">
                <span class="categoria-nombre">Joyería</span>
              </div>
            </a>
          </div>
          <div class="categorias-cta">
            <a href="categorias.php" class="hero-btn">Explorar todas las categorías</a>
          </div>
        </section>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="js/navbar-hamburguesa.js"></script>
</body>
</html> 