<?php
require_once 'db.php';

function obtenerUsuarioPorId($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
