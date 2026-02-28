<?php
require_once 'conn.php';
// Recebe os dados para vincular um novo carro a um usuário já cadastrado
$id_usuario = $_POST['id_usuario'];
$placa      = strtoupper(trim($_POST['placa']));
$modelo     = trim($_POST['modelo']);

$stmt = $pdo->prepare("INSERT INTO veiculos (id_usuario, placa, modelo, id_empresa) VALUES (?, ?, ?, 1)");
$stmt->execute([$id_usuario, $placa, $modelo]);

header("Location: index.php?veiculo_adicionado=1");