<?php
require_once 'includes/db.php';

$sql = "SELECT o.id, o.titulo, o.descripcion, o.fecha_creacion, img.url, u.nombre, u.apellidos
        FROM obras o
        LEFT JOIN imagenes_obras img ON o.id = img.obra_id AND img.orden = 1
        LEFT JOIN usuarios u ON o.usuario_id = u.id
        ORDER BY o.fecha_creacion DESC
        LIMIT 3";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="obras-destacadas-lista" style="display: flex; gap: 20px; justify-content: center; width: 100%;">
<?php foreach($result as $obra): ?>
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
 