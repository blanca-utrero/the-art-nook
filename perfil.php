<?php
session_start();
require_once 'includes/db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // Si no está logueado, redirigir a la página de login
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';
$edit_mode = false; // Variable para controlar si se muestra la vista o el formulario de edición

// Lógica para obtener datos del usuario
try {
    $stmt = $pdo->prepare('SELECT id, nombre, apellidos, email, descripcion, estado, telefono, instagram, foto FROM usuarios WHERE id = ?');
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        // Si no se encuentra el usuario (no debería pasar si está logueado)
        session_destroy();
        header('Location: login.php');
        exit;
    }

    // Lógica para guardar cambios si el formulario de edición fue enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         // Si se hizo clic en el botón "Editar Perfil"
        if (isset($_POST['edit_profile'])) {
            $edit_mode = true;
        }
        // Si se hizo clic en el botón "Guardar y publicar"
        elseif (isset($_POST['save_profile'])) {
            // Recoger y limpiar datos del formulario de edición
            $nombre = trim($_POST['nombre'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            // El email de la cuenta no se edita en este formulario según las capturas, solo el de contacto si existiera
            // Usaremos el email ya cargado del usuario para la actualización de otros campos si es necesario.
            $descripcion = trim($_POST['descripcion'] ?? '');
            $estado = trim($_POST['estado'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $instagram = trim($_POST['instagram'] ?? '');
            // Aquí iría el email de contacto si tuvieras un campo para él en la BD
            // $contact_email = trim($_POST['contact_email'] ?? '');

            // Validaciones (puedes añadir más según necesites, por ejemplo para teléfono/instagram)
            if (empty($nombre) || empty($apellidos)) { // El email ya está validado al registrarse
                 $mensaje = 'Nombre y apellidos son obligatorios.';
                 $edit_mode = true; // Mantener en modo edición si hay error
            } elseif (!empty($telefono) && !preg_match('/^[0-9]+$/', $telefono)) {
                 $mensaje = 'El teléfono solo puede contener números.';
                 $edit_mode = true;
            } else {
                // Actualizar datos en la base de datos (sin tocar la columna foto)
                $stmt_update = $pdo->prepare('UPDATE usuarios SET nombre = ?, apellidos = ?, descripcion = ?, estado = ?, telefono = ?, instagram = ? WHERE id = ?');
                if ($stmt_update->execute([$nombre, $apellidos, $descripcion, $estado, $telefono, $instagram, $usuario_id])) {
                    $mensaje = 'Perfil actualizado con éxito.';
                    // --- Lógica para manejar la subida de la foto de perfil ---
                    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                        $file = $_FILES['profile_picture'];
                        $upload_dir = 'uploads/profile-pictures/';
                        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        if (in_array($file['type'], $allowed_types)) {
                            if (!is_dir($upload_dir)) {
                                mkdir($upload_dir, 0777, true);
                            }
                            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $new_file_name = $usuario_id . '_' . uniqid() . '.' . $file_extension;
                            $target_file = $upload_dir . $new_file_name;
                            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                                $stmt_photo = $pdo->prepare('UPDATE usuarios SET foto = ? WHERE id = ?');
                                $stmt_photo->execute([$target_file, $usuario_id]);
                            } else {
                                $mensaje .= ' Error al subir la imagen.';
                                error_log("Error moviendo archivo subido para usuario " . $usuario_id . ". Error: " . $file['error']);
                            }
                        } else {
                            $mensaje .= ' Formato de imagen no permitido.';
                        }
                    } elseif (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
                        $mensaje .= ' Error en la subida del archivo: Código ' . $_FILES['profile_picture']['error'];
                        error_log("Error de subida de archivo para usuario " . $usuario_id . ": Código " . $_FILES['profile_picture']['error']);
                    }
                    // --- Fin Lógica para manejar la subida de la foto de perfil ---
                    // Refrescar los datos del usuario después de la actualización
                    $stmt = $pdo->prepare('SELECT id, nombre, apellidos, email, descripcion, estado, telefono, instagram, foto FROM usuarios WHERE id = ?');
                    $stmt->execute([$usuario_id]);
                    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                    $edit_mode = false; // Salir del modo edición al guardar con éxito
                } else {
                    $mensaje = 'Error al actualizar el perfil.';
                    $edit_mode = true;
                    error_log("Error al ejecutar UPDATE en perfil para usuario " . $usuario_id);
                }
            }
        }
    }

     // Determinar si es un perfil básico (para la vista)
    $is_basic_profile_view = empty($usuario['descripcion']) && empty($usuario['estado']) && empty($usuario['telefono']) && empty($usuario['instagram']);

    // Obtener las obras del usuario actual
    $stmt_obras = $pdo->prepare('SELECT o.id, o.titulo, o.descripcion, o.fecha_creacion, img.url
        FROM obras o
        LEFT JOIN imagenes_obras img ON o.id = img.obra_id AND img.orden = 1
        WHERE o.usuario_id = ?
        ORDER BY o.fecha_creacion DESC');
    $stmt_obras->execute([$usuario_id]);
    $obras_usuario = $stmt_obras->fetchAll(PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    $mensaje = 'Ocurrió un error en la base de datos.';
    error_log("Error de DB en perfil.php para usuario " . $usuario_id . ": " . $e->getMessage());
} catch (\Exception $e) {
     $mensaje = 'Ocurrió un error inesperado.';
     error_log("Error inesperado en perfil.php para usuario " . $usuario_id . ": " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="fondo-principal">
        <div class="rectangle-3"></div>
        <div class="login-content">
            <!-- Encabezado y Título -->
            <header>
                <h1 class="hero-title">Mi Nook</h1>
                <!-- Aquí irían botones de navegación global si los tienes -->
            </header>

            <?php if ($mensaje): ?>
                <p class="profile-message"><?php echo htmlspecialchars($mensaje); ?></p>
            <?php endif; ?>

            <!-- Contenedor Principal del Perfil -->
            <div class="profile-container" style="width: 100%; max-width: 1238px; margin: 0 auto;">
                <div class="profile-header-row" style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom: 35px; margin-top: 60px;">
                    <h2 class="profile-titulo">Mi información:</h2>
                    <?php if (!$edit_mode): ?>
                    <form method="post" style="display: inline; margin: 0;">
                        <button type="submit" name="edit_profile" class="hero-btn" id="btn-editar-perfil">Editar Perfil</button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php if ($edit_mode): ?>
                    <form method="post" enctype="multipart/form-data">
                        <div class="edit-message-bar">
                            <span>¡Psst! Estás editando tu perfil. ¡Asegúrate de guardarlo antes de salir de la página!</span>
                            <button type="submit" name="save_profile" class="hero-btn" id="btn-guardar-perfil">Guardar y publicar</button>
                        </div>
                        <div class="profile-main">
                            <div class="profile-col-izq" style="display: flex; flex-direction: column; align-items: center;">
                                <div class="profile-picture-placeholder">
                                    <?php if (!empty($usuario['foto']) && file_exists($usuario['foto'])): ?>
                                        <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto de perfil de <?php echo htmlspecialchars($usuario['nombre']); ?>">
                                    <?php else: ?>
                                        Foto de Perfil
                                    <?php endif; ?>
                                </div>
                                <div class="custom-file-upload">
                                    <label for="profile-picture" class="file-label-outline">
                                        <span>Subir foto de perfil</span>
                                        <input type="file" id="profile-picture" name="profile_picture" accept="image/*" style="display:none;">
                                    </label>
                                </div>
                                <div class="profile-contacto-bloque" style="margin-top: 32px; width: 293px; display: flex; flex-direction: column; align-items: flex-start;">
                                    <div class="profile-contacto-titulo" style="font-family: 'Lato', sans-serif; font-weight: 700; font-size: 30px; line-height: 36px; color: #4D5B6A; margin-bottom: 8px;">Contacto:</div>
                                    <div class="profile-contacto-dato" style="display: flex; align-items: center; gap: 10px; height: 26px; margin-bottom: 6px;">
                                        <img src="uploads/iconos/envelope.svg" alt="Email" width="26" height="26" style="flex-shrink:0; filter: invert(36%) sepia(41%) saturate(1042%) hue-rotate(330deg) brightness(92%) contrast(91%);">
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" class="login-form-input contact-input" id="input-email-contacto" disabled>
                                    </div>
                                    <div class="profile-contacto-dato" style="display: flex; align-items: center; gap: 10px; height: 26px; margin-bottom: 6px;">
                                        <img src="uploads/iconos/phone.svg" alt="Teléfono" width="26" height="26" style="flex-shrink:0; filter: invert(36%) sepia(41%) saturate(1042%) hue-rotate(330deg) brightness(92%) contrast(91%);">
                                        <input type="tel" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>" placeholder="+ Añadir teléfono" class="login-form-input contact-input">
                                    </div>
                                    <div class="profile-contacto-dato" style="display: flex; align-items: center; gap: 10px; height: 26px; margin-bottom: 6px;">
                                        <img src="uploads/iconos/instagram-logo.svg" alt="Instagram" width="26" height="26" style="flex-shrink:0; filter: invert(36%) sepia(41%) saturate(1042%) hue-rotate(330deg) brightness(92%) contrast(91%);">
                                        <input type="text" name="instagram" value="<?php echo htmlspecialchars($usuario['instagram'] ?? ''); ?>" placeholder="+ Añadir instagram" class="login-form-input contact-input">
                                    </div>
                                </div>
                            </div>
                            <div class="profile-info">
                                <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" placeholder="Nombre" class="login-form-input" style="font-size:36px; font-weight:700; color:#4D5B6A; font-family:'Lato',sans-serif; width:100%; margin-bottom:12px;">
                                <input type="text" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos'] ?? ''); ?>" placeholder="Apellidos" class="login-form-input" style="font-size:36px; font-weight:700; color:#4D5B6A; font-family:'Lato',sans-serif; width:100%; margin-bottom:12px;">
                                <input type="text" name="estado" value="<?php echo htmlspecialchars($usuario['estado'] ?? ''); ?>" placeholder="Frase corta (estado)" class="login-form-input" style="font-size:22px; color:#B86B4B; font-style:italic; margin-bottom:18px; width:100%; font-family:'Lato',sans-serif;">
                                <textarea name="descripcion" placeholder="Añade una descripción, ¡cuéntale al mundo quién eres y qué haces!" class="login-form-input" style="font-size:18px; color:#4D5B6A; width:100%; min-height:90px; font-family:'Lato',sans-serif; margin-bottom:18px;"><?php echo htmlspecialchars($usuario['descripcion'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="profile-main">
                        <div class="profile-col-izq" style="display: flex; flex-direction: column; align-items: center;">
                            <div class="profile-picture-placeholder">
                                <?php if (!empty($usuario['foto']) && file_exists($usuario['foto'])): ?>
                                    <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto de perfil de <?php echo htmlspecialchars($usuario['nombre']); ?>">
                                <?php else: ?>
                                    Foto de Perfil
                                <?php endif; ?>
                            </div>
                            <div class="profile-contacto-bloque" style="margin-top: 32px; width: 293px; display: flex; flex-direction: column; align-items: flex-start;">
                                <div class="profile-contacto-titulo" style="font-family: 'Lato', sans-serif; font-weight: 700; font-size: 30px; line-height: 36px; color: #4D5B6A; margin-bottom: 8px;">Contacto:</div>
                                <div class="profile-contacto-dato" style="display: flex; align-items: center; gap: 10px; height: 26px; margin-bottom: 6px;">
                                    <img src="uploads/iconos/envelope.svg" alt="Email" width="26" height="26" style="flex-shrink:0; filter: invert(36%) sepia(41%) saturate(1042%) hue-rotate(330deg) brightness(92%) contrast(91%);">
                                    <span style="font-family: 'Lato', sans-serif; font-size: 14px; color: #4D5B6A;"> <?php echo htmlspecialchars($usuario['email'] ?? ''); ?> </span>
                                </div>
                                <div class="profile-contacto-dato" style="display: flex; align-items: center; gap: 10px; height: 26px; margin-bottom: 6px;">
                                    <img src="uploads/iconos/phone.svg" alt="Teléfono" width="26" height="26" style="flex-shrink:0; filter: invert(36%) sepia(41%) saturate(1042%) hue-rotate(330deg) brightness(92%) contrast(91%);">
                                    <span style="font-family: 'Lato', sans-serif; font-size: 14px; color: #4D5B6A;"> <?php echo empty($usuario['telefono']) ? '+ Añadir teléfono' : htmlspecialchars($usuario['telefono']); ?> </span>
                                </div>
                                <div class="profile-contacto-dato" style="display: flex; align-items: center; gap: 10px; height: 26px; margin-bottom: 6px;">
                                    <img src="uploads/iconos/instagram-logo.svg" alt="Instagram" width="26" height="26" style="flex-shrink:0; filter: invert(36%) sepia(41%) saturate(1042%) hue-rotate(330deg) brightness(92%) contrast(91%);">
                                    <span style="font-family: 'Lato', sans-serif; font-size: 14px; color: #4D5B6A;"> <?php echo empty($usuario['instagram']) ? '+ Añadir instagram' : htmlspecialchars($usuario['instagram']); ?> </span>
                                </div>
                            </div>
                        </div>
                        <div class="profile-info">
                            <div class="profile-nombre"><?php echo htmlspecialchars($usuario['nombre'] ?? '') . ' ' . htmlspecialchars($usuario['apellidos'] ?? ''); ?></div>
                            <?php if (empty($usuario['estado']) && empty($usuario['descripcion'])): ?>
                                <div class="profile-info-placeholder">
                                    <div class="profile-frase" style="color:#B86B4B; font-style:italic;">
                                        ¿Qué frase te representa? ¡Escríbela aquí! (Será lo primero que vean los amantes del arte al ver tu perfil)
                                    </div>
                                    <div class="profile-descripcion" style="color:#4D5B6A;">
                                        Añade una descripción, ¡cuéntale al mundo quién eres y qué haces!
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php if (!empty($usuario['estado'])): ?>
                                    <div class="profile-frase">“<?php echo htmlspecialchars($usuario['estado']); ?>”</div>
                                <?php endif; ?>
                                <?php if (!empty($usuario['descripcion'])): ?>
                                    <div class="profile-descripcion"><?php echo nl2br(htmlspecialchars($usuario['descripcion'])); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Sección Mis Obras -->
                <div class="profile-header-row" style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom: 18px; margin-top: 64px;">
                    <h3 class="profile-titulo">Mis obras:</h3>
                    <button class="hero-btn hero-btn-outline" style="font-size:16px; padding: 6px 18px; height: auto; min-width: unset;" onclick="window.location.href='subir-obra.php'">Añadir nueva obra</button>
                </div>

                <?php if (count($obras_usuario) === 0): ?>
                    <div class="no-works-message">
                        <div style="display: flex; justify-content: center; align-items: center; width: 100%; margin-bottom: 24px;">
                            <img src="uploads/iconos/Sad-face.png" alt=":(" style="width: 220px; height: 220px; object-fit: contain; display: block; margin: 0 auto;">
                        </div>
                        <h2 class="no-works-title">Ups, parece que todavía no tienes ninguna obra publicada</h2>
                    </div>
                <?php else: ?>
                    <div class="obras-usuario-lista" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; width: 100%; margin-top: 32px;">
                        <?php foreach($obras_usuario as $obra): ?>
                            <a href="editar-obra.php?id=<?= $obra['id'] ?>" style="text-decoration: none; color: inherit;">
                                <div class="tarjeta-obra">
                                    <div class="obra-foto" style="background-image: url('<?= htmlspecialchars($obra['url']) ?>');"></div>                           
                                    <div class="obra-info">
                                        <div class="obra-titulo"><?= htmlspecialchars($obra['titulo']) ?></div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div> <!-- /.profile-container -->

        </div> <!-- /.login-content -->
    </div> <!-- /.fondo-principal -->
    <?php include 'footer.php'; ?>
    <script src="js/scripts.js"></script>
    <script src="js/navbar-hamburguesa.js"></script>
</body>
</html>
