<?php
require_once 'conn.php';
session_start();

// Função para gerar o ID de 7 dígitos único
function gerarCodigoAcesso($pdo) {
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

        // 1. Captura de Dados do Condutor
        $nome = trim($_POST['nome_completo']);
        $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove pontos e traços do CPF
        $email = trim($_POST['email']);
        $telefone = preg_replace('/\D/', '', $_POST['telefone']); // Remove máscaras do telefone
        $empresa_id = $_SESSION['empresa_id'] ?? 1;

        // Novos campos de tipo de acesso
        $tipo_acesso = $_POST['tipo_acesso'] ?? 'Outros';
        $curso = $_POST['curso_aluno'] ?? null;
        $periodo = $_POST['periodo_aluno'] ?? null;
        $modulo = $_POST['modulo_aluno'] ?? null;
        $funcao = $_POST['funcao_equipe'] ?? null;

        if ($tipo_acesso !== 'Aluno') {
            $curso = null;
            $periodo = null;
            $modulo = null;
        }
        if ($tipo_acesso !== 'Equipe') {
            $funcao = null;
        }

        // 2. Captura de Dados do Veículo
        $tipo_veiculo = $_POST['tipo_veiculo'];
        $placa = strtoupper(trim($_POST['placa']));
        $modelo = trim($_POST['modelo_veiculo']);
        $cor = trim($_POST['cor_veiculo']);

        // Gerar um email falso temporário se estiver vazio para satisfazer a constraint unique
        if (empty($email)) {
            $email = $cpf . '@rav.tmp';
        }

        // 3. Gerar e Salvar Usuário
        $novo_codigo = gerarCodigoAcesso($pdo);
        $sqlUser = "INSERT INTO usuarios (codigo_acesso, nome_completo, email, senha, cpf, id_empresa, id_unidade, role, contato_valor, tipo_acesso, curso, periodo, modulo, funcao) 
                    VALUES (?, ?, ?, '123', ?, ?, ?, 'usuario', ?, ?, ?, ?, ?, ?)";
        $stmtUser = $pdo->prepare($sqlUser);
        
        // Usamos o telefone como contato principal, se não houver, usamos o email original
        $contato_principal = !empty($telefone) ? $telefone : (strpos($email, '@rav.tmp') === false ? $email : '');
        
        $stmtUser->execute([
            $novo_codigo, $nome, $email, $cpf, $empresa_id, $empresa_id, $contato_principal,
            $tipo_acesso, $curso, $periodo, $modulo, $funcao
        ]);
        $novo_usuario_id = $pdo->lastInsertId();

        // 4. Salvar Veículo Vinculado
        $sqlVei = "INSERT INTO veiculos (placa, modelo, cor, tipo_veiculo, id_empresa, id_unidade, id_usuario) 
                   VALUES (?, ?, ?, ?, ?, ?, ?) 
                   ON DUPLICATE KEY UPDATE modelo = ?, cor = ?, id_usuario = ?, tipo_veiculo = ?";
        
        $stmtVei = $pdo->prepare($sqlVei);
        $stmtVei->execute([
            $placa, $modelo, $cor, $tipo_veiculo, $empresa_id, $empresa_id, $novo_usuario_id,
            $modelo, $cor, $novo_usuario_id, $tipo_veiculo
        ]);

        $pdo->commit();
        // Redireciona com o código gerado pro alerta de sucesso no painel-admin
        header("Location: painel-admin.php?sucesso_cadastro=1&codigo=" . $novo_codigo);
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        die("Erro no cadastro: " . $e->getMessage());
    }
}