<?php

session_start();
require_once "conn.php";

// Verifica se o usuário está logado
// A lógica do script 1 usa $_SESSION["id"]

if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}


$idUsuario = $_SESSION["id"];
$idEmpresa = $_SESSION["id_empresa"] ?? null;

// Busca informações do usuário logado + empresa
$sql = "
    SELECT u.nome_completo, u.email, u.role, e.empresaNome
    FROM usuarios u
    INNER JOIN empresas e ON u.id_empresa = e.id
    WHERE u.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    echo "Erro: usuário não encontrado.";
    exit;
}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrador - RAV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/painel-controle.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">RAV - admin</a>
            <div class="collapse navbar-collapse margin-right">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person-circle"></i>
                            Olá, <?= htmlspecialchars($usuario["nome_completo"]) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sair.php">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <ul class="nav flex-column p-3">
            <li class="nav-item mb-2">
                <a class="nav-link text-white active" href="#">
                    <i class="bi bi-house-door"></i> Início
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="gerenciar_porteiros.php">
                    <i class="bi bi-people"></i> Gerenciar Porteiros
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="#">
                    <i class="bi bi-card-checklist"></i> Relatórios de Acesso
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="#">
                    <i class="bi bi-gear"></i> Configurações da Conta
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content p-4">
        <div class="container-fluid">
            <h1 class="titulo-pagina">Painel de controle</h1>

            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    Empresa: <?= htmlspecialchars($usuario["empresaNome"]) ?>
                </div>
                <div class="card-body">
                    <p><strong>Email:</strong> <?= htmlspecialchars($usuario["email"]) ?></p>
                    <p><strong>Função:</strong> <?= htmlspecialchars($usuario["role"]) ?></p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    Registro de acesso
                </div>
                <div class="card-body" style="padding: 2em">
                    <div class="flex">

                        <div class="margin-bottom margin-right">
                            <label>Placa:</label>
                            <input>
                        </div>

                        <div class="margin-bottom margin-right">
                            <label>Nome:</label>
                            <input>
                        </div>

                        <div class="margin-bottom margin-right">
                            <label>Tipo Veículo</label>
                            <select>
                                <option>Carro</option>
                                <option>Moto</option>
                                <option>Biciletas</option>
                                <option>Outros</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button class="margin-bottom btn  btn-success margin-left" style="width:25%">Registrar</button>


            </div>
        </div>


        <div class="card mb-4">
            <div class="card-header color-rav">
                Funcionários
            </div>
            <div class="card-body">

                <table>
                    <th class="text-white bg-primary" colspan="3">Funcionários cadastrados</th>
                    <tr>
                        <td>Nome</td>
                        <td>ID</td>
                        <td>E-mail</td>
                    </tr>
                    <tr>
                        <td>Lucas Silva</td>
                        <td>1</td>
                        <td>lcprojetos23@gmail.com</td>
                    </tr>
                </table>
                <button class="btn btn-secondary" style="margin-top: 15px;">Cadastrar funcionário</button>
            </div>

        </div>


        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">Relatórios</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Entradas Registradas Hoje</div>
                            <div class="card-body">
                                <h5 class="card-title">0</h5>
                                <p class="card-text">Registros de entrada e saída nas últimas 24 horas.</p>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total de Porteiros</div>
                            <div class="card-body">
                                <h5 class="card-title">0</h5>
                                <p class="card-text">Usuários com permissão de porteiro cadastrados no sistema.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>