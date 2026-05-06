<?php
require_once 'conn.php';

if (isset($_GET['id']) && isset($_GET['usuario'])) {
    $id_veiculo = $_GET['id'];
    $id_usuario = $_GET['usuario'];

    try {
        // Obter nome para o feedback
        $stmtV = $pdo->prepare("SELECT v.placa, u.nome_completo FROM veiculos v INNER JOIN usuarios u ON v.id_usuario = u.id WHERE v.id = ? AND v.id_usuario = ?");
        $stmtV->execute([$id_veiculo, $id_usuario]);
        $veiculo = $stmtV->fetch();

        if ($veiculo) {
            $stmt = $pdo->prepare("DELETE FROM veiculos WHERE id = ? AND id_usuario = ?");
            $stmt->execute([$id_veiculo, $id_usuario]);
            
            header("Location: gerenciar_cadastros.php?sucesso=" . urlencode("Veículo placa " . $veiculo['placa'] . " desvinculado de " . $veiculo['nome_completo'] . "."));
            exit;
        } else {
            header("Location: gerenciar_cadastros.php?erro=" . urlencode("Veículo não encontrado ou não pertence a este condutor."));
            exit;
        }
    } catch (PDOException $e) {
        header("Location: gerenciar_cadastros.php?erro=" . urlencode("Erro no banco de dados ao tentar excluir."));
        exit;
    }
} else {
    header("Location: gerenciar_cadastros.php");
    exit;
}
