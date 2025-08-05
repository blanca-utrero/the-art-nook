<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
$usuario_logueado = isset($_SESSION['usuario_id']);
?>
<nav class="navbar">
    <div class="navbar-logo">
        <img src="uploads/Logo/Logo The Art Nook.png" alt="Logo The Art Nook" width="200" height="200">
    </div>
    <ul class="navbar-links">
        <li><a href="home.php" class="<?php echo ($current == 'home.php') ? 'active' : ''; ?>">Inicio</a></li>
        <li><a href="galeria.php" class="<?php echo ($current == 'galeria.php') ? 'active' : ''; ?>">Galería</a></li>
        <li><a href="artistas.php" class="<?php echo ($current == 'artistas.php') ? 'active' : ''; ?>">Artistas</a></li>
        <li><a href="categorias.php" class="<?php echo ($current == 'categorias.php') ? 'active' : ''; ?>">Categorías</a></li>
    </ul>
    <div class="navbar-icons">
        <span class="icon-user">
            <a href="<?php echo $usuario_logueado ? 'perfil.php' : 'login.php'; ?>">
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="15" cy="11" r="6" stroke="#FFF5E1" stroke-width="2" fill="none"/>
                  <path d="M5 27c0-5.523 4.477-10 10-10s10 4.477 10 10" stroke="#FFF5E1" stroke-width="2" fill="none"/>
                </svg>
            </a>
        </span>
        <?php if ($usuario_logueado): ?>
        <span class="icon-logout">
            <a href="logout.php" title="Cerrar sesión">
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 21V23C17 24.1046 16.1046 25 15 25H7C5.89543 25 5 24.1046 5 23V5C5 3.89543 5.89543 3 7 3H15C16.1046 3 17 3.89543 17 5V7" stroke="#FFF5E1" stroke-width="2"/>
                    <path d="M11 14H25M25 14L21 10M25 14L21 18" stroke="#FFF5E1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </span>
        <?php endif; ?>
        <button class="hamburger" aria-label="Abrir menú" aria-expanded="false" aria-controls="navbar-links">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav> 