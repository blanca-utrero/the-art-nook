<?php
require_once 'includes/db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: galeria.php');
    exit;
}
$stmt = $pdo->prepare('SELECT o.titulo, o.descripcion, o.fecha_creacion, u.id as artista_id, u.nombre, u.apellidos, u.foto as artista_foto, u.email, u.telefono, u.instagram
    FROM obras o
    JOIN usuarios u ON o.usuario_id = u.id
    WHERE o.id = ?');
$stmt->execute([$id]);
$obra = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$obra) {
    header('Location: galeria.php');
    exit;
}
$stmt_imgs = $pdo->prepare('SELECT url FROM imagenes_obras WHERE obra_id = ? ORDER BY orden ASC LIMIT 4');
$stmt_imgs->execute([$id]);
$imagenes = $stmt_imgs->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($obra['titulo']) ?> | The Art Nook</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,500,500italic,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="fondo-principal">
    <div class="rectangle-3">
        <div class="subir-obra-form" style="padding-top: 140px;">
            <div class="profile-main" style="gap:48px; align-items: flex-start;">
                <!-- Columna Izquierda: Imágenes -->
                <div class="profile-col-izq" style="display:flex; flex-direction:column; align-items:flex-start;">
                    <div class="preview-img-box-main obra-preview-img-box-main" id="preview-img-box-main">
                        <?php if (!empty($imagenes[0]['url'])): ?>
                            <img src="<?= htmlspecialchars($imagenes[0]['url']) ?>" alt="Obra principal" id="obra-img-principal">
                        <?php else: ?>
                            <span style="color:#A3B18C; font-size:48px;">+</span>
                        <?php endif; ?>
                    </div>
                    <?php if (count($imagenes) > 1): ?>
                    <div class="imagenes-preview-row" id="miniaturas-row">
                        <?php foreach($imagenes as $i => $img): ?>
                            <div class="preview-img-box obra-preview-img-box miniatura-img" data-img="<?= htmlspecialchars($img['url']) ?>">
                                <img src="<?= htmlspecialchars($img['url']) ?>" alt="Obra extra <?= $i+1 ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php elseif (count($imagenes) === 1): ?>
                    <div class="imagenes-preview-row" id="miniaturas-row">
                        <div class="preview-img-box obra-preview-img-box miniatura-img activa" data-img="<?= htmlspecialchars($imagenes[0]['url']) ?>">
                            <img src="<?= htmlspecialchars($imagenes[0]['url']) ?>" alt="Obra extra 1">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <!-- Columna Derecha: Info -->
                <div class="obra-col-der">
                    <h1 class="obra-titulo-detalle"> <?= htmlspecialchars($obra['titulo']) ?> </h1>
                    <a href="artista.php?id=<?= $obra['artista_id'] ?>" class="obra-artista-bloque" style="text-decoration:none; color:inherit;">
                        <?php if (!empty($obra['artista_foto']) && file_exists($obra['artista_foto'])): ?>
                            <img src="<?= htmlspecialchars($obra['artista_foto']) ?>" alt="Artista" class="obra-artista-foto">
                        <?php endif; ?>
                        <span class="obra-artista-nombre"> <?= htmlspecialchars($obra['nombre'] . ' ' . $obra['apellidos']) ?> </span>
                    </a>
                    <div class="obra-descripcion-detalle">
                        <?= nl2br(htmlspecialchars($obra['descripcion'])) ?>
                    </div>
                    <div class="popup-anchor" style="position:relative; width:100%; display:flex; flex-direction:column; align-items:center;">
                        <button class="btn-contactar-artista" id="btn-contactar-artista" type="button">Contactar con el artista</button>
                        <div class="popup-contacto" id="popup-contacto" style="display:none; position:absolute; left:0; transform:none; top:auto; bottom:100%;">
                            <div class="popup-contacto-content">
                                <button class="popup-contacto-cerrar" id="cerrar-popup-contacto">&times;</button>
                                <div class="popup-contacto-titulo">Contacto:</div>
                                <div class="popup-contacto-linea"></div>
                                <div class="popup-contacto-dato"><img src="uploads/iconos/envelope.svg" alt="Email" width="26" height="26"> <?= htmlspecialchars($obra['email']) ?></div>
                                <?php if (!empty($obra['telefono'])): ?>
                                <div class="popup-contacto-dato"><img src="uploads/iconos/phone.svg" alt="Teléfono" width="26" height="26"> <?= htmlspecialchars($obra['telefono']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($obra['instagram'])): ?>
                                <div class="popup-contacto-dato"><img src="uploads/iconos/instagram-logo.svg" alt="Instagram" width="26" height="26"> @<?= htmlspecialchars($obra['instagram']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/obra-popup.js"></script>
<script src="js/obra-slider.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
