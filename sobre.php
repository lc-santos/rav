<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV ETEC - Sobre a Iniciativa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
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
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3" href="funcionalidades.php">Funcionalidades</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3 active border-bottom border-3 border-white" href="sobre.php">Sobre o Sistema</a></li>

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
        <div class="container mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h6 class="text-cps-red fw-bold text-uppercase tracking-wider">Histórico e Propósito</h6>
                    <h1 class="display-5 fw-bold text-contrast mb-4">Por que criamos o RAV?</h1>
                    <p class="fs-5 text-contrast-secondary lh-lg mb-4">
                        Por anos, as portarias institucionais basearam-se em cadernos físicos. Isso gerava falhas, perdas de histórico e dificuldade enorme em realizar auditorias de segurança.
                    </p>
                    <p class="fs-5 text-contrast-secondary lh-lg mb-4">
                        O RAV (Registro de Acesso de Veículos) nasceu da urgência de modernizar a entrada das Escolas Técnicas do Centro Paula Souza. Ao levar a portaria integralmente para o formato Web, economizamos tempo na fila, papel para a escola, e garantimos que a diretoria possua métricas em tempo real sobre quem e quantos carros circulam pelo pátio a cada hora.
                    </p>
                 
                </div>
                <!-- Imagem Direita -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="bg-white rounded-4 shadow-sm border p-3">
                        <!-- Placeholder -->
                        <div class="rounded-3 bg-light d-flex align-items-center justify-content-center" style="height: 480px; font-size: 5rem; color: #ccc;">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white border-top border-bottom py-5">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <h2 class="fw-bold display-6">Nossos Pilares</h2>
                </div>
                <div class="row g-5">
                    <div class="col-md-4 text-center">
                        <i class="bi bi-shield-lock-fill text-cps-red pb-3 mb-3 border-bottom border-2 border-danger d-inline-block" style="font-size: 3rem;"></i>
                        <h4 class="fw-bold">Segurança</h4>
                        <p class="text-secondary">Arquitetura focada em proteção de senhas e identificação em conformidade com as exigências LGPD e de segurança estudantil.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-lightning-charge-fill text-primary pb-3 mb-3 border-bottom border-2 border-primary d-inline-block" style="font-size: 3rem;"></i>
                        <h4 class="fw-bold">Praticidade</h4>
                        <p class="text-secondary">Uma interface focada no dia a dia, concebida para exigir 'zero treinamento'. Elementos claros tornam óbvio e instintivo o fluxo da portaria.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-server text-success pb-3 mb-3 border-bottom border-2 border-success d-inline-block" style="font-size: 3rem;"></i>
                        <h2 class="fw-bold mb-3">Multi-Veículos e Gestão de Cadastros</h2>
                        <p class="mb-4">
                            Problemas com registros que mudam de veículo? O módulo de Gestão de Cadastros permite vincular diversas placas à mesma pessoa com histórico centralizado, evitando duplicidade e retrabalho de cadastro.
                        </p>
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
