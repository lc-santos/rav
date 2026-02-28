<?php
require_once 'conn.php';
session_start();

function gerarCodigoAcesso($pdo)
{
    do {
        $codigo = str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE codigo_acesso = ?");
        $stmt->execute([$codigo]);
    } while ($stmt->fetch());
    return $codigo;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $nome = trim($_POST['nome_completo']);
        $cpf = trim($_POST['cpf']);
        $placa = strtoupper(trim($_POST['placa']));
        $modelo = trim($_POST['modelo_veiculo']);
        $empresa_id = $_SESSION['empresa_id'] ?? 1;

        // 1. Gerar e Salvar Usuário
        $novo_codigo = gerarCodigoAcesso($pdo);
        $sqlUser = "INSERT INTO usuarios (codigo_acesso, nome_completo, email, senha, cpf, id_empresa, role) 
                    VALUES (?, ?, ?, '123', ?, ?, 'visitante')";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([$novo_codigo, $nome, $cpf . "@rav.com", $cpf, $empresa_id]);

        // Pegamos o ID do usuário que acabou de ser criado para vincular ao veículo
        $novo_usuario_id = $pdo->lastInsertId();

        // 2. Salvar Veículo VINCULADO ao usuário
        // Usamos ON DUPLICATE KEY para caso a placa já exista, apenas atualizar o dono e o modelo
        $sqlVei = "INSERT INTO veiculos (placa, modelo, id_empresa, id_usuario) 
                   VALUES (?, ?, ?, ?) 
                   ON DUPLICATE KEY UPDATE modelo = ?, id_usuario = ?";
        
        $stmtVei = $pdo->prepare($sqlVei);
        $stmtVei->execute([$placa, $modelo, $empresa_id, $novo_usuario_id, $modelo, $novo_usuario_id]);

        $pdo->commit();
        header("Location: index.php?sucesso_cadastro=1&codigo=" . $novo_codigo);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro no cadastro: " . $e->getMessage());
    }
}