<?php
require_once 'conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // --- PARTE 1: Salvar os dados da UNIDADE (Empresa) ---
        $empresaNome = trim($_POST['empresaNome'] ?? '');
        $tipoDocumento = trim($_POST['tipoDocumento'] ?? '');
        $documento = preg_replace('/\D/', '', $_POST['documento'] ?? ''); // Limpar CNPJ/INEP
        $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? ''); // Limpar Telefone
        $endereco = trim($_POST['endereco'] ?? '');

        $sql_empresa = "INSERT INTO empresas (nome_empresa, tipoDocumento, documento, telefone, endereco) VALUES (?, ?, ?, ?, ?)";
        $stmt_empresa = $pdo->prepare($sql_empresa);
        $stmt_empresa->execute([$empresaNome, $tipoDocumento, $documento, $telefone, $endereco]);
        
        $id_nova_empresa = $pdo->lastInsertId();

        // --- PARTE 2: Salvar os dados do USUÁRIO (Admin/Gestor) ---
        $nomeAdmin = trim($_POST['nome_completo'] ?? '');
        $cpfAdmin = preg_replace('/\D/', '', $_POST['cpf'] ?? ''); // Limpar CPF
        $datanasc = $_POST['datanasc'] ?? '';
        $emailAdmin = trim($_POST['email'] ?? '');
        $senha_pura = $_POST['senha'] ?? '';
        
        // Criptografa a senha
        $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);
        
        // Define a 'role' deste primeiro usuário como 'admin'
        $role = 'admin';

        // SQL para inserir na tabela 'usuarios', ligando-a com o ID da empresa
        $sql_usuario = "INSERT INTO usuarios (nome_completo, cpf, datanasc, email, senha, role, id_empresa, contato_valor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_usuario = $pdo->prepare($sql_usuario);
        $stmt_usuario->execute([$nomeAdmin, $cpfAdmin, $datanasc, $emailAdmin, $senha_hash, $role, $id_nova_empresa, $telefone]);
        
        $pdo->commit();
        
        // Redireciona para o login com sucesso
        header("Location: login.php?sucesso=Unidade cadastrada com sucesso! Faça login para continuar.");
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        die("Erro ao cadastrar unidade: " . $e->getMessage());
    }
} else {
    header("Location: cadastro.php");
    exit;
}