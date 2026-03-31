<?php
require_once 'conn.php';

// Busca todos os usuários visitantes
$stmt = $pdo->query("SELECT id, codigo_acesso, nome_completo, cpf, email, contato_valor FROM usuarios WHERE role = 'visitante' ORDER BY nome_completo ASC");
$cadastros = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtV = $pdo->query("SELECT id, id_usuario, placa, modelo, cor, tipo_veiculo FROM veiculos WHERE id_usuario IS NOT NULL");
$todos_veiculos = $stmtV->fetchAll(PDO::FETCH_ASSOC);

// Agrupa os veículos por cadastro
$veiculos_map = [];
foreach ($todos_veiculos as $v) {
    $veiculos_map[$v['id_usuario']][] = $v;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV Admin - Gerenciar Cadastros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cadastro-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.3s ease;
            border-top: 4px solid var(--border-color); /* Neutro inicialmente */
            border: 1px solid var(--border-color);
        }
        .cadastro-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            border-color: var(--bs-primary) !important; /* Borda hover azul */
            border-top-color: var(--bs-primary);
        }
        .veiculo-item {
            transition: all 0.2s;
        }
        .veiculo-item:hover {
            border-color: var(--bs-primary) !important;
            background-color: rgba(0, 128, 128, 0.03) !important;
        }
    </style>
</head>

