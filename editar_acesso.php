<?php
require_once 'conn.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'erro' => 'Método não permitido.']);
    exit;
}

try {
    // 1. Captura dos campos do modal
    $id_registro   = $_POST['id_registro'] ?? null;
    $placa         = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_POST['edit_placa'] ?? ''));
    $nome_condutor = trim($_POST['edit_nome_condutor'] ?? '');
    $tipo_veiculo  = $_POST['edit_tipo_veiculo'] ?? 'Carro';
    $modelo        = trim($_POST['edit_modelo_veiculo'] ?? '');
    $cor           = trim($_POST['edit_cor_veiculo'] ?? '');
    $tipo_acesso   = $_POST['edit_tipo_acesso'] ?? 'Serviço';
    $curso         = $_POST['edit_curso_aluno'] ?? null;
    $periodo       = $_POST['edit_periodo_aluno'] ?? null;
    $funcao        = $_POST['edit_funcao_equipe'] ?? null;
    $contato_valor = trim($_POST['edit_contato_valor'] ?? '');
    $observacao    = trim($_POST['edit_observacao'] ?? null);

    if (empty($id_registro) || empty($placa) || empty($nome_condutor)) {
        echo json_encode(['sucesso' => false, 'erro' => 'Placa e Nome são obrigatórios.']);
        exit;
    }

    $pdo->beginTransaction();

    // Buscar registro original para saber qual veículo alterar
    $stmtOrig = $pdo->prepare("SELECT id_veiculo FROM registros_acesso WHERE id = :id LIMIT 1");
    $stmtOrig->execute([':id' => $id_registro]);
    $registroOriginal = $stmtOrig->fetch(PDO::FETCH_ASSOC);

    if (!$registroOriginal) {
        throw new Exception("Registro não encontrado.");
    }
    
    $id_veiculo = $registroOriginal['id_veiculo'];

    // Para evitar conflito caso o usuário edite a placa para uma placa que JÁ existe em outro acesso,
    // o certo seria checar. Mas como é rápido, faremos UPDATE no id_veiculo original
    // Obs: se ele mudar a placa, a placa do carro muda para todos os registros que usam aquele carro.
    // O mais seguro para auditoria é checar se a nova placa já existe num veículo diferente:
    $stmtCheckPlaca = $pdo->prepare("SELECT id FROM veiculos WHERE placa = :placa AND id != :id_veiculo LIMIT 1");
    $stmtCheckPlaca->execute([':placa' => $placa, ':id_veiculo' => $id_veiculo]);
    $veiculoExistente = $stmtCheckPlaca->fetch(PDO::FETCH_ASSOC);

    if ($veiculoExistente) {
        // Se a placa já pertencia a outro carro antes, apenas move o acesso para esse novo carro.
        $id_veiculo_final = $veiculoExistente['id'];
        // Atualiza modelo/cor do veículo encontrado
        $stmtUpdateVeic = $pdo->prepare("UPDATE veiculos SET modelo = ?, cor = ?, tipo_veiculo = ? WHERE id = ?");
        $stmtUpdateVeic->execute([$modelo, $cor, $tipo_veiculo, $id_veiculo_final]);
    } else {
        // Se não, atualiza a placa do carro original mesmo.
        $id_veiculo_final = $id_veiculo;
        $stmtUpdateVeic = $pdo->prepare("UPDATE veiculos SET placa = ?, modelo = ?, cor = ?, tipo_veiculo = ? WHERE id = ?");
        $stmtUpdateVeic->execute([$placa, $modelo, $cor, $tipo_veiculo, $id_veiculo_final]);
    }

    // Limpeza condicional para não sujar o banco
    if ($tipo_acesso !== 'Aluno') { $curso = null; $periodo = null; }
    if ($tipo_acesso !== 'Equipe') { $funcao = null; }

    // Formata telefone
    $contato_valor_limpo = preg_replace('/\D/', '', $contato_valor);

    // 2. Atualiza o Registro
    $stmt = $pdo->prepare("UPDATE registros_acesso SET 
                            id_veiculo = ?, 
                            tipo_acesso = ?, 
                            curso = ?, 
                            periodo = ?, 
                            funcao = ?, 
                            nome_condutor = ?, 
                            contato_valor = ?, 
                            observacao = ? 
                           WHERE id = ?");
    $stmt->execute([
        $id_veiculo_final, 
        $tipo_acesso, 
        $curso, 
        $periodo, 
        $funcao, 
        $nome_condutor, 
        $contato_valor_limpo, 
        $observacao,
        $id_registro
    ]);

    $pdo->commit();
    echo json_encode(['sucesso' => true]); 

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['sucesso' => false, 'erro' => 'Erro no banco: ' . $e->getMessage()]);
}
