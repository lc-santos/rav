<?php
session_start();
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $assunto = $_POST['assunto'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';

    if (empty($nome) || empty($email) || empty($assunto) || empty($mensagem)) {
        header("Location: contato.php?status=error&msg=" . urlencode("Todos os campos obrigatórios devem ser preenchidos."));
        exit;
    }

    try {
        // Tenta criar a tabela dinamicamente caso o usuario ainda nÃ£o tenha importado o rav.sql atualizado
        $pdo->exec("CREATE TABLE IF NOT EXISTS fale_conosco (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            telefone VARCHAR(20) NULL,
            assunto VARCHAR(150) NOT NULL,
            mensagem TEXT NOT NULL,
            data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
            status ENUM('Pendente', 'Lido', 'Respondido') DEFAULT 'Pendente'
        )");

        $sql = "INSERT INTO fale_conosco (nome, email, telefone, assunto, mensagem) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $telefone, $assunto, $mensagem]);

        header("Location: contato.php?status=success");
        exit;
    } catch (PDOException $e) {
        header("Location: contato.php?status=error&msg=" . urlencode("Erro ao enviar mensagem: " . $e->getMessage()));
        exit;
    }
} else {
    header("Location: contato.php");
    exit;
}
?>
