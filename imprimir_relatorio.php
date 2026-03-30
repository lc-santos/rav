<?php
require_once 'conn.php';

// Verificação Básica
if (empty($_GET['data_inicio']) || empty($_GET['data_fim'])) {
    die("<h3>Erro crítico:</h3> <p>O período de Data Inicial e Data Final é obrigatório para a geração da ata consolidada.</p><a href='relatorios.php'>Voltar</a>");
}

$dataInicio = $_GET['data_inicio'];
$dataFim = $_GET['data_fim'];

// Buscando todos os registros do período de forma contínua sem limite de paginação (Com separação de eventos via SQL)
$whereConditions = [
    "DATE(historico.data_evento) >= :data_inicio",
    "DATE(historico.data_evento) <= :data_fim"
];
$params = [
    ':data_inicio' => $dataInicio,
    ':data_fim' => $dataFim
];

$whereSQL = "WHERE " . implode(" AND ", $whereConditions);

$sqlAta = "SELECT historico.* 
           FROM (
               SELECT r.id, r.nome_condutor, v.placa, v.tipo_veiculo, v.modelo, 
                      'Entrada' as tipo_movimento, r.data_hora_entrada as data_evento 
               FROM registros_acesso r 
               LEFT JOIN veiculos v ON r.id_veiculo = v.id 

               UNION ALL 

               SELECT r.id, r.nome_condutor, v.placa, v.tipo_veiculo, v.modelo, 
                      'Saída' as tipo_movimento, r.data_hora_saida as data_evento 
               FROM registros_acesso r 
               LEFT JOIN veiculos v ON r.id_veiculo = v.id 
               WHERE r.data_hora_saida IS NOT NULL
           ) AS historico 
           $whereSQL 
           ORDER BY historico.data_evento ASC";
           
$stmtAta = $pdo->prepare($sqlAta);
$stmtAta->execute($params);
$registros = $stmtAta->fetchAll(PDO::FETCH_ASSOC);

// Data Formatação para o Cabeçalho
$dInicioBR = date('d/m/Y', strtotime($dataInicio));
$dFimBR = date('d/m/Y', strtotime($dataFim));
$dataEmissao = date('d/m/Y H:i:s');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Portaria RAV - <?= $dInicioBR ?> até <?= $dFimBR ?></title>
    <!-- Incluindo o CSS via CDN pois a página não possui menu nem nada -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* Estilos base da folha e layout de tela cheia */
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .paper-container {
            background: #ffffff;
            width: 100%;
            max-width: 1000px; /* Simulando a largura em desktop normal */
            margin: 0 auto;
            min-height: 100vh;
            padding: 30px 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Estilização de Cabeçalho Corporativo */
        .doc-header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: end;
        }

        .cps-logo {
            font-size: 1.5rem;
            font-weight: 900;
            color: #b30000;
            margin: 0;
            line-height: 1;
        }

        .cps-subtitle {
            font-size: 0.9rem;
            color: #333;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .report-meta {
            text-align: right;
            font-size: 0.85rem;
            color: #555;
        }

        .table th {
            background-color: #f1f1f1 !important;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            border-bottom: 2px solid #000 !important;
        }

        .table td {
            vertical-align: middle;
            border-bottom: 1px solid #ddd;
        }

        /* --- MAGIC HAPPENS HERE: @media print --- */
        /* Isso é acionado pelo navegador quando 'Salvar como PDF' ou 'Imprimir' */
        @media print {
            @page {
                size: A4 portrait;
                margin: 1cm;
            }
            body {
                background: #FFF;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .paper-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100%;
                width: 100%;
            }
            .table-striped>tbody>tr:nth-of-type(odd)>* {
                box-shadow: inset 0 0 0 9999px rgba(0, 0, 0, 0.04); /* Força fundo zebra na impressão */
            }
            .table th {
                background-color: #e9ecef !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact; 
            }
        }
    </style>
</head>
<body>

    <div class="paper-container">
        
        <!-- Cabeçalho Institucional -->
        <div class="doc-header">
            <div>
                <h1 class="cps-logo">RAV <span class="text-dark">ETEC</span></h1>
                <p class="cps-subtitle">Sistema de Registro de Acessos e Veículos - <strong class="text-danger">Centro Paula Souza</strong></p>
                <div class="mt-3">
                    <h4 class="mb-0 fw-bold">Ata Consolidada de Movimentação</h4>
                    <span class="text-secondary fw-bold">Período Referência: <?= $dInicioBR ?> a <?= $dFimBR ?></span>
                </div>
            </div>
            <div class="report-meta">
                <p class="mb-1"><strong>Documento ID:</strong> <?= time() ?></p>
                <p class="mb-1"><strong>Emissão:</strong> <?= $dataEmissao ?></p>
                <p class="mb-0"><strong>Total de Registros Constados:</strong> <?= count($registros) ?></p>
            </div>
        </div>

        <!-- Tabela Contínua -->
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th width="5%">Nº</th>
                    <th width="15%">Data / Hora</th>
                    <th width="10%">Status</th>
                    <th width="35%">Condutor / Beneficiário</th>
                    <th width="10%">Placa</th>
                    <th width="25%">Veículo / Detalhes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($registros) == 0): ?>
                    <tr><td colspan="6" class="text-center py-4">Nenhum acesso registrado constado neste exato período.</td></tr>
                <?php else: ?>
                    <?php 
                    $count = 1;
                    foreach ($registros as $reg): 
                    ?>
                        <tr>
                            <td class="text-secondary"><?= $count++ ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($reg['data_evento'])) ?></td>
                            <td>
                                <!-- Mapeamento de texto bruto para o PDF, com badge suave dependendo de suporte print css -->
                                <strong class="<?= $reg['tipo_movimento'] === 'Entrada' ? 'text-success' : 'text-secondary' ?>">
                                    <?= $reg['tipo_movimento'] === 'Entrada' ? 'Acessou' : 'Saiu' ?>
                                </strong>
                            </td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($reg['nome_condutor']) ?></td>
                            <td class="fw-bold" style="color: #660000;"><?= htmlspecialchars($reg['placa'] ?? 'PEDESTRE') ?></td>
                            <td class="text-secondary small">
                                <?= !empty($reg['tipo_veiculo']) ? htmlspecialchars($reg['tipo_veiculo']) . ' - ' . htmlspecialchars($reg['modelo']) : 'Acesso Não Veicular' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Rodapé do Conhecimento Opcional -->
        <div class="mt-4 pt-3 border-top text-center text-secondary" style="font-size: 10px;">
            <p class="mb-0">Este arquivo é de gerência interna (uso administrativo estrito). Criado eletronicamente pelo módulo RAV-Estacionamento.</p>
        </div>

    </div>

    <!-- Script Automático para Engatilhar PDF/Impressão -->
    <script>
        window.onload = function() {
            // Aguarda meio segundo para garantir as renderizações de fonte do bootstrap CDN e invoca
            setTimeout(() => {
                window.print();
            }, 600);
        }
    </script>
</body>
</html>
