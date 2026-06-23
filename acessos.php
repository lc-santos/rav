<?php
require_once 'trava_seguranca.php';
require_once 'conn.php';

// Filtro opcional via GET (vindo da busca do painel-admin)
$filtroNome = trim($_GET['nome'] ?? '');

if (!empty($filtroNome)) {
    $stmtAcessos = $pdo->prepare("
        SELECT r.id, r.nome_condutor, r.tipo_acesso, r.data_hora_entrada, r.data_hora_saida, r.observacao, r.status, 
               r.curso, r.periodo, r.modulo, r.funcao, r.contato_tipo, r.contato_valor,
               v.placa, v.tipo_veiculo, v.modelo, v.cor
        FROM registros_acesso r 
        LEFT JOIN veiculos v ON r.id_veiculo = v.id 
        WHERE r.nome_condutor LIKE :nome OR v.placa LIKE :nome
        ORDER BY r.data_hora_entrada DESC 
        LIMIT 200
    ");
    $stmtAcessos->execute([':nome' => "%$filtroNome%"]);
} else {
    $stmtAcessos = $pdo->query("
        SELECT r.id, r.nome_condutor, r.tipo_acesso, r.data_hora_entrada, r.data_hora_saida, r.observacao, r.status, 
               r.curso, r.periodo, r.modulo, r.funcao, r.contato_tipo, r.contato_valor,
               v.placa, v.tipo_veiculo, v.modelo, v.cor
        FROM registros_acesso r 
        LEFT JOIN veiculos v ON r.id_veiculo = v.id 
        ORDER BY r.data_hora_entrada DESC 
        LIMIT 200
    ");
}
$acessos = $stmtAcessos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV Admin - Acessos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .list-acesso-item {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border-left: 5px solid transparent;
        }
        .list-acesso-item:hover {
            background-color: #f8fcf9;
            border-left-color: #198754;
        }
        .list-acesso-item.active-item {
            background-color: #ebf6ee;
            border-left-color: #157347;
            border-right: 3px solid #157347;
        }
        .chat-container {
            background-color: #f1f3f5; /* Cinza claro */
            border-radius: 12px;
            height: calc(100vh - 280px);
            min-height: 400px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.05);
        }
        .chat-header {
            background-color: #e9ecef;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .chat-body {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            position: relative;
        }
        .chat-bubble {
            background-color: #ffffff;
            border-radius: 12px;
            border-bottom-left-radius: 2px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            max-width: 90%;
            margin-bottom: 20px;
            position: relative;
            animation: fadeIn 0.3s ease;
        }
        .chat-bubble::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: -8px;
            border-width: 0 10px 10px 0;
            border-style: solid;
            border-color: transparent #ffffff transparent transparent;
        }
        .chat-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #adb5bd;
            text-align: center;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Custom Scrollbar for chat and list */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05); 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.15); 
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.3); 
        }
        /* Fix the edit button overlaying list */
        .btn-edit-acesso {
            opacity: 0.85;
            transition: all 0.2s ease-in-out;
        }
        .list-acesso-item:hover .btn-edit-acesso {
            opacity: 1;
        }
        .btn-edit-acesso:hover {
            transform: scale(1.05);
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
                    <h1 class="logo-text m-0 fw-bold d-flex align-items-center flex-wrap">
                        <span class="text-cps-red fs-2 me-2">RAV</span>
                        <span class="text-dark fs-4 mt-1">Registro de acesso de veículos</span>
                        <span class="badge bg-cps-red text-white ms-2 mt-2" style="font-size: 0.70rem; padding: 0.35em 0.65em;">ETEC's e FATEC's</span>
                        <span class="badge bg-secondary text-white font-monospace ms-2 mt-2" style="font-size: 0.70rem;"><?= (isset($_SESSION['acesso']) && $_SESSION['acesso'] === 'portaria') ? 'Portaria' : 'Admin' ?></span>
                    </h1>
                </a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-light rounded-pill border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-badge-fill fs-5 text-cps-red"></i>
                        <span class="d-none d-md-inline fw-medium text-dark small"><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Operador') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><h6 class="dropdown-header"><?= htmlspecialchars($_SESSION['etec_nome'] ?? 'Guarita') ?></h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger fw-bold" href="sair.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                    </ul>
                </div>
                <a href="configuracoes.php" class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center nav-gear-btn" title="Configurações">
                    <i class="bi bi-gear-fill text-secondary"></i>
                </a>
            </div>
        </div>
    </header>

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
                        <a href="painel-admin.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-house-door me-2 me-lg-1"></i>Painel Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a href="acessos.php" class="nav-link text-white fw-medium px-4 py-3 active"><i class="bi bi-speedometer2 me-2 me-lg-1"></i>Acessos Rápidos</a>
                    </li>
                    <li class="nav-item">
                        <a href="estacionamento.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-car-front-fill me-2 me-lg-1"></i>Estacionamento</a>
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
        <div class="row g-4 h-100 align-items-stretch">
            
            <!-- LISTA DE ACESSOS (COR DIRETA VERDE) -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-lg h-100 bg-white">
                    <div class="card-header bg-success text-white py-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                        <div class="fw-bold"><i class="bi bi-speedometer2 me-2"></i>Acessos Rápidos</div>
                        <div class="input-group input-group-sm w-100 w-sm-50" style="max-width: 300px;">
                            <span class="input-group-text bg-light text-success border-0"><i class="bi bi-search"></i></span>
                            <input type="text" id="filtroAcessos" class="form-control border-0" placeholder="Buscar placa, nome...">
                        </div>
                    </div>
                    
                    <div class="card-body p-0" style="height: calc(100vh - 280px); min-height: 400px; overflow-y: auto;">
                        <div class="list-group list-group-flush custom-scrollbar" id="listaAcessos">
                            <?php if (count($acessos) > 0): ?>
                                <?php foreach ($acessos as $reg): 
                                    $hasObs = !empty(trim($reg['observacao'] ?? ''));
                                    // Escapar dados para Data Attributes do JS
                                    $dataHora = date('d/m/Y H:i', strtotime($reg['data_hora_entrada']));
                                    $nome = htmlspecialchars($reg['nome_condutor']);
                                    $placa = htmlspecialchars($reg['placa'] ?? 'PÉ');
                                    $tipo = htmlspecialchars($reg['tipo_acesso'] ?? 'Serviço');
                                    $obs = $hasObs ? htmlspecialchars(trim($reg['observacao'] ?? '')) : '';
                                    
                                    // Novos campos para edição
                                    $id = $reg['id'];
                                    $curso = htmlspecialchars($reg['curso'] ?? '');
                                    $periodo = htmlspecialchars($reg['periodo'] ?? '');
                                    $modulo = htmlspecialchars($reg['modulo'] ?? '');
                                    $funcao = htmlspecialchars($reg['funcao'] ?? '');
                                    $modelo = htmlspecialchars($reg['modelo'] ?? '');
                                    $cor = htmlspecialchars($reg['cor'] ?? '');
                                    $contato_tipo = htmlspecialchars($reg['contato_tipo'] ?? 'tel');
                                    $contato_valor = htmlspecialchars($reg['contato_valor'] ?? '');
                                    $tipo_veiculo = htmlspecialchars($reg['tipo_veiculo'] ?? 'Carro');
                                    
                                    $dataHoraEntradaRaw = $reg['data_hora_entrada'] ? date('Y-m-d\TH:i', strtotime($reg['data_hora_entrada'])) : '';
                                    $dataHoraSaidaRaw = $reg['data_hora_saida'] ? date('Y-m-d\TH:i', strtotime($reg['data_hora_saida'])) : '';
                                ?>
                                    <div class="list-group-item list-acesso-item p-3 border-bottom"
                                         data-id="<?= $id ?>"
                                         data-nome="<?= $nome ?>"
                                         data-placa="<?= $placa ?>"
                                         data-hora="<?= $dataHora ?>"
                                         data-tipo="<?= $tipo ?>"
                                         data-obs="<?= $obs ?>"
                                         data-hasobs="<?= $hasObs ? 'true' : 'false' ?>"
                                         data-curso="<?= $curso ?>"
                                         data-periodo="<?= $periodo ?>"
                                         data-modulo="<?= $modulo ?>"
                                         data-funcao="<?= $funcao ?>"
                                         data-modelo="<?= $modelo ?>"
                                         data-cor="<?= $cor ?>"
                                         data-contatotipo="<?= $contato_tipo ?>"
                                         data-contatovalor="<?= $contato_valor ?>"
                                         data-tipoveiculo="<?= $tipo_veiculo ?>"
                                         data-entradaraw="<?= $dataHoraEntradaRaw ?>"
                                         data-saidaraw="<?= $dataHoraSaidaRaw ?>">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                                            <div class="d-flex align-items-center gap-3 w-100">
                                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px;">
                                                    <i class="bi <?= $tipo === 'Aluno' ? 'bi-person' : 'bi-car-front' ?> fs-5"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2 flex-wrap">
                                                        <?= $nome ?>
                                                        <!-- Ícone de anexo só aparece se tiver observação -->
                                                        <?php if($hasObs): ?>
                                                            <i class="bi bi-chat-square-text-fill text-warning fs-6" title="Possui observação"></i>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <div class="text-secondary fw-medium small d-flex flex-wrap align-items-center gap-2 mt-1">
                                                        <span class="badge bg-secondary bg-opacity-25 text-dark fw-bold"><?= $placa ?></span>
                                                        <span><i class="bi bi-clock me-1"></i><?= $dataHora ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 ms-5 ms-sm-0 mt-1 mt-sm-0">
                                                <span class="badge bg-success bg-opacity-75 rounded-pill"><?= $tipo ?></span>
                                                <button type="button" class="btn btn-sm btn-outline-success btn-edit-acesso d-flex align-items-center gap-1" title="Editar Acesso" onclick="abrirModalEdicao(this, event)">
                                                    <i class="bi bi-pencil-square"></i>
                                                    <span>Editar</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="p-5 text-center text-secondary">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <p>Nenhum acesso registrado no momento.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHAT FEED DE OBSERVAÇÕES (CINZA CLARO) -->
            <div class="col-12 col-lg-5">
                <div class="chat-container">
                    <div class="chat-header text-dark fw-bold d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 fs-5" style="color: var(--cps-red);"></i> 
                        Dados do Registro
                    </div>
                    
                    <div class="chat-body custom-scrollbar" id="chatArea">
                        <!-- Placeholder visível quando nada estiver clicado -->
                        <div class="chat-placeholder" id="chatPlaceholder">
                            <i class="bi bi-cursor fs-1 mb-2"></i>
                            <h6 class="fw-bold">Nenhum Registro Selecionado</h6>
                            <p class="small bg-white px-3 py-2 rounded-bill shadow-sm mt-2">
                                Clique em um acesso da lista ao lado para visualizar os dados completos e observações aqui.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de Edição de Acesso -->
    <div class="modal fade" id="modalEditarAcesso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success"><i class="bi bi-pencil-square me-2"></i>Editar Registro de Acesso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="formEditarAcesso" novalidate>
                        <input type="hidden" id="edit_id_registro" name="id_registro">
                        
                        <div class="mb-4">
                            <h6 class="pb-2 mb-3 text-success border-bottom border-success" style="border-width: 2px !important;">
                                <i class="bi bi-person-lines-fill me-2"></i>Dados do Condutor
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nome Completo</label>
                                    <input type="text" id="edit_nome_condutor" name="edit_nome_condutor" class="form-control" required style="border-radius: 20px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Contato</label>
                                    <input type="tel" id="edit_inputContato" name="edit_contato_valor" class="form-control" data-mask="tel" placeholder="(00) 00000-0000" style="border-radius: 20px;">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold mb-3">Tipo de Acesso:</label>
                            <div class="selectable-group" id="edit_groupTipoAcesso">
                                <label class="selectable-item">
                                    <input type="radio" name="edit_tipo_acesso" value="Aluno" required>
                                    <div class="icon-box"><i class="bi bi-person-fill"></i></div>
                                    <span>Aluno</span>
                                </label>
                                <label class="selectable-item">
                                    <input type="radio" name="edit_tipo_acesso" value="Equipe">
                                    <div class="icon-box"><i class="bi bi-people-fill"></i></div>
                                    <span>Equipe</span>
                                </label>
                                <label class="selectable-item">
                                    <input type="radio" name="edit_tipo_acesso" value="Outros">
                                    <div class="icon-box"><i class="bi bi-person"></i></div>
                                    <span>Outros</span>
                                </label>
                            </div>
                        </div>

                        <!-- Campos dinâmicos para ALUNO -->
                        <div class="col-12 mb-4" id="edit_camposAlunoDinamico" style="display: none;">
                            <div class="row g-2">
                                <div class="col-4">
                                    <label class="form-label small fw-bold">Curso:</label>
                                    <select id="edit_curso_aluno" name="edit_curso_aluno" class="form-select" style="border-radius: 20px;">
                                        <option value="">Selecione...</option>
                                        <optgroup label="Técnico">
                                            <option value="Administração">Administração</option>
                                            <option value="Contabilidade">Contabilidade</option>
                                            <option value="Desenvolvimento de sistemas">Desenvolvimento de sistemas</option>
                                            <option value="Recursos humanos">Recursos humanos</option>
                                            <option value="Segurança do trabalho">Segurança do trabalho</option>
                                        </optgroup>
                                        <optgroup label="M-Tec">
                                            <option value="Administração (M-Tec)">Administração (M-Tec)</option>
                                            <option value="Desenvolvimento de sistemas (M-Tec)">Desenvolvimento de sistemas (M-Tec)</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label id="edit_label_modulo_aluno" class="form-label small fw-bold">Módulo:</label>
                                    <select id="edit_modulo_aluno" name="edit_modulo_aluno" class="form-select" style="border-radius: 20px;">
                                        <option value="">Selecione...</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label small fw-bold">Período:</label>
                                    <select id="edit_periodo_aluno" name="edit_periodo_aluno" class="form-select" style="border-radius: 20px;">
                                        <option value="">Selecione...</option>
                                        <option value="Matutino">Matutino</option>
                                        <option value="Vespertino">Vespertino</option>
                                        <option value="Noturno">Noturno</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Campos dinâmicos para EQUIPE -->
                        <div class="col-12 mb-4" id="edit_camposEquipeDinamico" style="display: none;">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Cargo / Função:</label>
                                    <select id="edit_funcao_equipe" name="edit_funcao_equipe" class="form-select" style="border-radius: 20px;">
                                        <option value="">Selecione se aplicável...</option>
                                        <option value="Secretaria">Secretaria</option>
                                        <option value="Professor(a)">Professor(a)</option>
                                        <option value="Funcionários">Funcionários</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="pb-2 mb-3 text-success border-bottom border-success" style="border-width: 2px !important;">
                                <i class="bi bi-car-front-fill me-2"></i>Dados do Veículo
                            </h6>
                            <div class="mb-3">
                                <label class="form-label small fw-bold mb-3">Tipo veículo:</label>
                                <div class="selectable-group" id="edit_groupTipoVeiculo">
                                    <label class="selectable-item">
                                        <input type="radio" name="edit_tipo_veiculo" value="Carro">
                                        <div class="icon-box"><i class="bi bi-car-front-fill"></i></div>
                                        <span>Carro</span>
                                    </label>
                                    <label class="selectable-item">
                                        <input type="radio" name="edit_tipo_veiculo" value="Moto">
                                        <div class="icon-box"><i class="bi bi-bicycle"></i></div>
                                        <span>Moto</span>
                                    </label>
                                    <label class="selectable-item">
                                        <input type="radio" name="edit_tipo_veiculo" value="Outros">
                                        <div class="icon-box"><i class="bi bi-vinyl-fill"></i></div>
                                        <span>Outros</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Placa</label>
                                    <input type="text" id="edit_placa" name="edit_placa" class="form-control" data-mask="placa" placeholder="AAA-0000" required style="border-radius: 20px;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Modelo</label>
                                    <input type="text" id="edit_modelo_veiculo" name="edit_modelo_veiculo" class="form-control" style="border-radius: 20px;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Cor</label>
                                    <input type="text" id="edit_cor_veiculo" name="edit_cor_veiculo" class="form-control" style="border-radius: 20px;">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="pb-2 mb-3 text-success border-bottom border-success" style="border-width: 2px !important;">
                                <i class="bi bi-clock-fill me-2"></i>Horários de Acesso
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Horário de Entrada</label>
                                    <input type="datetime-local" id="edit_data_hora_entrada" name="edit_data_hora_entrada" class="form-control" style="border-radius: 20px;" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Horário de Saída <small class="text-muted">(Opcional)</small></label>
                                    <input type="datetime-local" id="edit_data_hora_saida" name="edit_data_hora_saida" class="form-control" style="border-radius: 20px;">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="pb-2 mb-3 text-success border-bottom border-success" style="border-width: 2px !important;">
                                <i class="bi bi-chat-square-text-fill me-2"></i>Observações
                            </h6>
                            <textarea id="edit_observacao" name="edit_observacao" class="form-control" placeholder="Avarias ou observações do acesso..." rows="3" style="border-radius: 15px;"></textarea>
                        </div>

                        <div class="text-end mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="btnSalvarEdicao" class="btn btn-success px-4 fw-bold rounded-pill">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bibliotecas externas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/imask"></script>
    <script src="script/script.js"></script>
    
    <script>
        // Lógica Dinâmica de Curso/Módulo/Período para Edição de Aluno
        function atualizarModuloPeriodoEdicao() {
            const cursoSelect = document.getElementById('edit_curso_aluno');
            const moduloSelect = document.getElementById('edit_modulo_aluno');
            const moduloLabel = document.getElementById('edit_label_modulo_aluno');
            const periodoSelect = document.getElementById('edit_periodo_aluno');

            const curso = cursoSelect.value;
            const isMtec = curso.includes('(M-Tec)');

            // Limpa opções antigas de Módulo
            moduloSelect.innerHTML = '<option value="">Selecione...</option>';

            if (!curso) {
                moduloLabel.textContent = 'Módulo:';
                periodoSelect.innerHTML = '<option value="">Selecione...</option>';
                return;
            }

            if (isMtec) {
                // M-Tec
                moduloLabel.textContent = 'Ano:';
                ['1º', '2º', '3º'].forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt;
                    option.textContent = opt;
                    moduloSelect.appendChild(option);
                });

                // Período para M-Tec
                periodoSelect.innerHTML = `
                    <option value="">Selecione...</option>
                    <option value="Integral">Integral</option>
                    <option value="Noturno">Noturno</option>
                `;
            } else {
                // Técnico Regular
                moduloLabel.textContent = 'Módulo:';
                ['I', 'II', 'III'].forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt;
                    option.textContent = opt;
                    moduloSelect.appendChild(option);
                });

                // Período para Técnico Regular
                periodoSelect.innerHTML = `
                    <option value="">Selecione...</option>
                    <option value="Matutino">Matutino</option>
                    <option value="Vespertino">Vespertino</option>
                    <option value="Noturno">Noturno</option>
                `;
            }
        }

        // Função GLOBAL (escopo da window) para lidar com clique no botão de edição
        let modalEdicaoInstancia = null;
        
        function abrirModalEdicao(btn, event) {
            event.stopPropagation(); // Impede o clique de ativar a exibição do card da direita
            
            // 1. Coleta dos dados do botão
            const listItem = btn.closest('.list-acesso-item');
            const dados = listItem.dataset;
            
            // 2. Preencher os formulários
            document.getElementById('edit_id_registro').value = dados.id;
            document.getElementById('edit_placa').value = dados.placa;
            document.getElementById('edit_modelo_veiculo').value = dados.modelo;
            document.getElementById('edit_cor_veiculo').value = dados.cor;
            document.getElementById('edit_nome_condutor').value = dados.nome;
            document.getElementById('edit_inputContato').value = dados.contatovalor;
            document.getElementById('edit_observacao').value = dados.obs;
            document.getElementById('edit_funcao_equipe').value = dados.funcao;
            document.getElementById('edit_data_hora_entrada').value = dados.entradaraw;
            document.getElementById('edit_data_hora_saida').value = dados.saidaraw;
            
            // Setar Curso e rodar lógica de preenchimento dinâmico de módulo/período
            document.getElementById('edit_curso_aluno').value = dados.curso;
            atualizarModuloPeriodoEdicao();
            
            // Agora sim podemos selecionar o módulo e o período corretos
            document.getElementById('edit_modulo_aluno').value = dados.modulo;
            document.getElementById('edit_periodo_aluno').value = dados.periodo;
            
            // Setar os radios selecionados (Tipo veículo)
            document.querySelectorAll('input[name="edit_tipo_veiculo"]').forEach(radio => {
                radio.checked = (radio.value === dados.tipoveiculo);
            });
            
            // Setar Tipo acesso e ativar dinâmica de ocultos
            document.querySelectorAll('input[name="edit_tipo_acesso"]').forEach(radio => {
                radio.checked = (radio.value === dados.tipo);
            });
            atualizarCamposDinamicosEdicao(dados.tipo);
            
            // Re-garantir a inicialização do iMask nos inputs após abrir
            initMasks();
            
            // 3. Abrir o Bootstrap Modal
            if(!modalEdicaoInstancia) {
                modalEdicaoInstancia = new bootstrap.Modal(document.getElementById('modalEditarAcesso'));
            }
            modalEdicaoInstancia.show();
        }
        
        // Lógica de Display dinâmico edição
        function atualizarCamposDinamicosEdicao(valor) {
            const camposAluno = document.getElementById('edit_camposAlunoDinamico');
            const camposEquipe = document.getElementById('edit_camposEquipeDinamico');
            
            if (camposAluno) camposAluno.style.display = (valor === 'Aluno') ? 'block' : 'none';
            if (camposEquipe) camposEquipe.style.display = (valor === 'Equipe') ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const listItems = document.querySelectorAll('.list-acesso-item');
            const chatArea = document.getElementById('chatArea');
            const chatPlaceholder = document.getElementById('chatPlaceholder');
            const filtroAcessos = document.getElementById('filtroAcessos');

            // --- LISTENERS DA EDIÇÃO ---
            const editRadios = document.querySelectorAll('input[name="edit_tipo_acesso"]');
            editRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    atualizarCamposDinamicosEdicao(this.value);
                });
            });

            const editCursoSelect = document.getElementById('edit_curso_aluno');
            if (editCursoSelect) {
                editCursoSelect.addEventListener('change', atualizarModuloPeriodoEdicao);
            }
            
            const btnSalvar = document.getElementById('btnSalvarEdicao');
            if(btnSalvar) {
                btnSalvar.addEventListener('click', function() {
                    const form = document.getElementById('formEditarAcesso');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }
                    
                    const formData = new FormData(form);
                    
                    btnSalvar.disabled = true;
                    btnSalvar.innerText = "Salvando...";

                    fetch('editar_acesso.php', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            if (data.sucesso) {
                                Swal.fire({ title: 'Sucesso', text: 'Registro atualizado com sucesso.', icon: 'success'})
                                .then(() => window.location.reload());
                            } else {
                                Swal.fire('Erro', data.erro, 'error');
                            }
                        })
                        .catch(() => Swal.fire('Erro', 'Falha na comunicação com o servidor.', 'error'))
                        .finally(() => {
                            btnSalvar.disabled = false;
                            btnSalvar.innerText = "Salvar Alterações";
                        });
                });
            }

            // --- LÓGICA DE CLIQUE NO ACESSO ---
            listItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (e.target.closest('.btn-edit-acesso')) return; // ignorar se clicou no botão de editar
                    
                    // Remover classes 'active-item' de todos
                    listItems.forEach(el => el.classList.remove('active-item'));
                    // Adicionar apenas ao clicado
                    this.classList.add('active-item');

                    // Pegar dados do dataset
                    const nome = this.dataset.nome;
                    const placa = this.dataset.placa;
                    const hora = this.dataset.hora;
                    const obs = this.dataset.obs;
                    const hasObs = this.dataset.hasobs === 'true';
                    const curso = this.dataset.curso;
                    const periodo = this.dataset.periodo;
                    const modulo = this.dataset.modulo;
                    const funcao = this.dataset.funcao;
                    const modelo = this.dataset.modelo;
                    const cor = this.dataset.cor;
                    const contato_tipo = this.dataset.contatotipo;
                    const contato_valor = this.dataset.contatovalor;
                    const tipo_veiculo = this.dataset.tipoveiculo;
                    const tipo_acesso = this.dataset.tipo;

                    // Ocultar placeholder
                    if (chatPlaceholder) chatPlaceholder.style.display = 'none';

                    // Formata contato amigável
                    let contatoHTML = '';
                    if (contato_valor) {
                        const icon = contato_tipo === 'tel' ? 'bi-telephone' : 'bi-envelope';
                        const label = contato_tipo === 'tel' ? 'Telefone' : 'E-mail';
                        contatoHTML = `<p class="mb-2 fs-6"><strong><i class="bi ${icon} me-1 text-secondary"></i> ${label}:</strong> ${contato_valor}</p>`;
                    } else {
                        contatoHTML = `<p class="mb-2 fs-6 text-muted"><strong><i class="bi bi-telephone me-1"></i> Contato:</strong> Não informado</p>`;
                    }

                    // Formata campos extras conforme o tipo
                    let extraHTML = '';
                    if (tipo_acesso === 'Aluno') {
                        let labelModulo = 'Módulo';
                        if (curso && curso.includes('(M-Tec)')) {
                            labelModulo = 'Ano';
                        }
                        extraHTML = `
                            <p class="mb-2 fs-6"><strong><i class="bi bi-book me-1 text-secondary"></i> Curso:</strong> ${curso || 'Não informado'}</p>
                            <p class="mb-2 fs-6"><strong><i class="bi bi-layers me-1 text-secondary"></i> ${labelModulo}:</strong> ${modulo || 'Não informado'}</p>
                            <p class="mb-2 fs-6"><strong><i class="bi bi-calendar-event me-1 text-secondary"></i> Período:</strong> ${periodo || 'Não informado'}</p>
                        `;
                    } else if (funcao) {
                        extraHTML = `<p class="mb-2 fs-6"><strong><i class="bi bi-briefcase me-1 text-secondary"></i> Cargo/Função:</strong> ${funcao}</p>`;
                    }

                    // Formata veículo
                    let veiculoHTML = '';
                    if (placa && placa !== 'PÉ') {
                        veiculoHTML = `
                            <p class="mb-2 fs-6"><strong>Placa:</strong> <span class="badge bg-secondary text-dark fw-bold fs-6">${placa}</span></p>
                            <p class="mb-2 fs-6"><strong>Tipo:</strong> ${tipo_veiculo}</p>
                            <p class="mb-2 fs-6"><strong>Modelo:</strong> ${modelo || 'Não informado'}</p>
                            <p class="mb-2 fs-6"><strong>Cor:</strong> ${cor || 'Não informado'}</p>
                        `;
                    } else {
                        veiculoHTML = `
                            <p class="mb-0 fs-6 text-muted"><i class="bi bi-person-walking me-1"></i> Acesso a Pé (Sem veículo)</p>
                        `;
                    }

                    // Formata Observações
                    let obsHTML = '';
                    if (hasObs) {
                        obsHTML = `
                            <p class="mb-0 fs-6 text-dark texto-msg">${obs.replace(/\n/g, '<br>')}</p>
                        `;
                    } else {
                        obsHTML = `
                            <p class="mb-0 fs-6 text-muted"><i class="bi bi-check-circle me-1 text-success"></i> Nenhum detalhe ou observação adicional registrada.</p>
                        `;
                    }

                    let chatHTML = `
                        <div class="d-flex flex-column align-items-center mb-3 pt-2">
                            <span class="badge bg-secondary bg-opacity-25 text-dark px-3 py-1 rounded-pill fs-6"><i class="bi bi-clock me-1"></i>${hora}</span>
                        </div>

                        <div class="chat-bubble w-100 text-start" style="max-width: 100%; border-bottom-left-radius: 12px; border-top-left-radius: 2px;">
                            <!-- Header do Registro -->
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <span class="fw-bold text-dark fs-5"><i class="bi bi-info-circle-fill text-success me-2"></i>Informações do Registro</span>
                                <span class="text-secondary fs-6 fw-bold">#${nome.substring(0, 3).toUpperCase()}-${placa.replace('-', '')}</span>
                            </div>

                            <!-- Seção: Condutor -->
                            <div class="mb-3">
                                <div class="fw-bold text-success fs-6 mb-2"><i class="bi bi-person-badge-fill me-1"></i>Condutor</div>
                                <div class="ps-2 border-start border-2 border-success-subtle">
                                    <p class="mb-2 fs-6"><strong>Nome:</strong> ${nome}</p>
                                    <p class="mb-2 fs-6"><strong>Tipo:</strong> <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold fs-6">${tipo_acesso}</span></p>
                                    ${contatoHTML}
                                    ${extraHTML}
                                </div>
                            </div>

                            <!-- Seção: Veículo -->
                            <div class="mb-3">
                                <div class="fw-bold text-success fs-6 mb-2"><i class="bi bi-car-front-fill me-1"></i>Veículo</div>
                                <div class="ps-2 border-start border-2 border-success-subtle">
                                    ${veiculoHTML}
                                </div>
                            </div>

                            <!-- Seção: Observações -->
                            <div class="mb-2">
                                <div class="fw-bold text-success fs-6 mb-2"><i class="bi bi-chat-left-text-fill me-1"></i>Observações</div>
                                <div class="ps-2 border-start border-2 border-success-subtle">
                                    ${obsHTML}
                                </div>
                            </div>
                        </div>
                    `;
                    
                    chatArea.innerHTML = chatHTML;

                    // Scroll suave até o painel de detalhes no mobile
                    if (window.innerWidth < 992) {
                        const containerDetalhes = document.querySelector('.chat-container');
                        if (containerDetalhes) {
                            containerDetalhes.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }
                });
            });

            // --- LÓGICA DO FILTRO DE BUSCA ---
            if (filtroAcessos) {
                filtroAcessos.addEventListener('input', function() {
                    const termo = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
                    
                    listItems.forEach(item => {
                        const nome = (item.dataset.nome || "").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                        const placa = (item.dataset.placa || "").toLowerCase();
                        
                        // Se o termo estiver em branco, ou placa, ou nome contiver o termo...
                        if (!termo || nome.includes(termo) || placa.includes(termo)) {
                            item.style.display = 'block'; // Mostra (como bootstrap usa d-block)
                        } else {
                            item.style.display = 'none'; // Esconde
                        }
                    });
                });
            }

            // --- ACESSIBILIDADE: CONTROLE DE FONTE & TEMA ---
            const body = document.body;
            const btnContrast = document.getElementById('btn-toggle-contrast');
            const btnContrastMobile = document.getElementById('btn-toggle-contrast-mobile');
            const btnIncrease = document.getElementById('btn-increase-font');
            const btnDecrease = document.getElementById('btn-decrease-font');
            const btnIncreaseMobile = document.getElementById('btn-increase-font-mobile');
            const btnDecreaseMobile = document.getElementById('btn-decrease-font-mobile');

            // Aplicar estado inicial do LocalStorage para Tema
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
                const sunIcon = '<i class="bi bi-sun-fill"></i>';
                if(btnContrast) btnContrast.innerHTML = sunIcon;
                if(btnContrastMobile) btnContrastMobile.innerHTML = sunIcon;
            }
            
            function toggleTheme() {
                if (body.classList.contains('dark-mode')) {
                    body.classList.remove('dark-mode');
                    body.classList.add('light-mode');
                    const moonIcon = '<i class="bi bi-moon-stars-fill"></i>';
                    if(btnContrast) btnContrast.innerHTML = moonIcon;
                    if(btnContrastMobile) btnContrastMobile.innerHTML = moonIcon;
                    localStorage.setItem('theme', 'light');
                } else {
                    body.classList.remove('light-mode');
                    body.classList.add('dark-mode');
                    const sunIcon = '<i class="bi bi-sun-fill"></i>';
                    if(btnContrast) btnContrast.innerHTML = sunIcon;
                    if(btnContrastMobile) btnContrastMobile.innerHTML = sunIcon;
                    localStorage.setItem('theme', 'dark');
                }
            }

            if(btnContrast) btnContrast.addEventListener('click', toggleTheme);
            if(btnContrastMobile) btnContrastMobile.addEventListener('click', toggleTheme);

            // Aplicar estado inicial do LocalStorage para Escala de Fonte
            const maxScale = 1.5; 
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
