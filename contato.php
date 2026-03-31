<?php
$status = $_GET['status'] ?? null;
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV ETEC - Fale Conosco</title>
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
                <a href="https://www.instagram.com/governosp" class="text-white text-decoration-none small"><i class="bi bi-instagram"></i></a>
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
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3" href="sobre.php">Sobre o Sistema</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3 active border-bottom border-3 border-white" href="contato.php">Fale Conosco</a></li>
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
            <div class="text-center mb-5">
                <h6 class="text-cps-red fw-bold text-uppercase tracking-wider">Suporte Direto</h6>
                <h1 class="display-5 fw-bold text-dark mb-4">Fale Conosco</h1>
                <p class="fs-5 text-secondary mx-auto" style="max-width: 700px;">Dúvidas sobre integração, sugestões de novas funcionalidades ou suporte à nossa plataforma escolar? Mande uma mensagem e o time de desenvolvimento entrará em contato.</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="h-100 pe-lg-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm text-cps-red border border-light-subtle me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Localização Sede</h6>
                                <p class="text-secondary small mb-0">São Paulo, SP - Centro Paula Souza</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm text-primary border border-light-subtle me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Email de Contato</h6>
                                <p class="text-secondary small mb-0">suporte@rav.cps.sp.gov.br</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm text-success border border-light-subtle me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Suporte Técnico</h6>
                                <p class="text-secondary small mb-0">(11) 99999-9999</p>
                            </div>
                        </div>
                        
                        <div class="card bg-cps-red text-white border-0 shadow-sm rounded-4 mt-5 p-4" style="background-image: var(--bg-pattern); background-size: 200px;">
                            <h5 class="fw-bold">Para ETECs e FATECs</h5>
                            <p class="small opacity-75 mb-0">Se a sua unidade escolar ainda não aderiu à modernização da portaria, preencha o formulário para solicitar o piloto de implementação gratuito no próximo semestre.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="bg-white shadow-sm p-4 p-lg-5 rounded-4 border border-light-subtle position-relative overflow-hidden">
                        <!-- Subtle BG -->
                        <div class="position-absolute w-100 h-100 opacity-25" style="background-image: var(--bg-pattern); background-size: 300px; pointer-events: none; top:0; left:0; z-index: 0;"></div>
                        
                        <div class="position-relative" style="z-index: 1;">
                            <?php if ($status === 'success'): ?>
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                    <div>Mensagem enviada com sucesso! Logo entraremos em contato.</div>
                                </div>
                            <?php elseif ($status === 'error'): ?>
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                    <div><?= htmlspecialchars($msg) ?></div>
                                </div>
                            <?php endif; ?>

                            <form action="processa_contato.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6 text-start">
                                        <label class="form-label fw-medium text-secondary small">Nome Completo</label>
                                        <input type="text" name="nome" class="form-control px-3 py-2 bg-light border-0" required placeholder="João da Silva">
                                    </div>
                                    <div class="col-md-6 text-start">
                                        <label class="form-label fw-medium text-secondary small">Email Institucional</label>
                                        <input type="email" name="email" class="form-control px-3 py-2 bg-light border-0" required placeholder="joao@etec.sp.gov.br">
                                    </div>
                                    <div class="col-md-6 text-start">
                                        <label class="form-label fw-medium text-secondary small">Telefone (Opcional)</label>
                                        <input type="text" name="telefone" class="form-control px-3 py-2 bg-light border-0" placeholder="(11) 90000-0000">
                                    </div>
                                    <div class="col-md-6 text-start">
                                        <label class="form-label fw-medium text-secondary small">Assunto</label>
                                        <select name="assunto" class="form-select px-3 py-2 bg-light border-0" required>
                                            <option value="">Selecione...</option>
                                            <option value="Dúvida Técnica">Dúvida Técnica</option>
                                            <option value="Integração (Nova Escola)">Integração (Nova Escola)</option>
                                            <option value="Relato de Bug">Relato de Bug</option>
                                            <option value="Sugestão">Sugestão de Funcionalidade</option>
                                        </select>
                                    </div>
                                    <div class="col-12 text-start">
                                        <label class="form-label fw-medium text-secondary small">Sua Mensagem</label>
                                        <textarea name="mensagem" class="form-control px-3 py-2 bg-light border-0" rows="5" required placeholder="Descreva brevemente o motivo do contato..."></textarea>
                                    </div>
                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm">
                                            Enviar Mensagem <i class="bi bi-send ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