<body class="light-mode section-bg-gray d-flex flex-column min-vh-100">

    <!-- Acessibility Bar -->
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

    <!-- Header Branco -->
    <header class="main-header bg-white shadow-sm sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-expanded="false">
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
                <button class="btn btn-light rounded-pill border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 text-cps-red"></i>
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

    <!-- Navbar Horizontal -->
    <nav class="navbar navbar-expand-lg nav-cps p-0" style="z-index: 1010;">
        <div class="container flex-column flex-lg-row">
            <div class="collapse navbar-collapse w-100" id="adminNavbar">
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
                        <a href="estacionamento.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-p-circle me-2 me-lg-1"></i>Estacionamento</a>
                    </li>
                    <li class="nav-item">
                        <a href="gerenciar_cadastros.php" class="nav-link text-white fw-medium px-4 py-3 active"><i class="bi bi-people-fill me-2 me-lg-1"></i>Cadastros</a>
                    </li>
                    <li class="nav-item">
                        <a href="relatorios.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-bar-chart-line me-2 me-lg-1"></i>Relatórios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="text-dark mb-1 fw-bold"><i class="bi bi-person-vcard text-primary me-2"></i>Gestão de Cadastros</h2>
                <p class="text-secondary small mb-0">Pesquise, edite e adicione veículos a registros cadastrados no sistema.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded shadow-sm border text-center">
                <span class="d-block small text-secondary fw-bold text-uppercase">Total Cadastrados</span>
                <span class="fs-4 fw-bold text-primary"><?= count($cadastros) ?> <i class="bi bi-people ms-1 fs-5"></i></span>
            </div>
        </div>

        <!-- Alertas de Sucesso/Erro -->
        <?php if(isset($_GET['sucesso'])): ?>
            <div class="alert alert-success alert-dismissible fade.show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($_GET['sucesso']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($_GET['erro']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Barra de Busca -->
        <div class="row mb-4">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="input-group input-group-lg bg-white rounded border border-primary-subtle overflow-hidden shadow-sm">
                    <span class="input-group-text bg-white border-0 text-primary">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="filtroCadastros" class="form-control border-0 custom-search text-dark" placeholder="Buscar por nome, CPF ou Código de Acesso...">
                </div>
            </div>
        </div>

        <!-- Grade de Cadastros -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4" id="gridCadastros">
            <?php foreach ($cadastros as $c): ?>
            <?php $veiculos = $veiculos_map[$c['id']] ?? []; ?>
            <div class="col cadastro-item">
                <div class="card h-100 cadastro-card bg-white rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold text-primary mb-0"><i class="bi bi-person-fill me-2"></i><?= htmlspecialchars($c['nome_completo']) ?></h5>
                            <span class="badge bg-secondary opacity-75">ID: <?= htmlspecialchars($c['codigo_acesso']) ?></span>
                        </div>
                        
                        <div class="text-secondary small mb-3">
                            <div class="mb-1"><i class="bi bi-card-text me-2"></i><strong>CPF:</strong> <?= htmlspecialchars($c['cpf']) ?></div>
                            <div class="mb-1 d-flex align-items-center gap-1">
                                <i class="bi bi-whatsapp text-success me-1"></i><strong>Contato:</strong> 
                                <?php if(!empty($c['contato_valor'])): ?>
                                    <span class="text-dark"><?= htmlspecialchars($c['contato_valor']) ?></span>
                                    <a href="https://wa.me/55<?= preg_replace('/\D/', '', $c['contato_valor']) ?>" target="_blank" class="btn btn-sm text-success p-0 ms-1 fw-bold" title="Chamar no WhatsApp"><i class="bi bi-box-arrow-up-right"></i></a>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">N/A</span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-envelope-fill me-1"></i><strong>E-mail:</strong> 
                                <?php if(strpos($c['email'], '@rav.tmp') === false): ?>
                                    <span class="text-truncate d-inline-block align-bottom text-dark" style="max-width:180px;" id="em-<?= $c['id'] ?>"><?= htmlspecialchars($c['email']) ?></span>
                                    <button type="button" class="btn btn-sm btn-link text-secondary p-0 ms-1" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($c['email']) ?>'); alert('E-mail (<?= htmlspecialchars($c['email']) ?>) copiado na área de transferência!')" title="Copiar E-mail"><i class="bi bi-copy fs-6 text-primary"></i></button>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Não informado</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Lista de Veículos do Cadastro -->
                        <div class="bg-light p-3 rounded border">
                            <h6 class="small fw-bold text-dark mb-2"><i class="bi bi-car-front-fill me-2 text-primary"></i>Veículos Vinculados (<?= count($veiculos) ?>)</h6>
                            <?php if (count($veiculos) > 0): ?>
                                <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                                    <?php foreach ($veiculos as $v): ?>
                                     <li class="bg-white border rounded p-2 d-flex justify-content-between align-items-center veiculo-item shadow-sm mb-2">
                                        <div>
                                            <span class="fw-bold small text-dark"><?= htmlspecialchars($v['placa']) ?></span>
                                            <span class="text-muted d-block" style="font-size: 0.75rem;"><?= htmlspecialchars($v['modelo']) ?> • <?= htmlspecialchars($v['cor']) ?></span>
                                        </div>
                                        <?php 
                                            $icone = 'car-front-fill';
                                            if ($v['tipo_veiculo'] == 'Moto') $icone = 'bicycle';
                                            else if ($v['tipo_veiculo'] == 'Outros') $icone = 'truck';
                                        ?>
                                        <span class="badge rounded-pill bg-light border text-secondary fw-bold shadow-sm px-3 py-2 d-flex align-items-center gap-2">
                                            <i class="bi bi-<?= $icone ?> fs-6 text-primary"></i> 
                                            <span style="font-size:0.8rem;" class="text-dark"><?= htmlspecialchars($v['tipo_veiculo']) ?></span>
                                        </span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="small text-muted mb-0 fst-italic">Nenhum veículo cadastrado.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent p-3 border-top grid gap-2 d-flex">
                        <button type="button" class="btn btn-outline-primary w-50 fw-bold d-flex align-items-center justify-content-center btn-editar" 
                            data-id="<?= $c['id'] ?>"
                            data-nome="<?= htmlspecialchars($c['nome_completo']) ?>"
                            data-cpf="<?= htmlspecialchars($c['cpf']) ?>"
                            data-email="<?= htmlspecialchars($c['email']) ?>"
                            data-contato="<?= htmlspecialchars($c['contato_valor']) ?>"
                            data-veiculos="<?= htmlspecialchars(json_encode($veiculos), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-pencil-square me-2"></i> Editar
                        </button>
                        <button type="button" class="btn btn-primary w-50 fw-bold d-flex align-items-center justify-content-center btn-add-veiculo"
                            data-id="<?= $c['id'] ?>"
                            data-nome="<?= htmlspecialchars($c['nome_completo']) ?>">
                            <i class="bi bi-plus-circle-fill me-2"></i> Auto
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div id="noResultsMsg" class="text-center py-5 d-none">
            <h5 class="text-secondary fw-bold"><i class="bi bi-search me-2"></i>Nenhum cadastro encontrado para esta busca.</h5>
        </div>
    </main>

    <!-- Modal Editar Cadastro -->
    <div class="modal fade" id="modalEditarCadastro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary"><i class="bi bi-pencil-square me-2"></i>Editar Cadastro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="processa_editar_condutor.php" method="POST">
                        <input type="hidden" id="edit_id" name="id_condutor">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nome Completo</label>
                            <input type="text" id="edit_nome" name="nome_completo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">CPF</label>
                            <input type="text" id="edit_cpf" name="cpf" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">E-mail</label>
                            <input type="email" id="edit_email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Telefone/Contato</label>
                            <input type="text" id="edit_contato" name="contato_valor" class="form-control">
                        </div>

                        <!-- Zona de Exclusão de Veículos -->
                        <div class="mt-4 pt-3 border-top">
                            <label class="form-label small fw-bold text-danger mb-2"><i class="bi bi-exclamation-octagon-fill me-1"></i> Remover Veículos Atuais</label>
                            <div id="edit_veiculos_list" class="d-flex flex-column gap-2">
                                <!-- Preenchido via JS -->
                            </div>
                        </div>

                        <div class="text-end mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Veículo -->
    <div class="modal fade" id="modalAddVeiculo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-top border-4 border-primary shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title text-primary"><i class="bi bi-car-front-fill me-2"></i>Adicionar Veículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 border-bottom pb-2">
                        <span class="small text-secondary">Vincular a: </span><br>
                        <strong class="text-dark" id="add_nome_cadastro">Nome Here</strong>
                    </div>
                    <form action="processa_add_veiculo.php" method="POST">
                        <input type="hidden" id="add_id_usuario" name="id_usuario">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Placa</label>
                                <input type="text" name="placa" class="form-control" placeholder="ABC-1234" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tipo</label>
                                <select name="tipo_veiculo" class="form-select" required>
                                    <option value="Carro">Carro</option>
                                    <option value="Moto">Moto</option>
                                    <option value="Outros">Outros</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Marca/Modelo</label>
                                <input type="text" name="modelo" class="form-control" placeholder="Ex: Honda Civic" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Cor</label>
                                <input type="text" name="cor" class="form-control" placeholder="Ex: Prata" required>
                            </div>
                        </div>

                        <div class="text-end mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Vincular Veículo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
    <script>
        // --- FILTRO DE CADASTROS ---
        const filtroInput = document.getElementById('filtroCadastros');
        const cadastrosList = document.querySelectorAll('.cadastro-item');
        const noResultsMsg = document.getElementById('noResultsMsg');

        if(filtroInput) {
            filtroInput.addEventListener('keyup', function() {
                const termo = this.value.toLowerCase().trim();
                let visibleCount = 0;

                cadastrosList.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(termo)) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if(visibleCount === 0) noResultsMsg.classList.remove('d-none');
                else noResultsMsg.classList.add('d-none');
            });
        }

        // --- SCRIPTS DE MODAIS ---
        const btnEditores = document.querySelectorAll('.btn-editar');
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEditarCadastro'));
        
        btnEditores.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit_id').value = this.getAttribute('data-id');
                document.getElementById('edit_nome').value = this.getAttribute('data-nome');
                
                // Trata o email mascarado
                let em = this.getAttribute('data-email');
                if(em.includes('@rav.tmp')) em = '';
                document.getElementById('edit_email').value = em;
                
                document.getElementById('edit_cpf').value = this.getAttribute('data-cpf');
                document.getElementById('edit_contato').value = this.getAttribute('data-contato');

                // Renderiza Veiculos para Remoção  
                let veiculosStr = this.getAttribute('data-veiculos');
                let divLista = document.getElementById('edit_veiculos_list');
                divLista.innerHTML = '';

                if (veiculosStr) {
                    try {
                        let veiculos = JSON.parse(veiculosStr);
                        if (veiculos && veiculos.length > 0) {
                            veiculos.forEach(v => {
                                divLista.innerHTML += `<div class="d-flex justify-content-between align-items-center p-2 border border-danger-subtle rounded bg-white shadow-sm">
                                    <div>
                                        <span class="badge bg-dark fw-bold me-2"><i class="bi bi-car-front-fill me-1"></i>${v.placa}</span>
                                        <span class="small text-muted">${v.tipo_veiculo} - ${v.modelo}</span>
                                    </div>
                                    <a href="processa_remover_veiculo.php?id=${v.id}&usuario=${v.id_usuario}" 
                                       class="btn btn-sm btn-outline-danger shadow-sm fw-bold d-flex align-items-center" 
                                       onclick="return confirm('ATENÇÃO: Deseja realmente excluir de forma irreversível APENAS a placa ${v.placa} deste condutor?');">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </div>`;
                            });
                        } else {
                            divLista.innerHTML = '<span class="text-muted small fst-italic">Nenhum veículo vinculado no momento.</span>';
                        }
                    } catch (e) { console.error('Veiculos parse error', e); }
                }
                
                modalEdit.show();
            });
        });

        const btnAddVeiculos = document.querySelectorAll('.btn-add-veiculo');
        const modalAdd = new bootstrap.Modal(document.getElementById('modalAddVeiculo'));

        btnAddVeiculos.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('add_id_usuario').value = this.getAttribute('data-id');
                document.getElementById('add_nome_cadastro').textContent = this.getAttribute('data-nome');
                modalAdd.show();
            });
        });

        // --- MÁSCARAS ---
        const elCpf = document.getElementById('edit_cpf');
        if(elCpf) IMask(elCpf, { mask: '000.000.000-00' });
        
        const elContato = document.getElementById('edit_contato');
        if(elContato) IMask(elContato, { mask: '(00) 00000-0000' });

        // --- ACESSIBILIDADE E TEMA ---
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
            }
            
            function toggleTheme() {
                if (body.classList.contains('dark-mode')) {
                    body.classList.remove('dark-mode');
                    body.classList.add('light-mode');
                    localStorage.setItem('theme', 'light');
                } else {
                    body.classList.remove('light-mode');
                    body.classList.add('dark-mode');
                    localStorage.setItem('theme', 'dark');
                }
            }
            const btnContrast = document.getElementById('btn-toggle-contrast');
            if(btnContrast) btnContrast.addEventListener('click', toggleTheme);

            let currentScale = parseFloat(localStorage.getItem('fontScale')) || 1.0;
            document.documentElement.style.setProperty('--font-scale', currentScale);
            
            document.getElementById('btn-increase-font')?.addEventListener('click', () => {
                if(currentScale < 1.3) { currentScale += 0.1; document.documentElement.style.setProperty('--font-scale', currentScale); localStorage.setItem('fontScale', currentScale.toFixed(1)); }
            });
            document.getElementById('btn-decrease-font')?.addEventListener('click', () => {
                if(currentScale > 0.8) { currentScale -= 0.1; document.documentElement.style.setProperty('--font-scale', currentScale); localStorage.setItem('fontScale', currentScale.toFixed(1)); }
            });
        });
    </script>
</body>
</html>
