<?php
require_once 'conn.php';
header('Content-Type: application/json');

$buscaRaw = $_GET['busca'] ?? '';
$busca = preg_replace('/\D/', '', $buscaRaw); // Garante que a busca por CPF/ID seja apenas números

if (empty($busca)) {
    echo json_encode(['sucesso' => false]);
    exit;
}

try {
    // Busca o usuário pelo Código de Acesso (7 dígitos) ou CPF
    $stmt = $pdo->prepare("SELECT id, nome_completo, contato_valor 
                           FROM usuarios 
                           WHERE codigo_acesso = :busca OR cpf = :busca LIMIT 1");
    $stmt->execute([':busca' => $busca]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Busca veículos vinculados a esse usuário
        $stmtV = $pdo->prepare("SELECT placa, modelo, cor FROM veiculos WHERE id_usuario = :id");
        $stmtV->execute([':id' => $user['id']]);
        $veiculos = $stmtV->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'sucesso' => true,
            'nome' => $user['nome_completo'],
            'contato' => $user['contato_valor'],
            'veiculos' => $veiculos
        ]);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Condutor não encontrado.']);
    }
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}