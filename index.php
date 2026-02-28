<?php
require_once 'conn.php';

// Busca veículos que estão "Dentro" para o card de Saída
$stmtSaida = $pdo->query("SELECT r.*, v.placa FROM registros_acesso r 
                          JOIN veiculos v ON r.id_veiculo = v.id 
                          WHERE r.status = 'Dentro' ORDER BY r.data_entrada DESC");
$veiculosDentro = $stmtSaida->fetchAll(PDO::FETCH_ASSOC);

// Busca os últimos 5 acessos para o card lateral
$stmtRecentes = $pdo->query("SELECT r.*, v.placa FROM registros_acesso r 
                             JOIN veiculos v ON r.id_veiculo = v.id 
                             ORDER BY r.data_entrada DESC LIMIT 5");
$acessosRecentes = $stmtRecentes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand fw-bold" href="#">RAV <span
                    class="badge bg-secondary font-monospace">Admin</span></a>
            <div class="dropdown">
                <i class="bi bi-person-circle text-white fs-4" data-bs-toggle="dropdown"></i>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Configurações</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right"></i> Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="menuLateral">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="p-3 bg-secondary bg-opacity-25 mb-3">
                <small class="text-uppercase text-secondary d-block">Unidade</small>
                <p class="mb-0 small">Guarita - ETEC</p>
                <p class="mb-0 small"><strong>Op:</strong> Lucas Silva</p>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="#" class="nav-link text-white active bg-success bg-opacity-50"><i
                            class="bi bi-house-door me-2"></i> Painel Inicial</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white"><i class="bi bi-p-circle me-2"></i>
                        Estacionamento</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white"><i class="bi bi-shield-check me-2"></i>
                        Acessos</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white border-top border-secondary mt-2 pt-2"><i
                            class="bi bi-pencil-square me-2"></i> Editar Painel</a></li>
            </ul>
        </div>
    </div>

    <main class="container py-4">

        <div class="row mb-4">
            <div class="col-12">
                <div class="input-group input-group-lg custom-search">
                    <span class="input-group-text bg-dark border-secondary text-secondary"><i
                            class="bi bi-search"></i></span>
                    <input type="text" class="form-control bg-dark border-secondary text-white"
                        placeholder="Placa, Veículo, Nome...">
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">

                <div class="card bg-dark-card border-success-subtle mb-4 shadow-lg">
                    <div class="card-header bg-success text-white py-2 px-3 d-flex align-items-center">
                        <i class="bi bi-plus-circle-fill me-2"></i> <span class="fw-bold">Registrar Acesso</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="col-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-info">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input type="text" id="busca_rapida" class="form-control bg-dark text-white border-secondary" placeholder="ID ou CPF cadastrado">
                            </div>
                            <div id="lista_veiculos_encontrados" class="mt-2 d-none">
                                <div class="card bg-dark border-info shadow-sm">
                                    <div class="card-body p-3" id="container_opcoes">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="registrar_acesso.php" method="POST" class="row g-3">
                            <div class="col-md-4 col-6">
                                <label class="form-label small text-secondary fw-bold">Tipo Veículo</label>
                                <select name="tipo_veiculo" class="form-select bg-dark border-secondary text-white">
                                    <option>Carro</option>
                                    <option>Moto</option>
                                    <option>Bicicleta</option>
                                    <option>Caminhão</option>
                                    <option>Outros</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-6">
                                <label class="form-label small text-secondary fw-bold">Placa</label>
                                <input type="text" id="placa" name="placa"
                                    class="form-control bg-dark border-secondary text-white" placeholder="ex: ABC1234">
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label small text-secondary fw-bold">Tipo Acesso</label>
                                <select name="tipo_acesso" class="form-select bg-dark border-secondary text-white">
                                    <option>Aluno</option>
                                    <option>Diretoria</option>
                                    <option>Professor</option>
                                    <option>Funcionário</option>
                                    <option>Serviço</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label small text-secondary fw-bold">Nome</label>
                                <input type="text" name="nome_condutor"
                                    class="form-control bg-dark border-secondary text-white">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-secondary fw-bold">Contato</label>
                                <div class="input-group">
                                    <select id="tipoContato" name="contato_tipo"
                                        class="form-select bg-dark text-white border-secondary"
                                        style="max-width: 110px; font-size: 0.8rem;">
                                        <option value="tel">Tel</option>
                                        <option value="email">E-mail</option>
                                    </select>
                                    <input type="text" name="contato_valor" id="inputContato"
                                        class="form-control bg-dark text-white border-secondary"
                                        placeholder="(00) 00000-0000">
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2 mb-2">
                                    <button type="submit" class="btn btn-success flex-grow-1 fw-bold py-2">
                                        Registrar <i class="bi bi-check2-circle ms-1"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary py-2 px-3" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseObs" aria-expanded="false">
                                        <i class="bi bi-chat-left-text"></i>
                                    </button>

                                </div>
                                <button type="button" class="btn btn-outline-info w-100 mt-3" data-bs-toggle="modal" data-bs-target="#modalCadastro">
                                    <i class="bi bi-person-plus-fill me-2"></i> Cadastrar
                                </button>

                                <div class="collapse" id="collapseObs">
                                    <div class="position-relative">
                                        <textarea name="observacao"
                                            class="form-control bg-dark text-white border-secondary"
                                            placeholder="Adicione uma observação (Ex: Veículo com avaria, portão específico...)"
                                            rows="3" style="resize: none;"></textarea>
                                        <button type="button"
                                            class="btn btn-sm btn-link text-success position-absolute bottom-0 end-0 m-2"
                                            data-bs-toggle="collapse" data-bs-target="#collapseObs">
                                            <i class="bi bi-plus-lg fs-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card bg-dark-card border-danger-subtle shadow-lg mt-4">
                    <div class="card-header bg-danger text-white text-center py-2 fw-bold">Registrar saída</div>
                    <div class="list-group list-group-flush">
                        <?php if (count($veiculosDentro) > 0): ?>
                            <?php foreach ($veiculosDentro as $reg): ?>
                                <div class="list-group-item bg-dark text-white border-secondary d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <h6 class="mb-0 text-success fw-bold"><?= $reg['placa'] ?></h6>
                                        <small class="text-secondary"><?= $reg['nome_condutor'] ?> - In: <?= date('H:i', strtotime($reg['data_entrada'])) ?></small>
                                    </div>
                                    <a href="registrar_saida.php?id=<?= $reg['id'] ?>" class="btn btn-sm btn-outline-danger">SAÍDA</a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-4 text-center text-secondary"><small>Nenhum veículo no pátio.</small></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="sticky-lg-top" style="top: 80px;">
                    <div class="card bg-dark-card border-primary shadow-lg overflow-hidden">
                        <div class="card-header bg-primary text-white text-center fw-bold">
                            Acessos Recentes
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush" id="lista-recentes">
                                <?php foreach ($acessosRecentes as $reg): ?>
                                    <li class="list-group-item bg-dark text-white border-secondary small py-3">
                                        <i class="bi bi-circle-fill <?= $reg['status'] == 'Dentro' ? 'text-success' : 'text-danger' ?> me-2"></i>
                                        <?= $reg['placa'] ?> - <?= date('H:i', strtotime($reg['data_entrada'])) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="p-3 bg-dark">
                                <a href="historico.php" class="btn btn-outline-primary btn-sm w-100">Ver histórico
                                    completo</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalCadastro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Cadastro Completo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="processa_cadastro.php" method="POST" class="row g-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Nome do Condutor (ID automático)</label>
                                <input type="text" id="modalNomeCondutor" name="nome_completo" class="form-control bg-dark text-white border-secondary" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Placa</label>
                                <input type="text" id="modalPlacaVeiculo" name="placa" class="form-control bg-dark text-white border-secondary" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small">CPF do Condutor</label>
                                <input type="text" name="cpf" id="cpf_modal" class="form-control bg-dark text-white border-secondary" placeholder="000.000.000-00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Marca/Modelo do Veículo</label>
                                <input type="text" name="modelo_veiculo" class="form-control bg-dark text-white border-secondary" placeholder="Ex: Honda Civic">
                            </div>

                            <hr class="border-secondary">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Salvar Cadastro</button>
                            </div>
                    </form>
                </div>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/imask"></script>
        <script src="script.js"></script>
</body>

</html>