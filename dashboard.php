<?php
// O "adminstrador" do seu sistema. Sempre a primeira coisa na página.
session_start();

// 1. VERIFICA SE O USUÁRIO ESTÁ LOGADO
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para o login
    header("Location: login.php");
    exit();
}

// Futuramente, você adicionará uma verificação de permissão aqui
// 2. VERIFICA SE O USUÁRIO É UM ADMINISTRADOR
// if ($_SESSION['usuario_role'] !== 'admin') {
//     // Se não for admin, redireciona para uma página de acesso negado ou de usuário comum
//     die("Acesso negado. Você não é um administrador.");
// }

// Pega o nome do administrador da sessão para uma saudação amigável
$nome_admin = $_SESSION['usuario_nome'];

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* Estilo para a barra lateral fixa */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            padding-top: 56px;
            /* Altura do navbar */
            background-color: #343a40;
            /* Cor escura */
            color: white;
        }

        /* Conteúdo principal com margem para não ficar atrás da sidebar */
        .main-content {
            margin-left: 250px;
            padding-top: 56px;
            /* Altura do navbar */
        }

        h1 {
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Painel Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person-circle"></i> Olá, <?php echo htmlspecialchars($nome_admin); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
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
            <h1 class="mb-4">Painel de controle</h1>

            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Total de Porteiros</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                            <p class="card-text">Usuários com permissão de porteiro cadastrados no sistema.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Entradas Registradas Hoje</div>
                        <div class="card-body">
                            <h5 class="card-title">0</h5>
                            <p class="card-text">Registros de entrada e saída nas últimas 24 horas.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>
