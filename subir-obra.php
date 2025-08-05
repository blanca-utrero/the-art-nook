<?php
session_start();
require_once 'includes/db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';

// Obtener datos del usuario para mostrar nombre y foto
$stmt_user = $pdo->prepare('SELECT nombre, apellidos, foto FROM usuarios WHERE id = ?');
$stmt_user->execute([$usuario_id]);
$usuario = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Obtener categorías
$stmt_cat = $pdo->query('SELECT id, nombre FROM categorias ORDER BY nombre ASC');
$categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria_id = $_POST['categoria_id'] ?? '';
    $imagenes = $_FILES['imagenes'] ?? null;
    $errores = [];

    // Validaciones
    if (empty($titulo)) {
        $errores[] = 'El nombre de la obra es obligatorio.';
    }
    if (empty($categoria_id)) {
        $errores[] = 'Selecciona una categoría.';
    }
    if (!$imagenes || $imagenes['error'][0] === UPLOAD_ERR_NO_FILE) {
        $errores[] = 'Debes subir al menos una imagen.';
    }

    // Si no hay errores, procesar subida
    if (empty($errores)) {
        try {
            // Insertar obra
            $stmt_obra = $pdo->prepare('INSERT INTO obras (usuario_id, titulo, descripcion, fecha_creacion) VALUES (?, ?, ?, NOW())');
            $stmt_obra->execute([$usuario_id, $titulo, $descripcion]);
            $obra_id = $pdo->lastInsertId();

            // Insertar relación obra-categoría
            $stmt_cat = $pdo->prepare('INSERT INTO obras_categorias (obra_id, categoria_id) VALUES (?, ?)');
            $stmt_cat->execute([$obra_id, $categoria_id]);

            // Subir imágenes
            $upload_dir = 'uploads/obras/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            for ($i = 0; $i < 5; $i++) {
                if (!empty($imagenes['name'][$i]) && $imagenes['error'][$i] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $imagenes['name'][$i],
                        'type' => $imagenes['type'][$i],
                        'tmp_name' => $imagenes['tmp_name'][$i],
                        'error' => $imagenes['error'][$i],
                        'size' => $imagenes['size'][$i],
                    ];
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $new_name = $obra_id . '_' . $i . '_' . uniqid() . '.' . $ext;
                    $destino = $upload_dir . $new_name;
                    if (move_uploaded_file($file['tmp_name'], $destino)) {
                        $stmt_img = $pdo->prepare('INSERT INTO imagenes_obras (obra_id, url, orden) VALUES (?, ?, ?)');
                        $stmt_img->execute([$obra_id, $destino, $i+1]);
                    }
                }
            }

            $mensaje = '¡Obra publicada con éxito!';
            header('Location: perfil.php');
            exit;
        } catch (Exception $e) {
            $errores[] = 'Error al guardar la obra: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Obra</title>
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
            <div class="edit-message-bar">
                <span>¡Psst! Estás editando esta obra. ¡Asegúrate de guardarla antes de salir de la página!</span>
                <button type="submit" form="form-subir-obra" class="hero-btn" id="btn-guardar-obra">Guardar y publicar</button>
            </div>
            <?php if (!empty($errores)): ?>
                <div class="errores-lista">
                    <?php foreach($errores as $err): ?>
                        <div>• <?php echo htmlspecialchars($err); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form class="subir-obra-form" id="form-subir-obra" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="profile-main" style="gap:48px;">
                    <!-- Columna Izquierda: Imágenes -->
                    <div class="profile-col-izq" style="display:flex; flex-direction:column; align-items:flex-start;">
                        <label style="margin-bottom:10px;">Imágenes de la obra (mínimo 1, máximo 4):</label>
                        <div class="preview-img-box-main" id="preview-img-box-main">
                            <span style="color:#A3B18C; font-size:48px;">+</span>
                        </div>
                        <div class="imagenes-preview-row" style="display:flex; gap:18px; margin-bottom:0; justify-content:center;">
                            <?php for($i=0; $i<4; $i++): ?>
                                <div class="preview-img-box" id="preview-img-box-<?php echo $i; ?>" style="cursor:pointer;">
                                    <span style="color:#A3B18C; font-size:32px;">+</span>
                                    <input type="file" id="img-<?php echo $i; ?>" name="imagenes[]" accept="image/*" style="display:none;">
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <!-- Columna Derecha: Campos -->
                    <div class="profile-info" style="flex:1.1;">
                        <input type="text" id="titulo" name="titulo" maxlength="100" required placeholder="Nombre de la obra" class="login-form-input subir-obra-titulo" value="<?php echo htmlspecialchars($_POST['titulo'] ?? ''); ?>">
                        <div class="subir-obra-artista-row" style="display:flex; align-items:center; gap:16px; margin-bottom:18px;">
                            <?php if (!empty($usuario['foto']) && file_exists($usuario['foto'])): ?>
                                <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Artista" class="subir-obra-artista-foto">
                            <?php else: ?>
                                <div class="subir-obra-artista-foto" style="background:#B86B4B; display:flex; align-items:center; justify-content:center; color:#FFF5E1; font-size:22px;">?</div>
                            <?php endif; ?>
                            <span class="subir-obra-artista-nombre"> <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']); ?> </span>
                        </div>
                        <textarea id="descripcion" name="descripcion" maxlength="1000" placeholder="Descripción de la obra" class="login-form-input" style="margin-bottom:32px;"><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>
                        <label for="categoria_id">¿A qué categoría pertenece esta obra? <span style="color:#B86B4B;">*</span></label>
                        <select id="categoria_id" name="categoria_id" required class="login-form-input subir-obra-select" >
                            <option value="">Selecciona una categoría</option>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php if(isset($_POST['categoria_id']) && $_POST['categoria_id']==$cat['id']) echo 'selected'; ?>><?php echo htmlspecialchars($cat['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
    // Previsualización y click en miniaturas para subir imagen
    for(let i=0; i<4; i++){
        const input = document.getElementById('img-'+i);
        const preview = document.getElementById('preview-img-box-'+i);
        const mainPreview = document.getElementById('preview-img-box-main');
        preview.addEventListener('click', function(){
            input.click();
        });
        input.addEventListener('change', function(e){
            if(this.files && this.files[0]){
                const reader = new FileReader();
                reader.onload = function(ev){
                    preview.innerHTML = '<img src="'+ev.target.result+'" alt="preview">';
                    preview.appendChild(input);
                    if(i===0){
                        mainPreview.innerHTML = '<img src="'+ev.target.result+'" alt="preview">';
                    }
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.innerHTML = '<span style=\'color:#A3B18C; font-size:32px;\'>+</span>';
                preview.appendChild(input);
                if(i===0){
                    mainPreview.innerHTML = '<span style=\'color:#A3B18C; font-size:48px;\'>+</span>';
                }
            }
        });
    }
    </script>
    <?php include 'footer.php'; ?>
    <script src="js/navbar-hamburguesa.js"></script>
</body>
</html>
