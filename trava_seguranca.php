<?php
/**
 * SISTEMA RAV (REGISTRO DE ACESSO DE VEÍCULOS)
 * trava_seguranca.php - Controle de acesso a páginas administrativas
 * Este script deve ser incluído no topo de arquivos como 'painel-admin.php'
 */

// Inicia a sessão se já não tiver sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verifica se a unidade (Etec) está devidamente autenticada
if (!isset($_SESSION['etec_id'])) {
    // Caso não esteja logado, destrói a sessão e manda para a tela de login
    session_unset();
    session_destroy();
    header("Location: login.php?error=nao_autenticado");
    exit();
}

// 2. Trava de segurança: Garante que apenas usuários com nível 'admin' ou 'portaria' acessem estas páginas
if (!in_array($_SESSION['acesso'], ['admin', 'portaria'])) {
    header("Location: login.php?error=acesso_negado");
    exit();
}

// Se passou nas validações, o script administrativo continuará sua execução normal...
?>
