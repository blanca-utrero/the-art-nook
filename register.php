<?php
require_once 'includes/db.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contraseña = $_POST['contraseña'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';
    $terminos = isset($_POST['terminos']);

    if (empty($nombre) || empty($apellidos) || empty($email) || empty($contraseña) || empty($confirmar)) {
        $mensaje = 'Por favor, rellena todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El email no es válido.';
    } elseif ($contraseña !== $confirmar) {
        $mensaje = 'Las contraseñas no coinciden.';
    } elseif (!$terminos) {
        $mensaje = 'Debes aceptar los Términos y Condiciones y la Política de Privacidad.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $mensaje = 'El email ya está registrado.';
        } else {
            $hash_contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, apellidos, email, contraseña) VALUES (?, ?, ?, ?)');
            if ($stmt->execute([$nombre, $apellidos, $email, $hash_contraseña])) {
                // Login automático tras registro
                $usuario_id = $pdo->lastInsertId();
                session_start();
                $_SESSION['usuario_id'] = $usuario_id;
                $_SESSION['usuario_nombre'] = $nombre;
                header('Location: perfil.php');
                exit;
            } else {
                $mensaje = 'Error al registrar. Intenta de nuevo.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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
        <div class="login-content">
            <div class="login-header">
                <h1 class="hero-title">Construye tu nook</h1>
                <div class="login-buttons">
                    <button class="hero-btn hero-btn-outline" onclick="window.location.href='login.php'">Iniciar Sesión</button>
                    <button class="hero-btn">Registrarme</button>
                </div>
            </div>
            <?php if ($mensaje): ?>
                <p class="mensaje-azul"><?php echo htmlspecialchars($mensaje); ?></p>
            <?php endif; ?>
            <div class="login-form-box register-form-box">
                <form method="post" class="login-form">
                    <div style="display: flex; gap: 12px;">
                        <div style="flex:1; display:flex; flex-direction:column;">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" placeholder="nombre" required value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
                        </div>
                        <div style="flex:1; display:flex; flex-direction:column;">
                            <label for="apellidos">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" placeholder="apellido" required value="<?php echo htmlspecialchars($_POST['apellidos'] ?? ''); ?>">
                        </div>
                    </div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="email@ejemplo.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" placeholder="contraseña" required>
                    <label for="confirmar">Confirmar Contraseña:</label>
                    <input type="password" id="confirmar" name="confirmar" placeholder="repetir contraseña" required>
                    <div style="display: flex; align-items: center; margin: 8px 0 0 0;">
                        <input type="checkbox" id="terminos" name="terminos" required style="margin-right: 8px;">
                        <label for="terminos" style="font-size: 15px; color: #4D5B6A; font-family: 'Lato', sans-serif;">Acepto los Términos y Condiciones y la Política de Privacidad</label>
                    </div>
                    <button type="submit" class="hero-btn">Entrar</button>
                </form>
                <div class="login-img" style="background-image: url('uploads/login-register/register-ilustracion.png'); height: 670px;"></div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="js/navbar-hamburguesa.js"></script>
</body>
</html>