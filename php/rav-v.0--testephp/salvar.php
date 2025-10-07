<?php
include "conn.php";

$id = ['id'];
$empresaNome = $_POST['empresaNome'];
$tipoDocumento = $_POST['tipoDocumento'];
$documento = $_POST['documento'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$nomeAdmin = $_POST['nomeAdmin'];
$cpfadmin = $_POST['cpfadmin'];
$datanasc = $_POST['datanasc'];
$emailAdmin = $_POST['emailAdmin'];
$senha2 = $_POST['senha'];

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$check = $conn->prepare("SELECT * FROM dados_empresa WHERE documento = ? OR cpfadmin = ?");
$check->bind_param("sss", $documento, $cpfadmin, $senha_hash);
$check->execute();
$result = $check->get_result();



if ($result->num_rows > 0) {
    echo "<a href='listar.php'>Listar</a>";
    die("Já existe um cadastro com esse documento ou cpfadmin.");
    
}

// Salvar no banco
$stmt = $conn->prepare("INSERT INTO dados_empresa(empresaNome, tipoDocumento, documento, telefone, endereco, nomeAdmin, cpfadmin, datanasc, emailAdmin, senha ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $empresaNome, $tipoDocumento, $documento, $telefone, $endereco, $nomeAdmin, $cpfadmin, $datanasc, $emailAdmin, $senha1);

if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
    echo "<a href='listar.php'>Listar</a>";
} else {
    echo "Erro ao salvar: " . $conn->error;
    echo "<a href='listar.php'>Listar</a>";
}
