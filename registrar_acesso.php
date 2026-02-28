<?php
require_once 'conn.php';
session_start();

/*
|--------------------------------------------------------------------------
| SIMULAÇÃO TEMPORÁRIA (REMOVER QUANDO TIVER LOGIN)
|--------------------------------------------------------------------------
| Em produção isso vem da sessão após login
*/
$_SESSION['usuario_id']  = 1; // operador logado
$_SESSION['empresa_id']  = 1; // empresa logada
$_SESSION['unidade_id']  = null; // opcional

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // =========================
    // DADOS DO FORM
    // =========================
    $placa         = strtoupper(trim($_POST['placa']));
    $tipo_acesso   = $_POST['tipo_acesso'];
    $nome_condutor = trim($_POST['nome_condutor']);
    $contato_tipo  = $_POST['contato_tipo'];
    $contato_valor = trim($_POST['contato_valor']);
    $observacao    = $_POST['observacao'] ?? null;

    $empresa_id  = $_SESSION['empresa_id'];
    $operador_id = $_SESSION['usuario_id'];
    $unidade_id  = $_SESSION['unidade_id'];

    if (!$placa || !$nome_condutor) {
        throw new Exception('Dados obrigatórios não informados.');
    }

    // =========================
    // 1️⃣ VEÍCULO EXISTE?
    // =========================
    $stmt = $pdo->prepare("
        SELECT id FROM veiculos 
        WHERE placa = :placa AND id_empresa = :empresa
        LIMIT 1
    ");
    $stmt->execute([
        ':placa'   => $placa,
        ':empresa' => $empresa_id
    ]);

    $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

    // =========================
    // 2️⃣ SE NÃO EXISTIR, CRIA
    // =========================
    if (!$veiculo) {
        $stmt = $pdo->prepare("
            INSERT INTO veiculos (placa, tipo, id_empresa)
            VALUES (:placa, 'visitante', :empresa)
        ");
        $stmt->execute([
            ':placa'   => $placa,
            ':empresa' => $empresa_id
        ]);

        $veiculo_id = $pdo->lastInsertId();
    } else {
        $veiculo_id = $veiculo['id'];
    }

    // =========================
    // 3️⃣ REGISTRA ACESSO
    // =========================
    $stmt = $pdo->prepare("
        INSERT INTO registros_acesso (
            id_veiculo,
            id_operador,
            id_empresa,
            id_unidade,
            tipo_acesso,
            nome_condutor,
            contato_tipo,
            contato_valor,
            observacao
        ) VALUES (
            :veiculo,
            :operador,
            :empresa,
            :unidade,
            :tipo_acesso,
            :nome,
            :contato_tipo,
            :contato_valor,
            :obs
        )
    ");

    $stmt->execute([
        ':veiculo'       => $veiculo_id,
        ':operador'      => $operador_id,
        ':empresa'       => $empresa_id,
        ':unidade'       => $unidade_id,
        ':tipo_acesso'   => $tipo_acesso,
        ':nome'          => $nome_condutor,
        ':contato_tipo'  => $contato_tipo,
        ':contato_valor' => $contato_valor,
        ':obs'           => $observacao
    ]);

    $pdo->commit();

    header('Location: index.php?sucesso=1');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die('Erro ao registrar acesso: ' . $e->getMessage());
}