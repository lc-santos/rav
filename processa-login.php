<?php
session_start();
require_once "conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user["senha"])) {
    $_SESSION["id"] = $user["id"];
    $_SESSION["id_empresa"] = $user["id_empresa"];
    $_SESSION["nome"] = $user["nome_completo"];
    $_SESSION["role"] = $user["role"];

    // Redireciona conforme o tipo de usuário
    if ($user["role"] === "admin") {
        header("Location: painel-admin.php");
    } else {
        header("Location: painel-controle.php");
    }
    exit();
}

        } else {
            echo "<script>alert('Senha incorreta'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado'); window.location.href='login.php';</script>";
    }
?>
