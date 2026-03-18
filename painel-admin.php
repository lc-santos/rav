<?php
require_once 'conn.php';

// Busca veículos que estão "Dentro" (Ajustado para data_hora_entrada)
$stmtSaida = $pdo->query("SELECT r.*, v.placa FROM registros_acesso r 
                          JOIN veiculos v ON r.id_veiculo = v.id 
                          WHERE r.status = 'Dentro' ORDER BY r.data_hora_entrada DESC");
$veiculosDentro = $stmtSaida->fetchAll(PDO::FETCH_ASSOC);

// Busca os últimos 5 acessos (Ajustado para data_hora_entrada)
$stmtRecentes = $pdo->query("SELECT r.*, v.placa FROM registros_acesso r 
                             JOIN veiculos v ON r.id_veiculo = v.id 
                             ORDER BY r.data_hora_entrada DESC LIMIT 5");
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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand fw-bold" href="#">RAV <span class="badge bg-secondary font-monospace">Admin</span></a>
            <div class="dropdown">
                <i class="bi bi-person-circle text-white fs-4" data-bs-toggle="dropdown"></i>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Configurações</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="sair.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
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
                <li class="nav-item"><a href="#" class="nav-link text-white active bg-success bg-opacity-50"><i class="bi bi-house-door me-2"></i> Painel Inicial</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white"><i class="bi bi-p-circle me-2"></i>
                        Estacionamento</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white"><i class="bi bi-shield-check me-2"></i>
                        Acessos</a></li>
            </ul>
        </div>
    </div>

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

        <div class="row g-4">
            <div class="col=12 col lg-8">
                <div class="card bg-dark-card border-success-subtle mb-4 shadow-lg">
                    <div class="card-header bg-primary text-white py-2 px-3 d-flex align-items-center">
                        <i class="bi bi-plus-circle-fill me-2"></i> <span class="fw-bold">Usuário Cadastrado</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="col-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-info">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input type="text" id="busca_rapida" class="form-control bg-dark text-white border-primary" placeholder="ID ou CPF cadastrado">
                            </div>

                            <button type="button" id="btnAbrirModal" class="btn btn-outline-info w-100 mt-2" data-bs-toggle="modal" data-bs-target="#modalCadastro">
                                <i class="bi bi-person-plus-fill me-2"></i> Cadastrar Novo Condutor
                            </button>

                            <div id="lista_veiculos_encontrados" class="mt-2 d-none">
                                <div class="card bg-dark border-info shadow-sm">
                                    <div class="card-body p-3" id="container_opcoes">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
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

                        <form action="registrar_acesso.php" method="POST" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-secondary fw-bold">Tipo Veículo</label>
                                <select id="selectTipoVeiculo" name="tipo_veiculo" class="form-select bg-dark border-secondary text-white">
                                    <option value="" selected disabled>Selecione...</option>
                                    <option value="Carro">Carro</option>
                                    <option value="Moto">Moto</option>
                                    <option value="Bicicleta">Bicicleta</option>
                                    <option value="Caminhão">Caminhão</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-secondary fw-bold">Tipo Acesso</label>
                                <select name="tipo_acesso" class="form-select bg-dark border-secondary text-white">
                                    <option>Aluno</option>
                                    <option>Diretoria</option>
                                    <option>Professor</option>
                                    <option>Serviço</option>
                                </select>
                            </div>

                            <div id="secaoDetalhesVeiculo" class="col-12 d-none">
                                <div class="card bg-secondary bg-opacity-10 border-secondary p-3">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label id="labelPlaca" class="form-label small text-info fw-bold">Placa</label>
                                            <input type="text" id="placa" name="placa" class="form-control bg-dark border-secondary text-white">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-secondary fw-bold">Modelo/Marca</label>
                                            <input type="text" name="modelo_veiculo" class="form-control bg-dark border-secondary text-white">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-secondary fw-bold">Cor</label>
                                            <input type="text" name="cor_veiculo" class="form-control bg-dark border-secondary text-white">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small text-secondary fw-bold">Nome</label>
                                <input type="text" name="nome_condutor" class="form-control bg-dark border-secondary text-white">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-secondary fw-bold">Contato</label>
                                <input type="text" name="contato_valor" id="inputContato" class="form-control bg-dark border-secondary text-white" placeholder="Telefone ou E-mail">
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2 mb-2">
                                    <button type="submit" class="btn btn-success flex-grow-1 fw-bold py-2">
                                        Registrar Acesso <i class="bi bi-check2-circle ms-1"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseObs">
                                        <i class="bi bi-chat-left-text"></i>
                                    </button>

                                </div>

                                <div class="collapse mt-3" id="collapseObs">
                                    <textarea name="observacao" class="form-control bg-dark text-white border-secondary" placeholder="Observações ou avarias..." rows="3"></textarea>
                                </div>




                            </div>

                        </form>
                    </div>
                </div>

                <div class="card bg-dark-card border-danger-subtle shadow-lg mt-4">
                    <div class="card-header bg-danger text-white text-center py-2 fw-bold">Registrar saída</div>
                    <div class="list-group list-group-flush">
                        <?php if (count($veiculosDentro) > 0) : ?>
                            <?php foreach ($veiculosDentro as $reg) : ?>
                                <div class="list-group-item bg-dark text-white border-secondary d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <h6 class="mb-0 text-success fw-bold"><?= $reg['placa'] ?></h6>
                                        <small class="text-secondary"><?= $reg['nome_condutor'] ?> - Dentro: <?= date('H:i', strtotime($reg['data_hora_entrada'])) ?></small>
                                    </div>
                                    <a href="registrar_saida.php?id=<?= $reg['id'] ?>" class="btn btn-sm btn-outline-danger">SAÍDA</a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
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
                                <?php foreach ($acessosRecentes as $reg) : ?>
                                    <li class="list-group-item bg-dark text-white border-secondary small py-3">
                                        <i class="bi bi-circle-fill <?= $reg['status'] == 'Dentro' ? 'text-success' : 'text-danger' ?> me-2"></i>
                                        <?= $reg['placa'] ?> - <?= $reg['nome_condutor'] ?> - <?= date('H:i', strtotime($reg['data_hora_entrada'])) ?>
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

    <<div class="modal fade" id="modalCadastro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Cadastro Completo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="processa_cadastro.php" method="POST">
                        <div class="mb-4">
                            <h6 class="text-info border-bottom border-secondary pb-2 mb-3">
                                <i class="bi bi-person-lines-fill me-2"></i>Dados do Condutor
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small">Nome Completo</label>
                                    <input type="text" id="modalNomeCondutor" name="nome_completo" class="form-control bg-dark text-white border-secondary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">CPF</label>
                                    <input type="text" id="modalCPF" name="cpf" class="form-control bg-dark text-white border-secondary" placeholder="000.000.000-00">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">E-mail</label>
                                    <input type="email" id="modalEmail" name="email" class="form-control bg-dark text-white border-secondary" placeholder="exemplo@email.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Telefone/Celular</label>
                                    <input type="text" id="modalTelefone" name="telefone" class="form-control bg-dark text-white border-secondary" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-success border-bottom border-secondary pb-2 mb-3">
                                <i class="bi bi-car-front-fill me-2"></i>Dados do Veículo
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small">Tipo Veículo</label>
                                    <select id="modalTipoVeiculo" name="tipo_veiculo" class="form-select bg-dark text-white border-secondary">
                                        <option value="Carro">Carro</option>
                                        <option value="Moto">Moto</option>
                                        <option value="Bicicleta">Bicicleta</option>
                                        <option value="Caminhão">Caminhão</option>
                                        <option value="Outros">Outros</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Placa</label>
                                    <input type="text" id="modalPlacaVeiculo" name="placa" class="form-control bg-dark text-white border-secondary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Marca/Modelo</label>
                                    <input type="text" id="modalModelo" name="modelo_veiculo" class="form-control bg-dark text-white border-secondary">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Cor</label>
                                    <input type="text" id="modalCor" name="cor_veiculo" class="form-control bg-dark text-white border-secondary">
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4 pt-3 border-top border-secondary">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success px-4 fw-bold">Salvar Cadastro</button>
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

</body>

</html>