<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV - Registro de Acesso</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/index.css">
</head>

<body class="light-mode">

    <!-- Acessibility Bar (SP Gov & CPS Style) -->
    <div class="accessibility-bar py-1">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <div class="gov-logo mb-2 mb-sm-0">
                <span class="fw-bold text-white small">RAV - PROJETO INSTITUCIONAL</span>
            </div>
            <div class="accessibility-tools d-flex align-items-center gap-3">
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-decrease-font"
                    title="Diminuir Fonte">A-</button>
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-increase-font"
                    title="Aumentar Fonte">A+</button>
                <button type="button" class="btn btn-sm text-white p-0 ms-2" id="btn-toggle-contrast"
                    title="Alto Contraste / Dark Mode"><i class="bi bi-moon-stars-fill fs-6"></i></button>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header bg-white shadow-sm sticky-top">
        <div class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <a href="index.php" class="text-decoration-none d-flex align-items-center mb-3 mb-md-0">
                <!-- Logotipo em texto, estilo CPS -->
                <h1 class="logo-text m-0 fw-bold d-flex align-items-center flex-wrap">
                    <span class="text-cps-red fs-1 me-2">RAV</span>
                    <span class="text-dark fs-4 mt-1">Registro de acesso de veículos</span>
                    <span class="badge bg-cps-red text-white ms-2 mt-2"
                        style="font-size: 0.70rem; padding: 0.35em 0.65em;">ETEC's e FATEC's</span>
                </h1>
            </a>

            <div class="search-bar position-relative" style="min-width: 300px;">
                <input type="text" class="form-control rounded-pill pe-5 py-2" placeholder="O que deseja localizar?">
                <button class="btn position-absolute end-0 top-0 h-100 text-cps-red" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>

        <!-- Navbar Vermelha -->
        <nav class="navbar navbar-expand-lg nav-cps p-0 shadow-sm">
            <div class="container">
                <button class="navbar-toggler collapsed my-2 border-white text-white" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <i class="bi bi-list fs-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav w-100 py-2 py-lg-0">
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3"
                                href="index.php">Início</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3"
                                href="funcionalidades.php">Funcionalidades</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3" href="sobre.php">Sobre o
                                Sistema</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3" href="comousar.php">Como
                                Usar</a></li>

                        <li
                            class="nav-item ms-lg-auto d-flex flex-column flex-lg-row align-items-center gap-2 py-2 py-lg-0">
                            <a class="btn btn-light text-cps-red fw-bold rounded-pill px-4 bg-white"
                                href="cadastro.php">
                                <i class="bi bi-building-fill me-1"></i> Cadastrar Unidade
                            </a>
                            <a class="btn btn-light text-cps-red fw-bold rounded-pill px-4 bg-white" href="login.php">
                                <i class="bi bi-person-fill me-1"></i> Acessar Sistema
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero-section position-relative" style="border-bottom: 2px solid var(--cps-red);">
            <div class="hero-bg position-absolute w-100 h-100"></div>
            <div class="container position-relative h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-7 text-white py-5 py-lg-0 hero-content text-center text-lg-start">
                        <span class="badge bg-cps-red text-white mb-4 fs-6 px-4 py-2 rounded-pill shadow-sm"
                            style="margin-top: 20px">RAV - Portaria Inteligente</span>
                        <h2 class="display-4 fw-bold mb-4" style="letter-spacing: -1px;">Gerenciamento de acesso de
                            veículos com segurança</h2>
                        <p class="fs-5 mb-5 opacity-75 fw-light lh-lg d-none d-md-block">
                            O Sistema RAV (Registro de Acesso de Veículos) moderniza o controle da portaria das ETECs,
                            oferecendo uma interface prática, relatórios automatizados e garantindo a proteção de
                            alunos, professores e visitantes.
                        </p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start mt-4">
                            <a href="#sobre" class="btn-capsule border-white text-white">Saiba Mais</a>
                            <a href="login.php" class="btn-capsule-white">Fazer Login <i
                                    class="bi bi-box-arrow-in-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Painel Central - Funcionalidades -->
        <div id="funcionalidades" class="hero-panel-wrapper w-100 position-relative"
            style="z-index: 10; margin-top: -60px;">
            <div class="container">
                <div class="bg-white shadow-lg rounded-4 p-2 custom-panel-bg border">
                    <div class="row scroll-cards flex-nowrap flex-lg-wrap align-items-center m-0 py-3">
                        <!-- Func 1 -->
                        <div class="col-10 col-md-5 col-lg-3 panel-item px-lg-4">
                            <div
                                class="d-flex flex-column flex-lg-row align-items-center gap-3 text-center text-lg-start">
                                <i class="bi bi-laptop fs-2 text-cps-red opacity-75"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">100% Web</h6>
                                    <p class="small text-muted mb-0 lh-sm">Sem instalação de apps ou aparelhos.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Func 2 -->
                        <div class="col-10 col-md-5 col-lg-3 panel-item px-lg-4 border-lg-start">
                            <div
                                class="d-flex flex-column flex-lg-row align-items-center gap-3 text-center text-lg-start">
                                <i class="bi bi-clock-history fs-2 text-warning opacity-75"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Ágil e Dinâmico</h6>
                                    <p class="small text-muted mb-0 lh-sm">Cadastros simplificados sem fila.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Func 3 -->
                        <div class="col-10 col-md-5 col-lg-3 panel-item px-lg-4 border-lg-start">
                            <div
                                class="d-flex flex-column flex-lg-row align-items-center gap-3 text-center text-lg-start">
                                <i class="bi bi-shield-check fs-2 text-success opacity-75"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Controle Total</h6>
                                    <p class="small text-muted mb-0 lh-sm">Supervisão de portaria garantida.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Func 4 -->
                        <div class="col-10 col-md-5 col-lg-3 panel-item px-lg-4 border-lg-start">
                            <div
                                class="d-flex flex-column flex-lg-row align-items-center gap-3 text-center text-lg-start">
                                <i class="bi bi-bar-chart-line fs-2 text-primary opacity-75"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Relatórios</h6>
                                    <p class="small text-muted mb-0 lh-sm">Métricas do pátio em tempo real.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botão Saiba Mais Funcionalidades -->

                </div>
            </div>
        </div>

        <!-- Espaçador -->
        <div style="height: 20px;" class="d-none d-lg-block spacer-cards"></div>

        <!-- Seção de Atalhos de Acesso -->
        <section id="sobre" class="py-5 section-bg-gray">
            <div class="container py-lg-4">
                <div class="row align-items-center g-5">

                    <!-- Coluna esquerda: Texto -->
                    <div class="col-lg-5 order-2 order-lg-1">
                        <div class="text-center text-lg-start">
                            <span class="atalho-label d-inline-block mb-3">Conheça o sistema</span>
                            <h2 class="fw-bold mb-4" style="font-size:2.2rem;line-height:1.2; letter-spacing: -0.5px;">Controle de acesso de veículos prático e ágil</h2>
                            <p class="text-muted mb-4 fs-5" style="line-height:1.6;">
                                O <strong>RAV</strong> moderniza a portaria de ETECs e FATECs sem necessidade de instalação de aplicativos.
                            </p>
                            
                            <ul class="list-unstyled mb-5 text-start d-flex flex-column gap-3">
                                <li class="d-flex align-items-center gap-3">
                                    <i class="bi bi-check2-circle text-success fw-bold fs-3" style="flex-shrink:0"></i>
                                    <span class="text-muted fs-5 fw-medium">Registros rápidos em segundos</span>
                                </li>
                                <li class="d-flex align-items-center gap-3">
                                    <i class="bi bi-check2-circle text-success fw-bold fs-3" style="flex-shrink:0"></i>
                                    <span class="text-muted fs-5 fw-medium">Painel otimizado para portaria</span>
                                </li>
                                <li class="d-flex align-items-center gap-3">
                                    <i class="bi bi-check2-circle text-success fw-bold fs-3" style="flex-shrink:0"></i>
                                    <span class="text-muted fs-5 fw-medium">Histórico digital unificado</span>
                                </li>
                            </ul>
                            
                            <a href="sobre.php" class="btn-cps-custom-primary px-4 py-2">Saiba mais</a>
                        </div>
                    </div>

                    <!-- Coluna direita: Grade de 3 atalhos -->
                    <div class="col-lg-7 order-1 order-lg-2">
                        <div class="row g-4">

                            <!-- Acessar Sistema (Destaque) -->
                            <div class="col-12">
                                <div class="sc-card sc-card--featured p-4 d-flex align-items-center justify-content-between gap-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="sc-icon" style="--sc-color:#c00000;--sc-bg:rgba(192,0,0,.08);">
                                            <i class="bi bi-door-open"></i>
                                        </div>
                                        <div>
                                            <div class="sc-title mb-1">Entrar no Sistema</div>
                                            <div class="sc-desc">Acesse o painel de portaria ou administração.</div>
                                        </div>
                                    </div>
                                    <a href="login.php" class="btn-cps-custom-primary px-4 py-2 text-decoration-none">Acessar</a>
                                </div>
                            </div>

                            <!-- Como Usar -->
                            <div class="col-12 col-md-6">
                                <div class="sc-card p-4 d-flex flex-column h-100 justify-content-between">
                                    <div>
                                        <div class="sc-icon mb-3" style="--sc-color:#2563eb;--sc-bg:rgba(37,99,235,.08);">
                                            <i class="bi bi-journal-text"></i>
                                        </div>
                                        <div class="sc-title mb-1">Como Usar</div>
                                        <div class="sc-desc mb-4">Guia rápido com instruções de operação.</div>
                                    </div>
                                    <a href="comousar.php" class="btn-cps-custom-primary px-3 py-2 text-center text-decoration-none">Ver guia</a>
                                </div>
                            </div>

                            <!-- Cadastrar Unidade -->
                            <div class="col-12 col-md-6">
                                <div class="sc-card p-4 d-flex flex-column h-100 justify-content-between">
                                    <div>
                                        <div class="sc-icon mb-3" style="--sc-color:#b45309;--sc-bg:rgba(180,83,9,.08);">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div class="sc-title mb-1">Cadastrar Unidade</div>
                                        <div class="sc-desc mb-4">Registre uma nova ETEC ou FATEC no RAV.</div>
                                    </div>
                                    <a href="cadastro.php" class="btn-cps-custom-primary px-3 py-2 text-center text-decoration-none">Cadastrar</a>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>


    </main>



    <!-- Call to Action -->
    <section class="py-5 section-bg-gray">
        <div class="container">
            <div class="rounded-4 shadow p-4 p-md-5 d-flex flex-column flex-md-row align-items-md-center justify-content-md-center gap-md-5 position-relative"
                style="background-color: #c00000; border: 1px solid #a30000;">

                <div class="text-white position-relative z-1 mb-4 mb-md-0 text-center text-md-start">
                    <h4 class="fw-bold mb-2">Pronto para transformar o acesso?</h4>
                    <p class="mb-0 text-white-50" style="font-size: 0.95rem;">Acesse o painel do RAV com suas
                        credenciais de portaria e simplifique hoje mesmo.</p>
                </div>

                <div class="position-relative z-1 flex-shrink-0 text-center">
                    <a href="login.php" class="btn-capsule-white fs-5 py-3 px-5 shadow-lg">
                        Acessar
                    </a>
                </div>
            </div>
        </div>
    </section>

    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3 footer-cps">
        <div class="container text-center text-md-start">
            <div class="row align-items-center g-4 mb-4">

                <!-- 1. Textos da Marca -->
                <div class="col-12 col-md-6 col-lg-5 text-center text-md-start">
                    <h3
                        class="fw-bold m-0 mb-3 d-flex align-items-center justify-content-center justify-content-md-start">
                        <span class="text-white">RAV</span>
                    </h3>
                    <p class="small text-white-50 lh-lg pe-md-3 m-0">
                        Sistema de Registro de Acesso de Veículos exclusivo para ETECs e FATECs. Desenvolvido para
                        modernizar, agilizar e trazer segurança inteligente para a portaria.
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

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts de Usabilidade -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;

            // --- Toggle Dark Mode / Alto Contraste ---
            const btnContrast = document.getElementById('btn-toggle-contrast');
            const iconContrast = btnContrast.querySelector('i');

            // Verifica o tema salvo
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

            // --- Acessibilidade: Controle de Fonte ---
            const btnIncrease = document.getElementById('btn-increase-font');
            const btnDecrease = document.getElementById('btn-decrease-font');
            const maxScale = 1.5;
            const minScale = 0.8;

            let currentScale = parseFloat(localStorage.getItem('fontScale')) || 1.0;
            document.documentElement.style.setProperty('--font-scale', currentScale);

            btnIncrease.addEventListener('click', () => {
                if (currentScale < maxScale) {
                    currentScale += 0.1;
                    document.documentElement.style.setProperty('--font-scale', currentScale);
                    localStorage.setItem('fontScale', currentScale.toFixed(1));
                }
            });

            btnDecrease.addEventListener('click', () => {
                if (currentScale > minScale) {
                    currentScale -= 0.1;
                    document.documentElement.style.setProperty('--font-scale', currentScale);
                    localStorage.setItem('fontScale', currentScale.toFixed(1));
                }
            });

        });
    </script>
</body>

</html>