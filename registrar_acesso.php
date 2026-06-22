<?php
require_once 'conn.php';
session_start();

header('Content-Type: application/json');

// Usa os IDs da sessão ativa ou fallback para 1 caso indefinido
$usuario_id = $_SESSION['usuario_id'] ?? 1;
$etec_id    = $_SESSION['etec_id'] ?? $_SESSION['empresa_id'] ?? 1; 

try {
    // 1. Captura dos campos
    $placa         = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_POST['placa'] ?? ''));
    $nome_condutor = trim($_POST['nome_condutor'] ?? '');
    $tipo_veiculo  = $_POST['tipo_veiculo'] ?? '';
    $modelo        = trim($_POST['modelo_veiculo'] ?? '');
    $cor           = trim($_POST['cor_veiculo'] ?? '');

    // 2. Normaliza tipo_veiculo para o ENUM do banco
    $tipoMap = [
        'Carro'  => 'CARRO',
        'Moto'   => 'MOTO',
        'Outros' => 'OUTRO',
        'CARRO'  => 'CARRO',
        'MOTO'   => 'MOTO',
        'OUTRO'  => 'OUTRO',
    ];
    $tipo_veiculo = $tipoMap[$tipo_veiculo] ?? 'OUTRO';

    // 3. Validação: nome obrigatório sempre; placa obrigatória exceto para tipo 'OUTRO'
    if (empty($nome_condutor)) {
        echo json_encode(['sucesso' => false, 'erro' => 'O nome do condutor é obrigatório.']);
        exit;
    }
    if (empty($placa) && $tipo_veiculo !== 'OUTRO') {
        echo json_encode(['sucesso' => false, 'erro' => 'Placa é obrigatória para este tipo de veículo.']);
        exit;
    }

    // ============================================================
    // VERIFICAÇÃO DE DUPLICIDADE (apenas quando há placa informada)
    // ============================================================
    if (!empty($placa)) {
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
    }
    // ============================================================

    // 4. Se tipo 'OUTRO' sem placa informada, gera placa única temporária
    if (empty($placa)) {
        $placa = 'OUT' . strtoupper(substr(uniqid(), -5));
    }

    // 5. Verifica ou Cria/Atualiza o Veículo
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT id FROM veiculos WHERE placa = :placa LIMIT 1");
    $stmt->execute([':placa' => $placa]);
    $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$veiculo) {
        $stmt = $pdo->prepare("INSERT INTO veiculos (placa, tipo_veiculo, modelo, cor, id_empresa, id_unidade) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$placa, $tipo_veiculo, $modelo, $cor, $etec_id, $etec_id]);
        $veiculo_id = $pdo->lastInsertId();
    } else {
        $stmt = $pdo->prepare("UPDATE veiculos SET modelo = ?, cor = ?, tipo_veiculo = ? WHERE id = ?");
        $stmt->execute([$modelo, $cor, $tipo_veiculo, $veiculo['id']]);
        $veiculo_id = $veiculo['id'];
    }

    // 2. Registra o Acesso no Pátio (Status 'Dentro')
    $stmt = $pdo->prepare("INSERT INTO registros_acesso (id_veiculo, id_usuario_registro, id_empresa, tipo_acesso, curso, periodo, funcao, nome_condutor, contato_tipo, contato_valor, observacao, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Dentro')");
    $stmt->execute([
        $veiculo_id, 
        $usuario_id, $etec_id, 
        $_POST['tipo_acesso'] ?? 'Serviço', 
        $_POST['curso_aluno'] ?? null,
        $_POST['periodo_aluno'] ?? null,
        $_POST['funcao_equipe'] ?? null,
        $nome_condutor, 
        $_POST['contato_tipo'] ?? 'tel', 
        ($_POST['contato_tipo'] ?? 'tel') === 'tel' ? preg_replace('/\D/', '', $_POST['contato_valor'] ?? '') : trim($_POST['contato_valor'] ?? ''),
        $_POST['observacao'] ?? null
    ]);

    $pdo->commit();
    echo json_encode(['sucesso' => true]); 
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['sucesso' => false, 'erro' => 'Erro no banco: ' . $e->getMessage()]);
}