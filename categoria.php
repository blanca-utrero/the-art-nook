<?php
require_once 'includes/db.php';

// Obtener id de la categoría
$categoria_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($categoria_id <= 0) {
    header('Location: categorias.php');
    exit;
}
// Obtener nombre y descripción de la categoría
$stmt_cat = $pdo->prepare('SELECT nombre, descripcion FROM categorias WHERE id = ?');
$stmt_cat->execute([$categoria_id]);
$categoria = $stmt_cat->fetch(PDO::FETCH_ASSOC);
if (!$categoria) {
    header('Location: categorias.php');
    exit;
}

// Obtener obras de la categoría
$sql = "SELECT o.id, o.titulo, o.descripcion, o.fecha_creacion, img.url, u.nombre, u.apellidos
        FROM obras o
        LEFT JOIN imagenes_obras img ON o.id = img.obra_id AND img.orden = 1
        LEFT JOIN usuarios u ON o.usuario_id = u.id
        JOIN obras_categorias oc ON oc.obra_id = o.id
        WHERE oc.categoria_id = ?
        ORDER BY o.fecha_creacion DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$categoria_id]);
$obras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categoria['nombre']) ?> | The Art Nook</title>
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
                <h1 class="hero-title"><?= htmlspecialchars($categoria['nombre']) ?></h1>
                <p class="hero-subtitle">
                    <?= !empty($categoria['descripcion']) 
                        ? htmlspecialchars($categoria['descripcion']) 
                        : 'Explora una selección de ' . strtolower(htmlspecialchars($categoria['nombre'])) . ' únicas y originales en distintos estilos y técnicas. Encuentra la obra ideal para dar vida y personalidad a tus espacios.'; ?>
                </p>
                <a href="#galeria-obras-lista" class="hero-btn">Ver <?= htmlspecialchars($categoria['nombre']) ?></a>
            </div>
        </div>
        <div id="galeria-obras-lista" class="galeria-obras-lista" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; width: 100%; max-width: 1240px; margin: 48px auto 0 auto;">
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
    <script src="js/hero-scroll.js"></script>
    <?php include 'footer.php'; ?>
    <script src="js/navbar-hamburguesa.js"></script>
</body>
</html> 