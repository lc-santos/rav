<?php
require_once 'conn.php';

// -------- 1. DADOS DO GRÁFICO (MÊS ATUAL) --------
$stmtGrafico = $pdo->query("SELECT DATE(data_hora_entrada) as data_acesso, COUNT(*) as total_acessos 
                            FROM registros_acesso 
                            WHERE MONTH(data_hora_entrada) = MONTH(CURRENT_DATE()) 
                            AND YEAR(data_hora_entrada) = YEAR(CURRENT_DATE()) 
                            GROUP BY DATE(data_hora_entrada) 
                            ORDER BY data_acesso ASC");
$dadosGrafico = $stmtGrafico->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$valores = [];

foreach ($dadosGrafico as $dado) {
    // Formata a data para d/m
    $dataFormatada = date('d/m', strtotime($dado['data_acesso']));
    $labels[] = $dataFormatada;
    $valores[] = $dado['total_acessos'];
}

// -------- 2. DADOS DA ATA (COM FILTROS GET) --------
$whereConditions = [];
$params = [];

if (!empty($_GET['data_inicio'])) {
    $whereConditions[] = "DATE(historico.data_evento) >= :data_inicio";
    $params[':data_inicio'] = $_GET['data_inicio'];
}
if (!empty($_GET['data_fim'])) {
    $whereConditions[] = "DATE(historico.data_evento) <= :data_fim";
    $params[':data_fim'] = $_GET['data_fim'];
}
if (!empty($_GET['busca'])) {
    $whereConditions[] = "(historico.placa LIKE :busca OR historico.modelo LIKE :busca OR historico.nome_condutor LIKE :busca)";
    $params[':busca'] = "%" . $_GET['busca'] . "%";
}

$whereSQL = "";
if (count($whereConditions) > 0) {
    $whereSQL = "WHERE " . implode(" AND ", $whereConditions);
}

// Busca a Ata com Clone Virtual (Entrada e Saída desmembradas via UNION ALL)
$sqlAta = "SELECT historico.* 
           FROM (
               SELECT r.id, r.nome_condutor, v.placa, v.tipo_veiculo, v.modelo, 
                      'Entrada' as tipo_movimento, r.data_hora_entrada as data_evento 
               FROM registros_acesso r 
               LEFT JOIN veiculos v ON r.id_veiculo = v.id 

               UNION ALL 

               SELECT r.id, r.nome_condutor, v.placa, v.tipo_veiculo, v.modelo, 
                      'Saída' as tipo_movimento, r.data_hora_saida as data_evento 
               FROM registros_acesso r 
               LEFT JOIN veiculos v ON r.id_veiculo = v.id 
               WHERE r.data_hora_saida IS NOT NULL
           ) AS historico 
           $whereSQL 
           ORDER BY historico.data_evento DESC LIMIT 500";
$stmtAta = $pdo->prepare($sqlAta);
$stmtAta->execute($params);
$registrosAta = $stmtAta->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV Admin - Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="light-mode section-bg-gray d-flex flex-column min-vh-100">

    <!-- Acessibility Bar -->
    <div class="accessibility-bar py-1 d-none d-md-block">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <div class="gov-logo mb-2 mb-sm-0">
                <span class="fw-bold text-white small">RAV - PROJETO INSTITUCIONAL</span>
            </div>
            <div class="accessibility-tools d-flex align-items-center gap-3">
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-decrease-font" title="Diminuir Fonte">A-</button>
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-increase-font" title="Aumentar Fonte">A+</button>
                <button type="button" class="btn btn-sm text-white p-0 ms-2" id="btn-toggle-contrast" title="Alto Contraste / Dark Mode"><i class="bi bi-moon-stars-fill fs-6"></i></button>
            </div>
        </div>
    </div>

    <!-- Header Branco -->
    <header class="main-header bg-white shadow-sm sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-expanded="false">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <a href="painel-admin.php" class="text-decoration-none d-flex align-items-center">
                    <h1 class="logo-text m-0 fw-bold d-flex align-items-center">
                        <span class="text-cps-red fs-2 me-1">RAV</span>
                        <span class="text-dark fs-4 mt-1">ETEC</span>
                        <span class="badge bg-cps-red text-white font-monospace ms-2 mt-2" style="font-size: 0.70rem;">Admin</span>
                    </h1>
                </a>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-light rounded-pill border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 text-cps-red"></i>
                    <span class="d-none d-md-inline fw-medium text-dark small">Lucas Silva</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><h6 class="dropdown-header">Guarita - ETEC</h6></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger fw-bold" href="sair.php"><i class="bi bi-box-arrow-right me-2"></i>Sair do Sistema</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Navbar Horizontal -->
    <nav class="navbar navbar-expand-lg nav-cps p-0" style="z-index: 1010;">
        <div class="container flex-column flex-lg-row">
            <div class="collapse navbar-collapse w-100" id="adminNavbar">
                <div class="d-md-none bg-dark p-3 text-white d-flex justify-content-between align-items-center mb-2 mx-3 mt-3 rounded border">
                    <span class="small fw-bold">Acessibilidade:</span>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-decrease-font-mobile">A-</button>
                        <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-increase-font-mobile">A+</button>
                        <button type="button" class="btn btn-sm text-white p-0" id="btn-toggle-contrast-mobile"><i class="bi bi-moon-stars-fill"></i></button>
                    </div>
                </div>

                <ul class="navbar-nav w-100 d-flex flex-lg-row gap-lg-1 py-1 py-lg-0 ms-lg-n3">
                    <li class="nav-item">
                        <a href="painel-admin.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-house-door me-2 me-lg-1"></i>Painel Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a href="estacionamento.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-p-circle me-2 me-lg-1"></i>Estacionamento</a>
                    </li>
                    <li class="nav-item">
                        <a href="gerenciar_condutores.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-people-fill me-2 me-lg-1"></i>Condutores</a>
                    </li>
                    <li class="nav-item">
                        <a href="relatorios.php" class="nav-link text-white fw-medium px-4 py-3 active"><i class="bi bi-bar-chart-line me-2 me-lg-1"></i>Relatórios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        <div class="row mb-3">
            <div class="col-12">
                <h2 class="text-dark border-bottom pb-2 mb-3" style="border-color: var(--cps-border) !important;"><i class="bi bi-file-earmark-text-fill me-2" style="color: var(--cps-red);"></i>Ata de Acessos e Relatórios</h2>
            </div>
        </div>

        <!-- Formulário de Filtros Dinâmicos -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <form method="GET" action="relatorios.php" class="row g-2 align-items-end">
                            <div class="col-md-4 col-lg-3">
                                <label class="form-label small text-secondary fw-bold mb-1">Busca Rápida</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-secondary"></i></span>
                                    <input type="text" name="busca" class="form-control border-start-0 custom-search" placeholder="Placa, Veículo, Condutor..." value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-6 col-md-3 col-lg-2">
                                <label class="form-label small text-secondary fw-bold mb-1">Data Inicial</label>
                                <input type="date" name="data_inicio" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['data_inicio'] ?? '') ?>">
                            </div>
                            <div class="col-6 col-md-3 col-lg-2">
                                <label class="form-label small text-secondary fw-bold mb-1">Data Final</label>
                                <input type="date" name="data_fim" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['data_fim'] ?? '') ?>">
                            </div>
                            <div class="col-12 col-lg-3 d-flex gap-2">
                                <button type="submit" class="btn btn-sm text-white flex-grow-1 fw-bold shadow-sm" style="background-color: var(--cps-red);"><i class="bi bi-funnel-fill me-1"></i> Filtrar Ata</button>
                                <a href="relatorios.php" class="btn btn-sm btn-light border text-secondary" title="Limpar Filtros"><i class="bi bi-eraser text-dark"></i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela (Ata Rolável) -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-lg">
                    <div class="card-header text-white fw-bold py-2 px-3 d-flex align-items-center" style="background-color: var(--cps-black);">
                        <i class="bi bi-card-list me-2"></i> Histórico de Movimentação (Ata)
                        <span class="badge bg-light text-dark ms-auto"><?= count($registrosAta) ?> Registros</span>
                    </div>
                    <!-- Área Interna Rolável via CSS -->
                    <div class="card-body p-0" style="max-height: 48vh; overflow-y: auto;">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0 align-middle">
                                <thead class="table-light sticky-top" style="z-index: 1;">
                                    <tr>
                                        <th class="ps-3 py-2 small fw-bold">Data / Hora</th>
                                        <th class="py-2 small fw-bold">Movimento</th>
                                        <th class="py-2 small fw-bold">Condutor / Visitante</th>
                                        <th class="py-2 small fw-bold">Placa</th>
                                        <th class="py-2 small fw-bold">Detalhes do Veículo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($registrosAta) > 0): ?>
                                        <?php foreach ($registrosAta as $reg): ?>
                                        <tr>
                                            <td class="ps-3 text-secondary" style="font-size: 0.85rem;">
                                                <i class="bi bi-calendar-event me-1"></i><?= date('d/m/Y', strtotime($reg['data_evento'])) ?>
                                                <span class="ms-1 fw-bold text-dark"><i class="bi bi-clock me-1"></i><?= date('H:i', strtotime($reg['data_evento'])) ?></span>
                                            </td>
                                            <td>
                                                <?php if($reg['tipo_movimento'] == 'Entrada'): ?>
                                                    <span class="badge bg-success bg-opacity-75 text-white fw-normal" style="font-size: 0.75rem;"><i class="bi bi-arrow-right-circle me-1"></i>Entrou</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary bg-opacity-75 text-white fw-normal" style="font-size: 0.75rem;"><i class="bi bi-arrow-left-circle me-1"></i>Saiu</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-medium text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($reg['nome_condutor']) ?></td>
                                            <td class="fw-bold" style="color: var(--cps-red); font-size: 0.9rem;">
                                                <?= !empty($reg['placa']) ? htmlspecialchars($reg['placa']) : '<span class="text-secondary fw-normal"><i class="bi bi-person-walking me-1"></i>Acesso a pé</span>' ?>
                                            </td>
                                            <td class="small text-secondary">
                                                <?= !empty($reg['tipo_veiculo']) ? htmlspecialchars($reg['tipo_veiculo']) . ' - ' . htmlspecialchars($reg['modelo']) : '-' ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-5 text-secondary">
                                            <i class="bi bi-search fs-2 mb-3 d-block"></i>
                                            Nenhum registro encontrado com estes filtros.<br>Tente expandir o calendário.
                                        </td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Central de Impressão (Ata Consolidada PDF) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-file-earmark-pdf-fill me-2" style="color: #dc3545;"></i>Imprimir relatório</h5>
                            <p class="text-secondary small mb-0">Selecione o período exato que deseja extrair todas as páginas da Ata Geral em um documento <strong class="text-dark">PDF</strong>.</p>
                        </div>
                        <div class="flex-shrink-0">
                            <form method="GET" action="imprimir_relatorio.php" target="_blank" class="d-flex flex-wrap gap-2 align-items-end">
                                <div>
                                    <label class="form-label small text-secondary fw-bold mb-1">A partir de</label>
                                    <input type="date" name="data_inicio" required class="form-control form-control-sm border-secondary">
                                </div>
                                <div>
                                    <label class="form-label small text-secondary fw-bold mb-1">Até (Inclusive)</label>
                                    <input type="date" name="data_fim" required class="form-control form-control-sm border-secondary">
                                </div>
                                <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm d-flex align-items-center h-100 mt-auto px-3 py-2" style="background-color: var(--cps-red);">
                                    <i class="bi bi-printer-fill me-2"></i> Gerar e Imprimir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico Estreito -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header text-white py-2 px-3 d-flex align-items-center bg-secondary">
                        <i class="bi bi-graph-up me-2"></i> <span class="fw-bold small">Frequência de Acesso (Mês Atual)</span>
                    </div>
                    <div class="card-body p-4 bg-white rounded-bottom border border-top-0">
                        <!-- Reduzindo altura do canvas para não tomar tanto espaço da ata -->
                        <canvas id="graficoAcessos" height="60"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('graficoAcessos').getContext('2d');
        const labels = <?php echo json_encode($labels); ?>;
        const data = <?php echo json_encode($valores); ?>;

        const graficoAcessos = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Número de Acessos',
                    data: data,
                    // Estilo de Colunas finas com cor principal do tema
                    backgroundColor: '#4A90E2', // Azul corporativo como no exemplo
                    borderWidth: 0,
                    borderRadius: 2,
                    barThickness: 16, // Deixa as colunas bem estreitas
                    maxBarThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#888',
                            font: { size: 11 },
                            stepSize: 10 // Pula de 10 em 10 baseado no exemplo de imagem
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.08)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#888',
                            font: { size: 10 },
                            maxRotation: 45,
                            minRotation: 45 // Rotação típica do Excel
                        },
                        grid: {
                            display: false, // Oculta as linhas verticais para dar aquele visual limpo e mais espaço visual
                            drawBorder: true
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Remover a legenda poluidora para focar nas colunas
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 30, 30, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 4,
                        displayColors: false
                    }
                }
            }
        });

        // Scripts de Usabilidade Compartilhados (RAV ETEC)
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const btnContrast = document.getElementById('btn-toggle-contrast');
            const btnContrastMobile = document.getElementById('btn-toggle-contrast-mobile');
            const btnIncrease = document.getElementById('btn-increase-font');
            const btnDecrease = document.getElementById('btn-decrease-font');
            const btnIncreaseMobile = document.getElementById('btn-increase-font-mobile');
            const btnDecreaseMobile = document.getElementById('btn-decrease-font-mobile');

            if (localStorage.getItem('theme') === 'dark') {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
                if(btnContrast) btnContrast.innerHTML = '<i class="bi bi-sun-fill fs-6"></i>';
                if(btnContrastMobile) btnContrastMobile.innerHTML = '<i class="bi bi-sun-fill"></i>';
            }
            
            function toggleTheme() {
                if (body.classList.contains('dark-mode')) {
                    body.classList.remove('dark-mode');
                    body.classList.add('light-mode');
                    if(btnContrast) btnContrast.innerHTML = '<i class="bi bi-moon-stars-fill fs-6"></i>';
                    if(btnContrastMobile) btnContrastMobile.innerHTML = '<i class="bi bi-moon-stars-fill"></i>';
                    localStorage.setItem('theme', 'light');
                } else {
                    body.classList.remove('light-mode');
                    body.classList.add('dark-mode');
                    if(btnContrast) btnContrast.innerHTML = '<i class="bi bi-sun-fill fs-6"></i>';
                    if(btnContrastMobile) btnContrastMobile.innerHTML = '<i class="bi bi-sun-fill"></i>';
                    localStorage.setItem('theme', 'dark');
                }
            }

            if(btnContrast) btnContrast.addEventListener('click', toggleTheme);
            if(btnContrastMobile) btnContrastMobile.addEventListener('click', toggleTheme);

            const maxScale = 1.3; 
            const minScale = 0.8; 
            let currentScale = parseFloat(localStorage.getItem('fontScale')) || 1.0;
            document.documentElement.style.setProperty('--font-scale', currentScale);

            function increaseFont() {
                if (currentScale < maxScale) {
                    currentScale += 0.1;
                    document.documentElement.style.setProperty('--font-scale', currentScale);
                    localStorage.setItem('fontScale', currentScale.toFixed(1));
                }
            }
            function decreaseFont() {
                if (currentScale > minScale) {
                    currentScale -= 0.1;
                    document.documentElement.style.setProperty('--font-scale', currentScale);
                    localStorage.setItem('fontScale', currentScale.toFixed(1));
                }
            }

            if(btnIncrease) btnIncrease.addEventListener('click', increaseFont);
            if(btnIncreaseMobile) btnIncreaseMobile.addEventListener('click', increaseFont);
            if(btnDecrease) btnDecrease.addEventListener('click', decreaseFont);
            if(btnDecreaseMobile) btnDecreaseMobile.addEventListener('click', decreaseFont);
        });
    </script>
</body>
</html>
