<?php
require_once 'conn.php';
session_start();

header('Content-Type: application/json');

$_SESSION['usuario_id']  = 1; 
$_SESSION['empresa_id']  = 1; 

try {
    // 1. Captura dos campos
    $placa         = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_POST['placa'] ?? ''));
    $nome_condutor = trim($_POST['nome_condutor'] ?? '');
    $tipo_veiculo  = $_POST['tipo_veiculo'] ?? '';
    $modelo        = trim($_POST['modelo_veiculo'] ?? '');
    $cor           = trim($_POST['cor_veiculo'] ?? '');

    // 2. Validação de segurança básica
    if (empty($placa) || empty($nome_condutor)) {
        echo json_encode(['sucesso' => false, 'erro' => 'Placa e Nome são obrigatórios.']);
        exit;
    }

    // ============================================================
    // INSERIR AQUI: VERIFICAÇÃO DE DUPLICIDADE
    // ============================================================
    $stmtCheck = $pdo->prepare("
        SELECT id FROM registros_acesso 
        WHERE id_veiculo = (SELECT id FROM veiculos WHERE placa = :placa LIMIT 1)
        AND status = 'Dentro'
        LIMIT 1
    ");
    $stmtCheck->execute([':placa' => $placa]);
    
    if ($stmtCheck->fetch()) {
        echo json_encode([
            'sucesso' => false, 
            'erro' => 'Este veículo ('.$placa.') já está no pátio. Registre a saída antes de uma nova entrada.'
        ]);
        exit;
    }
    // ============================================================

    $pdo->beginTransaction();

    // 1. Verifica ou Cria/Atualiza o Veículo
    $stmt = $pdo->prepare("SELECT id FROM veiculos WHERE placa = :placa LIMIT 1");
    $stmt->execute([':placa' => $placa]);
    $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$veiculo) {
        $stmt = $pdo->prepare("INSERT INTO veiculos (placa, tipo_veiculo, modelo, cor, id_empresa) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$placa, $tipo_veiculo, $modelo, $cor, 1]);
        $veiculo_id = $pdo->lastInsertId();
    } else {
        $stmt = $pdo->prepare("UPDATE veiculos SET modelo = ?, cor = ?, tipo_veiculo = ? WHERE id = ?");
        $stmt->execute([$modelo, $cor, $tipo_veiculo, $veiculo['id']]);
        $veiculo_id = $veiculo['id'];
    }

    // 2. Registra o Acesso no Pátio (Status 'Dentro')
    $stmt = $pdo->prepare("INSERT INTO registros_acesso (id_veiculo, id_usuario_registro, id_empresa, tipo_acesso, nome_condutor, contato_tipo, contato_valor, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, 'Dentro')");
    $stmt->execute([
        $veiculo_id, 
        1, 1, 
        $_POST['tipo_acesso'] ?? 'Serviço', 
        $nome_condutor, 
        $_POST['contato_tipo'] ?? 'tel', 
        ($_POST['contato_tipo'] ?? 'tel') === 'tel' ? preg_replace('/\D/', '', $_POST['contato_valor'] ?? '') : trim($_POST['contato_valor'] ?? '')
    ]);

    $pdo->commit();
    echo json_encode(['sucesso' => true]); 
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['sucesso' => false, 'erro' => 'Erro no banco: ' . $e->getMessage()]);
}