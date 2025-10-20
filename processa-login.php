<?php
session_start(); // 1. Iniciar a sessão

include 'conn.php';


// Receber os dados do formulário de login
// Usamos filter_input para uma camada extra de segurança
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = $_POST['senha']; // A senha não tem um filtro específico, pegamos diretamente.


// Verificar se o email é válido
if (!$email) {
    echo "Formato de e-mail inválido!";
    // Redirecionar de volta com erro, se desejar
    // header('Location: login.html?status=erro_email');
    exit();
}

// Preparar e Executar a Consulta SQL
// Vamos buscar um usuário cujo email corresponda ao digitado.
// Usar prepared statements é ESSENCIAL para a segurança contra SQL Injection.

$sql = "SELECT id, nomeAdmin, senha FROM dados_empresa WHERE emailAdmin = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro ao preparar a consulta: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar se o usuário foi encontrado

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc(); // Se encontrou um usuário, pegamos os dados dele


    // VERIFICAR A SENHA
    // O ideal é que esteja "hasheada" com password_hash()
    
    if (password_verify($senha, $usuario['senha'])) { // Se a senha no banco bate com a senha digitada...
        
        // Login bem-sucedido!
        
        // 7. Salvar informações do usuário na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nomeAdmin'];
        
        // 8. Redirecionar para a página restrita (dashboard)
        header("Location: dashboard.php");
        exit();

    } else {
        // Senha incorreta
        echo "Login falhou: E-mail ou senha inválidos.";
        // header('Location: login.html?status=erro_login');
    }

} else {
    // Usuário não encontrado
    echo "Login falhou: E-mail ou senha inválidos.";
    // header('Location: login.html?status=erro_login');
}

$stmt->close();
$conn->close();
?>