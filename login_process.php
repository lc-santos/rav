<?php
/**
 * SISTEMA RAV (REGISTRO DE ACESSO DE VEÍCULOS)
 * login_process.php - Processamento de autenticação unificada por Unidade (Etec)
 * Desenvolvido nativamente em PHP/PDO focado na banca de TCC
 */

// Inicia a sessão para armazenamento dos dados do login
session_start();

// Importa a conexão com o banco de dados ($pdo)
require_once "conn.php";

// Verifica se os dados foram enviados via método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Captura e limpa os campos enviados pelo formulário
    $codigo_identificador = isset($_POST["codigo_identificador"]) ? trim($_POST["codigo_identificador"]) : "";
    $senha = isset($_POST["senha"]) ? trim($_POST["senha"]) : "";

    // Validação básica dos campos obrigatórios
    if (empty($codigo_identificador) || empty($senha)) {
        $_SESSION['login_error'] = "Por favor, preencha todos os campos.";
        header("Location: login.php");
        exit();
    }

    try {
        // Busca a unidade (Etec) pelo código identificador único na tabela 'unidades'
        $sql = "SELECT id, empresaNome, codigo_identificador, senha_portaria, senha_admin FROM unidades WHERE codigo_identificador = :codigo LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':codigo' => $codigo_identificador]);
        $etec = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se a Etec foi encontrada no banco
        if ($etec) {
            
            // 1. Verifica se a senha corresponde ao perfil ADMINISTRADOR
            if (password_verify($senha, $etec['senha_admin'])) {
                // Registra os dados da Etec e do nível administrativo na sessão
                $_SESSION['etec_id'] = $etec['id'];
                $_SESSION['etec_nome'] = $etec['empresaNome'];
                $_SESSION['acesso'] = 'admin';
                
                // Redireciona para a área administrativa do sistema
                header("Location: painel-admin.php");
                exit();
            } 
            // 2. Verifica se a senha corresponde ao perfil PORTARIA
            elseif (password_verify($senha, $etec['senha_portaria'])) {
                // Registra os dados da Etec e do nível da portaria na sessão
                $_SESSION['etec_id'] = $etec['id'];
                $_SESSION['etec_nome'] = $etec['empresaNome'];
                $_SESSION['acesso'] = 'portaria';
                
                // Redireciona para o painel operacional de portaria / estacionamento
                header("Location: estacionamento.php");
                exit();
            } 
            // Caso as senhas não coincidam
            else {
                $_SESSION['login_error'] = "Senha incorreta para esta unidade.";
                header("Location: login.php");
                exit();
            }
        } 
        // Caso a Etec com o código fornecido não seja encontrada
        else {
            $_SESSION['login_error'] = "Código identificador de unidade não encontrado.";
            header("Location: login.php");
            exit();
        }

    } catch (PDOException $e) {
        // Exibe erro genérico ou registra em log em ambiente de produção
        $_SESSION['login_error'] = "Erro interno no servidor: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }
} else {
    // Se tentarem acessar este arquivo diretamente por GET, redireciona de volta
    header("Location: login.php");
    exit();
}
