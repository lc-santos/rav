<?php
require_once 'conn.php'; 
header('Content-Type: application/json');

$busca = $_GET['busca'] ?? '';
$buscaLimpa = preg_replace('/\D/', '', $busca); 

if (empty($buscaLimpa)) {
    echo json_encode(['sucesso' => false]);
    exit;
}

try {
    // Busca o condutor e a lista de veículos vinculada ao ID dele
    $sql = "SELECT u.id as usuario_id, u.nome_completo, u.contato_valor, v.placa, v.modelo 
            FROM usuarios u 
            LEFT JOIN veiculos v ON u.id = v.id_usuario 
            WHERE u.codigo_acesso = :busca OR u.cpf = :busca";
        
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':busca' => $buscaLimpa]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($resultados) {
        $veiculos = [];
        foreach ($resultados as $row) {
            // Só adiciona à lista se o veículo realmente existir no banco
            if (!empty($row['placa'])) {
                $veiculos[] = [
                    'placa' => $row['placa'],
                    'modelo' => $row['modelo'] ?? 'Não informado'
                ];
            }
        }

        echo json_encode([
            'sucesso' => true,
            'id_usuario' => $resultados[0]['usuario_id'], // Corrigido para garantir o ID
            'nome' => $resultados[0]['nome_completo'],
            'contato' => $resultados[0]['contato_valor'],
            'veiculos' => $veiculos
        ]);
    } else {
        echo json_encode(['sucesso' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}