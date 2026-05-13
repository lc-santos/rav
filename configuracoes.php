<?php
require_once 'conn.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV Admin - Configurações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .settings-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        .settings-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .settings-icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .bg-light-primary { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .bg-light-success { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
        .bg-light-warning { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .bg-light-danger { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
        
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }
    </style>
</head>
<body class="light-mode section-bg-gray d-flex flex-column min-vh-100">

    <!-- Navegação / Header Idêntico ao Painel Admin -->
    <div class="accessibility-bar py-1 d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="fw-bold text-white small">RAV - PROJETO INSTITUCIONAL</span>
            <div class="accessibility-tools gap-3 d-flex align-items-center">
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-decrease-font">A-</button>
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-increase-font">A+</button>
                <button type="button" class="btn btn-sm text-white p-0 ms-2" id="btn-toggle-contrast"><i class="bi bi-moon-stars-fill"></i></button>
            </div>
        </div>
    </div>

    <header class="main-header bg-white shadow-sm sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <a href="painel-admin.php" class="text-decoration-none d-flex align-items-center">
                    <h1 class="logo-text m-0 fw-bold d-flex align-items-center">
                        <span class="text-cps-red fs-2 me-1">RAV</span>
                        <span class="text-dark fs-4 mt-1">ETEC</span>
                        <span class="badge bg-secondary text-white font-monospace ms-2 mt-2" style="font-size: 0.70rem;">Config.</span>
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
                    <li><a class="dropdown-item" href="configuracoes.php"><i class="bi bi-gear me-2"></i>Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger fw-bold" href="sair.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                </ul>
            </div>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg nav-cps p-0" style="z-index: 1010;">
        <div class="container flex-column flex-lg-row">
            <div class="collapse navbar-collapse w-100" id="adminNavbar">
                <ul class="navbar-nav w-100 d-flex flex-lg-row gap-lg-1 py-1 py-lg-0 ms-lg-n3">
                    <li class="nav-item">
                        <a href="painel-admin.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-house-door me-2 me-lg-1"></i>Painel Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a href="acessos.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-list-check me-2 me-lg-1"></i>Acessos Diários</a>
                    </li>
                    <li class="nav-item">
                        <a href="estacionamento.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-p-circle me-2 me-lg-1"></i>Estacionamento</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark m-0">Configurações do Sistema</h3>
        </div>

        <div class="row g-4">
            <!-- Conta de Usuário -->
            <div class="col-md-6 col-lg-4">
                <div class="card settings-card">
                    <div class="card-body p-4">
                        <div class="settings-icon-box bg-light-primary">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Minha Conta</h5>
                        <p class="text-secondary small mb-4">Gerencie suas informações pessoais, senha e dados de acesso ao sistema.</p>
                        <form>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nome de Exibição</label>
                                <input type="text" class="form-control" value="Lucas Silva">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">E-mail</label>
                                <input type="email" class="form-control" value="lucas.silva@etec.sp.gov.br">
                            </div>
                            <button type="button" class="btn btn-primary w-100 fw-bold">Atualizar Perfil</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preferências de Sistema -->
            <div class="col-md-6 col-lg-4">
                <div class="card settings-card">
                    <div class="card-body p-4">
                        <div class="settings-icon-box bg-light-success">
                            <i class="bi bi-sliders"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Preferências Visuais</h5>
                        <p class="text-secondary small mb-4">Ajuste o visual do sistema para melhor conforto durante o uso.</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                            <div>
                                <h6 class="mb-0 fw-bold">Modo Escuro (Tema)</h6>
                                <small class="text-secondary">Ativar interface escura</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="themeSwitchConfig">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                            <div>
                                <h6 class="mb-0 fw-bold">Animações da Interface</h6>
                                <small class="text-secondary">Suavizar transições</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" checked>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notificações e Alertas -->
            <div class="col-md-6 col-lg-4">
                <div class="card settings-card">
                    <div class="card-body p-4">
                        <div class="settings-icon-box bg-light-warning">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Alertas e Notificações</h5>
                        <p class="text-secondary small mb-4">Configure como e quando o sistema deve alertar sobre atividades.</p>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="notif1" checked>
                            <label class="form-check-label" for="notif1">
                                Alertar quando a capacidade do estacionamento atingir 90%
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="notif2" checked>
                            <label class="form-check-label" for="notif2">
                                Emitir som ao registrar entrada
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="notif3">
                            <label class="form-check-label" for="notif3">
                                Notificações no navegador
                            </label>
                        </div>
                        
                        <button type="button" class="btn btn-warning text-dark w-100 fw-bold mt-2">Salvar Preferências</button>
                    </div>
                </div>
            </div>
            
            <!-- Segurança -->
            <div class="col-md-6 col-lg-4">
                <div class="card settings-card border-danger-subtle">
                    <div class="card-body p-4">
                        <div class="settings-icon-box bg-light-danger">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3 text-danger">Segurança</h5>
                        <p class="text-secondary small mb-4">Alterar senha e gerenciar segurança da conta ativa.</p>
                        
                        <form>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Senha Atual</label>
                                <input type="password" class="form-control" placeholder="••••••••">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nova Senha</label>
                                <input type="password" class="form-control" placeholder="••••••••">
                            </div>
                            <button type="button" class="btn btn-outline-danger w-100 fw-bold">Alterar Senha</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const themeSwitchConfig = document.getElementById('themeSwitchConfig');
            
            // Elementos da Barra Desktop e Mobile
            const btnContrast = document.getElementById('btn-toggle-contrast');
            const btnIncrease = document.getElementById('btn-increase-font');
            const btnDecrease = document.getElementById('btn-decrease-font');

            // Sincronizar o switch de tema da página de config com o localStorage
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
                if(btnContrast) btnContrast.innerHTML = '<i class="bi bi-sun-fill fs-6"></i>';
                if(themeSwitchConfig) themeSwitchConfig.checked = true;
            }
            
            function toggleTheme() {
                if (body.classList.contains('dark-mode')) {
                    body.classList.remove('dark-mode');
                    body.classList.add('light-mode');
                    if(btnContrast) btnContrast.innerHTML = '<i class="bi bi-moon-stars-fill fs-6"></i>';
                    if(themeSwitchConfig) themeSwitchConfig.checked = false;
                    localStorage.setItem('theme', 'light');
                } else {
                    body.classList.remove('light-mode');
                    body.classList.add('dark-mode');
                    if(btnContrast) btnContrast.innerHTML = '<i class="bi bi-sun-fill fs-6"></i>';
                    if(themeSwitchConfig) themeSwitchConfig.checked = true;
                    localStorage.setItem('theme', 'dark');
                }
            }

            if(btnContrast) btnContrast.addEventListener('click', toggleTheme);
            if(themeSwitchConfig) themeSwitchConfig.addEventListener('change', toggleTheme);

            // --- Acessibilidade: Controle de Fonte ---
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
            if(btnDecrease) btnDecrease.addEventListener('click', decreaseFont);
        });
    </script>
</body>
</html>
