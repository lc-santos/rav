<?php
require_once 'conn.php';
$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("UPDATE registros_acesso SET status = 'Saiu', data_saida = NOW() WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: index.php");