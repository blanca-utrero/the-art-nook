<?php
require_once 'includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: artistas.php');
    exit;
}

$stmt = $pdo->prepare('SELECT nombre, apellidos, foto, estado, descripcion, email, telefono, instagram FROM usuarios WHERE id = ?');
$stmt->execute([$id]);
$artista = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$artista) {
    header('Location: artistas.php');
    exit;
}

// Obras del artista
$stmt_obras = $pdo->prepare('SELECT o.id, o.titulo, o.descripcion, o.fecha_creacion, img.url
    FROM obras o
    LEFT JOIN imagenes_obras img ON o.id = img.obra_id AND img.orden = 1
    WHERE o.usuario_id = ?
    ORDER BY o.fecha_creacion DESC');
$stmt_obras->execute([$id]);
$obras = $stmt_obras->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($artista['nombre'] . ' ' . $artista['apellidos']) ?> | Artista</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="fondo-principal">
    <div class="rectangle-3"></div>
    <div class="login-content" style="margin-top: 180px;">
        <div class="profile-container" style="width: 100%; max-width: 1238px; margin: 0 auto;">
            <div class="profile-main">
                <div class="profile-col-izq" style="display: flex; flex-direction: column; align-items: center;">
                    <div class="profile-picture-placeholder">
                        <?php if (!empty($artista['foto']) && file_exists($artista['foto'])): ?>
                            <img src="<?= htmlspecialchars($artista['foto']) ?>" alt="Foto de <?= htmlspecialchars($artista['nombre']) ?>">
                        <?php else: ?>
                            Foto de Perfil
                        <?php endif; ?>
                    </div>
                    <div class="profile-contacto-bloque" style="margin-top: 32px; width: 293px; display: flex; flex-direction: column; align-items: flex-start;">
                        <div class="profile-contacto-titulo">Contacto:</div>
                        <div class="profile-contacto-dato">
                            <img src="uploads/iconos/envelope.svg" alt="Email" width="26" height="26" style="flex-shrink:0; filter: invert(36%) sepia(41%) saturate(1042%) hue-rotate(330deg) brightness(92%) contrast(91%);">
                            <span><?= htmlspecialchars($artista['email']) ?></span>
                        </div>
                        <?php if (!empty($artista['telefono'])): ?>
                        <div class="profile-contacto-dato">
                            <img src="uploads/iconos/phone.svg" alt="Teléfono" width="26" height="26" style="flex-shrink:0; filter: invert(36%) sepia(41%) saturate(1042%) hue-rotate(330deg) brightness(92%) contrast(91%);">
                            <span><?= htmlspecialchars($artista['telefono']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($artista['instagram'])): ?>
                        <div class="profile-contacto-dato">
                            <img src="uploads/iconos/instagram-logo.svg" alt="Instagram" width="26" height="26" style="flex-shrink:0; filter: invert(36%) sepia(41%) saturate(1042%) hue-rotate(330deg) brightness(92%) contrast(91%);">
                            <span>@<?= htmlspecialchars($artista['instagram']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="profile-info">
                    <div class="profile-nombre"><?= htmlspecialchars($artista['nombre'] . ' ' . $artista['apellidos']) ?></div>
                    <?php if (!empty($artista['estado'])): ?>
                        <div class="profile-frase">“<?= htmlspecialchars($artista['estado']) ?>”</div>
                    <?php endif; ?>
                    <?php if (!empty($artista['descripcion'])): ?>
                        <div class="profile-descripcion"><?= nl2br(htmlspecialchars($artista['descripcion'])) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div style="width: 100%; margin-bottom: 18px; margin-top: 64px; display: flex; justify-content: center; align-items: center;">
                <h2 class="destacadas-title" style="color: #4D5B6A; text-align: center;">Obras</h2>
            </div>
            <?php if (count($obras) === 0): ?>
                <div class="no-works-message">
                    <div style="display: flex; justify-content: center; align-items: center; width: 100%; margin-bottom: 24px;">
                        <img src="uploads/iconos/Sad-face.png" alt=":(" style="width: 220px; height: 220px; object-fit: contain; display: block; margin: 0 auto;">
                    </div>
                    <h2 class="no-works-title">Este artista todavía no tiene ninguna obra publicada</h2>
                </div>
            <?php else: ?>
                <div class="obras-usuario-lista" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; width: 100%; margin-top: 32px;">
                    <?php foreach($obras as $obra): ?>
                        <a href="obra.php?id=<?= $obra['id'] ?>" style="text-decoration: none; color: inherit;">
                            <div class="tarjeta-obra">
                                <div class="obra-foto" style="background-image: url('<?= htmlspecialchars($obra['url']) ?>');"></div>
                                <div class="obra-info">
                                    <div class="obra-titulo"><?= htmlspecialchars($obra['titulo']) ?></div>
                                    <div class="obra-artista"><?= htmlspecialchars($artista['nombre'] . ' ' . $artista['apellidos']) ?></div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script src="js/navbar-hamburguesa.js"></script>
</body>
</html> 