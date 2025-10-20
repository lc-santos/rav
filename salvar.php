<?php
include "conn.php";

$empresaNome = $_POST['empresaNome'];
$tipoDocumento = $_POST['tipoDocumento'];
$documento = $_POST['documento'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$nomeAdmin = $_POST['nomeAdmin'];
$cpfAdmin = $_POST['cpfadmin']; 
$datanasc = $_POST['datanasc'];
$emailAdmin = $_POST['emailAdmin'];
$senha_pura = $_POST['senha']; // Método para melhorar a segurança da senha


// Criptografar a senha antes de salvar no banco
// A função password_hash cria uma "impressão digital" segura da senha.

$senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);


// !! Falta verificar se já existe um cadastro com este documento ou cpfadmin 


$sql_insert = "INSERT INTO dados_empresa(empresaNome, tipoDocumento, documento, telefone, endereco, nomeAdmin, cpfadmin, datanasc, emailAdmin, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql_insert);

if ($stmt === false) {
    die("Erro ao preparar a consulta de inserção: " . $conn->error);
}


$stmt->bind_param("ssssssssss", 
    $empresaNome, 
    $tipoDocumento, 
    $documento, 
    $telefone, 
    $endereco, 
    $nomeAdmin, 
    $cpfAdmin, 
    $datanasc, 
    $emailAdmin, 
    $senha_hash // Usamos a senha criptografada!
);

if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
    echo "<a href='listar.php'>Listar</a>"; 
    echo "<a href='index.php'>Voltar</a>";
} else {
    echo "Erro ao salvar: " . $stmt->error;
    echo "<a href='listar.php'>Listar</a>";
    echo "<a href='index.php'>Voltar</a>";
}

$stmt->close();
$conn->close();

?>