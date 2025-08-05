<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artistas | The Art Nook</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body class="pagina-artistas">
    <?php include 'navbar.php'; ?>
    <div class="fondo-principal">
        <div class="ellipse-1"></div>
        <div class="hero">
            <div class="hero-content">
                <h1 class="hero-title">Voces creativas</h1>
                <p class="hero-subtitle">
                    Artistas emergentes y únicos que dan vida a nuestras paredes con talento y pasión.
                </p>
                <a href="#artistas-lista" class="hero-btn">Descubrir artistas</a>
            </div>
        </div>
        <div id="artistas-lista" class="artistas-lista" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; width: 100%; max-width: 1240px; margin: 48px auto 0 auto;">
            <?php
            require_once 'includes/db.php';
            // Selecciona todos los artistas con al menos 1 obra
            $sql = "SELECT u.id, u.nombre, u.apellidos, u.estado, u.foto
                    FROM usuarios u
                    WHERE EXISTS (SELECT 1 FROM obras o WHERE o.usuario_id = u.id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($artistas as $artista) {
                // Obtener las 3 últimas obras del artista
                $sqlObras = "SELECT img.url FROM obras o JOIN imagenes_obras img ON img.obra_id = o.id AND img.orden = 1 WHERE o.usuario_id = ? ORDER BY o.fecha_creacion DESC LIMIT 3";
                $stmtObras = $pdo->prepare($sqlObras);
                $stmtObras->execute([$artista['id']]);
                $obras = $stmtObras->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <a href="artista.php?id=<?= $artista['id'] ?>" style="text-decoration:none;color:inherit;display:block;height:100%;">
                <div class="tarjeta-artista">
                    <div class="artista-header">
                        <img class="artista-foto" src="<?php echo htmlspecialchars($artista['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($artista['nombre']); ?>">
                        <div class="artista-info">
                            <div class="artista-nombre"><?php echo htmlspecialchars($artista['nombre'] . ' ' . $artista['apellidos']); ?></div>
                            <div class="artista-estado"><?php echo htmlspecialchars($artista['estado']); ?></div>
                        </div>
                    </div>
                    <div class="artista-linea"></div>
                    <div class="artista-obras">
                        <?php foreach ($obras as $obra) { ?>
                            <div class="artista-obra-foto" style="background-image: url('<?php echo htmlspecialchars($obra['url']); ?>');"></div>
                        <?php } ?>
                    </div>
                </div>
                </a>
            <?php } ?>
        </div>
    </div>
    <script src="js/hero-scroll.js"></script>
    <?php include 'footer.php'; ?>
    <script src="js/navbar-hamburguesa.js"></script>
</body>
</html> 