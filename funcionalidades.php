<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV ETEC - Funcionalidades em Detalhes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
    <style>
        .feature-card { transition: all 0.3s ease; border-top: 4px solid transparent; }
        .feature-card:hover { transform: translateY(-5px); border-top-color: var(--cps-red); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
        .feature-icon-wrapper { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 15px; background: rgba(204, 0, 0, 0.1); }
    </style>
</head>
<body class="light-mode section-bg-gray">

    <!-- Acessibility Bar -->
    <div class="accessibility-bar py-1">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <div class="gov-logo mb-2 mb-sm-0">
                <span class="fw-bold text-white small">RAV - PROJETO INSTITUCIONAL</span>
            </div>
            <div class="accessibility-tools d-flex align-items-center gap-3">
                <a href="#" class="text-white text-decoration-none small"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white text-decoration-none small"><i class="bi bi-instagram"></i></a>
                <span class="text-white-50 mx-1">|</span>
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-decrease-font" title="Diminuir Fonte">A-</button>
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-increase-font" title="Aumentar Fonte">A+</button>
                <button type="button" class="btn btn-sm text-white p-0 ms-2" id="btn-toggle-contrast"><i class="bi bi-moon-stars-fill fs-6"></i></button>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header bg-white shadow-sm sticky-top">
        <div class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <a href="index.php" class="text-decoration-none d-flex align-items-center mb-3 mb-md-0">
                <h1 class="logo-text m-0 fw-bold d-flex align-items-center">
                    <span class="text-cps-red fs-1 me-1">RAV</span>
                    <span class="text-dark fs-3 mt-1">ETEC</span>
                </h1>
            </a>
            <div class="search-bar position-relative" style="min-width: 300px;">
                <input type="text" class="form-control rounded-pill pe-5 py-2" placeholder="O que deseja localizar?">
                <button class="btn position-absolute end-0 top-0 h-100 text-cps-red" type="button"><i class="bi bi-search"></i></button>
            </div>
        </div>
        
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg nav-cps p-0 shadow-sm">
            <div class="container">
                <button class="navbar-toggler my-2 border-white text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <i class="bi bi-list fs-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav w-100 py-2 py-lg-0">
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3" href="index.php">Início</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3 active border-bottom border-3 border-white" href="funcionalidades.php">Funcionalidades</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3" href="sobre.php">Sobre o Sistema</a></li>

                        <li class="nav-item ms-lg-auto d-flex align-items-center py-2 py-lg-0">
                            <a class="btn btn-light text-cps-red fw-bold rounded-pill px-4 bg-white" href="login.php">
                                <i class="bi bi-person-fill me-1"></i> Acessar Sistema
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-5">
        <!-- Hero Funcionalidades -->
        <div class="container mb-5 text-center">
            <h1 class="display-5 fw-bold text-dark mb-4">Tudo que o RAV ETEC Oferece</h1>
            <p class="fs-5 text-secondary mx-auto" style="max-width: 800px;">
                Uma quebra de paradigma na forma como unidades de ensino técnico operam. Conheça detalhadamente cada módulo que engloba a segurança inteligente da plataforma RAV.
            </p>
        </div>

        <div class="container">
            <!-- Funcionalidade 1 -->
            <div class="row align-items-center mb-5 pb-5 border-bottom border-secondary-subtle">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="bg-white p-2 rounded-4 shadow-sm border">
                        <!-- Imagem placeholder representativa -->
                        <div class="bg-light rounded-3 display-1 d-flex align-items-center justify-content-center text-primary" style="height: 350px;">
                            <i class="bi bi-textarea-resize"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 ms-auto">
                    <div class="feature-icon-wrapper mb-3">
                        <i class="bi bi-stopwatch fs-2 text-cps-red"></i>
                    </div>
                    <h2 class="fw-bold mb-3 text-contrast">Controle em Tempo Real no Estacionamento</h2>
                    <p class="text-secondary fs-6 lh-lg mb-4 text-contrast-secondary">
                        O monitoramento diário é a espinha dorsal de um centro Paula Souza seguro. No módulo "Estacionamento", a portaria tem a visão cirúrgica de todos os veículos presentes no pátio, organizados por card em um painel sem recarregamentos automáticos invasivos.
                    </p>
                    <ul class="list-unstyled text-dark mb-4">
                        
                    <a href="login.php" class="btn-capsule">Testar Agora <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>

            <!-- Funcionalidade 2 -->
            <div class="row align-items-center flex-row-reverse mb-5 pb-5 border-bottom border-secondary-subtle">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="bg-white p-2 rounded-4 shadow-sm border">
                        <div class="bg-light rounded-3 display-1 d-flex align-items-center justify-content-center text-primary" style="height: 350px;">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 me-auto">
                    <div class="feature-icon-wrapper mb-3" style="background: rgba(18, 113, 135, 0.1);">
                        <i class="bi bi-people-fill fs-2 text-primary"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Multi-Veículos e Gestão de Condutores</h2>
                    <p class="text-secondary fs-6 lh-lg mb-4">
                        Problemas com condutores que mudam de veículo? O módulo de Gestão de Condutores permite vincular diversas placas à mesma pessoa com histórico centralizado, evitando duplicidade e retrabalho de cadastro.
                    </p>
                    <ul class="list-unstyled text-dark mb-4">
                        
                </div>
            </div>

            <!-- Funcionalidade 3 -->
            <div class="row align-items-center mb-5 pb-4">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="bg-white p-2 rounded-4 shadow-sm border">
                        <div class="bg-light rounded-3 display-1 d-flex align-items-center justify-content-center text-success" style="height: 350px;">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 ms-auto">
                    <div class="feature-icon-wrapper mb-3" style="background: rgba(25, 135, 84, 0.1);">
                        <i class="bi bi-bar-chart-line-fill fs-2 text-success"></i>
                    </div>
                    <h2 class="fw-bold mb-3 text-contrast">Relatórios Automatizados</h2>
                    <p class="text-contrast-secondary fs-6 lh-lg mb-4">
                        A direção escolar exige transparência e agilidade na auditoria. Com o Módulo de Relacionamentos, geramos Atas em formato PDF oficial com a assinatura do sistema para envio à central de controle ou conselho tutelar em segundos.
                    </p>
                    <ul class="list-unstyled text-contrast mb-4">
                        
                    <a href="login.php" class="btn-capsule">Ver Modelos de Ata <i class="bi bi-file-pdf ms-2"></i></a>
                </div>
            </div>
            
            <!-- Cards menores para extras -->
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="card h-100 bg-white border border-light-subtle shadow-sm rounded-4 feature-card p-4">
                        <i class="bi bi-phone-fill fs-2 text-dark mb-3"></i>
                        <h5 class="fw-bold mb-2">Interface Responsiva</h5>
                        <p class="text-secondary small mb-0">Totalmente acessível via celular, tablet ou desktop. A tela do porteiro funciona onde ele precisar.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 bg-white border border-light-subtle shadow-sm rounded-4 feature-card p-4">
                        <i class="bi bi-whatsapp fs-2 text-success mb-3"></i>
                        <h5 class="fw-bold mb-2">Ação Rápida WhatsApp</h5>
                        <p class="text-secondary small mb-0">Em caso de estacionamento indevido, acione rapidamente o responsável no WhatsApp pelo perfil do condutor.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 bg-white border border-light-subtle shadow-sm rounded-4 feature-card p-4">
                        <i class="bi bi-cloud-check-fill fs-2 text-primary mb-3"></i>
                        <h5 class="fw-bold mb-2">100% Cloud Base</h5>
                        <p class="text-secondary small mb-0">Sem hardwares complexos. A arquitetura roda através de servidores web e bancos SQL em tempo real.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3 footer-cps">
        <div class="container text-center text-md-start">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-12 col-md-6 col-lg-5 text-center text-md-start">
                    <h3 class="fw-bold m-0 mb-3 d-flex align-items-center justify-content-center justify-content-md-start">
                       <span class="text-white">RAV</span> <span class="text-cps-red ms-2">ETEC</span>
                    </h3>
                    <p class="small text-white-50 lh-lg pe-md-3">
                        Sistema Integrado de Registro de Acesso de Veículos. Desenvolvido para modernizar, agilizar e trazer segurança inteligente para a portaria da sua unidade escolar.
                    </p>
                </div>

            </div>
            <hr class="border-white opacity-25">
            <div class="row align-items-center pt-2">
                <div class="col-md-6 text-center text-md-start">
                    <small class="text-white-50">© 2026 rav-e. Todos os direitos reservados.</small>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <div class="d-inline-flex gap-3 social-icons">
                        <a href="#"><i class="bi bi-envelope"></i></a>
                        <a href="#"><i class="bi bi-shield-check"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap/Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const btnContrast = document.getElementById('btn-toggle-contrast');
            const iconContrast = btnContrast.querySelector('i');
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
                iconContrast.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
            }
            btnContrast.addEventListener('click', () => {
                if (body.classList.contains('dark-mode')) {
                    body.classList.remove('dark-mode');
                    body.classList.add('light-mode');
                    iconContrast.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
                    localStorage.setItem('theme', 'light');
                } else {
                    body.classList.remove('light-mode');
                    body.classList.add('dark-mode');
                    iconContrast.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
                    localStorage.setItem('theme', 'dark');
                }
            });
            let currentScale = parseFloat(localStorage.getItem('fontScale')) || 1.0;
            document.documentElement.style.setProperty('--font-scale', currentScale);
            document.getElementById('btn-increase-font').addEventListener('click', () => {
                if (currentScale < 1.3) { currentScale += 0.1; document.documentElement.style.setProperty('--font-scale', currentScale); localStorage.setItem('fontScale', currentScale.toFixed(1)); }
            });
            document.getElementById('btn-decrease-font').addEventListener('click', () => {
                if (currentScale > 0.8) { currentScale -= 0.1; document.documentElement.style.setProperty('--font-scale', currentScale); localStorage.setItem('fontScale', currentScale.toFixed(1)); }
            });
        });
    </script>
</body>
</html>
