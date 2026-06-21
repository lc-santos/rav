<?php
/**
 * SISTEMA RAV (REGISTRO DE ACESSO DE VEÍCULOS)
 * login_process.php — Processamento de autenticação unificada por Unidade (Etec)
 * Versão 2.0: credenciais migradas de `unidades` para `usuarios` (role-based).
 */

session_start();
require_once "conn.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

// Captura e sanitiza os campos do formulário
$codigo_identificador = isset($_POST["codigo_identificador"]) ? trim($_POST["codigo_identificador"]) : "";
$senha                = isset($_POST["senha"])                ? trim($_POST["senha"])                : "";

// Validação básica
if (empty($codigo_identificador) || empty($senha)) {
    $_SESSION['login_error'] = "Por favor, preencha todos os campos.";
    header("Location: login.php");
    exit();
}

try {
    // ── Passo 1: Localiza a Etec pelo código público ──────────────────────
    $stmtEtec = $pdo->prepare(
        "SELECT id, empresaNome FROM unidades WHERE codigo_identificador = :codigo LIMIT 1"
    );
    $stmtEtec->execute([':codigo' => $codigo_identificador]);
    $etec = $stmtEtec->fetch(PDO::FETCH_ASSOC);

    if (!$etec) {
        $_SESSION['login_error'] = "Código identificador de unidade não encontrado.";
        header("Location: login.php");
        exit();
    }

    // ── Passo 2: Busca os usuários com perfil de acesso vinculados a esta Etec ──
    // Apenas roles que têm permissão de login via este formulário institucional.
    $stmtUser = $pdo->prepare(
        "SELECT id, nome_completo, email, senha, role
           FROM usuarios
          WHERE id_unidade = :id_unidade
            AND role IN ('admin', 'portaria')
          ORDER BY FIELD(role, 'admin', 'portaria')"
    );
    $stmtUser->execute([':id_unidade' => $etec['id']]);
    $operadores = $stmtUser->fetchAll(PDO::FETCH_ASSOC);

    // ── Passo 3: Verifica a senha contra cada operador encontrado ─────────
    $autenticado = null;
    foreach ($operadores as $op) {
        if (password_verify($senha, $op['senha'])) {
            $autenticado = $op;
            break;
        }
    }

    if (!$autenticado) {
        $_SESSION['login_error'] = "Senha incorreta para esta unidade.";
        header("Location: login.php");
        exit();
    }

    // ── Passo 4: Registra a sessão e redireciona conforme o perfil ────────
    $_SESSION['etec_id']    = $etec['id'];
    $_SESSION['etec_nome']  = $etec['empresaNome'];
    $_SESSION['usuario_id'] = $autenticado['id'];
    $_SESSION['acesso']     = $autenticado['role'];  // 'admin' | 'portaria'

    if ($autenticado['role'] === 'admin') {
        header("Location: painel-admin.php");
    } else {
        header("Location: estacionamento.php");
    }
    exit();

} catch (PDOException $e) {
    $_SESSION['login_error'] = "Erro interno no servidor: " . $e->getMessage();
    header("Location: login.php");
    exit();
}
