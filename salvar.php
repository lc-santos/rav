<?php
/**
 * SISTEMA RAV (REGISTRO DE ACESSO DE VEÍCULOS)
 * salvar.php — Processamento e inserção do cadastro de novas Unidades
 * Versão 2.0: adaptado para a nova arquitetura onde credenciais
 * de admin e portaria ficam em `usuarios` (role-based), não em `unidades`.
 */

require_once 'conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastro.php");
    exit();
}

// ── 1. CAPTURA DOS CAMPOS ENVIADOS VIA POST ──────────────────────────────

// Dados da Unidade (Institucionais)
$empresaNome          = trim($_POST['empresaNome']          ?? '');
$tipoDocumento        = trim($_POST['tipoDocumento']        ?? '');
$documento            = trim($_POST['documento']            ?? '');
$telefone             = trim($_POST['telefone']             ?? '');
$codigo_identificador = trim($_POST['codigo_identificador'] ?? '');

// Endereço
$cep         = trim($_POST['cep']         ?? '');
$logradouro  = trim($_POST['logradouro']  ?? '');
$numero      = trim($_POST['numero']      ?? '');
$complemento = trim($_POST['complemento'] ?? '');
$bairro      = trim($_POST['bairro']      ?? '');
$cidade      = trim($_POST['cidade']      ?? '');
$uf          = trim($_POST['uf']          ?? '');

// Dados do Gestor (agora vai para `usuarios` com role='admin')
$nome_completo = trim($_POST['nome_completo'] ?? '');
$email         = trim($_POST['email']         ?? '');
$cpf           = trim($_POST['cpf']           ?? '');
$datanasc      = trim($_POST['datanasc']      ?? '');

// Credenciais
$senha_admin    = $_POST['senha_admin']    ?? '';
$senha_portaria = $_POST['senha_portaria'] ?? '';

// ── 2. VALIDAÇÃO DE CAMPOS OBRIGATÓRIOS ──────────────────────────────────
if (
    empty($empresaNome) || empty($tipoDocumento) || empty($documento) || empty($telefone) ||
    empty($cep) || empty($logradouro) || empty($numero) || empty($bairro) || empty($cidade) || empty($uf) ||
    empty($nome_completo) || empty($email) || empty($cpf) || empty($datanasc) ||
    empty($codigo_identificador) || empty($senha_admin) || empty($senha_portaria)
) {
    $_SESSION['login_error'] = "Erro: Todos os campos do cadastro são obrigatórios (exceto complemento).";
    header("Location: cadastro.php");
    exit();
}

// ── 3. LIMPEZA DE MÁSCARAS ────────────────────────────────────────────────
$documento_limpo = preg_replace('/\D/', '', $documento);
$telefone_limpo  = preg_replace('/\D/', '', $telefone);
$cpf_limpo       = preg_replace('/\D/', '', $cpf);
$cep_limpo       = preg_replace('/\D/', '', $cep);
$cep_formatado   = (strlen($cep_limpo) === 8)
    ? substr($cep_limpo, 0, 5) . '-' . substr($cep_limpo, 5, 3)
    : $cep;

// ── 4. HASH DAS SENHAS ────────────────────────────────────────────────────
$hash_admin    = password_hash($senha_admin,    PASSWORD_DEFAULT);
$hash_portaria = password_hash($senha_portaria, PASSWORD_DEFAULT);

