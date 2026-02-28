/**
 * RAV - Sistema de Controle de Acesso
 * Script Principal Refatorado
 */

console.log('JS - Conectado');

document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMENTOS ---
    const tipoContato = document.getElementById('tipoContato');
    const inputContato = document.getElementById('inputContato');
    const inputPlaca = document.getElementById('placa');
    const campoBuscaRapida = document.getElementById('busca_rapida');
    const btnAbrirModal = document.querySelector('[data-bs-target="#modalCadastro"]');

    let currentMask = null;

    // --- MÁSCARAS (IMask) ---
    const atualizarCampoContato = () => {
        if (currentMask) {
            currentMask.destroy();
            currentMask = null;
        }

        if (tipoContato.value === 'tel') {
            inputContato.type = 'text';
            inputContato.placeholder = '(00) 00000-0000';
            currentMask = IMask(inputContato, {
                mask: [
                    { mask: '(00) 0000-0000' },
                    { mask: '(00) 00000-0000' }
                ]
            });
        } else {
            inputContato.type = 'email';
            inputContato.placeholder = 'exemplo@email.com';
            inputContato.value = '';
        }
    };

    // Máscara para Placa (Mercosul/Antiga)
    if (inputPlaca) {
        IMask(inputPlaca, {
            mask: [
                { mask: 'aaa-0000' }, // Padrão antigo
                { mask: 'aaa-0a00' }  // Padrão Mercosul
            ],
            prepare: str => str.toUpperCase(), // Força maiúsculas
            lazy: true, // Só mostra a máscara quando você começa a digitar
            definitions: {
                'a': /[A-Z]/,
                '0': /[0-9]/
            }
        });
    }
    // --- BUSCA RÁPIDA (ID de 7 dígitos ou CPF) ---
    // --- BUSCA RÁPIDA (ID de 7 dígitos ou CPF) ---
    if (campoBuscaRapida) {
        campoBuscaRapida.addEventListener('input', function () {
            const valor = this.value.replace(/\D/g, '');
            const containerLista = document.getElementById('lista_veiculos_encontrados');
            const containerOpcoes = document.getElementById('container_opcoes');

            if (valor.length === 7 || valor.length === 11) {
                fetch(`buscar_condutor.php?busca=${valor}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            containerLista.classList.remove('d-none');
                            containerOpcoes.innerHTML = `<h6 class="text-info mb-3"><i class="bi bi-person-check"></i> ${data.nome}</h6>`;

                            if (data.veiculos && data.veiculos.length > 0) {
                                data.veiculos.forEach(v => {
                                    containerOpcoes.innerHTML += `
                <div class="d-flex align-items-center justify-content-between bg-secondary bg-opacity-10 p-2 mb-2 rounded border border-secondary">
                    <div>
                        <span class="fw-bold text-success">${v.placa}</span><br>
                        <small class="text-secondary">${v.modelo}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-success fw-bold" 
                        onclick="selecionarVeiculo('${data.nome}', '${v.placa}', '${data.contato}')">
                        ENTRADA <i class="bi bi-box-arrow-in-right"></i>
                    </button>
                </div>`;
                                });
                            } else {
                                containerOpcoes.innerHTML += `<p class="text-warning small">Nenhum veículo vinculado a este condutor.</p>`;
                            }
                        }
                    });
            } else {
                containerLista.classList.add('d-none');
            }
        });
    }

    // Função para preencher o formulário principal e focar no botão de registro
    function confirmarEntradaRapida(nome, placa, contato) {
        document.getElementsByName('nome_condutor')[0].value = nome;
        document.getElementById('placa').value = placa;
        document.getElementById('inputContato').value = contato;

        // Esconde a lista de busca
        document.getElementById('lista_veiculos_encontrados').classList.add('d-none');
        document.getElementById('busca_rapida').value = '';

        // Rola a tela levemente para o formulário e foca no botão de registrar
        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    // --- INTEGRAÇÃO COM MODAL (Transferência de Dados) ---
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener('click', () => {
            const nomePrincipal = document.getElementsByName('nome_condutor')[0].value;
            const placaPrincipal = inputPlaca.value;

            // Preenche campos do modal (IDs do seu HTML)
            const mNomeEmpresa = document.getElementById('modalNomeEmpresa');
            const mNomeCondutor = document.getElementById('modalNomeCondutor');
            const mPlaca = document.getElementById('modalPlacaVeiculo');

            if (mNomeEmpresa) mNomeEmpresa.value = nomePrincipal;
            if (mNomeCondutor) mNomeCondutor.value = nomePrincipal;
            if (mPlaca) mPlaca.value = placaPrincipal;
        });
    }

    // --- INICIALIZAÇÃO ---
    if (tipoContato && inputContato) {
        atualizarCampoContato();
        tipoContato.addEventListener('change', atualizarCampoContato);
    }

    // --- NOTIFICAÇÕES (Alertas de Sucesso) ---
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('codigo')) {
        const codigo = urlParams.get('codigo');
        // Usando setTimeout para garantir que o layout carregou antes do alert
        setTimeout(() => {
            alert(`CADASTRO CONCLUÍDO!\nO ID de acesso permanente é: ${codigo}`);
        }, 500);
    }
});

// Adicione isso ao final do seu script.js
// Função global para preencher o formulário ao escolher um veículo da lista
// No final do script.js, fora de qualquer addEventListener
// No final do script.js, fora de qualquer addEventListener
// Adicione ou substitua no final do seu arquivo script.js
function selecionarVeiculo(nome, placa, contato) {
    // 1. Preenche os campos do formulário de Registro de Acesso
    document.getElementsByName('nome_condutor')[0].value = nome;
    document.getElementById('placa').value = placa;
    document.getElementById('inputContato').value = contato;
    
    // 2. Esconde o card de resultados e limpa a busca
    document.getElementById('lista_veiculos_encontrados').classList.add('d-none');
    document.getElementById('busca_rapida').value = '';
    
    // 3. ENVIO AUTOMÁTICO
    // Localiza o formulário de registro e envia os dados para o registrar_acesso.php
    const formulario = document.querySelector('form[action="registrar_acesso.php"]');
    if (formulario) {
        formulario.submit(); // Esta linha faz o registro acontecer no clique
    }
}