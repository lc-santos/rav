<?php
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_condutor = $_POST['id_condutor'] ?? '';
    $nome = $_POST['nome_completo'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $email = $_POST['email'] ?? '';
    $contato = $_POST['contato_valor'] ?? '';

    if (empty($id_condutor) || empty($nome) || empty($cpf)) {
        header("Location: gerenciar_condutores.php?erro=" . urlencode("Dados vazios! Preencha Nome e CPF obrigatórios."));
        exit;
    }

    // Caso o e-mail não seja informado, geramos um temporário no padrão do sistema
    if (empty(trim($email))) {
        $email = str_replace(['.', '-'], '', $cpf) . "@rav.tmp";
    }

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome_completo = ?, cpf = ?, email = ?, contato_valor = ? WHERE id = ?");
        $stmt->execute([$nome, $cpf, $email, $contato, $id_condutor]);
        
        header("Location: gerenciar_condutores.php?sucesso=" . urlencode("Dados do condutor $nome atualizados com sucesso!"));
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            header("Location: gerenciar_condutores.php?erro=" . urlencode("Atenção: Este E-mail ou CPF já está atrelado a outro registro ativo!"));
            exit;
        }
        header("Location: gerenciar_condutores.php?erro=" . urlencode("Erro inesperado no BD: " . $e->getMessage()));
        exit;
    }
}

header("Location: gerenciar_condutores.php");
exit;
