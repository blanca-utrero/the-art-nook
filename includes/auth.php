<?php
session_start();

function usuarioAutenticado() {
    return isset($_SESSION['user_id']);
}

function redirigirSiNoAutenticado() {
    if (!usuarioAutenticado()) {
        header("Location: login.php");
        exit;
    }
}