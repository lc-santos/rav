<?php
require_once 'conn.php';
header('Content-Type: application/json');

$termo = trim($_GET['termo'] ?? '');

if (strlen($termo) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $like = "%$termo%";

    // Busca 1: Veículos com histórico de acesso
    $sqlVeiculos = "
        SELECT
            'veiculo' AS tipo_resultado,
            v.id      AS id_veiculo,
            v.placa,
            v.modelo,
            v.cor,
            v.tipo_veiculo,
            v.id_usuario,
            u.id          AS id_usuario_lookup,
            u.nome_completo,
            u.codigo_acesso,
            u.tipo_acesso,
            u.curso,
            u.periodo,
            u.modulo,
            u.funcao,
            (SELECT COUNT(*) FROM registros_acesso ra WHERE ra.id_veiculo = v.id) AS total_acessos,
            (SELECT status FROM registros_acesso ra WHERE ra.id_veiculo = v.id ORDER BY ra.data_hora_entrada DESC LIMIT 1) AS ultimo_status
        FROM veiculos v
        LEFT JOIN usuarios u ON v.id_usuario = u.id
        WHERE v.placa LIKE :like OR v.modelo LIKE :like OR u.nome_completo LIKE :like
        LIMIT 8
    ";

    // Busca 2: Usuários sem veículo cadastrado
    $sqlUsuarios = "
        SELECT
            'usuario' AS tipo_resultado,
            NULL      AS id_veiculo,
            '---'     AS placa,
            'Sem veículo' AS modelo,
            ''        AS cor,
            ''        AS tipo_veiculo,
            u.id      AS id_usuario,
            u.id      AS id_usuario_lookup,
            u.nome_completo,
            u.codigo_acesso,
            u.tipo_acesso,
            u.curso,
            u.periodo,
            u.modulo,
            u.funcao,
            0         AS total_acessos,
            'Cadastrado' AS ultimo_status
        FROM usuarios u
        WHERE u.nome_completo LIKE :like
          AND u.id NOT IN (SELECT DISTINCT id_usuario FROM veiculos WHERE id_usuario IS NOT NULL)
        LIMIT 5
    ";

    $stmtV = $pdo->prepare($sqlVeiculos);
    $stmtV->execute([':like' => $like]);
    $veiculos = $stmtV->fetchAll(PDO::FETCH_ASSOC);

    $stmtU = $pdo->prepare($sqlUsuarios);
    $stmtU->execute([':like' => $like]);
    $usuarios = $stmtU->fetchAll(PDO::FETCH_ASSOC);

    $resultados = array_merge($veiculos, $usuarios);

    echo json_encode($resultados);
} catch (Exception $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}