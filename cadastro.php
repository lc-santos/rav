<?php
session_start();
$login_error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | RAV</title>
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
                <h1 class="logo-text m-0 fw-bold d-flex align-items-center flex-wrap">
                    <span class="text-cps-red fs-2 me-2">RAV</span>
                    <span class="text-dark fs-4 mt-1">Registro de acesso de veículos</span>
                    <span class="badge bg-cps-red text-white ms-2 mt-2" style="font-size: 0.70rem; padding: 0.35em 0.65em;">ETEC's e FATEC's</span>
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
                            <h3 class="fw-bold">Cadastro de Unidade (ETEC/FATEC)</h3>
                            <p class="small text-muted mb-0">Insira os dados da instituição, do gestor principal e defina as credenciais de acesso.</p>
                        </div>

                        <?php if (!empty($login_error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show text-start small mb-4" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= htmlspecialchars($login_error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="salvar.php" method="POST">
                            <!-- LINHA 1: Dados da Unidade e Gestor Principal (Lado a lado - 6 colunas cada) -->
                            <div class="row g-4 mb-4">
                                <!-- Coluna 1: Dados da Unidade -->
                                <div class="col-12 col-lg-6 border-lg-end pe-lg-4 custom-border-mode">
                                    <h5 class="fw-bold text-cps-red mb-3 d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill me-2"></i> Dados da Unidade
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="empresaNome" class="form-label fw-medium small">Nome da Unidade (ETEC/FATEC)</label>
                                        <input type="text" class="form-control bg-light" id="empresaNome" name="empresaNome" placeholder="Ex: ETEC Centro Paula Souza" required>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-4 mb-3">
                                            <label for="tipoDocumento" class="form-label fw-medium small">Tipo</label>
                                            <select class="form-select bg-light" id="tipoDocumento" name="tipoDocumento" required>
                                                <option value="CNPJ" selected>CNPJ</option>
                                                <option value="INEP">INEP</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-8 mb-3">
                                            <label for="documento" class="form-label fw-medium small">Número do Documento</label>
                                            <input type="text" class="form-control bg-light" id="documento" name="documento" placeholder="00.000.000/0000-00" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label fw-medium small">Telefone Institucional</label>
                                        <input type="tel" class="form-control bg-light" id="telefone" name="telefone" data-mask="tel" placeholder="(11) 0000-0000" required>
                                    </div>
                                </div>

                                <!-- Coluna 2: Gestor Principal -->
                                <div class="col-12 col-lg-6 ps-lg-4">
                                    <h5 class="fw-bold text-cps-teal mb-3 d-flex align-items-center">
                                        <i class="bi bi-person-badge-fill me-2"></i> Gestor Principal
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="nome_completo" class="form-label fw-medium small">Nome Completo</label>
                                        <input type="text" class="form-control bg-light" id="nome_completo" name="nome_completo" placeholder="Nome do Gestor" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-medium small">E-mail Administrativo</label>
                                        <input type="email" class="form-control bg-light" id="email" name="email" placeholder="gestor@etec.sp.gov.br" required>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6 mb-3">
                                            <label for="cpf" class="form-label fw-medium small">CPF do Gestor</label>
                                            <input type="text" class="form-control bg-light" id="cpf" name="cpf" data-mask="cpf" placeholder="000.000.000-00" required>
                                        </div>
                                        <div class="col-12 col-sm-6 mb-3">
                                            <label for="datanasc" class="form-label fw-medium small">Data de Nascimento</label>
                                            <input type="date" class="form-control bg-light text-muted" id="datanasc" name="datanasc" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- LINHA 2: Endereço da Unidade (Largura total, com buscador de CEP integrado) -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="border rounded p-4 bg-white shadow-sm custom-panel-bg">
                                        <h5 class="fw-bold text-cps-red mb-3 d-flex align-items-center">
                                            <i class="bi bi-map-fill me-2"></i> Localização / Endereço da Unidade
                                        </h5>
                                        
                                        <div class="row g-3">
                                            <!-- CEP com Buscador -->
                                            <div class="col-12 col-md-3">
                                                <label for="cep" class="form-label fw-medium small">CEP</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control bg-light" id="cep" name="cep" placeholder="00000-000" required>
                                                    <button class="btn btn-outline-secondary" type="button" id="btn-buscar-cep" title="Buscar Endereço pelo CEP">
                                                        <i class="bi bi-search" id="icon-buscar-cep"></i>
                                                    </button>
                                                </div>
                                                <div class="form-text x-small text-muted mt-1" id="cep-feedback">Digite o CEP para autocompletar.</div>
                                            </div>
                                            
                                            <!-- Logradouro -->
                                            <div class="col-12 col-md-6">
                                                <label for="logradouro" class="form-label fw-medium small">Rua / Logradouro</label>
                                                <input type="text" class="form-control bg-light" id="logradouro" name="logradouro" placeholder="Avenida, Rua, Travessa..." required>
                                            </div>
                                            
                                            <!-- Número -->
                                            <div class="col-12 col-md-3">
                                                <label for="numero" class="form-label fw-medium small">Número</label>
                                                <input type="text" class="form-control bg-light" id="numero" name="numero" placeholder="Ex: 123 ou S/N" required>
                                            </div>
                                        </div>
                                        
                                        <div class="row g-3 mt-2">
                                            <!-- Complemento -->
                                            <div class="col-12 col-md-3">
                                                <label for="complemento" class="form-label fw-medium small">Complemento</label>
                                                <input type="text" class="form-control bg-light" id="complemento" name="complemento" placeholder="Sala, Bloco, etc. (Opcional)">
                                            </div>
                                            
                                            <!-- Bairro -->
                                            <div class="col-12 col-md-4">
                                                <label for="bairro" class="form-label fw-medium small">Bairro</label>
                                                <input type="text" class="form-control bg-light" id="bairro" name="bairro" placeholder="Nome do Bairro" required>
                                            </div>
                                            
                                            <!-- Cidade -->
                                            <div class="col-12 col-md-3">
                                                <label for="cidade" class="form-label fw-medium small">Cidade</label>
                                                <input type="text" class="form-control bg-light" id="cidade" name="cidade" placeholder="Ex: São Paulo" required>
                                            </div>
                                            
                                            <!-- UF -->
                                            <div class="col-12 col-md-2">
                                                <label for="uf" class="form-label fw-medium small">Estado (UF)</label>
                                                <select class="form-select bg-light" id="uf" name="uf" required>
                                                    <option value="" disabled selected>UF</option>
                                                    <option value="AC">AC</option>
                                                    <option value="AL">AL</option>
                                                    <option value="AP">AP</option>
                                                    <option value="AM">AM</option>
                                                    <option value="BA">BA</option>
                                                    <option value="CE">CE</option>
                                                    <option value="DF">DF</option>
                                                    <option value="ES">ES</option>
                                                    <option value="GO">GO</option>
                                                    <option value="MA">MA</option>
                                                    <option value="MT">MT</option>
                                                    <option value="MS">MS</option>
                                                    <option value="MG">MG</option>
                                                    <option value="PA">PA</option>
                                                    <option value="PB">PB</option>
                                                    <option value="PR">PR</option>
                                                    <option value="PE">PE</option>
                                                    <option value="PI">PI</option>
                                                    <option value="RJ">RJ</option>
                                                    <option value="RN">RN</option>
                                                    <option value="RS">RS</option>
                                                    <option value="RO">RO</option>
                                                    <option value="RR">RR</option>
                                                    <option value="SC">SC</option>
                                                    <option value="SP">SP</option>
                                                    <option value="SE">SE</option>
                                                    <option value="TO">TO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- LINHA 3: Credenciais do Sistema (3 colunas de igual tamanho col-lg-4) -->
                            <div class="row g-4">
                                <!-- Box 1: Código Identificador -->
                                <div class="col-12 col-lg-4">
                                    <div class="border rounded p-3 bg-white shadow-sm h-100 custom-panel-bg">
                                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center" style="font-size: 0.95rem;">
                                            <i class="bi bi-shield-lock-fill me-2 text-cps-red"></i> Identificação
                                        </h6>
                                        <div class="mb-3">
                                            <label for="codigo_identificador" class="form-label fw-medium small">Código Identificador (Login)</label>
                                            <input type="text" class="form-control bg-light" id="codigo_identificador" name="codigo_identificador" placeholder="Ex: etec123" required>
                                            <div class="form-text x-small text-muted mt-2">Código único para logar no sistema.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Box 2: Senha Administrativa -->
                                <div class="col-12 col-lg-4">
                                    <div class="border rounded p-3 bg-white shadow-sm h-100 custom-panel-bg">
                                        <h6 class="fw-bold text-cps-red mb-3 d-flex align-items-center" style="font-size: 0.95rem;">
                                            <i class="bi bi-person-gear me-2"></i> Senha Administrativa
                                        </h6>
                                        <div class="mb-3">
                                            <label for="senha_admin" class="form-label fw-medium small mb-1">Senha Geral</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control bg-light" id="senha_admin" name="senha_admin" minlength="6" placeholder="Mínimo 6 caracteres" required>
                                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="senha_admin"><i class="bi bi-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label for="senha_admin_confirm" class="form-label fw-medium small mb-1">Confirmar Senha</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control bg-light" id="senha_admin_confirm" placeholder="Repita a senha" required>
                                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="senha_admin_confirm"><i class="bi bi-eye"></i></button>
                                            </div>
                                            <div class="invalid-feedback text-danger x-small mt-1" id="error-senha-admin" style="display: none;">As senhas não coincidem.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Box 3: Senha da Portaria -->
                                <div class="col-12 col-lg-4">
                                    <div class="border rounded p-3 bg-white shadow-sm h-100 custom-panel-bg">
                                        <h6 class="fw-bold text-cps-teal mb-3 d-flex align-items-center" style="font-size: 0.95rem;">
                                            <i class="bi bi-shield-lock me-2"></i> Senha da Portaria
                                        </h6>
                                        <div class="mb-3">
                                            <label for="senha_portaria" class="form-label fw-medium small mb-1">Senha da Portaria</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control bg-light" id="senha_portaria" name="senha_portaria" minlength="6" placeholder="Mínimo 6 caracteres" required>
                                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="senha_portaria"><i class="bi bi-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label for="senha_portaria_confirm" class="form-label fw-medium small mb-1">Confirmar Senha</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control bg-light" id="senha_portaria_confirm" placeholder="Repita a senha" required>
                                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="senha_portaria_confirm"><i class="bi bi-eye"></i></button>
                                            </div>
                                            <div class="invalid-feedback text-danger x-small mt-1" id="error-senha-portaria" style="display: none;">As senhas não coincidem.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-center mt-4 pt-3 border-top">
                                <button type="submit" class="btn-capsule btn-lg px-5 shadow border-0 text-white" style="background-color: var(--cps-red);">
                                    Finalizar Cadastro da Unidade <i class="bi bi-check-circle-fill ms-2"></i>
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
            <small class="text-white-50">© 2026 RAV. Todos os direitos reservados. Sistema exclusivo para ETECs e FATECs.</small>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
    <script src="script/script.js"></script>

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

            // --- Controle de Máscara do Documento (CNPJ / INEP) ---
            const tipoDocumentoSelect = document.getElementById('tipoDocumento');
            const documentoInput = document.getElementById('documento');
            let documentoMask = null;

            function aplicarMascaraDocumento() {
                if (documentoMask) {
                    documentoMask.destroy();
                    documentoMask = null;
                }

                const tipo = tipoDocumentoSelect.value;
                if (tipo === 'CNPJ') {
                    documentoInput.placeholder = '00.000.000/0000-00';
                    documentoMask = IMask(documentoInput, { mask: '00.000.000/0000-00' });
                } else if (tipo === 'INEP') {
                    documentoInput.placeholder = '00000000';
                    documentoMask = IMask(documentoInput, { mask: '00000000' });
                }
            }

            if (tipoDocumentoSelect && documentoInput && typeof IMask !== 'undefined') {
                tipoDocumentoSelect.addEventListener('change', aplicarMascaraDocumento);
                aplicarMascaraDocumento();
            }

            // --- Toggle de Visualização de Senha ---
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            });

            // --- Validação de Senha em Tempo Real ---
            const form = document.querySelector('form');
            const senhaAdmin = document.getElementById('senha_admin');
            const senhaAdminConfirm = document.getElementById('senha_admin_confirm');
            const errorAdmin = document.getElementById('error-senha-admin');

            const senhaPortaria = document.getElementById('senha_portaria');
            const senhaPortariaConfirm = document.getElementById('senha_portaria_confirm');
            const errorPortaria = document.getElementById('error-senha-portaria');

            function validarSenhas() {
                let isValid = true;

                // Valida Admin
                if (senhaAdminConfirm.value !== '') {
                    if (senhaAdmin.value !== senhaAdminConfirm.value) {
                        senhaAdminConfirm.classList.add('is-invalid');
                        errorAdmin.style.display = 'block';
                        isValid = false;
                    } else {
                        senhaAdminConfirm.classList.remove('is-invalid');
                        errorAdmin.style.display = 'none';
                    }
                }

                // Valida Portaria
                if (senhaPortariaConfirm.value !== '') {
                    if (senhaPortaria.value !== senhaPortariaConfirm.value) {
                        senhaPortariaConfirm.classList.add('is-invalid');
                        errorPortaria.style.display = 'block';
                        isValid = false;
                    } else {
                        senhaPortariaConfirm.classList.remove('is-invalid');
                        errorPortaria.style.display = 'none';
                    }
                }

                return isValid;
            }

            if (senhaAdmin && senhaAdminConfirm && senhaPortaria && senhaPortariaConfirm) {
                senhaAdminConfirm.addEventListener('input', validarSenhas);
                senhaAdmin.addEventListener('input', validarSenhas);
                senhaPortariaConfirm.addEventListener('input', validarSenhas);
                senhaPortaria.addEventListener('input', validarSenhas);
            }

            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validarSenhas()) {
                        e.preventDefault();
                        alert('Por favor, corrija os erros de confirmação de senha.');
                    }
                });
            }

            // --- Controle de Máscara de CEP ---
            const cepInput = document.getElementById('cep');
            let cepMask = null;
            if (cepInput && typeof IMask !== 'undefined') {
                cepMask = IMask(cepInput, { mask: '00000-000' });
            }

            // --- Buscador de CEP (API ViaCEP) ---
            const btnBuscarCep = document.getElementById('btn-buscar-cep');
            const iconBuscarCep = document.getElementById('icon-buscar-cep');
            const cepFeedback = document.getElementById('cep-feedback');
            
            const logradouroInput = document.getElementById('logradouro');
            const bairroInput = document.getElementById('bairro');
            const cidadeInput = document.getElementById('cidade');
            const ufSelect = document.getElementById('uf');
            const numeroInput = document.getElementById('numero');

            async function buscarCEP() {
                const cepValor = cepInput.value.replace(/\D/g, '');
                
                if (cepValor.length !== 8) {
                    cepFeedback.textContent = 'CEP inválido. Digite 8 números.';
                    cepFeedback.className = 'form-text x-small text-danger mt-1';
                    return;
                }

                // Efeito visual de carregamento
                iconBuscarCep.className = 'bi bi-arrow-repeat spin-animation';
                cepFeedback.textContent = 'Buscando CEP...';
                cepFeedback.className = 'form-text x-small text-primary mt-1';
                btnBuscarCep.disabled = true;

                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cepValor}/json/`);
                    const data = await response.json();

                    if (data.erro) {
                        cepFeedback.textContent = 'CEP não encontrado.';
                        cepFeedback.className = 'form-text x-small text-danger mt-1';
                        logradouroInput.value = '';
                        bairroInput.value = '';
                        cidadeInput.value = '';
                        ufSelect.value = '';
                    } else {
                        cepFeedback.textContent = 'CEP encontrado!';
                        cepFeedback.className = 'form-text x-small text-success mt-1';
                        
                        logradouroInput.value = data.logradouro || '';
                        bairroInput.value = data.bairro || '';
                        cidadeInput.value = data.localidade || '';
                        ufSelect.value = data.uf || '';
                        
                        // Foca no campo de Número para agilizar o fluxo
                        numeroInput.focus();
                    }
                } catch (error) {
                    console.error('Erro ao buscar CEP:', error);
                    cepFeedback.textContent = 'Erro ao consultar a base de CEP.';
                    cepFeedback.className = 'form-text x-small text-danger mt-1';
                } finally {
                    iconBuscarCep.className = 'bi bi-search';
                    btnBuscarCep.disabled = false;
                }
            }

            if (btnBuscarCep && cepInput) {
                btnBuscarCep.addEventListener('click', buscarCEP);
                cepInput.addEventListener('blur', buscarCEP);
                cepInput.addEventListener('keyup', (e) => {
                    const cleaned = cepInput.value.replace(/\D/g, '');
                    if (cleaned.length === 8 && e.key !== 'Tab') {
                        buscarCEP();
                    }
                });
            }
        });
    </script>
    
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spin-animation {
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        
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
