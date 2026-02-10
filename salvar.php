<?php
include '../includes/conn.php'; // Caminho atualizado

// --- Iniciar Transação ---
// Isso garante que se o cadastro do usuário falhar, o da empresa também é desfeito.
$conn->begin_transaction();

try {
    // --- PARTE 1: Salvar os dados da EMPRESA ---
    // Pega os dados da empresa do $_POST
    $empresaNome = $_POST['empresaNome'];
    $tipoDocumento = $_POST['tipoDocumento'];
    $documento = $_POST['documento'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // SQL para inserir na tabela 'empresas'
    $sql_empresa = "INSERT INTO empresas (nome_empresa, tipoDocumento, documento, telefone, endereco) VALUES (?, ?, ?, ?, ?)";
    $stmt_empresa = $conn->prepare($sql_empresa);
    $stmt_empresa->bind_param("sssss", $empresaNome, $tipoDocumento, $documento, $telefone, $endereco);
    $stmt_empresa->execute();

    // --- Pega o ID da empresa que ACABOU de ser criada ---
    $id_nova_empresa = $conn->insert_id;

    // --- PARTE 2: Salvar os dados do USUÁRIO (Admin) ---
    // Pega os dados do admin do $_POST
    $nomeAdmin = $_POST['nomeAdmin'];
    $cpfAdmin = $_POST['cpfadmin'];
    $datanasc = $_POST['datanasc'];
    $emailAdmin = $_POST['emailAdmin'];
    $senha_pura = $_POST['senha'];
    // IMPORTANTE: Verificar se "Confirmar Senha" é igual a "Senha"
    
    // Criptografa a senha
    $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);
    
    // Define a 'role' deste primeiro usuário como 'admin'
    $role = 'admin';

    // SQL para inserir na tabela 'usuarios', ligando-a com o ID da empresa
    $sql_usuario = "INSERT INTO usuarios (nome_completo, cpf, datanasc, email, senha, role, id_empresa) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_usuario = $conn->prepare($sql_usuario);
    $stmt_usuario->bind_param("ssssssi", $nomeAdmin, $cpfAdmin, $datanasc, $emailAdmin, $senha_hash, $role, $id_nova_empresa);
    $stmt_usuario->execute();
    
    // --- Finalização ---
    // Se chegou até aqui sem erros, confirma as duas operações
    $conn->commit();
    
    echo "Empresa e Administrador cadastrados com sucesso!";
    // Redireciona para o login
    header("Location: ../login.php");

} catch (mysqli_sql_exception $exception) {
    // Se qualquer uma das inserções falhar, desfaz tudo
    $conn->rollback();
    
    echo "Erro ao cadastrar: " . $exception->getMessage();
}

$stmt_empresa->close();
$stmt_usuario->close();
$conn->close();

?>