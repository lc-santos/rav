<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | RAV ETEC</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS Base do Index -->
    <link rel="stylesheet" href="css/index.css">
</head>
<body class="light-mode d-flex flex-column min-vh-100 section-bg-gray">

    <!-- Acessibility Bar (SP Gov & CPS Style) -->
    <div class="accessibility-bar py-1">
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

    <!-- Header Foco Total -->
    <header class="main-header bg-white shadow-sm sticky-top border-bottom border-cps-red" style="border-bottom: 3px solid var(--cps-red);">
        <div class="container py-3 d-flex justify-content-center justify-content-md-between align-items-center">
            <a href="index.php" class="text-decoration-none d-flex align-items-center">
                <h1 class="logo-text m-0 fw-bold d-flex align-items-center">
                    <span class="text-cps-red fs-2 me-1">RAV</span>
                    <span class="text-dark fs-4 mt-1">ETEC</span>
                </h1>
            </a>
            <a href="index.php" class="btn btn-outline-secondary btn-sm d-none d-md-flex align-items-center rounded-pill px-3">
                <i class="bi bi-arrow-left me-2"></i> Voltar à Home
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container flex-grow-1 d-flex align-items-center justify-content-center py-5">
        <div class="row justify-content-center w-100">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-lg border-0 rounded-4 custom-panel-bg">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle fs-1 text-cps-red mb-2 d-inline-block"></i>
                            <h3 class="fw-bold">Acesso ao Sistema</h3>
                            <p class="small text-muted">Entre com suas credenciais de portaria ou administração.</p>
                        </div>

                        <form action="processa-login.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium small">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control border-start-0 ps-0 bg-light" id="email" name="email" placeholder="nome@etec.sp.gov.br" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="senha" class="form-label fw-medium small d-flex justify-content-between">
                                    Senha 
                                    <a href="#" class="text-cps-red text-decoration-none">Esqueceu?</a>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control border-start-0 ps-0 bg-light" id="senha" name="senha" placeholder="Sua senha" required>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn bg-cps-red text-white btn-lg fw-bold rounded-pill shadow-sm login-btn">
                                    Acessar Painel <i class="bi bi-box-arrow-in-right ms-1"></i>
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="small text-muted">Ainda não possui acesso?</span><br>
                            <a href="cadastro.php" class="fw-bold text-cps-red text-decoration-none">Cadastre sua unidade</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-cps bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <small class="text-white-50">© 2026 RAV ETEC. Todos os direitos reservados.</small>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts de Usabilidade Compartilhados -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            
            // --- Toggle Dark Mode / Alto Contraste ---
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

            // --- Acessibilidade: Controle de Fonte ---
            const btnIncrease = document.getElementById('btn-increase-font');
            const btnDecrease = document.getElementById('btn-decrease-font');
            const maxScale = 1.3; 
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
    
    <style>
        .login-btn {
            transition: all 0.3s ease;
        }
        .login-btn:hover {
            background-color: var(--cps-red-dark) !important;
            transform: translateY(-2px);
        }
        
        .dark-mode .input-group-text, 
        .dark-mode .form-control {
            background-color: #2a2a2a !important;
            border-color: #444 !important;
            color: #f4f4f4 !important;
        }
        
        .dark-mode .form-control::placeholder {
            color: #888 !important;
        }
    </style>
</body>
</html>