try {
    // ── 5. VERIFICAÇÕES DE DUPLICIDADE ────────────────────────────────────

    // Código identificador único na Etec
    $stmt = $pdo->prepare("SELECT id FROM unidades WHERE codigo_identificador = :codigo LIMIT 1");
    $stmt->execute([':codigo' => $codigo_identificador]);
    if ($stmt->fetch()) {
        $_SESSION['login_error'] = "O Código Identificador '{$codigo_identificador}' já está cadastrado por outra unidade.";
        header("Location: cadastro.php");
        exit();
    }

    // Documento único
    $stmt = $pdo->prepare("SELECT id FROM unidades WHERE documento = :documento LIMIT 1");
    $stmt->execute([':documento' => $documento_limpo]);
    if ($stmt->fetch()) {
        $_SESSION['login_error'] = "O documento (CNPJ/INEP) '{$documento}' já está registrado no sistema.";
        header("Location: cadastro.php");
        exit();
    }

    // E-mail do gestor único
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        $_SESSION['login_error'] = "O e-mail '{$email}' já está cadastrado no sistema.";
        header("Location: cadastro.php");
        exit();
    }

    // ── 6. INSERÇÃO EM TRANSAÇÃO ──────────────────────────────────────────
    $pdo->beginTransaction();

    // 6.1 — Insere a Unidade (apenas dados institucionais)
    $stmt = $pdo->prepare(
        "INSERT INTO unidades (empresaNome, tipoDocumento, documento, telefone, codigo_identificador)
         VALUES (:empresaNome, :tipoDocumento, :documento, :telefone, :codigo_identificador)"
    );
    $stmt->execute([
        ':empresaNome'          => $empresaNome,
        ':tipoDocumento'        => $tipoDocumento,
        ':documento'            => $documento_limpo,
        ':telefone'             => $telefone_limpo,
        ':codigo_identificador' => $codigo_identificador,
    ]);
    $id_unidade = $pdo->lastInsertId();

    // 6.2 — Insere o Endereço vinculado à Unidade
    $stmt = $pdo->prepare(
        "INSERT INTO enderecos (id_unidade, cep, logradouro, numero, complemento, bairro, cidade, uf)
         VALUES (:id_unidade, :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :uf)"
    );
    $stmt->execute([
        ':id_unidade'  => $id_unidade,
        ':cep'         => $cep_formatado,
        ':logradouro'  => $logradouro,
        ':numero'      => $numero,
        ':complemento' => $complemento,
        ':bairro'      => $bairro,
        ':cidade'      => $cidade,
        ':uf'          => $uf,
    ]);

    // 6.3 — Insere o Usuário ADMIN (Gestor Principal)
    //        codigo_acesso gerado como ADM- + id_unidade para garantir unicidade
    $stmt = $pdo->prepare(
        "INSERT INTO usuarios (id_unidade, codigo_acesso, nome_completo, email, senha, cpf, role)
         VALUES (:id_unidade, :codigo_acesso, :nome_completo, :email, :senha, :cpf, 'admin')"
    );
    $stmt->execute([
        ':id_unidade'   => $id_unidade,
        ':codigo_acesso'=> 'ADM-' . str_pad($id_unidade, 4, '0', STR_PAD_LEFT),
        ':nome_completo'=> $nome_completo,
        ':email'        => $email,
        ':senha'        => $hash_admin,
        ':cpf'          => $cpf_limpo,
    ]);

    // 6.4 — Insere o Usuário PORTARIA (genérico, pode ser renomeado depois)
    //        E-mail gerado como portaria@<codigo>.rav para não colidir com o admin
    $email_portaria = 'portaria@' . strtolower(preg_replace('/\s+/', '', $codigo_identificador)) . '.rav';
    $stmt = $pdo->prepare(
        "INSERT INTO usuarios (id_unidade, codigo_acesso, nome_completo, email, senha, cpf, role)
         VALUES (:id_unidade, :codigo_acesso, :nome_completo, :email, :senha, :cpf, 'portaria')"
    );
    $stmt->execute([
        ':id_unidade'   => $id_unidade,
        ':codigo_acesso'=> 'PORT-' . str_pad($id_unidade, 4, '0', STR_PAD_LEFT),
        ':nome_completo'=> 'Operador Portaria — ' . $empresaNome,
        ':email'        => $email_portaria,
        ':senha'        => $hash_portaria,
        ':cpf'          => '00000000000', // placeholder, pode ser editado depois
    ]);

    $pdo->commit();

    // ── 7. REDIRECIONAMENTO COM SUCESSO ───────────────────────────────────
    header("Location: login.php?sucesso=" . urlencode("Unidade cadastrada com sucesso! Acesse utilizando o código identificador."));
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['login_error'] = "Erro de Banco de Dados: " . $e->getMessage();
    header("Location: cadastro.php");
    exit();
}