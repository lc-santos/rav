<?php
require_once 'conn.php';

// Captura o ID que vem da URL (ex: registrar_saida.php?id=10)
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Ajustado para usar a variável $id correta e a coluna data_hora_saida do rav3
        $stmt = $pdo->prepare("UPDATE registros_acesso SET status = 'Saiu', data_hora_saida = NOW() WHERE id = ?");
        $stmt->execute([$id]); // Aqui deve ser exatamente o nome da variável acima
    } catch (PDOException $e) {
        // Opcional: tratar erro de banco de dados
    }
}

$origem = $_GET['origem'] ?? 'painel-admin.php';

// Redireciona de volta para a origem
header("Location: " . $origem);
exit;
    