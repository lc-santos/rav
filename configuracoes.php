<?php
/**
 * RAV ETEC — configuracoes.php
 * Painel de Configurações do Sistema (Exclusivo Admin)
 * Segurança: autenticação de sessão + password_verify + PDO/try-catch
 */

session_start();
require_once 'conn.php';

// ============================================================
// TRAVA DE SEGURANÇA — Apenas administradores
// ============================================================
if (!isset($_SESSION['acesso']) || $_SESSION['acesso'] !== 'admin') {
    http_response_code(403);
    header('Location: login.php');
    exit('HTTP/1.1 403 Forbidden');
}

$etec_id   = $_SESSION['etec_id']  ?? 0;
$mensagens = []; // ['tipo' => 'success|danger', 'texto' => '...']

// ============================================================
// Busca dados atuais da unidade logada
// ============================================================
try {
    $stmtEtec = $pdo->prepare("
        SELECT id, empresaNome, email, telefone,
               codigo_identificador, senha_admin, senha_portaria,
               nome_completo
        FROM unidades WHERE id = :id LIMIT 1
    ");
    $stmtEtec->execute([':id' => $etec_id]);
    $etec = $stmtEtec->fetch(PDO::FETCH_ASSOC);

    if (!$etec) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    die('<div class="alert alert-danger m-4">Erro crítico de banco de dados: ' . htmlspecialchars($e->getMessage()) . '</div>');
}

// ============================================================
// PROCESSAMENTO POST — Card: Minha Conta
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar_conta') {
    try {
        $conf_senha = $_POST['confirmacao_senha_admin_conta'] ?? '';
        $novo_nome  = trim($_POST['nome_completo']  ?? '');
        $novo_email = trim($_POST['email']           ?? '');
        $novo_tel   = preg_replace('/\D/', '', $_POST['telefone'] ?? '');

        // 1. Verificar senha administrativa atual
        if (!password_verify($conf_senha, $etec['senha_admin'])) {
            $mensagens[] = ['tipo' => 'danger', 'card' => 'conta',
                'texto' => '<i class="bi bi-shield-x me-2"></i>Senha administrativa incorreta. Alteração bloqueada.'];
        } elseif (empty($novo_nome) || empty($novo_email)) {
            $mensagens[] = ['tipo' => 'danger', 'card' => 'conta',
                'texto' => '<i class="bi bi-exclamation-triangle me-2"></i>Nome e e-mail são obrigatórios.'];
        } else {
            $stmtUp = $pdo->prepare("
                UPDATE unidades SET nome_completo = :nome, email = :email, telefone = :tel
                WHERE id = :id
            ");
            $stmtUp->execute([
                ':nome'  => $novo_nome,
                ':email' => $novo_email,
                ':tel'   => $novo_tel,
                ':id'    => $etec_id
            ]);
            // Recarrega os dados
            $etec['nome_completo'] = $novo_nome;
            $etec['email']         = $novo_email;
            $etec['telefone']      = $novo_tel;
            $mensagens[] = ['tipo' => 'success', 'card' => 'conta',
                'texto' => '<i class="bi bi-check-circle me-2"></i>Perfil atualizado com sucesso.'];
        }
    } catch (PDOException $e) {
        $mensagens[] = ['tipo' => 'danger', 'card' => 'conta',
            'texto' => 'Erro no banco: ' . htmlspecialchars($e->getMessage())];
    }
}

// ============================================================
// PROCESSAMENTO POST — Card: Segurança (Alterar Senhas)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'alterar_senha') {
    try {
        $conf_senha_atual = $_POST['confirmacao_senha_admin_seg'] ?? '';
        $qual_senha       = $_POST['qual_senha']          ?? 'admin'; // 'admin' ou 'portaria'
        $nova_senha       = $_POST['nova_senha']           ?? '';
        $confirmar_nova   = $_POST['confirmar_nova_senha'] ?? '';

        // 1. Verificar senha administrativa atual
        if (!password_verify($conf_senha_atual, $etec['senha_admin'])) {
            $mensagens[] = ['tipo' => 'danger', 'card' => 'seguranca',
                'texto' => '<i class="bi bi-shield-x me-2"></i>Senha administrativa atual incorreta. Alteração bloqueada por segurança.'];
        } elseif (strlen($nova_senha) < 6) {
            $mensagens[] = ['tipo' => 'danger', 'card' => 'seguranca',
                'texto' => '<i class="bi bi-exclamation-triangle me-2"></i>A nova senha deve ter pelo menos 6 caracteres.'];
        } elseif ($nova_senha !== $confirmar_nova) {
            $mensagens[] = ['tipo' => 'danger', 'card' => 'seguranca',
                'texto' => '<i class="bi bi-x-circle me-2"></i>A nova senha e a confirmação não coincidem.'];
        } else {
            $hash_novo = password_hash($nova_senha, PASSWORD_BCRYPT);
            $campo     = ($qual_senha === 'portaria') ? 'senha_portaria' : 'senha_admin';

            $stmtSenha = $pdo->prepare("UPDATE unidades SET {$campo} = :hash WHERE id = :id");
            $stmtSenha->execute([':hash' => $hash_novo, ':id'  => $etec_id]);

            $nomeAmigavel = ($qual_senha === 'portaria') ? 'Portaria' : 'Administrativa';
            $mensagens[] = ['tipo' => 'success', 'card' => 'seguranca',
                'texto' => "<i class='bi bi-check-circle me-2'></i>Senha {$nomeAmigavel} alterada com sucesso."];

            // Atualiza hash em memória se for a admin
            if ($qual_senha === 'admin') $etec['senha_admin'] = $hash_novo;
            else $etec['senha_portaria'] = $hash_novo;
        }
    } catch (PDOException $e) {
        $mensagens[] = ['tipo' => 'danger', 'card' => 'seguranca',
            'texto' => 'Erro no banco: ' . htmlspecialchars($e->getMessage())];
    }
}

