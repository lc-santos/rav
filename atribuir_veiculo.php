<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conn.php';

// Recebe os dados para vincular um novo carro a um usuário já cadastrado
$id_usuario = $_POST['id_usuario'];
$placa      = strtoupper(trim($_POST['placa']));
$modelo     = trim($_POST['modelo']);

$etec_id    = $_SESSION['etec_id'] ?? $_SESSION['empresa_id'] ?? 1;

$stmt = $pdo->prepare("INSERT INTO veiculos (id_usuario, placa, modelo, id_empresa, id_unidade) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$id_usuario, $placa, $modelo, $etec_id, $etec_id]);

header("Location: gerenciar_cadastros.php?veiculo_adicionado=1");