<?php
require_once 'conn.php';

// 1. Busca veículos que estão "Dentro" - Corrigido para data_hora_entrada
$stmtSaida = $pdo->query("SELECT r.*, v.placa, v.tipo_veiculo, v.modelo 
                          FROM registros_acesso r 
                          JOIN veiculos v ON r.id_veiculo = v.id 
                          WHERE r.status = 'Dentro' 
                          ORDER BY r.data_hora_entrada DESC");
$veiculosDentro = $stmtSaida->fetchAll(PDO::FETCH_ASSOC);

// 2. Busca os últimos 50 acessos para o histórico lateral - Corrigido para data_hora_entrada
$stmtRecentes = $pdo->query("SELECT r.*, v.placa, v.tipo_veiculo, v.modelo 
                             FROM registros_acesso r 
                             JOIN veiculos v ON r.id_veiculo = v.id 
                             ORDER BY r.data_hora_entrada DESC LIMIT 50");
$acessosRecentes = $stmtRecentes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV Admin - Estacionamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-white">

    <nav class="navbar navbar-dark bg-dark sticky-top border-bottom border-secondary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand fw-bold" href="#">RAV <span class="badge bg-secondary font-monospace">Admin</span></a>
            <div class="dropdown">
                <i class="bi bi-person-circle text-white fs-4" data-bs-toggle="dropdown" style="cursor:pointer;"></i>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="sair.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="menuLateral">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="p-3 bg-secondary bg-opacity-25 mb-3">
                <small class="text-uppercase text-secondary d-block">Unidade</small>
                <p class="mb-0 small">Guarita - ETEC</p>
                <p class="mb-0 small"><strong>Op:</strong> Lucas Silva</p>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="painel-admin.php" class="nav-link text-white"><i class="bi bi-house-door me-2"></i> Painel Inicial</a></li>
                <li class="nav-item"><a href="estacionamento.php" class="nav-link text-white active bg-success bg-opacity-50"><i class="bi bi-p-circle me-2"></i> Estacionamento</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white"><i class="bi bi-shield-check me-2"></i> Acessos</a></li>
            </ul>
        </div>
    </div>

    <main class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-dark border-secondary text-secondary">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="Filtrar placa ou condutor...">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card bg-dark border-primary shadow-lg">
                    <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-p-circle-fill me-2"></i>Veículos no Estacionamento</span>
                        <span class="badge bg-light text-primary"><?= count($veiculosDentro) ?> veículos</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Status</th>
                                        <th>Placa</th>
                                        <th>Tipo</th>
                                        <th>Modelo</th>
                                        <th>Condutor</th>
                                        <th>Entrada</th>
                                        <th class="text-center">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($veiculosDentro) > 0): ?>
                                        <?php foreach ($veiculosDentro as $reg): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success border border-success">
                                                        <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> <?= $reg['status'] ?>
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-info"><?= $reg['placa'] ?></td>
                                                <td><?= $reg['tipo_veiculo'] ?></td>
                                                <td><?= $reg['modelo'] ?></td>
                                                <td><?= $reg['nome_condutor'] ?></td>
                                                <td><?= date('H:i', strtotime($reg['data_hora_entrada'])) ?></td>
                                                <td class="text-center">
                                                    <a href="registrar_saida.php?id=<?= $reg['id'] ?>" class="btn btn-sm btn-outline-danger">Registrar Saída</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-secondary">Nenhum veículo no pátio no momento.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-dark border-secondary p-3">
                        <a href="historico.php" class="btn btn-outline-primary btn-sm w-100">Ver histórico completo</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Atualiza a página a cada 10 segundos para manter o pátio sincronizado
        setInterval(() => {
            location.reload();
        }, 10000);
    </script>
</body>
</html>