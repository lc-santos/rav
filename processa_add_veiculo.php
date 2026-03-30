<?php
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? '';
    $placaParam = $_POST['placa'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $cor = $_POST['cor'] ?? '';
    $tipo = $_POST['tipo_veiculo'] ?? 'Carro';
    
    // Tratamento unificado de placa (letras maiúsculas e remove caracteres não alfanuméricos)
    $placa = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $placaParam));

    if (empty($id_usuario) || empty($placa)) {
        header("Location: gerenciar_condutores.php?erro=" . urlencode("Por favor, preencha corretamente a Placa do veículo."));
        exit;
    }

    try {
        // Obter id corporativo do usuário selecionado para atrelar a mesma 'empresa' (escola) ao novo veiculo
        $stmtU = $pdo->prepare("SELECT id_empresa, nome_completo FROM usuarios WHERE id = ? AND role = 'visitante'");
        $stmtU->execute([$id_usuario]);
        $user = $stmtU->fetch();

        if (!$user) {
            header("Location: gerenciar_condutores.php?erro=" . urlencode("Usuário não encontrado na base de visitantes."));
            exit;
        }
        $id_empresa = $user['id_empresa'];
        $nome_condutor = $user['nome_completo'];

        // Tentar Inserir o veículo
        $stmt = $pdo->prepare("INSERT INTO veiculos (placa, modelo, cor, tipo_veiculo, id_usuario, id_empresa, tipo) VALUES (?, ?, ?, ?, ?, ?, 'visitante')");
        $stmt->execute([$placa, $modelo, $cor, $tipo, $id_usuario, $id_empresa]);

        header("Location: gerenciar_condutores.php?sucesso=" . urlencode("Veículo ($placa) vinculado à garagem virtual de $nome_condutor com sucesso!"));
        exit;

    } catch (PDOException $e) {
        // Se disparar o erro 23000, violação da UNIQUE PLATE constraints
        if ($e->getCode() == 23000) {
            header("Location: gerenciar_condutores.php?erro=" . urlencode("Ação Bloqueada. A placa ($placaParam) já está registrada para outra pessoa dentro do RAV!"));
            exit;
        }
        header("Location: gerenciar_condutores.php?erro=" . urlencode("Erro no banco de dados ao tentar salvar veiculo."));
        exit;
    }
}
header("Location: gerenciar_condutores.php");
exit;
