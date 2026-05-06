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
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Design Exclusivo: Placa Mercosul CSS */
        .placa-mercosul {
            border: 2px solid #222;
            border-radius: 6px;
            background: #fff;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
            display: inline-block;
            width: 100%;
            max-width: 200px;
        }
        .placa-top {
            background-color: #003399;
            color: white;
            font-size: 0.55rem;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 0;
            letter-spacing: 2px;
            display: flex;
            justify-content: space-between;
            padding: 2px 10px;
        }
        .placa-number {
            font-family: 'Arial Black', Impact, sans-serif;
            font-size: 1.8rem;
            font-weight: 900;
            color: #111;
            padding: 4px 0;
            letter-spacing: 3px;
            line-height: 1.2;
        }
        
        /* Ajuste do Card da Vaga */
        .vaga-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(0,0,0,0.08);
            border-top: 4px solid var(--cps-red);
        }
        .vaga-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .dark-mode .vaga-card {
            background-color: #1e1e1e !important;
            border-color: #333 !important;
        }
        .dark-mode .placa-mercosul {
            box-shadow: 0 0 10px rgba(255,255,255,0.05);
        }
    </style>
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
                    <i class="bi bi-person-badge-fill fs-5 text-cps-red"></i>
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
                        <a href="estacionamento.php" class="nav-link text-white fw-medium px-4 py-3 active"><i class="bi bi-p-circle me-2 me-lg-1"></i>Estacionamento</a>
                    </li>
                    <li class="nav-item">
                        <a href="gerenciar_cadastros.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-people-fill me-2 me-lg-1"></i>Cadastros</a>
                    </li>
                    <li class="nav-item">
                        <a href="relatorios.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-bar-chart-line me-2 me-lg-1"></i>Relatórios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        
        <!-- Header Rápido e KPIs -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="text-dark mb-1 fw-bold"><i class="bi bi-p-circle-fill me-2" style="color: var(--cps-red);"></i>Pátio de Veículos</h2>
                <p class="text-secondary small mb-0">Visão geral e controle em tempo real do estacionamento</p>
            </div>
            <div class="d-flex gap-3">
                <div class="bg-white px-4 py-2 rounded shadow-sm border text-center">
                    <span class="d-block small text-secondary fw-bold text-uppercase">Ocupação Atual</span>
                    <span class="fs-4 fw-bold" style="color: var(--cps-red);"><?= count($veiculosDentro) ?> <i class="bi bi-car-front-fill ms-1 fs-5"></i></span>
                </div>
                <?php if (count($veiculosDentro) > 0): ?>
                <div class="bg-white px-4 py-2 rounded shadow-sm border text-center d-none d-sm-block">
                    <span class="d-block small text-secondary fw-bold text-uppercase">Última Entrada</span>
                    <span class="fs-5 fw-bold text-dark"><?= date('H:i', strtotime($veiculosDentro[0]['data_hora_entrada'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Barra de Filtro de Busca Interativa -->
        <div class="row mb-4">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="input-group input-group-lg bg-white rounded border overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0 text-secondary">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="filtroPatio" class="form-control border-0 custom-search" placeholder="Buscar por placa, nome, ou veículo...">
                </div>
            </div>
        </div>

        <!-- Grade de Cartões de Veículos (O Novo Layout) -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4" id="gridVeiculos">
            <?php if (count($veiculosDentro) > 0): ?>
                <?php foreach ($veiculosDentro as $reg): ?>
                    <div class="col vaga-item">
                        <div class="card h-100 vaga-card bg-white shadow-sm rounded-3 overflow-hidden">
                            <div class="card-body text-center pt-4 pb-3">
                                
                                <!-- Simulação de Placa Mercosul -->
                                <div class="placa-mercosul mb-3">
                                    <div class="placa-top">
                                        <span>Mercosul</span>
                                        <span>BR</span>
                                    </div>
                                    <div class="placa-number">
                                        <?= !empty($reg['placa']) ? htmlspecialchars($reg['placa']) : 'S/ PLACA' ?>
                                    </div>
                                </div>
                                
                                <h5 class="card-title fw-bold text-dark mb-1 d-flex align-items-center justify-content-center">
                                    <i class="bi <?= stripos($reg['tipo_veiculo'], 'moto') !== false ? 'bi-bicycle' : 'bi-car-front' ?> me-2 text-secondary"></i>
                                    <?= htmlspecialchars($reg['nome_condutor']) ?>
                                </h5>
                                
                                <p class="card-text text-secondary tag-veiculo small mb-2">
                                    <?= !empty($reg['tipo_veiculo']) ? htmlspecialchars($reg['tipo_veiculo']) . ' • ' . htmlspecialchars($reg['modelo']) : 'Visitante Sem Veículo/Pedestre' ?>
                                </p>
                                
                                <div class="d-flex align-items-center justify-content-center text-success small fw-bold bg-success bg-opacity-10 py-1 px-2 rounded-pill d-inline-block mx-auto mb-3">
                                    <i class="bi bi-clock-history me-1"></i> Entrou às <?= date('H:i', strtotime($reg['data_hora_entrada'])) ?>
                                </div>
                            </div>
                            
                            <!-- Ação da Vaga -->
                            <div class="card-footer bg-transparent border-top-0 p-3 pt-0">
                                <a href="registrar_saida.php?id=<?= $reg['id'] ?>&origem=estacionamento.php" class="btn btn-outline-danger w-100 fw-bold d-flex justify-content-center align-items-center gap-2">
                                    <i class="bi bi-box-arrow-right fs-5"></i> Confirmar Saída
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Estado Vazio Bonito -->
                <div class="col-12">
                    <div class="text-center py-5 bg-white rounded-3 shadow-sm border border-secondary border-dashed" style="border-style: dashed !important;">
                        <i class="bi bi-cup-hot text-secondary opacity-50 mb-3 block" style="font-size: 4rem;"></i>
                        <h4 class="text-secondary fw-bold">Pátio Livre</h4>
                        <p class="text-muted">Nenhum veículo estacionado no momento.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Alerta quando Filtro não encontra nada -->
        <div id="noResultsMsg" class="text-center py-5 d-none">
            <h5 class="text-secondary fw-bold"><i class="bi bi-search me-2"></i>Nenhuma vaga encontrada para esta busca.</h5>
        </div>
        
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- LÓGICA DE FILTRO INSTANTÂNEO ---
        const filtroInput = document.getElementById('filtroPatio');
        const gridVeiculos = document.getElementById('gridVeiculos');
        const vagas = document.querySelectorAll('.vaga-item');
        const noResultsMsg = document.getElementById('noResultsMsg');
        let isTyping = false;

        if(filtroInput && vagas.length > 0) {
            filtroInput.addEventListener('keyup', function() {
                const termo = this.value.toLowerCase();
                isTyping = termo.length > 0;
                let visibleCount = 0;

                vagas.forEach(vaga => {
                    // Pega todo o texto dentro do card (Placa, Nome, etc)
                    const conteudo = vaga.textContent.toLowerCase();
                    if (conteudo.includes(termo)) {
                        vaga.style.display = 'block';
                        visibleCount++;
                    } else {
                        vaga.style.display = 'none';
                    }
                });

                if(visibleCount === 0) {
                    noResultsMsg.classList.remove('d-none');
                } else {
                    noResultsMsg.classList.add('d-none');
                }
            });
        }

        // --- SISTEMA DE RECARREGAMENTO INTELIGENTE ---
        // Só atualiza a página a cada 10 segs SE o usuário não estiver buscando nada!
        setInterval(() => {
            if(!isTyping) {
                location.reload();
            }
        }, 10000);

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