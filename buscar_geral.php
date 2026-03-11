<?php
require_once 'conn.php';
header('Content-Type: application/json');

$termo = $_GET['termo'] ?? '';

if (empty($termo)) {
    echo json_encode([]);
    exit;
}

try {
    // Busca unificada: Prioriza quem tem veículo vinculado no histórico ou cadastro
    $sql = "SELECT 
                r.id as registro_id,
                r.nome_condutor,
                r.status,
                v.placa,
                v.modelo,
                v.cor,
                DATE_FORMAT(r.data_entrada, '%H:%i') as hora 
            FROM registros_acesso r
            JOIN veiculos v ON r.id_veiculo = v.id
            WHERE v.placa LIKE :termo 
               OR r.nome_condutor LIKE :termo 
            UNION
            SELECT 
                null as registro_id,
                u.nome_completo as nome_condutor,
                'Cadastrado' as status,
                '---' as placa,
                'Sem veículo' as modelo,
                '' as cor,
                '--:--' as hora
            FROM usuarios u
            WHERE u.nome_completo LIKE :termo AND u.id NOT IN (SELECT id_usuario FROM veiculos WHERE id_usuario IS NOT NULL)
            LIMIT 10";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':termo' => "%$termo%"]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);
} catch (Exception $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}