// Função auxiliar para exibir alertas por card
function alertasDoCard(array $mensagens, string $card): string {
    $html = '';
    foreach ($mensagens as $m) {
        if ($m['card'] === $card) {
            $html .= '<div class="alert alert-' . $m['tipo'] . ' alert-dismissible fade show py-2 small mt-3" role="alert">'
                . $m['texto']
                . '<button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>'
                . '</div>';
        }
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAV Admin — Configurações do Sistema</title>
    <meta name="description" content="Painel de configurações seguro da unidade ETEC no sistema RAV.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ---- Cards de Configuração ---- */
        .cfg-card {
            border: 1px solid #eaeaea;
            border-radius: 14px;
            transition: box-shadow 0.25s ease, transform 0.25s ease, border-color 0.25s ease;
            height: 100%;
            background: #fff;
        }
        .cfg-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.10) !important;
            transform: translateY(-3px);
            border-color: #d0d0d0;
        }
        .cfg-card .card-header-custom {
            border-radius: 13px 13px 0 0;
            padding: 1.1rem 1.5rem 0.8rem;
            border-bottom: 1px solid #f0f0f0;
        }

        /* ---- Ícone de secção ---- */
        .cfg-icon {
            width: 46px; height: 46px;
            border-radius: 11px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }
        .cfg-icon-blue   { background: rgba(13,110,253,.1);  color: #0d6efd; }
        .cfg-icon-green  { background: rgba(25,135,84,.1);   color: #198754; }
        .cfg-icon-amber  { background: rgba(255,193,7,.1);   color: #e6a817; }
        .cfg-icon-red    { background: rgba(184,0,5,.1);     color: var(--cps-red, #b80005); }
        .cfg-icon-teal   { background: rgba(18,113,135,.1);  color: #127187; }

        /* ---- Switch customizado ---- */
        .form-switch .form-check-input {
            width: 2.8em; height: 1.4em; cursor: pointer;
        }
        .form-switch .form-check-input:checked { background-color: var(--cps-red, #b80005); border-color: var(--cps-red, #b80005); }

        /* ---- Toggle senha ---- */
        .input-group .btn-toggle-pass {
            border: 1px solid #ced4da;
            background: #f8f9fa;
            color: #6c757d;
        }
        .input-group .btn-toggle-pass:hover { background: #e9ecef; }

        /* ---- Linha de preferência (switch row) ---- */
        .pref-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            background: #f8f9fa;
            margin-bottom: 0.6rem;
            transition: background 0.2s;
        }
        .pref-row:hover { background: #f0f0f0; }
        .dark-mode .pref-row { background: #2a2a2a; }
        .dark-mode .pref-row:hover { background: #333; }

        /* ---- Badge de nível de acesso ---- */
        .badge-admin { background: rgba(184,0,5,.12); color: var(--cps-red, #b80005); border: 1px solid rgba(184,0,5,.25); }
        .badge-portaria { background: rgba(18,113,135,.12); color: #127187; border: 1px solid rgba(18,113,135,.25); }

        /* ---- Força da senha (barra) ---- */
        .pwd-strength-bar { height: 5px; border-radius: 4px; transition: width 0.4s, background 0.4s; }

        /* ---- Dark Mode overrides ---- */
        .dark-mode .cfg-card { background: #1e1e1e !important; border-color: #333 !important; }
        .dark-mode .cfg-card:hover { border-color: #555 !important; }
        .dark-mode .cfg-card .card-header-custom { border-color: #333; }

        @media (max-width: 767px) {
            .cfg-card { margin-bottom: 0; }
        }
    </style>
</head>
<body class="light-mode section-bg-gray d-flex flex-column min-vh-100">

    <!-- Barra de Acessibilidade -->
    <div class="accessibility-bar py-1 d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="fw-bold text-white small">RAV — PROJETO INSTITUCIONAL</span>
            <div class="accessibility-tools gap-3 d-flex align-items-center">
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-decrease-font" title="Diminuir fonte">A-</button>
                <button type="button" class="btn btn-sm text-white p-0 fw-bold" id="btn-increase-font" title="Aumentar fonte">A+</button>
                <button type="button" class="btn btn-sm text-white p-0 ms-2" id="btn-toggle-contrast" title="Alternar Dark Mode">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="main-header bg-white shadow-sm sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" type="button"
                        data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <a href="painel-admin.php" class="text-decoration-none d-flex align-items-center">
                    <h1 class="logo-text m-0 fw-bold d-flex align-items-center">
                        <span class="text-cps-red fs-2 me-1">RAV</span>
                        <span class="text-dark fs-4 mt-1">ETEC</span>
                        <span class="badge bg-secondary text-white font-monospace ms-2 mt-2" style="font-size:.70rem;">Config.</span>
                    </h1>
                </a>
            </div>
            <!-- Info da Unidade Logada -->
            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-flex flex-column align-items-end">
                    <span class="fw-bold small text-dark"><?= htmlspecialchars($etec['empresaNome']) ?></span>
                    <span class="badge badge-admin rounded-pill px-2 small">
                        <i class="bi bi-shield-check me-1"></i> Administrador
                    </span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light rounded-pill border d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-badge-fill fs-5 text-cps-red"></i>
                        <span class="d-none d-md-inline fw-medium text-dark small">
                            <?= htmlspecialchars($etec['nome_completo']) ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><h6 class="dropdown-header"><?= htmlspecialchars($etec['empresaNome']) ?></h6></li>
                        <li><a class="dropdown-item active" href="configuracoes.php">
                            <i class="bi bi-gear me-2"></i>Configurações</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger fw-bold" href="sair.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg nav-cps p-0" style="z-index:1010;">
        <div class="container flex-column flex-lg-row">
            <div class="collapse navbar-collapse w-100" id="adminNavbar">
                <ul class="navbar-nav w-100 d-flex flex-lg-row gap-lg-1 py-1 py-lg-0 ms-lg-n3">
                    <li class="nav-item"><a href="painel-admin.php"    class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-house-door me-1"></i>Painel</a></li>
                    <li class="nav-item"><a href="acessos.php"         class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-list-check me-1"></i>Acessos</a></li>
                    <li class="nav-item"><a href="estacionamento.php"  class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-p-circle me-1"></i>Estacionamento</a></li>
                    <li class="nav-item"><a href="gerenciar_cadastros.php" class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-people-fill me-1"></i>Cadastros</a></li>
                    <li class="nav-item"><a href="relatorios.php"      class="nav-link text-white fw-medium px-4 py-3"><i class="bi bi-bar-chart-line me-1"></i>Relatórios</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN -->
    <main class="container py-4 flex-grow-1">

        <!-- Cabeçalho da Página -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-sliders me-2 text-cps-red"></i>Configurações do Sistema
                </h2>
                <p class="text-secondary small mb-0">
                    Gerencie as preferências da unidade
                    <strong><?= htmlspecialchars($etec['empresaNome']) ?></strong>.
                    Alterações requerem confirmação de senha.
                </p>
            </div>
            <a href="painel-admin.php" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Voltar ao Painel
            </a>
        </div>

        <!-- ================================================================
             LINHA 1: Minha Conta | Preferências Visuais
        ================================================================= -->
        <div class="row g-4 mb-4">

            <!-- ---- Card: Minha Conta ---- -->
            <div class="col-12 col-lg-7">
                <div class="cfg-card card border-0 shadow-sm">
                    <div class="card-header-custom bg-white d-flex align-items-center gap-3">
                        <div class="cfg-icon cfg-icon-blue"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0">Minha Conta</h5>
                            <small class="text-secondary">Informações do gestor e dados institucionais da unidade.</small>
                        </div>
                    </div>
                    <div class="card-body p-4">

                        <?= alertasDoCard($mensagens, 'conta') ?>

                        <form method="POST" action="configuracoes.php" novalidate id="formConta">
                            <input type="hidden" name="acao" value="atualizar_conta">

                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <label for="nome_completo" class="form-label small fw-bold">
                                        Nome do Gestor <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nome_completo" name="nome_completo"
                                           value="<?= htmlspecialchars($etec['nome_completo']) ?>" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label for="email_conta" class="form-label small fw-bold">
                                        E-mail Administrativo <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email_conta" name="email"
                                           value="<?= htmlspecialchars($etec['email']) ?>" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label for="telefone_conta" class="form-label small fw-bold">Telefone Institucional</label>
                                    <input type="tel" class="form-control" id="telefone_conta" name="telefone"
                                           data-mask="tel"
                                           value="<?= htmlspecialchars($etec['telefone'] ?? '') ?>">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label small fw-bold text-secondary">Código de Acesso (Login)</label>
                                    <input type="text" class="form-control bg-light text-secondary"
                                           value="<?= htmlspecialchars($etec['codigo_identificador']) ?>"
                                           readonly disabled>
                                    <div class="form-text">O código identificador não pode ser alterado aqui.</div>
                                </div>

                                <!-- Confirmação de senha obrigatória -->
                                <div class="col-12">
                                    <hr class="my-2">
                                    <label for="conf_senha_conta" class="form-label small fw-bold text-danger">
                                        <i class="bi bi-lock-fill me-1"></i>
                                        Confirme sua Senha Administrativa Atual <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="conf_senha_conta"
                                               name="confirmacao_senha_admin_conta"
                                               placeholder="••••••••" required autocomplete="current-password">
                                        <button class="btn btn-toggle-pass" type="button"
                                                onclick="toggleSenha('conf_senha_conta', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Necessário para confirmar a identidade antes de salvar qualquer alteração de perfil.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill">
                                    <i class="bi bi-save me-2"></i>Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ---- Card: Preferências Visuais ---- -->
            <div class="col-12 col-lg-5">
                <div class="cfg-card card border-0 shadow-sm">
                    <div class="card-header-custom bg-white d-flex align-items-center gap-3">
                        <div class="cfg-icon cfg-icon-green"><i class="bi bi-palette-fill"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0">Preferências Visuais</h5>
                            <small class="text-secondary">Personalize a interface para maior conforto de uso.</small>
                        </div>
                    </div>
                    <div class="card-body p-4">

                        <!-- Modo Escuro -->
                        <div class="pref-row">
                            <div>
                                <div class="fw-semibold small">
                                    <i class="bi bi-moon-stars me-2 text-secondary"></i>Modo Escuro (Tema)
                                </div>
                                <div class="text-secondary" style="font-size:.78rem;">Ativa interface de alto contraste.</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="themeSwitchConfig" title="Ativar modo escuro">
                                <label class="form-check-label visually-hidden" for="themeSwitchConfig">Modo Escuro</label>
                            </div>
                        </div>

                        <!-- Animações -->
                        <div class="pref-row">
                            <div>
                                <div class="fw-semibold small">
                                    <i class="bi bi-lightning-fill me-2 text-secondary"></i>Animações da Interface
                                </div>
                                <div class="text-secondary" style="font-size:.78rem;">Suaviza transições e microinterações.</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="animSwitch" checked title="Animações ativas">
                                <label class="form-check-label visually-hidden" for="animSwitch">Animações</label>
                            </div>
                        </div>

                        <!-- Tamanho de Fonte -->
                        <div class="pref-row">
                            <div>
                                <div class="fw-semibold small">
                                    <i class="bi bi-fonts me-2 text-secondary"></i>Escala de Fonte
                                </div>
                                <div class="text-secondary" style="font-size:.78rem;">Ajuste o tamanho do texto na interface.</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 fw-bold"
                                        id="btn-decrease-font-cfg" title="Diminuir fonte">A-</button>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 fw-bold"
                                        id="btn-increase-font-cfg" title="Aumentar fonte">A+</button>
                            </div>
                        </div>

                        <div class="mt-3 p-3 rounded-3" style="background:rgba(18,113,135,.06); border:1px solid rgba(18,113,135,.15);">
                            <small class="text-secondary">
                                <i class="bi bi-info-circle me-1 text-primary"></i>
                                Preferências visuais são salvas localmente no navegador (<code>localStorage</code>)
                                e não requerem autenticação.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================
             LINHA 2: Alertas e Notificações | Segurança
        ================================================================= -->
        <div class="row g-4">

            <!-- ---- Card: Alertas e Notificações ---- -->
            <div class="col-12 col-lg-5">
                <div class="cfg-card card border-0 shadow-sm">
                    <div class="card-header-custom bg-white d-flex align-items-center gap-3">
                        <div class="cfg-icon cfg-icon-amber"><i class="bi bi-bell-fill"></i></div>
                        <div>
                            <h5 class="fw-bold mb-0">Alertas e Notificações</h5>
                            <small class="text-secondary">Configure como o sistema alerta sobre atividades na portaria.</small>
                        </div>
                    </div>
                    <div class="card-body p-4">

                        <!-- Switch: Alerta de capacidade -->
                        <div class="pref-row">
                            <div>
                                <div class="fw-semibold small">
                                    <i class="bi bi-p-circle me-2 text-warning"></i>Alerta de Ocupação Alta
                                </div>
                                <div class="text-secondary" style="font-size:.78rem;">Avisar quando pátio atingir 90% da capacidade.</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="notif_capacidade" checked>
                                <label class="form-check-label visually-hidden" for="notif_capacidade">Alerta de capacidade</label>
                            </div>
                        </div>

                        <!-- Switch: Som de entrada -->
                        <div class="pref-row">
                            <div>
                                <div class="fw-semibold small">
                                    <i class="bi bi-volume-up me-2 text-warning"></i>Som ao Registrar Entrada
                                </div>
                                <div class="text-secondary" style="font-size:.78rem;">Emite um bipe de confirmação ao registrar acesso.</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="notif_som" checked>
                                <label class="form-check-label visually-hidden" for="notif_som">Som de entrada</label>
                            </div>
                        </div>

                        <!-- Switch: Notificações do navegador -->
                        <div class="pref-row">
                            <div>
                                <div class="fw-semibold small">
                                    <i class="bi bi-browser-chrome me-2 text-warning"></i>Notificações do Navegador
                                </div>
                                <div class="text-secondary" style="font-size:.78rem;">Push notifications mesmo com a aba em segundo plano.</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="notif_browser">
                                <label class="form-check-label visually-hidden" for="notif_browser">Notificação do navegador</label>
                            </div>
                        </div>

                        <!-- Switch: Atualização automática -->
                        <div class="pref-row">
                            <div>
                                <div class="fw-semibold small">
                                    <i class="bi bi-arrow-repeat me-2 text-warning"></i>Atualização Automática do Pátio
                                </div>
                                <div class="text-secondary" style="font-size:.78rem;">Recarrega o pátio a cada 10 segundos automaticamente.</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="notif_autorefresh" checked>
                                <label class="form-check-label visually-hidden" for="notif_autorefresh">Auto-refresh</label>
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-outline-warning fw-bold rounded-pill px-4"
                                    onclick="salvarPreferencias()">
                                <i class="bi bi-save me-2"></i>Salvar Preferências
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---- Card: Segurança (Alterar Senhas) ---- -->
            <div class="col-12 col-lg-7">
                <div class="cfg-card card border-0 shadow-sm" style="border-left: 4px solid var(--cps-red, #b80005) !important;">
                    <div class="card-header-custom bg-white d-flex align-items-center gap-3">
                        <div class="cfg-icon cfg-icon-red"><i class="bi bi-shield-lock-fill"></i></div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-0 text-danger">Segurança — Alteração de Senhas</h5>
                            <small class="text-secondary">Gerencie as senhas de acesso Admin e Portaria da unidade.</small>
                        </div>
                        <span class="badge rounded-pill" style="background:rgba(184,0,5,.1);color:var(--cps-red,#b80005);border:1px solid rgba(184,0,5,.2);">
                            <i class="bi bi-shield-check me-1"></i>Verificação dupla
                        </span>
                    </div>
                    <div class="card-body p-4">

                        <?= alertasDoCard($mensagens, 'seguranca') ?>

                        <form method="POST" action="configuracoes.php" novalidate id="formSeguranca">
                            <input type="hidden" name="acao" value="alterar_senha">

                            <div class="row g-3">

                                <!-- Qual senha alterar -->
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Qual senha deseja alterar?</label>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="qual_senha"
                                                   id="qual_admin" value="admin" checked>
                                            <label class="form-check-label small fw-semibold" for="qual_admin">
                                                <i class="bi bi-shield-fill me-1 text-danger"></i>Senha Administrativa
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="qual_senha"
                                                   id="qual_portaria" value="portaria">
                                            <label class="form-check-label small fw-semibold" for="qual_portaria">
                                                <i class="bi bi-door-open me-1" style="color:#127187;"></i>Senha da Portaria
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <strong>Admin:</strong> acesso ao painel de gestão.
                                        <strong>Portaria:</strong> acesso ao controle de entrada/saída.
                                    </div>
                                </div>

                                <!-- Confirmação da senha admin atual -->
                                <div class="col-12 col-sm-6">
                                    <label for="conf_senha_seg" class="form-label small fw-bold text-danger">
                                        <i class="bi bi-lock-fill me-1"></i>
                                        Confirme a Senha Administrativa Atual <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="conf_senha_seg"
                                               name="confirmacao_senha_admin_seg"
                                               placeholder="••••••••" required autocomplete="current-password">
                                        <button class="btn btn-toggle-pass" type="button"
                                                onclick="toggleSenha('conf_senha_seg', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        Sempre exigida para qualquer alteração de senha, por segurança.
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6"></div><!-- espaçador -->

                                <!-- Nova senha -->
                                <div class="col-12 col-sm-6">
                                    <label for="nova_senha" class="form-label small fw-bold">
                                        Nova Senha <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="nova_senha" name="nova_senha"
                                               placeholder="Mínimo 6 caracteres" required minlength="6"
                                               oninput="medirForca(this.value)"
                                               autocomplete="new-password">
                                        <button class="btn btn-toggle-pass" type="button"
                                                onclick="toggleSenha('nova_senha', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <!-- Barra de força da senha -->
                                    <div class="mt-1">
                                        <div class="bg-light rounded" style="height:5px;">
                                            <div id="forca-bar" class="pwd-strength-bar" style="width:0%;"></div>
                                        </div>
                                        <small id="forca-label" class="text-secondary" style="font-size:.72rem;"></small>
                                    </div>
                                </div>

                                <!-- Confirmar nova senha -->
                                <div class="col-12 col-sm-6">
                                    <label for="confirmar_nova_senha" class="form-label small fw-bold">
                                        Confirmar Nova Senha <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirmar_nova_senha"
                                               name="confirmar_nova_senha"
                                               placeholder="Repita a nova senha" required
                                               oninput="verificarMatch()"
                                               autocomplete="new-password">
                                        <button class="btn btn-toggle-pass" type="button"
                                                onclick="toggleSenha('confirmar_nova_senha', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div id="match-feedback" class="form-text"></div>
                                </div>

                            </div><!-- /row -->

                            <!-- Aviso de impacto -->
                            <div class="alert alert-warning d-flex align-items-start gap-2 py-2 mt-3 small" role="alert">
                                <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                                <div>
                                    Ao alterar a <strong>Senha Administrativa</strong>, todos os acessos admin ativos
                                    serão encerrados e será necessário fazer novo login.
                                    A <strong>Senha da Portaria</strong> afeta apenas o acesso operacional da guarita.
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn fw-bold px-4 rounded-pill"
                                        id="btnAlterarSenha"
                                        style="background:var(--cps-red,#b80005);color:#fff;border:none;">
                                    <i class="bi bi-shield-lock me-2"></i>Confirmar Alteração de Senha
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /row g-4 linha 2 -->

    </main>

    <!-- Footer -->
    <footer class="footer-cps bg-dark text-white text-center py-3 mt-auto">
        <small class="text-white-50">
            © <?= date('Y') ?> RAV ETEC — Sistema de Registro de Acesso de Veículos.
            Sessão: <strong><?= htmlspecialchars($etec['codigo_identificador']) ?></strong> (Admin)
        </small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
    <script src="script/script.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const body          = document.body;
        const btnContrast   = document.getElementById('btn-toggle-contrast');
        const themeSwitch   = document.getElementById('themeSwitchConfig');
        const btnIncDt      = document.getElementById('btn-increase-font');
        const btnDecDt      = document.getElementById('btn-decrease-font');
        const btnIncCfg     = document.getElementById('btn-increase-font-cfg');
        const btnDecCfg     = document.getElementById('btn-decrease-font-cfg');

        // ---- Tema ----
        const isDark = localStorage.getItem('theme') === 'dark';
        if (isDark) {
            body.classList.replace('light-mode', 'dark-mode');
            if (btnContrast) btnContrast.innerHTML = '<i class="bi bi-sun-fill"></i>';
            if (themeSwitch) themeSwitch.checked   = true;
        }

        function toggleTheme() {
            const going = body.classList.contains('dark-mode') ? 'light' : 'dark';
            body.classList.replace(going === 'dark' ? 'light-mode' : 'dark-mode',
                                   going === 'dark' ? 'dark-mode'  : 'light-mode');
            localStorage.setItem('theme', going);
            if (btnContrast) btnContrast.innerHTML = going === 'dark'
                ? '<i class="bi bi-sun-fill"></i>' : '<i class="bi bi-moon-stars-fill"></i>';
            if (themeSwitch) themeSwitch.checked = (going === 'dark');
        }

        if (btnContrast) btnContrast.addEventListener('click', toggleTheme);
        if (themeSwitch) themeSwitch.addEventListener('change', toggleTheme);

        // ---- Fonte ----
        const maxScale = 1.3, minScale = 0.8;
        let scale = parseFloat(localStorage.getItem('fontScale')) || 1.0;
        document.documentElement.style.setProperty('--font-scale', scale);

        function changeFont(dir) {
            scale = dir > 0
                ? Math.min(maxScale, parseFloat((scale + 0.1).toFixed(1)))
                : Math.max(minScale, parseFloat((scale - 0.1).toFixed(1)));
            document.documentElement.style.setProperty('--font-scale', scale);
            localStorage.setItem('fontScale', scale);
        }

        [btnIncDt, btnIncCfg].forEach(b => b && b.addEventListener('click', () => changeFont(1)));
        [btnDecDt, btnDecCfg].forEach(b => b && b.addEventListener('click', () => changeFont(-1)));

        // ---- Máscara de telefone ----
        if (typeof IMask !== 'undefined') {
            const tel = document.getElementById('telefone_conta');
            if (tel) IMask(tel, { mask: [{ mask: '(00) 0000-0000' }, { mask: '(00) 00000-0000' }] });
        }
    });

    // ---- Toggle visibilidade de senha ----
    function toggleSenha(id, btn) {
        const input = document.getElementById(id);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    // ---- Medir força da senha ----
    function medirForca(senha) {
        const bar   = document.getElementById('forca-bar');
        const label = document.getElementById('forca-label');
        let score = 0;
        if (senha.length >= 6)  score++;
        if (senha.length >= 10) score++;
        if (/[A-Z]/.test(senha)) score++;
        if (/[0-9]/.test(senha)) score++;
        if (/[^A-Za-z0-9]/.test(senha)) score++;

        const niveis = [
            { pct: 0,   cor: '#dc3545', txt: '' },
            { pct: 20,  cor: '#dc3545', txt: 'Muito fraca' },
            { pct: 40,  cor: '#fd7e14', txt: 'Fraca' },
            { pct: 60,  cor: '#ffc107', txt: 'Moderada' },
            { pct: 80,  cor: '#20c997', txt: 'Forte' },
            { pct: 100, cor: '#198754', txt: 'Muito forte' },
        ];
        const n = niveis[score];
        bar.style.width      = n.pct + '%';
        bar.style.background = n.cor;
        label.textContent    = n.txt;
        label.style.color    = n.cor;
    }

    // ---- Verificar match de senha ----
    function verificarMatch() {
        const nova     = document.getElementById('nova_senha').value;
        const confirm  = document.getElementById('confirmar_nova_senha').value;
        const fb       = document.getElementById('match-feedback');
        const btn      = document.getElementById('btnAlterarSenha');

        if (confirm.length === 0) { fb.textContent = ''; return; }

        if (nova === confirm) {
            fb.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i><span class="text-success">Senhas coincidem.</span>';
            btn.disabled = false;
        } else {
            fb.innerHTML = '<i class="bi bi-x-circle-fill text-danger me-1"></i><span class="text-danger">As senhas não coincidem.</span>';
            btn.disabled = true;
        }
    }

    // ---- Salvar preferências de notificação (localStorage) ----
    function salvarPreferencias() {
        const prefs = {
            notif_capacidade:  document.getElementById('notif_capacidade').checked,
            notif_som:         document.getElementById('notif_som').checked,
            notif_browser:     document.getElementById('notif_browser').checked,
            notif_autorefresh: document.getElementById('notif_autorefresh').checked,
        };
        localStorage.setItem('rav_prefs', JSON.stringify(prefs));

        // Toast de confirmação
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 m-4 alert alert-success d-flex align-items-center gap-2 shadow-lg';
        toast.style.cssText = 'z-index:9999;border-radius:12px;animation:fadeSlideDown .3s ease;min-width:260px;';
        toast.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-5"></i> Preferências salvas com sucesso!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3200);
    }

    // Restaurar preferências ao carregar
    (function restaurarPrefs() {
        try {
            const p = JSON.parse(localStorage.getItem('rav_prefs') || '{}');
            ['notif_capacidade','notif_som','notif_browser','notif_autorefresh'].forEach(id => {
                const el = document.getElementById(id);
                if (el && p[id] !== undefined) el.checked = p[id];
            });
        } catch(e) {}
    })();
    </script>
</body>
</html>
