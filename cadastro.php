<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | RAV ETEC</title>
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
            <a href="login.php" class="btn-capsule d-none d-md-flex" style="padding: 0.4rem 1.5rem; font-size: 0.8rem;">
                <i class="bi bi-person-fill me-2"></i> Ir para Login
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container flex-grow-1 d-flex align-items-center justify-content-center py-5">
        <div class="row justify-content-center w-100">
            <div class="col-12 col-lg-12 col-xl-11">
                <div class="card shadow-lg border-0 rounded-4 custom-panel-bg">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-5 border-bottom pb-4">
                            <i class="bi bi-building fs-1 text-cps-red mb-2 d-inline-block"></i>
                            <h3 class="fw-bold">Cadastro de Unidade e Gestor</h3>
                            <p class="small text-muted mb-0">Insira os dados da sua instituição de ensino e do primeiro administrador.</p>
                        </div>

                        <form action="processa-cadastro.php" method="POST">
                            <div class="row g-4">
                                <!-- Coluna Empresa/Unidade -->
                                <div class="col-12 col-md-6 border-md-end pe-md-4 custom-border-mode">
                                    <h5 class="fw-bold text-cps-red mb-3 d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill me-2"></i> Dados da Unidade
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="empresaNome" class="form-label fw-medium small">Nome da Unidade (ETEC/FATEC)</label>
                                        <input type="text" class="form-control bg-light" id="empresaNome" name="empresaNome" placeholder="Ex: ETEC..." required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tipoDocumento" class="form-label fw-medium small">Tipo de Documento</label>
                                        <input type="text" class="form-control bg-light" id="tipoDocumento" name="tipoDocumento" placeholder="CNPJ / INEP" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="documento" class="form-label fw-medium small">Número do Documento</label>
                                        <input type="text" class="form-control bg-light" id="documento" name="documento" placeholder="00.000.000/0000-00" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label fw-medium small">Telefone Institucional</label>
                                        <input type="text" class="form-control bg-light" id="telefone" name="telefone" placeholder="(11) 0000-0000" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="endereco" class="form-label fw-medium small">Endereço Completo</label>
                                        <input type="text" class="form-control bg-light" id="endereco" name="endereco" placeholder="Rua, Número, Cidade" required>
                                    </div>
                                </div>

                                <!-- Coluna Usuário Administrador -->
                                <div class="col-12 col-md-6 ps-md-4">
                                    <h5 class="fw-bold text-cps-teal mb-3 d-flex align-items-center">
                                        <i class="bi bi-person-badge-fill me-2"></i> Gestor Principal
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="nome_completo" class="form-label fw-medium small">Nome Completo</label>
                                        <input type="text" class="form-control bg-light" id="nome_completo" name="nome_completo" placeholder="Nome do Admin" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-medium small">E-mail Administrativo</label>
                                        <input type="email" class="form-control bg-light" id="email" name="email" placeholder="admin@etec.sp.gov.br" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cpf" class="form-label fw-medium small">CPF</label>
                                        <input type="text" class="form-control bg-light" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="datanasc" class="form-label fw-medium small">Data de Nascimento</label>
                                        <input type="date" class="form-control bg-light text-muted" id="datanasc" name="datanasc">
                                    </div>
                                    <div class="mb-3">
                                        <label for="senha" class="form-label fw-medium small">Senha de Acesso</label>
                                        <input type="password" class="form-control bg-light" id="senha" name="senha" placeholder="Crie uma senha forte" required>
                                    </div>
                                </div>
                            </div>

                                <div class="col-12 text-center mt-3 pt-3 border-top">
                                    <button type="submit" class="btn-capsule btn-lg px-5 shadow border-0 text-white" style="background-color: var(--cps-red);">
                                        Finalizar Cadastro <i class="bi bi-check-circle-fill ms-2"></i>
                                    </button>
                                </div>
                        </form>

                        <div class="text-center mt-4 pt-3">
                            <span class="small text-muted">A unidade já está registrada?</span><br>
                            <a href="login.php" class="fw-bold text-cps-teal text-decoration-none">Faça login no painel</a>
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
        
        .dark-mode .form-control {
            background-color: #2a2a2a !important;
            border-color: #444 !important;
            color: #f4f4f4 !important;
        }
        
        .dark-mode .form-control::placeholder {
            color: #888 !important;
        }

        .dark-mode .border-md-end {
            border-color: #333 !important;
        }

        .dark-mode .custom-border-mode {
            border-color: #333 !important;
        }
        
        @media (max-width: 576px) {
            .login-btn {
                width: 100% !important;
            }
        }
    </style>
</body>
</html>