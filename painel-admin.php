<?php
require_once 'conn.php';

// Busca veículos que estão "Dentro" (Ajustado para data_hora_entrada)
$stmtSaida = $pdo->query("SELECT r.*, v.placa FROM registros_acesso r 
                          JOIN veiculos v ON r.id_veiculo = v.id 
                          WHERE r.status = 'Dentro' ORDER BY r.data_hora_entrada DESC");
$veiculosDentro = $stmtSaida->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="light-mode section-bg-gray d-flex flex-column min-vh-100">

    <!-- Acessibility Bar (SP Gov & CPS Style) -->
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

    <!-- Header Branco (Logo e Usuário) -->
    <header class="main-header bg-white shadow-sm sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <!-- Mobile Menu Button (Hamburger) -->
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-expanded="false" aria-controls="adminNavbar">
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
                <button class="btn btn-light rounded-pill border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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

    <!-- Navbar Horizontal vermelha padronizada -->
    <nav class="navbar navbar-expand-lg nav-cps p-0" style="z-index: 1010;">
        <div class="container flex-column flex-lg-row">
            <div class="collapse navbar-collapse w-100" id="adminNavbar">
                <!-- Mobile only Accessibility Links -->
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
                        <a href="painel-admin.php" class="nav-link text-white fw-medium px-4 py-3 active"><i class="bi bi-house-door me-2 me-lg-1"></i>Painel Inicial</a>
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

    <main class="container py-4">

        <div class="row mb-4">
            <div class="col-12">
                <div class="input-group input-group-lg custom-search position-relative">
                    <span class="busca-icon">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Placa, Veículo, Nome...">
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-12 col-lg-6">

                <div class="card bg-dark-card border-success-subtle mb-4 shadow-lg h-100 d-flex flex-column">
                    <div class="card-header bg-success text-white py-2 px-3 d-flex align-items-center flex-shrink-0">
                        <i class="bi bi-plus-circle-fill me-2"></i> <span class="fw-bold">Registrar Acesso</span>
                    </div>
                    <div class="card-body p-4 flex-grow-1" style="min-height: 0; overflow-y: auto;">

                        <!-- Seção Busca Rápida de Cadastrados -->
                        <div class="row g-2 mb-3 pb-3 border-bottom align-items-stretch">
                            <div class="col-12 mb-3">
                                <span class="d-inline-block px-3 py-2 rounded-pill fw-bold bg-primary text-white shadow-sm" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                    <i class="bi bi-person-badge-fill me-1"></i> Acesso de cadastrados
                                </span>
                            </div>
                            <div class="col-8 col-md-8">
                                <input type="text" id="busca_rapida" class="form-control border-primary-subtle h-100 shadow-sm" data-mask="cpf-id" placeholder="Busca Rápida: CPF ou ID (7 dígitos)">
                            </div>
                            <div class="col-4 col-md-4">
                                <button type="button" id="btnAbrirModal" class="btn btn-primary w-100 h-100 d-flex justify-content-center align-items-center gap-2 px-1 shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalCadastro" title="Cadastrar Conduzinte Oficial">
                                    <i class="bi bi-person-plus-fill"></i><span class="d-none d-md-inline fw-bold small">Novo Cadastro</span>
                                </button>
                            </div>
                            <!-- Container para opções de Cadastro (Preenchido via JS) -->
                            <div class="col-12 mt-2 d-none" id="lista_veiculos_encontrados">
                                <div id="container_opcoes"></div>
                            </div>
                        </div>

                        <div class="mb-4 mt-2">
                           <span class="d-inline-block px-4 py-2 rounded-pill fw-bold bg-acesso-comum text-white shadow-sm" style="font-size: 0.9rem; letter-spacing: 0.5px;">
                               Acesso comum
                           </span>
                        </div>
                        <form action="registrar_acesso.php" method="POST" class="row g-2 form-registrar-acesso">
                            <div class="col-12 d-flex align-items-end flex-wrap mb-2">
                                <div>
                                    <label class="form-label small fw-bold mb-3">Tipo veículo:</label>
                                    <div class="selectable-group" id="groupTipoVeiculo">
                                        <label class="selectable-item">
                                            <input type="radio" name="tipo_veiculo" value="Carro" checked>
                                            <div class="icon-box"><i class="bi bi-car-front-fill"></i></div>
                                            <span>Carro</span>
                                        </label>
                                        <label class="selectable-item">
                                            <input type="radio" name="tipo_veiculo" value="Moto">
                                            <div class="icon-box"><i class="bi bi-bicycle"></i></div>
                                            <span>Moto</span>
                                        </label>
                                        <label class="selectable-item">
                                            <input type="radio" name="tipo_veiculo" value="Outros">
                                            <div class="icon-box"><i class="bi bi-vinyl-fill"></i></div>
                                            <span>Outros</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="placa-inline">
                                    <label class="form-label small fw-bold label-required">Placa :</label>
                                    <input type="text" id="placa" name="placa" class="form-control" data-mask="placa" placeholder="AAA-0000" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <label class="form-label small fw-bold">Modelo:</label>
                                <input type="text" name="modelo_veiculo" class="form-control" style="border-radius: 20px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Cor:</label>
                                <input type="text" name="cor_veiculo" class="form-control" style="border-radius: 20px;">
                            </div>

                            <div class="col-12 mt-4 mb-2">
                                <label class="form-label small fw-bold mb-3">Tipo acesso:</label>
                                <div class="selectable-group" id="groupTipoAcesso">
                                    <label class="selectable-item">
                                        <input type="radio" name="tipo_acesso" value="Aluno" required>
                                        <div class="icon-box"><i class="bi bi-person-fill"></i></div>
                                        <span>Aluno</span>
                                    </label>
                                    <label class="selectable-item">
                                        <input type="radio" name="tipo_acesso" value="Equipe">
                                        <div class="icon-box"><i class="bi bi-people-fill"></i></div>
                                        <span>Equipe</span>
                                    </label>
                                    <label class="selectable-item">
                                        <input type="radio" name="tipo_acesso" value="Outros">
                                        <div class="icon-box"><i class="bi bi-person"></i></div>
                                        <span>Outros</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Campos dinâmicos para ALUNO -->
                            <div class="col-12" id="camposAlunoDinamico">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Curso:</label>
                                        <select name="curso_aluno" class="form-select" style="border-radius: 20px;">
                                            <option value="">Selecione...</option>
                                            <option value="DSI">DSI</option>
                                            <option value="DSII">DSII</option>
                                            <option value="DSIII">DSIII</option>
                                            <option value="RHI">RHI</option>
                                            <option value="RHII">RHII</option>
                                            <option value="RHIII">RHIII</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Período:</label>
                                        <select name="periodo_aluno" class="form-select" style="border-radius: 20px;">
                                            <option value="">Selecione...</option>
                                            <option value="Matutino">Matutino</option>
                                            <option value="Vespertino">Vespertino</option>
                                            <option value="Noturno">Noturno</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Campos dinâmicos para EQUIPE -->
                            <div class="col-12" id="camposEquipeDinamico" style="display: none;">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-bold">Cargo / Função:</label>
                                        <select name="funcao_equipe" class="form-select" style="border-radius: 20px;">
                                            <option value="">Selecione se aplicável...</option>
                                            <option value="Secretaria">Secretaria</option>
                                            <option value="Professor(a)">Professor(a)</option>
                                            <option value="Funcionários">Funcionários</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <label class="form-label small fw-bold label-required">Nome</label>
                                <input type="text" name="nome_condutor" class="form-control" required style="border-radius: 20px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Contato</label>
                                <input type="tel" name="contato_valor" id="inputContato" class="form-control" data-mask="tel" placeholder="(00) 00000-0000" style="border-radius: 20px;">
                            </div>

                            <div class="col-12 mt-3">
                                <div class="d-flex gap-3 mb-2">
                                    <button class="btn btn-obs flex-grow-1 py-2 fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseObs" style="font-size: 0.95rem;">
                                        Observação +
                                    </button>
                                    <button type="submit" class="btn btn-success flex-grow-1 fw-bold py-2" style="background-color: #157347; border: none; border-radius: 20px; font-size: 0.95rem;">
                                        Registrar acesso
                                    </button>
                                </div>

                                <div class="collapse mt-2" id="collapseObs">
                                    <textarea name="observacao" class="form-control"
                                        placeholder="Observações ou avarias..." rows="2"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card bg-dark-card border-danger-subtle mb-4 shadow-lg h-100 d-flex flex-column">
                    <div class="card-header bg-danger text-white text-center py-2 fw-bold flex-shrink-0">Registrar saída</div>
                    
                    <!-- Busca de Saída Eficiente -->
                    <div class="px-3 py-2 bg-light border-bottom border-danger-subtle">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-danger"><i class="bi bi-search"></i></span>
                            <input type="text" id="filtroTextoSaida" class="form-control border-start-0 shadow-none" placeholder="Buscar por placa ou nome do condutor...">
                        </div>
                    </div>

                    <div class="flex-grow-1" style="overflow-y: auto; min-height: 380px;">
                        <div id="listaSaidaVeiculos" class="list-group list-group-flush custom-scrollbar">
                            <?php if (count($veiculosDentro) > 0): ?>
                                <?php foreach ($veiculosDentro as $reg): ?>
                                    <div
                                        class="list-group-item d-flex justify-content-between align-items-center p-3 item-saida"
                                        data-placa="<?= htmlspecialchars($reg['placa']) ?>"
                                        data-nome="<?= htmlspecialchars($reg['nome_condutor']) ?>"
                                        data-hora="<?= date('H:i', strtotime($reg['data_hora_entrada'])) ?>">
                                        <div>
                                            <h6 class="mb-0 fw-bold" style="color: var(--cps-red);"><?= $reg['placa'] ?></h6>
                                            <small class="text-secondary"><?= $reg['nome_condutor'] ?> - Dentro:
                                                <?= date('H:i', strtotime($reg['data_hora_entrada'])) ?></small>
                                        </div>
                                        <a href="registrar_saida.php?id=<?= $reg['id'] ?>"
                                            class="btn btn-sm btn-outline-danger">Saída</a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="p-4 text-center text-secondary"><small>Nenhum veículo no pátio.</small></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalCadastro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary"><i class="bi bi-person-plus-fill me-2"></i>Cadastro Completo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="processa_cadastro.php" method="POST">
                        <div class="mb-4">
                            <h6 class="pb-2 mb-3 text-primary border-bottom border-primary" style="border-width: 2px !important;">
                                <i class="bi bi-person-lines-fill me-2"></i>Dados do Condutor
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small">Nome Completo</label>
                                    <input type="text" id="modalNomeCondutor" name="nome_completo"
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">CPF</label>
                                    <input type="text" id="modalCPF" name="cpf"
                                        class="form-control" data-mask="cpf"
                                        placeholder="000.000.000-00" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">E-mail</label>
                                    <input type="email" id="modalEmail" name="email"
                                        class="form-control"
                                        placeholder="exemplo@email.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Telefone/Celular</label>
                                    <input type="tel" id="modalTelefone" name="telefone"
                                        class="form-control" data-mask="tel"
                                        placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="pb-2 mb-3 text-primary border-bottom border-primary" style="border-width: 2px !important;">
                                <i class="bi bi-car-front-fill me-2"></i>Dados do Veículo
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small">Tipo Veículo</label>
                                    <select id="modalTipoVeiculo" name="tipo_veiculo"
                                        class="form-select">
                                        <option value="Carro">Carro</option>
                                        <option value="Moto">Moto</option>
                                        <option value="Outros">Outros</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Placa</label>
                                    <input type="text" id="modalPlacaVeiculo" name="placa"
                                        class="form-control" data-mask="placa" placeholder="AAA-0000" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Marca/Modelo</label>
                                    <input type="text" id="modalModelo" name="modelo_veiculo"
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Cor</label>
                                    <input type="text" id="modalCor" name="cor_veiculo"
                                        class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-secondary px-4"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Salvar Cadastro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/imask"></script>
        <script src="script/script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Scripts de Usabilidade Compartilhados (RAV ETEC) -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const body = document.body;
                
                // Elementos da Barra Desktop e Mobile
                const btnContrast = document.getElementById('btn-toggle-contrast');
                const btnContrastMobile = document.getElementById('btn-toggle-contrast-mobile');
                const btnIncrease = document.getElementById('btn-increase-font');
                const btnDecrease = document.getElementById('btn-decrease-font');
                const btnIncreaseMobile = document.getElementById('btn-increase-font-mobile');
                const btnDecreaseMobile = document.getElementById('btn-decrease-font-mobile');

                // Aplicar estado inicial do LocalStorage
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
                if(btnIncreaseMobile) btnIncreaseMobile.addEventListener('click', increaseFont);
                if(btnDecrease) btnDecrease.addEventListener('click', decreaseFont);
                if(btnDecreaseMobile) btnDecreaseMobile.addEventListener('click', decreaseFont);
            });
        </script>
</body>

</html>