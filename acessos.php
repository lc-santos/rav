<?php
require_once 'conn.php';

// Fetch all accesses to display on the list
$stmtAcessos = $pdo->query("
    SELECT r.id, r.nome_condutor, r.tipo_acesso, r.data_hora_entrada, r.observacao, r.status, 
           r.curso, r.periodo, r.funcao, r.contato_tipo, r.contato_valor,
           v.placa, v.tipo_veiculo, v.modelo, v.cor
    FROM registros_acesso r 
    LEFT JOIN veiculos v ON r.id_veiculo = v.id 
    ORDER BY r.data_hora_entrada DESC 
    LIMIT 200
");
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
            opacity: 0.5;
            transition: all 0.2s;
        }
        .list-acesso-item:hover .btn-edit-acesso {
            opacity: 1;
        }
        .btn-edit-acesso:hover {
            color: var(--cps-red) !important;
            transform: scale(1.1);
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
                        <span class="badge bg-success text-white font-monospace ms-2 mt-2" style="font-size: 0.70rem;">Acessos</span>
                    </h1>
                </a>
            </div>
            <div class="dropdown">
                <button class="btn btn-light rounded-pill border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-badge-fill fs-5 text-cps-red"></i>
                    <span class="d-none d-md-inline fw-medium text-dark small">Lucas Silva</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
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
                        <a href="acessos.php" class="nav-link text-white fw-medium px-4 py-3 active"><i class="bi bi-list-check me-2 me-lg-1"></i>Acessos Diários</a>
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
        <div class="row g-4 h-100 align-items-stretch">
            
            <!-- LISTA DE ACESSOS (COR DIRETA VERDE) -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-lg h-100 bg-white">
                    <div class="card-header bg-success text-white py-3 px-4 d-flex justify-content-between align-items-center">
                        <div class="fw-bold"><i class="bi bi-ui-checks-grid me-2"></i>Lista de Acessos</div>
                        <div class="input-group input-group-sm w-50">
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
                                    $funcao = htmlspecialchars($reg['funcao'] ?? '');
                                    $modelo = htmlspecialchars($reg['modelo'] ?? '');
                                    $cor = htmlspecialchars($reg['cor'] ?? '');
                                    $contato_tipo = htmlspecialchars($reg['contato_tipo'] ?? 'tel');
                                    $contato_valor = htmlspecialchars($reg['contato_valor'] ?? '');
                                    $tipo_veiculo = htmlspecialchars($reg['tipo_veiculo'] ?? 'Carro');
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
                                         data-funcao="<?= $funcao ?>"
                                         data-modelo="<?= $modelo ?>"
                                         data-cor="<?= $cor ?>"
                                         data-contatotipo="<?= $contato_tipo ?>"
                                         data-contatovalor="<?= $contato_valor ?>"
                                         data-tipoveiculo="<?= $tipo_veiculo ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-3 w-100">
                                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 45px;">
                                                    <i class="bi <?= $tipo === 'Aluno' ? 'bi-person' : 'bi-car-front' ?> fs-5"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                                        <?= $nome ?>
                                                        <!-- Ícone de anexo só aparece se tiver observação -->
                                                        <?php if($hasObs): ?>
                                                            <i class="bi bi-chat-square-text-fill text-warning fs-6" title="Possui observação"></i>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <small class="text-secondary fw-medium">
                                                        <span class="badge bg-secondary bg-opacity-25 text-dark fw-bold me-1"><?= $placa ?></span>
                                                        <i class="bi bi-clock me-1"></i><?= $dataHora ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-success bg-opacity-75 rounded-pill"><?= $tipo ?></span>
                                                <button type="button" class="btn btn-sm text-secondary btn-edit-acesso p-1 border-0" title="Editar Acesso" onclick="abrirModalEdicao(this, event)">
                                                    <i class="bi bi-pencil-square fs-5"></i>
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
                        <i class="bi bi-chat-dots-fill me-2 fs-5" style="color: var(--cps-red);"></i> 
                        Feed de Observação
                    </div>
                    
                    <div class="chat-body custom-scrollbar" id="chatArea">
                        <!-- Placeholder visível quando nada estiver clicado -->
                        <div class="chat-placeholder" id="chatPlaceholder">
                            <i class="bi bi-cursor fs-1 mb-2"></i>
                            <h6 class="fw-bold">Nenhum Registro Selecionado</h6>
                            <p class="small bg-white px-3 py-2 rounded-bill shadow-sm mt-2">
                                Clique em um acesso da lista ao lado para inspecionar os alertas e detalhes aqui.
                            </p>
    <!-- Bibliotecas externas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
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
            document.getElementById('edit_curso_aluno').value = dados.curso;
            document.getElementById('edit_periodo_aluno').value = dados.periodo;
            document.getElementById('edit_funcao_equipe').value = dados.funcao;
            
            // Setar os radios selectionados (Tipo veículo)
            document.querySelectorAll('input[name="edit_tipo_veiculo"]').forEach(radio => {
                radio.checked = (radio.value === dados.tipoveiculo);
            });
            
            // Setar Tipo acesso e ativar dinâmica de ocultos
            document.querySelectorAll('input[name="edit_tipo_acesso"]').forEach(radio => {
                radio.checked = (radio.value === dados.tipo);
            });
            atualizarCamposDinamicosEdicao(dados.tipo);
            
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

                    // Ocultar placeholder
                    if (chatPlaceholder) chatPlaceholder.style.display = 'none';

                    // Se não tem observação enviada, gera um card amigável dizendo isso
                    let chatHTML = '';
                    if (!hasObs) {
                        chatHTML = `
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-secondary" style="opacity: 0.6; animation: fadeIn 0.3s ease;">
                                <i class="bi bi-check-circle fs-1 mb-2 text-success"></i>
                                <span class="fw-bold">Acesso Normal</span>
                                <small>Nenhuma observação foi anotada para ${nome}</small>
                            </div>
                        `;
                        chatArea.innerHTML = chatHTML;
                        // Anexa o placeholder de volta escondido caso queiramos reciclar (o innerHTML apagou ele)
                        return;
                    }

                    // Se tiver observação, monta o layout de Chat/Mensagem
                    chatHTML = `
                        <div class="d-flex flex-column align-items-center mb-4 pt-2">
                            <span class="badge bg-secondary bg-opacity-25 text-dark px-3 py-1 rounded-pill small">${hora}</span>
                        </div>
                        
                        <div class="d-flex align-items-start gap-3 w-100">
                            <!-- Avatar de guarita generico -->
                            <div class="bg-dark bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-badge-fill text-dark"></i>
                            </div>
                            
                            <div class="w-100">
                                <div class="mb-1 ms-1 d-flex justify-content-between align-items-center">
                                    <span class="fw-bold small text-dark">Operador / Guarita</span>
                                </div>
                                <div class="chat-bubble flex-grow-1">
                                    <p class="mb-2 texto-msg">${obs.replace(/\n/g, '<br>')}</p>
                                    <hr class="text-secondary opacity-25 my-2">
                                    <div class="d-flex justify-content-between align-items-center px-1">
                                      <small class="text-secondary fw-bold"><i class="bi bi-person me-1"></i>${nome}</small>
                                      <small class="badge border border-success text-success"><i class="bi bi-card-heading me-1"></i>${placa}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    chatArea.innerHTML = chatHTML;
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
        });
    </script>
</body>
</html>
