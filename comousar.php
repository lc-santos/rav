<?php
/**
 * SISTEMA RAV (REGISTRO DE ACESSO DE VEÍCULOS)
 * Helper para renderizar prints ou placeholders
 */
function render_print_guide($filename, $title, $description)
{
    $filepath = 'img/' . $filename;
    if (file_exists($filepath)) {
        return '
        <div class="mb-4 text-center">
            <img src="' . $filepath . '" alt="' . htmlspecialchars($title) . '" class="img-fluid rounded-4 shadow border" style="max-height: 500px; object-fit: contain; width: 100%;">
        </div>';
    } else {
        return '
        <div class="print-placeholder mb-4">
            <i class="bi bi-image print-placeholder-icon"></i>
            <h5 class="fw-bold">Print: ' . htmlspecialchars($title) . '</h5>
            <p class="small text-muted px-4 text-center">' . htmlspecialchars($description) . '</p>
            <div class="x-small text-secondary mt-1">💡 Para exibir a imagem, salve o print em: <code>img/' . htmlspecialchars($filename) . '</code></div>
        </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Como Usar | RAV - Guia Completo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
    <style>
        :root {
            --cps-red: #c00000;
            --cps-dark-gray: #333333;
            --cps-light-gray: #f8f9fa;
            --font-scale: 1.0;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: calc(1rem * var(--font-scale));
            scroll-behavior: smooth;
        }

        .hero-guide {
            background: linear-gradient(135deg, #1e1e24 0%, #0d0d0f 100%);
            border-bottom: 3px solid var(--cps-red);
            padding: 5rem 0 3rem 0;
            color: #ffffff;
        }

        .guide-card-quick {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
        }

        .guide-card-quick:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(192, 0, 0, 0.1) !important;
            border-color: rgba(192, 0, 0, 0.2);
        }

        .guide-section {
            padding: 4rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        .guide-section:last-of-type {
            border-bottom: none;
        }

        .badge-step {
            width: 35px;
            height: 35px;
            background-color: var(--cps-red);
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 10px;
        }

        .print-placeholder {
            background: linear-gradient(145deg, #f1f3f5, #e9ecef);
            border: 2px dashed #ced4da;
            border-radius: 16px;
            min-height: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .print-placeholder:hover {
            border-color: var(--cps-red);
            color: var(--cps-red);
            background: linear-gradient(145deg, #fff2f2, #ffe5e5);
        }

        .print-placeholder-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .sticky-sidebar {
            position: sticky;
            top: 100px;
            z-index: 100;
        }

        .list-group-item-guide {
            border: none;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            color: var(--cps-dark-gray);
            transition: all 0.2s ease;
            border-radius: 8px !important;
            margin-bottom: 4px;
        }

        .list-group-item-guide:hover,
        .list-group-item-guide.active {
            background-color: rgba(192, 0, 0, 0.08) !important;
            color: var(--cps-red) !important;
            font-weight: 600;
        }

        .bg-light-guide {
            background-color: #fcfcfd;
        }

        .icon-box-title {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(192, 0, 0, 0.1);
            color: var(--cps-red);
            border-radius: 12px;
            margin-right: 15px;
            font-size: 1.5rem;
        }

        .dark-mode .print-placeholder {
            background: linear-gradient(145deg, #2b2b35, #1e1e24);
            border-color: #4a4a5a;
            color: #a0a0b0;
        }

        .dark-mode .print-placeholder:hover {
            background: linear-gradient(145deg, #3d2424, #2a1515);
            border-color: var(--cps-red);
            color: #ff8080;
        }
    </style>
</head>

<body class="light-mode section-bg-gray">

    <!-- Accessibility Bar -->
    <div class="accessibility-bar py-1">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <div class="gov-logo mb-2 mb-sm-0">
                <span class="fw-bold text-white small">RAV - PROJETO INSTITUCIONAL</span>
            </div>
            <div class="accessibility-tools d-flex align-items-center gap-3">
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-decrease-font"
                    title="Diminuir Fonte">A-</button>
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-increase-font"
                    title="Aumentar Fonte">A+</button>
                <button type="button" class="btn btn-sm text-white p-0 ms-2" id="btn-toggle-contrast"><i
                        class="bi bi-moon-stars-fill fs-6"></i></button>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header bg-white shadow-sm sticky-top">
        <div class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <a href="index.php" class="text-decoration-none d-flex align-items-center mb-3 mb-md-0">
                <h1 class="logo-text m-0 fw-bold d-flex align-items-center flex-wrap">
                    <span class="text-cps-red fs-1 me-2">RAV</span>
                    <span class="text-dark fs-4 mt-1">Registro de acesso de veículos</span>
                    <span class="badge bg-cps-red text-white ms-2 mt-2"
                        style="font-size: 0.70rem; padding: 0.35em 0.65em;">ETEC's e FATEC's</span>
                </h1>
            </a>
            <div class="search-bar position-relative" style="min-width: 300px;">
                <input type="text" class="form-control rounded-pill pe-5 py-2" placeholder="O que deseja localizar?">
                <button class="btn position-absolute end-0 top-0 h-100 text-cps-red" type="button"><i
                        class="bi bi-search"></i></button>
            </div>
        </div>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg nav-cps p-0 shadow-sm">
            <div class="container">
                <button class="navbar-toggler collapsed my-2 border-white text-white" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <i class="bi bi-list fs-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav w-100 py-2 py-lg-0">
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3"
                                href="index.php">Início</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3"
                                href="funcionalidades.php">Funcionalidades</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-medium px-3 py-3" href="sobre.php">Sobre o
                                Sistema</a></li>
                        <li class="nav-item"><a
                                class="nav-link text-white fw-medium px-3 py-3 active border-bottom border-3 border-white"
                                href="comousar.php">Como Usar</a></li>

                        <li
                            class="nav-item ms-lg-auto d-flex flex-column flex-lg-row align-items-center gap-2 py-2 py-lg-0">
                            <a class="btn btn-light text-cps-red fw-bold rounded-pill px-4 bg-white"
                                href="cadastro.php">
                                <i class="bi bi-building-fill me-1"></i> Cadastrar Unidade
                            </a>
                            <a class="btn btn-light text-cps-red fw-bold rounded-pill px-4 bg-white" href="login.php">
                                <i class="bi bi-person-fill me-1"></i> Acessar Sistema
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>



    <!-- Main Navigation Grid -->
    <div class="bg-white border-bottom py-5">
        <div class="container">
            <h5 class="fw-bold mb-4 text-center">Navegação Rápida pelos Módulos</h5>
            <div class="row row-cols-2 row-cols-md-5 g-3">
                <div class="col">
                    <a href="#cadastro" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-building-add text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Cadastro de Unidade</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#senhas" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-key text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Senhas Portaria e Admin</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#acesso-saida" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-arrow-left-right text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Registrar Entrada e Saída</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#condutores" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-person-vcard text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Cadastrar Condutor</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#visualizar" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-eye text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Visualizar Acessos</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#editar" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-pencil-square text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Editar Cadastros</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#add-veiculo" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-car-front-fill text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Adicionar Veículos</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#estacionamento" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-p-square-fill text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Estacionamento</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#relatorios" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-file-earmark-bar-graph text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Relatórios</span>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#configuracoes" class="text-decoration-none">
                        <div
                            class="guide-card-quick bg-light p-3 text-center h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-gear-fill text-cps-red fs-2 mb-2"></i>
                            <span class="small fw-semibold text-dark">Configurações</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="py-5">
        <div class="container">
            <div class="row g-4">

                <!-- Sticky Sidebar -->
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="sticky-sidebar card p-3 border shadow-sm rounded-4 bg-white">
                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-cps-red">Tópicos do Guia</h6>
                        <div class="list-group list-group-flush">
                            <a href="#cadastro" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-building-add me-2"></i>Cadastro de Unidade</a>
                            <a href="#senhas" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-key me-2"></i>Senhas Portaria/Admin</a>
                            <a href="#acesso-saida" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-arrow-left-right me-2"></i>Acesso e Saída</a>
                            <a href="#condutores" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-person-vcard me-2"></i>Cadastrar Condutor</a>
                            <a href="#visualizar" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-eye me-2"></i>Visualizar Acessos</a>
                            <a href="#editar" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-pencil-square me-2"></i>Editar Cadastros</a>
                            <a href="#add-veiculo" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-car-front me-2"></i>Adicionar Veículos</a>
                            <a href="#estacionamento" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-p-square me-2"></i>Estacionamento</a>
                            <a href="#relatorios" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-file-earmark-bar-graph me-2"></i>Relatórios</a>
                            <a href="#configuracoes" class="list-group-item list-group-item-guide"><i
                                    class="bi bi-gear me-2"></i>Configurações</a>
                        </div>
                    </div>
                </div>

                <!-- Guide Sections -->
                <div class="col-lg-9">
                    <div class="card border shadow-sm rounded-4 bg-white p-4 p-md-5">

                        <!-- SECTION 1: Cadastro de Unidade -->
                        <section id="cadastro" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-building-add"></i></div>
                                <h3 class="fw-bold mb-0">1. Cadastro de Unidade (ETEC/FATEC)</h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                Para iniciar o uso do RAV em sua escola, a unidade de ensino (ETEC ou FATEC) deve ser
                                previamente registrada. O cadastro define os dados da unidade e as chaves de acesso.
                            </p>

                            <?= render_print_guide('print_cadastro.png', 'Tela de Cadastro de Unidade', 'Mostra os campos: Nome da Unidade, Documento (CNPJ/INEP), Telefone, Dados do Gestor (Nome, Email, CPF, Data Nasc.), Endereço da Unidade, Código Identificador e Senhas (Geral/Admin e Portaria).') ?>

                            <h5 class="fw-bold mb-3">Passo a Passo:</h5>
                            <ol class="lh-lg mb-0 text-secondary">
                                <li>Clique no botão <strong class="text-dark">Cadastrar Unidade</strong> localizado no
                                    canto superior direito do site.</li>
                                <li>Insira o nome oficial da escola (ex: <em class="text-dark">ETEC Centro Paula
                                        Souza</em>), documento (CNPJ/INEP) e telefone institucional.</li>
                                <li>Preencha os dados do Gestor Principal (Nome, E-mail, CPF, Data de Nascimento) e o
                                    endereço completo da unidade.</li>
                                <li>Crie um <strong class="text-dark">Código Identificador único</strong> (ex: <em
                                        class="text-dark">etec123</em>), que servirá de login de acesso dos usuários
                                    associados a esta Etec.</li>
                                <li>Defina a senha administrativa (geral) e a senha dos porteiros.</li>
                                <li>Clique em <strong class="text-dark">Finalizar Cadastro da Unidade</strong>. O
                                    sistema irá registrar a sua unidade no banco de dados centralizado.</li>
                            </ol>
                        </section>

                        <!-- SECTION 2: Senhas -->
                        <section id="senhas" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-key"></i></div>
                                <h3 class="fw-bold mb-0">2. Senha Portaria e Administrativa</h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                O RAV opera com perfis de acesso bem definidos utilizando as duas chaves de segurança
                                criadas no momento do cadastro.
                            </p>

                            <?= render_print_guide('print_login.png', 'Tela de Login e Seleção de Perfil', 'Foco no formulário de login exibindo o campo "Código Identificador" e campo de senha.') ?>

                            <div class="row g-4 mt-2">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light-guide h-100">
                                        <h6 class="fw-bold text-cps-red"><i class="bi bi-door-open-fill me-2"></i>Senha
                                            de Portaria (Operacional)</h6>
                                        <p class="small text-secondary mb-0 mt-2">
                                            Utilizada pelos porteiros no dia a dia da escola. Permite apenas ações
                                            rápidas de portaria: registrar novas entradas, saídas de automóveis,
                                            visualizar o pátio e cadastrar novos condutores. Não permite alterar
                                            configurações gerais ou excluir registros.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-light-guide h-100">
                                        <h6 class="fw-bold text-dark"><i class="bi bi-shield-lock-fill me-2"></i>Senha
                                            Administrativa (Gestor)</h6>
                                        <p class="small text-secondary mb-0 mt-2">
                                            Utilizada pela direção ou equipe de TI responsável. Fornece controle total
                                            sobre o sistema: alteração de senhas das duas contas, exclusão e edição de
                                            condutores cadastrados, emissão de relatórios consolidados e alteração de
                                            parâmetros da unidade.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- SECTION 3: Registrar Acesso e Variações -->
                        <section id="acesso-saida" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-arrow-left-right"></i></div>
                                <h3 class="fw-bold mb-0">3. Registrar Entrada, Saída e suas Variações</h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                O controle em tempo real é o núcleo do RAV. O operador de portaria registra quando um
                                veículo passa pela guarita.
                            </p>

                            <?= render_print_guide('print_registro_acesso.png', 'Formulário de Registro de Acesso Rápido', 'Interface com campo de busca por placa, exibição dos dados do condutor e botão de liberação.') ?>

                            <h5 class="fw-bold mb-3">Variações de Entrada:</h5>
                            <div class="accordion mb-4" id="accordionAcesso">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne">
                                            A. Condutor Pré-Cadastrado
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionAcesso">
                                        <div class="accordion-body text-secondary">
                                            Basta digitar a placa no campo de busca rápida. O sistema exibirá a foto,
                                            nome, setor (ex: Professor, Aluno) e o veículo vinculado. Clique no botão de
                                            confirmação verde para registrar a entrada em apenas um clique.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            B. Condutor Não Cadastrado / Visitante
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionAcesso">
                                        <div class="accordion-body text-secondary">
                                            Se a placa não for localizada, clique em <strong class="text-dark">Cadastrar
                                                Visitante Rápido</strong>. Preencha o nome do visitante, telefone e
                                            dados básicos do automóvel para registrar a entrada. Esse registro entra
                                            automaticamente na fila.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3">Como Registrar Saída:</h5>
                            <p class="text-secondary lh-lg">
                                No painel da portaria, haverá um painel visual denominado "Veículos no Pátio". Ao lado
                                do registro de cada carro ativo, há um botão vermelho <strong
                                    class="text-danger">Registrar Saída</strong>. Clicando nele, o automóvel é marcado
                                como fora das dependências, atualizando a ocupação do pátio imediatamente.
                            </p>
                        </section>

                        <!-- SECTION 4: Cadastrar Condutor -->
                        <section id="condutores" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-person-vcard"></i></div>
                                <h3 class="fw-bold mb-0">4. Cadastrar Condutor</h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                O cadastro de condutores recorrentes (Professores, Diretores, Funcionários
                                Administrativos e Alunos autorizados) facilita o controle e elimina a necessidade de
                                preenchimento manual a cada acesso.
                            </p>

                            <?= render_print_guide('print_cadastro_condutor.png', 'Tela de Cadastro Completo de Condutor', 'Foco nos formulários de Nome, Categoria (Funcionário/Aluno), RG/CPF e informações iniciais do veículo.') ?>

                            <h5 class="fw-bold mb-3">Como Realizar:</h5>
                            <ol class="lh-lg text-secondary">
                                <li>Acesse o menu <strong class="text-dark">Gerenciar Condutores</strong> na barra
                                    lateral do painel.</li>
                                <li>Clique no botão <strong class="text-dark">Adicionar Novo Condutor</strong>.</li>
                                <li>Preencha o nome completo, telefone e escolha o tipo de vínculo institucional
                                    (Categoria).</li>
                                <li>Adicione os dados do primeiro veículo do condutor (Placa, Modelo, Cor e Tipo).</li>
                                <li>Salve o cadastro.</li>
                            </ol>
                        </section>

                        <!-- SECTION 5: Visualizar Acessos -->
                        <section id="visualizar" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-eye"></i></div>
                                <h3 class="fw-bold mb-0">5. Visualizar Acessos</h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                A tela de acessos mostra em ordem cronológica de tempo real quem entrou e saiu da
                                escola. É a auditoria mais importante para garantir a segurança no ambiente de ensino.
                            </p>

                            <?= render_print_guide('print_visualizar_acessos.png', 'Histórico de Acessos Recentes', 'Grade exibindo Foto/Nome do Condutor, Placa do Veículo, Data/Hora da Entrada e Data/Hora da Saída com botões de filtro.') ?>

                            <p class="text-secondary lh-lg">
                                É possível usar a barra de filtros rápidos nesta página para buscar um acesso específico
                                por data de ocorrência, nome do motorista ou placa. Os registros ativos (ainda dentro da
                                escola) são destacados de forma visual (linha com fundo verde ou ícone ativo).
                            </p>
                        </section>

                        <!-- SECTION 6: Editar Cadastros -->
                        <section id="editar" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-pencil-square"></i></div>
                                <h3 class="fw-bold mb-0">6. Editar Cadastros</h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                Caso o número de telefone, nome ou categoria de um motorista mude, a atualização
                                cadastral pode ser realizada de maneira instantânea (Exclusivo do Perfil
                                Administrativo).
                            </p>

                            <?= render_print_guide('print_editar_cadastros.png', 'Painel de Edição de Condutor', 'Modal pop-up de edição com campos preenchidos e botão "Salvar Alterações".') ?>

                            <h5 class="fw-bold mb-3">Passos:</h5>
                            <ol class="lh-lg text-secondary">
                                <li>No menu <strong class="text-dark">Gerenciar Condutores</strong>, localize a pessoa
                                    desejada.</li>
                                <li>Clique no botão azul com ícone de lápis (<strong
                                        class="text-primary">Editar</strong>) ao lado do nome.</li>
                                <li>Altere os campos no formulário.</li>
                                <li>Clique em <strong class="text-dark">Salvar Alterações</strong> para atualizar os
                                    dados no banco central.</li>
                            </ol>
                        </section>

                        <!-- SECTION 7: Adicionar Automóveis a Cadastro -->
                        <section id="add-veiculo" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-car-front-fill"></i></div>
                                <h3 class="fw-bold mb-0">7. Adicionar Automóveis a um Cadastro Existente</h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                O RAV permite vincular múltiplos veículos (carro próprio, moto, carro do cônjuge, etc.)
                                ao mesmo cadastro de condutor, evitando duplicidades.
                            </p>

                            <?= render_print_guide('print_adicionar_veiculos.png', 'Aba de Veículos Vinculados', 'Foco na seção de um condutor onde é possível listar e adicionar novas placas com o botão "Vincular Novo Veículo".') ?>

                            <h5 class="fw-bold mb-3">Como Associar:</h5>
                            <ol class="lh-lg text-secondary">
                                <li>Acesse os detalhes de um condutor no painel.</li>
                                <li>Na seção <strong class="text-dark">Veículos Vinculados</strong>, clique em <strong
                                        class="text-dark">Adicionar Veículo</strong>.</li>
                                <li>Digite a nova Placa, Marca/Modelo, Cor e Tipo de veículo.</li>
                                <li>Clique em <strong class="text-dark">Vincular</strong>. Agora, qualquer busca pela
                                    nova placa reconhecerá esse mesmo condutor.</li>
                            </ol>
                        </section>

                        <!-- SECTION 8: Estacionamento -->
                        <section id="estacionamento" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-p-square-fill"></i></div>
                                <h3 class="fw-bold mb-0">8. Como Funciona o Estacionamento (Capacidade em Tempo Real)
                                </h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                O sistema possui um contador de vagas configurável para evitar sobrecargas no pátio
                                interno da unidade escolar.
                            </p>

                            <?= render_print_guide('print_estacionamento.png', 'Dashboard do Estacionamento e Vagas', 'Painel com gráfico circular de ocupação: "85% ocupado - 85 de 100 vagas preenchidas".') ?>

                            <p class="text-secondary lh-lg mb-0">
                                O painel da portaria exibe um card com o status atual do pátio: <strong
                                    class="text-dark">Vagas Ocupadas / Vagas Totais</strong>. Cada vez que a entrada de
                                um carro é confirmada, o número de vagas disponíveis diminui automaticamente. No caso da
                                confirmação de saída, a vaga é liberada. Caso a capacidade chegue a 100%, o sistema
                                alerta na tela do porteiro que o estacionamento está lotado.
                            </p>
                        </section>

                        <!-- SECTION 9: Relatórios -->
                        <section id="relatorios" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-file-earmark-bar-graph"></i></div>
                                <h3 class="fw-bold mb-0">9. Relatórios</h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                Módulo voltado para análise de dados e auditoria em casos de sinistros ou incidentes de
                                segurança.
                            </p>

                            <?= render_print_guide('print_relatorios.png', 'Painel de Exportação e Filtros de Relatório', 'Filtros de data inicial/final, exportação em arquivo CSV ou opção de impressão direta otimizada.') ?>

                            <h5 class="fw-bold mb-3">Recursos Disponíveis:</h5>
                            <ul class="lh-lg text-secondary">
                                <li><strong class="text-dark">Relatório por Data</strong>: Obtenha a lista completa de
                                    todos os veículos que cruzaram a portaria em um dia ou período específico.</li>
                                <li><strong class="text-dark">Relatório por Condutor</strong>: Saiba quais datas e horas
                                    um professor ou aluno específico entrou na unidade.</li>
                                <li><strong class="text-dark">Impressão Otimizada (Print Ready)</strong>: Formato CSS de
                                    impressão limpo, ideal para exportação em PDF ou papel.</li>
                            </ul>
                        </section>

                        <!-- SECTION 10: Configurações -->
                        <section id="configuracoes" class="guide-section">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box-title"><i class="bi bi-gear-fill"></i></div>
                                <h3 class="fw-bold mb-0">10. Configurações</h3>
                            </div>
                            <p class="text-secondary lh-lg mb-4">
                                O Painel de Configurações centraliza a manutenção cadastral e de segurança da unidade de
                                ensino.
                            </p>

                            <?= render_print_guide('print_configuracoes.png', 'Aba de Configurações do Sistema', 'Foco nos painéis de alteração de senha da portaria, e-mail institucional e quantidade de vagas do pátio.') ?>

                            <h5 class="fw-bold mb-3">O que pode ser alterado (Administrador):</h5>
                            <ul class="lh-lg text-secondary mb-0">
                                <li><strong class="text-dark">Senha de Acesso Geral</strong>: Trocar as credenciais da
                                    Portaria ou do Administrador sempre que necessário.</li>
                                <li><strong class="text-dark">Capacidade de Vagas</strong>: Ajustar o limite do
                                    estacionamento em tempo real de acordo com as necessidades ou reformas da escola.
                                </li>
                                <li><strong class="text-dark">Dados de Contato</strong>: Atualizar o e-mail ou telefone
                                    institucional da unidade.</li>
                            </ul>
                        </section>

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
                    <h3
                        class="fw-bold m-0 mb-3 d-flex align-items-center justify-content-center justify-content-md-start">
                        <span class="text-white">RAV</span>
                    </h3>
                    <p class="small text-white-50 lh-lg pe-md-3">
                        Sistema de Registro de Acesso de Veículos exclusivo para ETECs e FATECs. Desenvolvido para
                        modernizar, agilizar e trazer segurança inteligente para a portaria.
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

    <!-- Bootstrap/Scripts -->
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