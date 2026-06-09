<?php
/**
 * SISTEMA RAV (REGISTRO DE ACESSO DE VEÍCULOS)
 * salvar.php - Processamento e inserção do cadastro de novas Unidades
 * Suporta o novo formulário de 3 colunas (Institucional, Gestor e Credenciais)
 */

require_once 'conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- 1. CAPTURA DOS CAMPOS ENVIADOS VIA POST ---
    
    // Coluna 1: Dados Institucionais Legados
    $empresaNome   = isset($_POST['empresaNome']) ? trim($_POST['empresaNome']) : '';
    $tipoDocumento = isset($_POST['tipoDocumento']) ? trim($_POST['tipoDocumento']) : '';
    $documento     = isset($_POST['documento']) ? trim($_POST['documento']) : '';
    $telefone      = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';

    // Novos Campos de Endereço Separados
    $cep           = isset($_POST['cep']) ? trim($_POST['cep']) : '';
    $logradouro    = isset($_POST['logradouro']) ? trim($_POST['logradouro']) : '';
    $numero        = isset($_POST['numero']) ? trim($_POST['numero']) : '';
    $complemento   = isset($_POST['complemento']) ? trim($_POST['complemento']) : '';
    $bairro        = isset($_POST['bairro']) ? trim($_POST['bairro']) : '';
    $cidade        = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
    $uf            = isset($_POST['uf']) ? trim($_POST['uf']) : '';

    // Coluna 2: Dados do Gestor Principal
    $nome_completo = isset($_POST['nome_completo']) ? trim($_POST['nome_completo']) : '';
    $email         = isset($_POST['email']) ? trim($_POST['email']) : '';
    $cpf           = isset($_POST['cpf']) ? trim($_POST['cpf']) : '';
    $datanasc      = isset($_POST['datanasc']) ? trim($_POST['datanasc']) : '';

    // Coluna 3: Novas Credenciais Simplificadas
    $codigo_identificador = isset($_POST['codigo_identificador']) ? trim($_POST['codigo_identificador']) : '';
    $senha_admin          = isset($_POST['senha_admin']) ? $_POST['senha_admin'] : '';
    $senha_portaria       = isset($_POST['senha_portaria']) ? $_POST['senha_portaria'] : '';

    // --- 2. VALIDAÇÃO DE CAMPOS OBRIGATÓRIOS ---
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

    // --- 3. LIMPEZA DE MÁSCARAS VIA REGEX (Remover pontos, traços e barras) ---
    $documento_limpo = preg_replace('/\D/', '', $documento);
    $telefone_limpo  = preg_replace('/\D/', '', $telefone);
    $cpf_limpo       = preg_replace('/\D/', '', $cpf);
    
    // Formata ou limpa o CEP
    $cep_limpo       = preg_replace('/\D/', '', $cep);
    $cep_formatado   = (strlen($cep_limpo) === 8) ? substr($cep_limpo, 0, 5) . '-' . substr($cep_limpo, 5, 3) : $cep;

    // --- 4. CRIPTOGRAFIA INDIVIDUAL DE SENHAS ---
    $hash_admin    = password_hash($senha_admin, PASSWORD_DEFAULT);
    $hash_portaria = password_hash($senha_portaria, PASSWORD_DEFAULT);

    try {
        // --- 5. VERIFICAÇÃO DE DUPLICIDADE (Aviso de Erro Amigável para Chaves Únicas) ---
        
        // Verifica duplicidade do código identificador
        $sql_check_codigo = "SELECT id FROM unidades WHERE codigo_identificador = :codigo LIMIT 1";
        $stmt_check_codigo = $pdo->prepare($sql_check_codigo);
        $stmt_check_codigo->execute([':codigo' => $codigo_identificador]);
        
        if ($stmt_check_codigo->fetch()) {
            $_SESSION['login_error'] = "O Código Identificador '{$codigo_identificador}' já está cadastrado por outra unidade.";
            header("Location: cadastro.php");
            exit();
        }

        // Verifica duplicidade do número do documento
        $sql_check_doc = "SELECT id FROM unidades WHERE documento = :documento LIMIT 1";
        $stmt_check_doc = $pdo->prepare($sql_check_doc);
        $stmt_check_doc->execute([':documento' => $documento_limpo]);
        
        if ($stmt_check_doc->fetch()) {
            $_SESSION['login_error'] = "O documento (CNPJ/INEP) '{$documento}' já está registrado no sistema.";
            header("Location: cadastro.php");
            exit();
        }

        // --- 6. EXECUÇÃO DO INSERT COM PREPARED STATEMENTS EM TRANSAÇÃO ---
        $pdo->beginTransaction();

        $sql_insert_unidade = "INSERT INTO unidades (
                            empresaNome, tipoDocumento, documento, telefone, 
                            nome_completo, email, cpf, datanasc, 
                            codigo_identificador, senha_admin, senha_portaria
                       ) VALUES (
                            :empresaNome, :tipoDocumento, :documento, :telefone, 
                            :nome_completo, :email, :cpf, :datanasc, 
                            :codigo_identificador, :senha_admin, :senha_portaria
                       )";

        $stmt_insert_unidade = $pdo->prepare($sql_insert_unidade);
        $stmt_insert_unidade->execute([
            ':empresaNome'          => $empresaNome,
            ':tipoDocumento'        => $tipoDocumento,
            ':documento'            => $documento_limpo,
            ':telefone'             => $telefone_limpo,
            ':nome_completo'        => $nome_completo,
            ':email'                => $email,
            ':cpf'                  => $cpf_limpo,
            ':datanasc'             => $datanasc,
            ':codigo_identificador' => $codigo_identificador,
            ':senha_admin'          => $hash_admin,
            ':senha_portaria'       => $hash_portaria
        ]);

        $id_unidade = $pdo->lastInsertId();

        $sql_insert_endereco = "INSERT INTO enderecos (
                            id_unidade, cep, logradouro, numero, complemento, bairro, cidade, uf
                        ) VALUES (
                            :id_unidade, :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :uf
                        )";

        $stmt_insert_endereco = $pdo->prepare($sql_insert_endereco);
        $stmt_insert_endereco->execute([
            ':id_unidade'   => $id_unidade,
            ':cep'          => $cep_formatado,
            ':logradouro'   => $logradouro,
            ':numero'       => $numero,
            ':complemento'  => $complemento,
            ':bairro'       => $bairro,
            ':cidade'       => $cidade,
            ':uf'           => $uf
        ]);

        $pdo->commit();

        // --- 7. REDIRECIONAMENTO COM SUCESSO ---
        header("Location: login.php?sucesso=" . urlencode("Unidade cadastrada com sucesso! Acesse utilizando o código identificador."));
        exit();

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        // Trata erros de banco de dados e retorna uma mensagem amigável
        $_SESSION['login_error'] = "Erro de Banco de Dados: " . $e->getMessage();
        header("Location: cadastro.php");
        exit();
    }
} else {
    // Redireciona caso tentem acesso direto
    header("Location: cadastro.php");
    exit();
}