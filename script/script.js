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
    const selectVeiculo = document.getElementById('selectTipoVeiculo');
    const secaoDetalhes = document.getElementById('secaoDetalhesVeiculo');
    const labelPlaca = document.getElementById('labelPlaca');
    const inputPlaca = document.getElementById('placa');
    const formAcesso = document.querySelector('form[action="registrar_acesso.php"]');
    const campoBuscaRapida = document.getElementById('busca_rapida');
    const buscaGeral = document.querySelector('.custom-search .form-control');
    const btnAbrirModal = document.getElementById('btnAbrirModal');

    // 1. CONTROLE DE EXIBIÇÃO DINÂMICA (Outros)
    if (selectVeiculo) {
        selectVeiculo.addEventListener('change', function () {
            secaoDetalhes.classList.remove('d-none');
            labelPlaca.innerText = 'Placa (Obrigatório)';

            const elModelo = document.querySelector('input[name="modelo_veiculo"]');
            const elCor = document.querySelector('input[name="cor_veiculo"]');
            const elContato = document.getElementById('inputContato');
            const elNome = document.querySelector('input[name="nome_condutor"]');

            const colPlaca = document.getElementById('placa').closest('div');
            const contModelo = elModelo ? elModelo.closest('div') : null;
            const contCor = elCor ? elCor.closest('div') : null;
            const contContato = elContato ? elContato.closest('div') : null;
            const colNome = elNome ? elNome.closest('div') : null;
            
            const collapseObs = document.getElementById('collapseObs');

            if (this.value === 'Outros') {
                if (contModelo) contModelo.classList.add('d-none');
                if (contCor) contCor.classList.add('d-none');
                if (contContato) contContato.classList.add('d-none');
                
                if (colPlaca) {
                    colPlaca.classList.remove('col-md-4');
                    colPlaca.classList.add('col-md-12');
                }
                if (colNome) {
                    colNome.classList.remove('col-md-6');
                    colNome.classList.add('col-md-12');
                }
                
                // Abre o campo de observação automaticamente
                if (collapseObs && typeof bootstrap !== 'undefined') {
                    const bsCollapse = new bootstrap.Collapse(collapseObs, {toggle: false});
                    bsCollapse.show();
                } else if (collapseObs) {
                    collapseObs.classList.add('show');
                }
            } else {
                if (contModelo) contModelo.classList.remove('d-none');
                if (contCor) contCor.classList.remove('d-none');
                if (contContato) contContato.classList.remove('d-none');
                
                if (colPlaca) {
                    colPlaca.classList.remove('col-md-12');
                    colPlaca.classList.add('col-md-4');
                }
                if (colNome) {
                    colNome.classList.remove('col-md-12');
                    colNome.classList.add('col-md-6');
                }
            }
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

    // 3. BUSCA GERAL (Barra Superior)
    if (buscaGeral) {
        const resultOverlay = document.createElement('div');
        resultOverlay.id = 'resultado_busca_geral';
        resultOverlay.className = 'list-group position-absolute w-100 d-none shadow-lg';
        resultOverlay.style.zIndex = '9999';
        buscaGeral.parentElement.appendChild(resultOverlay);

        buscaGeral.addEventListener('input', function () {
            const termo = this.value.trim();
            if (termo.length >= 2) {
                fetch(`buscar_geral.php?termo=${termo}`)
                    .then(res => res.json())
                    .then(data => {
                        resultOverlay.innerHTML = '';
                        if (data && data.length > 0) {
                            resultOverlay.classList.remove('d-none');
                            data.forEach(item => {
                                const corS = item.status === 'Dentro' ? 'text-success' : 'text-info';
                                resultOverlay.innerHTML += `
                                <a href="#" class="list-group-item list-group-item-action bg-dark text-white border-secondary py-3" 
                                   onclick="selecionarVeiculo('${item.nome_condutor}', '${item.placa}', '', '${item.modelo}', '${item.cor}')">
                                    <div class="d-flex justify-content-between">
                                        <div><strong class="${corS}">${item.placa}</strong> - <small>${item.nome_condutor}</small></div>
                                        <span class="badge bg-secondary opacity-75">${item.status}</span>
                                    </div>
                                </a>`;
                            });
                        } else { resultOverlay.classList.add('d-none'); }
                    });
            } else { resultOverlay.classList.add('d-none'); }
        });
    }

    // 4. INTEGRAÇÃO COM MODAL (Puxar dados do formulário principal)
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener('click', () => {
            document.getElementById('modalNomeCondutor').value = document.getElementsByName('nome_condutor')[0].value;
            document.getElementById('modalTipoVeiculo').value = selectVeiculo.value || 'Carro';
            document.getElementById('modalPlacaVeiculo').value = inputPlaca.value;
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
                const placa = (item.getAttribute('data-placa') || "").toLowerCase();
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
});

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