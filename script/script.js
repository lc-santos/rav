// 1. FUNÇÃO GLOBAL DE SELEÇÃO (Fora do DOMContentLoaded para ser acessível pelos resultados da busca)
function selecionarVeiculo(nome, placa, contato, modelo = '', cor = '') {
    // Preenchimento dos dados básicos
    document.getElementsByName('nome_condutor')[0].value = nome;
    document.getElementById('placa').value = placa;
    document.getElementById('inputContato').value = contato;

    // Garante que a seção oculta (modelo/cor) apareça
    const secao = document.getElementById('secaoDetalhesVeiculo');
    if (secao) secao.classList.remove('d-none');

    // Preenchimento dos detalhes do veículo
    document.getElementsByName('modelo_veiculo')[0].value = modelo;
    document.getElementsByName('cor_veiculo')[0].value = cor;

    // Esconde as listas de resultados após a seleção
    const listaRapida = document.getElementById('lista_veiculos_encontrados');
    if (listaRapida) listaRapida.classList.add('d-none');

    const overlayGeral = document.getElementById('resultado_busca_geral');
    if (overlayGeral) overlayGeral.classList.add('d-none');
}

document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMENTOS ---
    const secaoDetalhes = document.getElementById('secaoDetalhesVeiculo');
    const labelPlaca = document.getElementById('labelPlaca');
    const inputPlaca = document.getElementById('placa');
    const formAcesso = document.querySelector('form[action="registrar_acesso.php"]');
    const campoBuscaRapida = document.getElementById('busca_rapida');
    const buscaGeral = document.querySelector('.custom-search .form-control');
    const btnAbrirModal = document.getElementById('btnAbrirModal');

    // 1. CONTROLE DE EXIBIÇÃO DINÂMICA (Radio Buttons)
    const radiosVeiculo = document.querySelectorAll('input[name="tipo_veiculo"]');
    const inputPlacaEl  = document.getElementById('placa');
    const labelPlacaEl  = document.getElementById('labelPlacaRequired');
    const wrapperPlacaEl = document.getElementById('wrapperPlaca');
    const balaoObsHint  = document.getElementById('balaoObsHint');

    function aplicarEstadoOutros(isOutros) {
        if (!inputPlacaEl) return;

        if (isOutros) {
            // Placa: remover required e indicar visualmente como opcional
            inputPlacaEl.removeAttribute('required');
            if (wrapperPlacaEl) wrapperPlacaEl.classList.add('placa-opcional');
            if (labelPlacaEl)  {
                labelPlacaEl.classList.remove('label-required');
                labelPlacaEl.classList.add('label-optional');
            }

            // Mostrar balão sutil (re-trigger animation)
            if (balaoObsHint) {
                balaoObsHint.classList.remove('d-none');
                // Restart animation by cloning
                balaoObsHint.style.animation = 'none';
                balaoObsHint.offsetHeight; // reflow
                balaoObsHint.style.animation = '';
            }

            // Abrir collapse de observação automaticamente
            const collapseObs = document.getElementById('collapseObs');
            if (collapseObs && typeof bootstrap !== 'undefined') {
                const bsCollapse = new bootstrap.Collapse(collapseObs, {toggle: false});
                bsCollapse.show();
            } else if (collapseObs) {
                collapseObs.classList.add('show');
            }
        } else {
            // Placa: restaurar required
            inputPlacaEl.setAttribute('required', '');
            if (wrapperPlacaEl) wrapperPlacaEl.classList.remove('placa-opcional');
            if (labelPlacaEl) {
                labelPlacaEl.classList.add('label-required');
                labelPlacaEl.classList.remove('label-optional');
            }

            // Esconder balão
            if (balaoObsHint) balaoObsHint.classList.add('d-none');
        }
    }

    if (radiosVeiculo.length > 0) {
        radiosVeiculo.forEach(radio => {
            radio.addEventListener('change', function () {
                aplicarEstadoOutros(this.value === 'Outros');
            });
        });
        // Estado inicial
        const checkedVeiculo = document.querySelector('input[name="tipo_veiculo"]:checked');
        aplicarEstadoOutros(checkedVeiculo && checkedVeiculo.value === 'Outros');
    }


    // 1.5 CONTROLE DE EXIBIÇÃO DINÂMICA (Tipos de Acesso)
    const radiosAcesso = document.querySelectorAll('input[name="tipo_acesso"]');
    const camposAlunoDinamico = document.getElementById('camposAlunoDinamico');
    const camposEquipeDinamico = document.getElementById('camposEquipeDinamico');

    if (radiosAcesso.length > 0) {
        // Função para atualizar visibilidade
        function atualizarCamposDinamicos(valor) {
            if (camposAlunoDinamico) camposAlunoDinamico.style.display = (valor === 'Aluno') ? 'block' : 'none';
            if (camposEquipeDinamico) camposEquipeDinamico.style.display = (valor === 'Equipe') ? 'block' : 'none';
        }

        // Estado inicial
        const acessoSelecionado = document.querySelector('input[name="tipo_acesso"]:checked');
        atualizarCamposDinamicos(acessoSelecionado ? acessoSelecionado.value : '');

        radiosAcesso.forEach(radio => {
            radio.addEventListener('change', function () {
                atualizarCamposDinamicos(this.value);
            });
        });
    }

    // 2. BUSCA RÁPIDA (CPF ou ID de 7 dígitos)
    if (campoBuscaRapida) {
        campoBuscaRapida.addEventListener('input', function () {
            const valor = this.value.replace(/\D/g, '');
            const containerLista = document.getElementById('lista_veiculos_encontrados');
            const containerOpcoes = document.getElementById('container_opcoes');

            if (valor.length === 7 || valor.length === 11) {
                fetch(`buscar_condutor.php?busca=${valor}`)
                    .then(res => res.json())
                    .then(data => {
                        // Dentro do fetch de buscar_condutor.php
                        if (data.sucesso) {
                            containerLista.classList.remove('d-none');
                            containerOpcoes.innerHTML = `
                                <div class="p-3 border border-primary-subtle rounded bg-white shadow-sm mt-2">
                                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="bi bi-person-badge-fill me-2"></i>${data.nome}</h6>
                                    <fieldset class="p-0">
                                        <legend class="float-none w-auto px-1 small text-secondary fw-bold mb-2">Autenticar Entrada de:</legend>
                                        <div id="lista_veiculos_interna" class="d-flex flex-column gap-2"></div>
                                    </fieldset>
                                </div>`;

                            const listaInterna = document.getElementById('lista_veiculos_interna');
                            data.veiculos.forEach(v => {
                                listaInterna.innerHTML += `
                                    <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-light">
                                        <div>
                                            <span class="fw-bold text-dark small"><i class="bi bi-card-text me-1 text-secondary"></i>${v.placa}</span><br>
                                            <small class="text-muted" style="font-size: 0.85rem;">${v.modelo} - ${v.cor}</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-success px-3 fw-bold shadow-sm" 
                                            onclick="selecionarVeiculo('${data.nome}', '${v.placa}', '${data.contato}', '${v.modelo}', '${v.cor}')">
                                            ENTRADA <i class="bi bi-box-arrow-in-right ms-1"></i>
                                        </button>
                                    </div>`;
                            });
                        }
                    });
            } else { if (containerLista) containerLista.classList.add('d-none'); }
        });
    }

    // 3. BUSCA GERAL — Painel de Resultados Rico
    const buscaGeralInput  = document.getElementById('buscaGeral');
    const painelResultados = document.getElementById('painelResultadosBusca');
    const btnLimparBusca   = document.getElementById('btnLimparBusca');
    const buscaIconEstado  = document.getElementById('busca-icon-estado');

    let buscaTimeout = null;

    function iconeVeiculo(tipo) {
        if (!tipo) return 'bi-person-fill';
        const t = tipo.toUpperCase();
        if (t === 'MOTO') return 'bi-bicycle';
        if (t === 'CARRO') return 'bi-car-front-fill';
        return 'bi-box-seam';
    }

    function badgeStatus(status) {
        if (status === 'Dentro')
            return `<span class="busca-badge-dentro"><i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> No pátio</span>`;
        if (status === 'Cadastrado')
            return `<span class="busca-badge-cadastrado"><i class="bi bi-person-check-fill" style="font-size:0.7rem;"></i> Cadastrado</span>`;
        return `<span class="busca-badge-fora"><i class="bi bi-circle" style="font-size:0.5rem;"></i> Fora</span>`;
    }

    function renderResultados(dados) {
        painelResultados.innerHTML = '';

        if (!dados || dados.length === 0) {
            painelResultados.innerHTML = `
                <div class="busca-vazio">
                    <i class="bi bi-search fs-3 d-block mb-2 opacity-30"></i>
                    Nenhum resultado encontrado.<br>
                    <a href="#" class="text-primary fw-bold small mt-2 d-inline-block"
                       data-bs-toggle="modal" data-bs-target="#modalCadastro"
                       onclick="painelResultados.classList.add('d-none')">
                        <i class="bi bi-person-plus me-1"></i> Fazer novo cadastro
                    </a>
                </div>`;
            return;
        }

        // Cabeçalho
        painelResultados.innerHTML = `<div class="busca-header">${dados.length} resultado${dados.length > 1 ? 's' : ''} encontrado${dados.length > 1 ? 's' : ''}</div>`;

        dados.forEach(item => {
            const nomeSafe    = (item.nome_condutor || '').replace(/'/g, "\\'");
            const placaSafe   = (item.placa || '').replace(/'/g, "\\'");
            const modeloSafe  = (item.modelo || '').replace(/'/g, "\\'");
            const corSafe     = (item.cor || '').replace(/'/g, "\\'");
            const tipoVeic    = item.tipo_veiculo || '';
            const totalAcessos = parseInt(item.total_acessos) || 0;
            const status       = item.ultimo_status || 'Fora';
            const idUsuario    = item.id_usuario_lookup || '';

            const icoClass = iconeVeiculo(tipoVeic);
            const icoColor = status === 'Dentro' ? '#28a745' : (tipoVeic ? '#6c757d' : '#127187');
            const icoBg    = status === 'Dentro' ? '#eafaf1' : (tipoVeic ? '#f5f5f5' : '#e3f2fd');

            const subInfo  = item.placa && item.placa !== '---'
                ? `${item.placa}${item.modelo ? ' · ' + item.modelo : ''}${totalAcessos ? ' · ' + totalAcessos + ' acesso(s)' : ''}`
                : `Sem veículo vinculado · ${totalAcessos} acesso(s)`;

            // Botões de ação contextuais
            const btnEntrada = (status !== 'Dentro' && item.placa && item.placa !== '---')
                ? `<button class="btn btn-success btn-sm"
                       onclick="preencherFormulario('${nomeSafe}','${placaSafe}','${modeloSafe}','${corSafe}'); fecharPainelBusca();">
                       <i class="bi bi-box-arrow-in-right me-1"></i>Entrada
                   </button>` : '';

            const btnAcessos = totalAcessos > 0
                ? `<a class="btn btn-outline-secondary btn-sm"
                       href="acessos.php?nome=${encodeURIComponent(item.nome_condutor || '')}">
                       <i class="bi bi-clock-history me-1"></i>Acessos
                   </a>` : '';

            const btnCadastro = idUsuario
                ? `<a class="btn btn-outline-primary btn-sm"
                       href="gerenciar_cadastros.php?busca=${encodeURIComponent(item.nome_condutor || '')}">
                       <i class="bi bi-person-fill me-1"></i>Cadastro
                   </a>`
                : `<button class="btn btn-outline-primary btn-sm"
                       data-bs-toggle="modal" data-bs-target="#modalCadastro"
                       onclick="fecharPainelBusca()">
                       <i class="bi bi-person-plus me-1"></i>Cadastrar
                   </button>`;

            const dentroAlerta = status === 'Dentro'
                ? `<div class="text-success small fw-bold mt-1"><i class="bi bi-p-circle-fill me-1"></i>Veículo no pátio agora</div>` : '';

            painelResultados.innerHTML += `
                <div class="busca-resultado-item">
                    <div class="busca-icone" style="background:${icoBg}; color:${icoColor};">
                        <i class="bi ${icoClass}"></i>
                    </div>
                    <div class="busca-info">
                        <div class="busca-nome">${item.nome_condutor || '—'}</div>
                        <div class="busca-sub">${subInfo}</div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            ${badgeStatus(status)}
                            ${dentroAlerta}
                        </div>
                    </div>
                    <div class="busca-acoes">
                        ${btnEntrada}
                        ${btnAcessos}
                        ${btnCadastro}
                    </div>
                </div>`;
        });
    }

    function fecharPainelBusca() {
        if (painelResultados) painelResultados.classList.add('d-none');
    }

    function preencherFormulario(nome, placa, modelo, cor) {
        const campoNome   = document.querySelector('[name="nome_condutor"]');
        const campoPlaca  = document.getElementById('placa');
        const campoModelo = document.querySelector('[name="modelo_veiculo"]');
        const campoCor    = document.querySelector('[name="cor_veiculo"]');
        if (campoNome)  campoNome.value  = nome;
        if (campoPlaca) campoPlaca.value = placa;
        if (campoModelo) campoModelo.value = modelo;
        if (campoCor)   campoCor.value   = cor;
        // Scroll suave até o formulário
        const form = document.querySelector('form[action="registrar_acesso.php"]');
        if (form) form.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    if (buscaGeralInput && painelResultados) {
        buscaGeralInput.addEventListener('input', function () {
            const termo = this.value.trim();

            // Mostrar/ocultar botão limpar
            if (btnLimparBusca) btnLimparBusca.classList.toggle('d-none', termo.length === 0);

            if (termo.length < 2) {
                painelResultados.classList.add('d-none');
                if (buscaIconEstado) buscaIconEstado.className = 'bi bi-search';
                clearTimeout(buscaTimeout);
                return;
            }

            // Spinner de carregamento
            if (buscaIconEstado) buscaIconEstado.className = 'bi bi-arrow-repeat spin-animation';
            painelResultados.classList.remove('d-none');
            painelResultados.innerHTML = '<div class="busca-digitando"><i class="bi bi-hourglass-split me-2"></i>Buscando...</div>';

            clearTimeout(buscaTimeout);
            buscaTimeout = setTimeout(() => {
                fetch(`buscar_geral.php?termo=${encodeURIComponent(termo)}`)
                    .then(res => res.json())
                    .then(dados => {
                        if (buscaIconEstado) buscaIconEstado.className = 'bi bi-search';
                        renderResultados(dados);
                    })
                    .catch(() => {
                        if (buscaIconEstado) buscaIconEstado.className = 'bi bi-search';
                        painelResultados.innerHTML = '<div class="busca-vazio text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Erro ao buscar. Tente novamente.</div>';
                    });
            }, 300);
        });

        // Botão limpar
        if (btnLimparBusca) {
            btnLimparBusca.addEventListener('click', () => {
                buscaGeralInput.value = '';
                painelResultados.classList.add('d-none');
                btnLimparBusca.classList.add('d-none');
                if (buscaIconEstado) buscaIconEstado.className = 'bi bi-search';
                buscaGeralInput.focus();
            });
        }

        // Fechar ao clicar fora
        document.addEventListener('click', (e) => {
            if (!buscaGeralInput.closest('.position-relative').contains(e.target)) {
                painelResultados.classList.add('d-none');
            }
        });

        // Esc fecha
        buscaGeralInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') fecharPainelBusca();
        });
    }

    // Expor para uso global (chamado pelos botões nos resultados)
    window.fecharPainelBusca   = fecharPainelBusca;
    window.preencherFormulario = preencherFormulario;


    // 4. INTEGRAÇÃO COM MODAL (Puxar dados do formulário principal)
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener('click', () => {
            const radioChecked = document.querySelector('input[name="tipo_veiculo"]:checked');
            document.getElementById('modalNomeCondutor').value = document.getElementsByName('nome_condutor')[0].value;
            document.getElementById('modalTipoVeiculo').value = radioChecked ? radioChecked.value : 'Carro';
            document.getElementById('modalPlacaVeiculo').value = inputPlaca.value;
            // Notifica o IMask que o valor mudou
            document.getElementById('modalPlacaVeiculo').dispatchEvent(new Event('input'));
            
            document.getElementById('modalModelo').value = document.getElementsByName('modelo_veiculo')[0].value;
            document.getElementById('modalCor').value = document.getElementsByName('cor_veiculo')[0].value;

            const tipoC = document.getElementById('tipoContato').value;
            const valorC = document.getElementById('inputContato').value;
            if (tipoC === 'tel') {
                document.getElementById('modalTelefone').value = valorC;
                document.getElementById('modalEmail').value = '';
            } else {
                document.getElementById('modalEmail').value = valorC;
                document.getElementById('modalTelefone').value = '';
            }
        });
    }

    // 5. ENVIO DO REGISTRO (AJAX)
    if (formAcesso) {
        formAcesso.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('registrar_acesso.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.sucesso) {
                        Swal.fire({ title: 'Sucesso!', icon: 'success' }).then(() => window.location.reload());
                    } else {
                        Swal.fire({ title: 'Erro', text: data.erro, icon: 'error' });
                    }
                }).catch(() => Swal.fire('Erro', 'Falha na comunicação.', 'error'));
        });
    }

    // 6. NOTIFICAÇÃO DE SUCESSO NO CADASTRO
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('sucesso_cadastro')) {
        const codigo = urlParams.get('codigo');
        Swal.fire({
            title: 'Cadastro Concluído!',
            html: `O condutor foi cadastrado com sucesso.<br><b>ID de Acesso: ${codigo}</b>`,
            icon: 'success',
            confirmButtonColor: '#1DB954'
        }).then(() => {
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    }

    // 7. BUSCA DE SAÍDA EFICIENTE
    const inputTextoSaida = document.getElementById('filtroTextoSaida');
    if (inputTextoSaida) {
        inputTextoSaida.addEventListener('input', function() {
            const termo = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
            const itensSaida = document.querySelectorAll('.item-saida');

            itensSaida.forEach(item => {
                const placa = (item.getAttribute('data-placa') || "").toLowerCase().replace(/[^a-z0-9]/g, '');
                const nome = (item.getAttribute('data-nome') || "").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                
                const mostrar = placa.includes(termo) || nome.includes(termo);

                if (mostrar) {
                    item.classList.add('d-flex');
                    item.classList.remove('d-none');
                } else {
                    item.classList.remove('d-flex');
                    item.classList.add('d-none');
                }
            });
        });
    }
    // 8. INICIAR MÁSCARAS
    initMasks();

    // Re-garante máscaras ao abrir qualquer modal (Bootstrap 5)
    document.querySelectorAll('.modal').forEach(m => {
        m.addEventListener('shown.bs.modal', initMasks);
    });
});

