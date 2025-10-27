<?php
require_once "conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Empresa
    $empresaNome = $_POST["empresaNome"];
    $tipoDocumento = $_POST["tipoDocumento"];
    $documento = $_POST["documento"];
    $telefone = $_POST["telefone"];
    $endereco = $_POST["endereco"];

    // Usuário
    $nome = $_POST["nome_completo"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $cpf = $_POST["cpf"];
    $datanasc = $_POST["datanasc"];
    $role = 'admin';

    // 1️⃣ Inserir empresa
    $sqlEmpresa = "INSERT INTO empresas (empresaNome, tipoDocumento, documento, telefone, endereco)
                   VALUES (?, ?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sqlEmpresa);
    $stmt1->bind_param("sssss", $empresaNome, $tipoDocumento, $documento, $telefone, $endereco);

    if ($stmt1->execute()) {
        $id_empresa = $conn->insert_id;

        // 2️⃣ Inserir usuário vinculado
        $sqlUser = "INSERT INTO usuarios (nome_completo, email, senha, cpf, datanasc, role, id_empresa)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sqlUser);
        $stmt2->bind_param("ssssssi", $nome, $email, $senha, $cpf, $datanasc, $role, $id_empresa);

        if ($stmt2->execute()) {
            echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='login.php';</script>";
        } else {
            echo "Erro ao cadastrar usuário: " . $stmt2->error;
        }
    } else {
        echo "Erro ao cadastrar empresa: " . $stmt1->error;
    }
}
?>