// --- MÁSCARAS GLOBAIS (Padrão Institucional) ---
function initMasks() {
    if (typeof IMask === 'undefined') return;

    // CPF
    document.querySelectorAll('[data-mask="cpf"]').forEach(el => {
        IMask(el, { mask: '000.000.000-00' });
    });

    // CNPJ
    document.querySelectorAll('[data-mask="cnpj"]').forEach(el => {
        IMask(el, { mask: '00.000.000/0000-00' });
    });

    // Telefone (Dinâmico: Fixo/Celular)
    document.querySelectorAll('[data-mask="tel"]').forEach(el => {
        IMask(el, {
            mask: [
                { mask: '(00) 0000-0000' },
                { mask: '(00) 00000-0000' }
            ]
        });
    });

    // Placa (Suporta Mercosul e Antiga dinamicamente com Dispatch inteligente)
    document.querySelectorAll('[data-mask="placa"]').forEach(el => {
        // Se já existir uma máscara, destrói para evitar duplicidade
        if (el.iMask) el.iMask.destroy();

        el.iMask = IMask(el, {
            mask: [
                { mask: 'aaa0000' }, // 0: Antiga sem traço
                { mask: 'aaa0a00' }, // 1: Mercosul Carro
                { mask: 'aaa00a0' }, // 2: Mercosul Moto
                { mask: 'aaa-0000' } // 3: Antiga com traço
            ],
            definitions: {
                'a': /[a-zA-Z]/,
                '0': /[0-9]/
            },
            dispatch: function (appended, dynamicMasked) {
                const isHyphen = dynamicMasked.value.includes('-') || appended === '-';
                if (isHyphen) return dynamicMasked.compiledMasks[3];

                const cleanValue = (dynamicMasked.value + appended).replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                
                if (cleanValue.length >= 5) {
                    const char5 = cleanValue.charAt(4);
                    const char6 = cleanValue.length >= 6 ? cleanValue.charAt(5) : null;
                    
                    // Se o 5º char for letra -> Mercosul Carro (ABC1D23)
                    if (/[A-Z]/.test(char5)) return dynamicMasked.compiledMasks[1];
                    
                    // Se o 5º char for número e o 6º for letra -> Mercosul Moto (ABC12D3)
                    if (char6 && /[A-Z]/.test(char6)) return dynamicMasked.compiledMasks[2];

                    // Caso contrário, tenta o padrão antigo (ABC1234)
                    return dynamicMasked.compiledMasks[0];
                }
                return dynamicMasked.compiledMasks[0];
            },
            lazy: true,
            prepare: function (str) {
                return str.toUpperCase();
            },
            commit: function (value, masked) {
                el.value = value.toUpperCase();
            }
        });
    });

    // CPF ou ID (7 dígitos)
    document.querySelectorAll('[data-mask="cpf-id"]').forEach(el => {
        IMask(el, {
            mask: [
                { mask: '0000000' }, // ID
                { mask: '000.000.000-00' } // CPF
            ]
        });
    });

    // CEP
    document.querySelectorAll('[data-mask="cep"]').forEach(el => {
        IMask(el, { mask: '00000-000' });
    });
}

// Alterado para registrar a entrada IMEDIATAMENTE
// Localize esta função no seu script.js e altere a linha do tipo_acesso
function selecionarVeiculo(nome, placa, contato, modelo = '', cor = '', tipo = 'Carro') {
    const formData = new FormData();
    formData.append('nome_condutor', nome);
    formData.append('placa', placa);
    formData.append('contato_valor', contato);
    formData.append('modelo_veiculo', modelo);
    formData.append('cor_veiculo', cor);
    formData.append('tipo_veiculo', tipo);

    // MUDANÇA AQUI: Use 'Serviço' ou 'Aluno' para não dar erro de ENUM no banco
    formData.append('tipo_acesso', 'Serviço');

    fetch('registrar_acesso.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.sucesso) {
                Swal.fire({
                    title: 'Registrado!',
                    text: `Entrada de: ${placa} registrada.`,
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => window.location.reload());
            } else {
                Swal.fire({ title: 'Erro', text: data.erro, icon: 'error' });
            }
        });
}