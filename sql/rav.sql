DROP DATABASE IF EXISTS rav;

CREATE DATABASE IF NOT EXISTS rav CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE rav;

-- Desativa checagem de FK para permitir recriação sem erros de ordem
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS registros_acesso;

DROP TABLE IF EXISTS usuarios;

DROP TABLE IF EXISTS acesso_academico;

DROP TABLE IF EXISTS acessos;

DROP TABLE IF EXISTS veiculos;

DROP TABLE IF EXISTS contatos;

DROP TABLE IF EXISTS condutores;

DROP TABLE IF EXISTS funcoes;

DROP TABLE IF EXISTS periodos;

DROP TABLE IF EXISTS cursos;

DROP TABLE IF EXISTS documentos;

DROP TABLE IF EXISTS enderecos;

DROP TABLE IF EXISTS unidades;

CREATE TABLE unidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresaNome VARCHAR(100) NOT NULL,
    tipoDocumento VARCHAR(50) NOT NULL,
    documento VARCHAR(50) NOT NULL UNIQUE, -- CNPJ ou equivalente
    telefone VARCHAR(20) NULL,
    codigo_identificador VARCHAR(20) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 1.1 ENDEREÇOS
-- Relacionamento 1-para-1 com unidades.
-- ============================================================
CREATE TABLE enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_unidade INT NOT NULL UNIQUE,
    cep VARCHAR(9) NOT NULL,
    logradouro VARCHAR(150) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    complemento VARCHAR(100) NULL,
    bairro VARCHAR(80) NOT NULL,
    cidade VARCHAR(80) NOT NULL,
    uf CHAR(2) NOT NULL,
    CONSTRAINT fk_endereco_unidade FOREIGN KEY (id_unidade) REFERENCES unidades (id) ON DELETE CASCADE
);

-- ============================================================
-- 2. DOCUMENTOS
-- Cadastro unificado de documentos de condutores (CNH, RG, etc.)
-- ============================================================
CREATE TABLE documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_documento VARCHAR(20) NOT NULL,
    numero_documento VARCHAR(30) NOT NULL UNIQUE
);

-- ============================================================
-- 3. CURSOS
-- ============================================================
CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
);

-- ============================================================
-- 4. PERÍODOS (Matutino, Vespertino, Noturno…)
-- ============================================================
CREATE TABLE periodos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(50) NOT NULL UNIQUE
);

-- ============================================================
-- 5. FUNÇÕES / CARGOS (Aluno, Professor, Funcionário, Visitante…)
-- ============================================================
CREATE TABLE funcoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
);

-- ============================================================
-- 6. CONDUTORES
-- Pessoas autorizadas a acessar a Etec com veículo.
-- ============================================================
CREATE TABLE condutores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    id_documento INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_condutor_documento FOREIGN KEY (id_documento) REFERENCES documentos (id) ON DELETE SET NULL
);

-- ============================================================
-- 7. CONTATOS
-- Telefones, e-mails ou WhatsApp vinculados ao Condutor.
-- ============================================================
CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_condutor INT NOT NULL,
    tipo ENUM(
        'EMAIL',
        'TELEFONE',
        'WHATSAPP'
    ) NOT NULL,
    valor VARCHAR(150) NOT NULL,
    CONSTRAINT fk_contato_condutor FOREIGN KEY (id_condutor) REFERENCES condutores (id) ON DELETE CASCADE
);

-- ============================================================
-- 8. VEÍCULOS
-- Veículos vinculados à Unidade; opcionalmente a um condutor.
-- Colunas legadas `id_usuario` e `id_empresa` preservadas para
-- compatibilidade com telas antigas do painel.
-- [FIX-2] Índice manual em `placa` NÃO é criado aqui; o UNIQUE
--         já garante o índice implicitamente no MySQL.
-- ============================================================
CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_unidade INT NOT NULL,
    id_condutor INT NULL,
    id_usuario INT NULL, -- Campo legado
    id_empresa INT NULL, -- Campo legado
    placa VARCHAR(10) NOT NULL UNIQUE,
    marca VARCHAR(80) NULL, -- NULL para compatibilidade legado
    modelo VARCHAR(80) NOT NULL,
    cor VARCHAR(30) NULL,
    ano INT NULL,
    tipo_veiculo ENUM(
        'CARRO',
        'MOTO',
        'CAMINHAO',
        'ONIBUS',
        'VAN',
        'OUTRO'
    ) NOT NULL,
    observacoes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_veiculo_unidade FOREIGN KEY (id_unidade) REFERENCES unidades (id) ON DELETE CASCADE,
    CONSTRAINT fk_veiculo_condutor FOREIGN KEY (id_condutor) REFERENCES condutores (id) ON DELETE SET NULL
);

-- ============================================================
-- 9. ACESSOS
-- Histórico normalizado de entradas/saídas na portaria.
-- [FIX-3] data_hora_entrada convertida para TIMESTAMP com
--         DEFAULT CURRENT_TIMESTAMP para portabilidade total.
--         data_hora_saida permanece anulável (sem default).
-- ============================================================
CREATE TABLE acessos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_unidade INT NOT NULL,
    id_veiculo INT NOT NULL,
    id_condutor INT NOT NULL,
    tipo_acesso ENUM(
        'ENTRADA',
        'SAIDA',
        'ENTRADA_SAIDA'
    ) NOT NULL,
    data_hora_entrada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- [FIX-3]
    data_hora_saida TIMESTAMP NULL,
    status ENUM(
        'ABERTO',
        'EM_ANDAMENTO',
        'FINALIZADO',
        'CANCELADO'
    ) DEFAULT 'ABERTO',
    observacao TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_acesso_unidade FOREIGN KEY (id_unidade) REFERENCES unidades (id) ON DELETE CASCADE,
    CONSTRAINT fk_acesso_veiculo FOREIGN KEY (id_veiculo) REFERENCES veiculos (id) ON DELETE CASCADE,
    CONSTRAINT fk_acesso_condutor FOREIGN KEY (id_condutor) REFERENCES condutores (id) ON DELETE CASCADE
);

-- ============================================================
-- 10. DADOS ACADÊMICOS DO ACESSO
-- Detalhes acadêmicos opcionais vinculados a um registro de acesso.
-- ============================================================
CREATE TABLE acesso_academico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_acesso INT NOT NULL,
    id_curso INT NULL,
    id_periodo INT NULL,
    id_funcao INT NULL,
    CONSTRAINT fk_academico_acesso FOREIGN KEY (id_acesso) REFERENCES acessos (id) ON DELETE CASCADE,
    CONSTRAINT fk_academico_curso FOREIGN KEY (id_curso) REFERENCES cursos (id) ON DELETE SET NULL,
    CONSTRAINT fk_academico_periodo FOREIGN KEY (id_periodo) REFERENCES periodos (id) ON DELETE SET NULL,
    CONSTRAINT fk_academico_funcao FOREIGN KEY (id_funcao) REFERENCES funcoes (id) ON DELETE SET NULL
);

-- ============================================================
-- 12. USUÁRIOS  ← TABELA CENTRAL DE AUTENTICAÇÃO (v2.0)
--
-- Esta tabela unifica DOIS fluxos de login:
--
--   a) LEGADO (processa-login.php):
--      Usa `email` + `senha` + `role` + `id_empresa`.
--      Campos `codigo_acesso` e `contato_valor` preservados.
--
--   b) NOVO (login_process.php):
--      O operador informa o `codigo_identificador` da Etec
--      (localiza a unidade) e depois a senha do seu perfil.
--      A consulta filtra por `id_unidade` + `role`.
--
-- [FIX-1] `id_unidade` FK adicionado → vincula cada usuário
--         à sua Etec.  `role` promovido a ENUM com os perfis
--         oficiais do sistema. Senhas `senha_admin` e
--         `senha_portaria` foram REMOVIDAS de `unidades`.
-- ============================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_unidade INT NULL,
    codigo_acesso VARCHAR(20) NOT NULL UNIQUE,
    nome_completo VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    role ENUM(
        'admin',
        'portaria',
        'usuario',
        'aluno'
    ) NOT NULL DEFAULT 'usuario',
    id_empresa INT NULL,
    contato_valor VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuario_unidade FOREIGN KEY (id_unidade) REFERENCES unidades (id) ON DELETE SET NULL
);

-- ============================================================
-- 13. REGISTROS DE ACESSO  (LEGADO)
-- Utilizado pelo painel operacional guarita, relatórios e
-- controle de pátio das telas antigas do TCC.
-- NÃO ALTERAR A ESTRUTURA — mantida para retrocompatibilidade.
-- [FIX-3] data_hora_entrada convertida para TIMESTAMP para
--         garantir portabilidade; comportamento idêntico ao DATETIME.
-- ============================================================
CREATE TABLE registros_acesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo INT NOT NULL,
    id_usuario_registro INT NULL, -- Campo legado
    id_empresa INT NULL, -- Campo legado
    tipo_acesso VARCHAR(50) NOT NULL,
    curso VARCHAR(50) NULL,
    periodo VARCHAR(50) NULL,
    modulo VARCHAR(50) NULL,
    funcao VARCHAR(50) NULL,
    nome_condutor VARCHAR(150) NOT NULL,
    contato_tipo VARCHAR(20) NULL,
    contato_valor VARCHAR(50) NULL,
    observacao TEXT NULL,
    status VARCHAR(20) DEFAULT 'Dentro',
    data_hora_entrada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- [FIX-3]
    data_hora_saida TIMESTAMP NULL,
    CONSTRAINT fk_registro_veiculo FOREIGN KEY (id_veiculo) REFERENCES veiculos (id) ON DELETE CASCADE
);

-- ============================================================
-- 14. ÍNDICES DE PERFORMANCE
-- [FIX-2] `idx_veiculo_placa` REMOVIDO — a declaração UNIQUE em
--         `veiculos(placa)` já cria o índice automaticamente no MySQL.
--         Os demais índices abaixo são válidos (colunas sem UNIQUE).
-- ============================================================
CREATE INDEX idx_veiculo_unidade ON veiculos (id_unidade);

CREATE INDEX idx_acesso_entrada ON acessos (data_hora_entrada);

CREATE INDEX idx_acesso_status ON acessos (status);

CREATE INDEX idx_condutor_nome ON condutores (nome);
-- Índice composto para a nova query de login (unidade + perfil)
CREATE INDEX idx_usuario_unidade_role ON usuarios (id_unidade, role);

-- ============================================================
-- 15. DADOS DE EXEMPLO — UNIDADE DE TESTE
--
-- Credenciais padrão (para apresentação do TCC):
--   Código da Etec : etec123
--   Admin   → email: admin@etec.sp.gov.br   / senha: admin123
--   Portaria→ email: portaria@etec.sp.gov.br / senha: portaria123
--
-- COMO A NOVA LÓGICA DE LOGIN FUNCIONA (login_process.php v2.0):
--   1. Usuário informa `codigo_identificador` → localiza a unidade.
--   2. Sistema busca em `usuarios` WHERE id_unidade = ? AND role IN (…).
--   3. Itera os registros e testa password_verify(senha_digitada, hash).
--   4. Redireciona conforme `role` (admin → painel-admin / portaria → estacionamento).
-- ============================================================

-- 15.1 Unidade (apenas dados institucionais — sem senhas)
INSERT INTO
    unidades (
        empresaNome,
        tipoDocumento,
        documento,
        telefone,
        codigo_identificador
    )
VALUES (
        'ETEC Centro Paula Souza',
        'CNPJ',
        '00000000000100',
        '1133243000',
        'etec123'
    );

-- 15.2 Endereço da unidade
INSERT INTO
    enderecos (
        id_unidade,
        cep,
        logradouro,
        numero,
        complemento,
        bairro,
        cidade,
        uf
    )
VALUES (
        1,
        '01108-000',
        'Avenida Tiradentes',
        '524',
        'Luz',
        'Bom Retiro',
        'São Paulo',
        'SP'
    );

-- 15.3 Usuário ADMIN — corresponde ao antigo `senha_admin` de unidades
--      role='admin' → redirecionado para painel-admin.php
INSERT INTO
    usuarios (
        id_unidade,
        codigo_acesso,
        nome_completo,
        email,
        senha,
        cpf,
        role
    )
VALUES (
        1,
        'ADM-ETEC1',
        'Gestor Principal Teste',
        'admin@etec.sp.gov.br',
        '$2y$10$Tosc652lOCjvqzMKX9ap5uHPZJOW6A3SgegPpXu7B9sK0O3IIr6ny', -- admin123
        '00000000000',
        'admin'
    );

-- 15.4 Usuário PORTARIA — corresponde ao antigo `senha_portaria` de unidades
--      role='portaria' → redirecionado para estacionamento.php
INSERT INTO
    usuarios (
        id_unidade,
        codigo_acesso,
        nome_completo,
        email,
        senha,
        cpf,
        role
    )
VALUES (
        1,
        'PORT-ETEC1',
        'Operador Portaria Turno A',
        'portaria@etec.sp.gov.br',
        '$2y$10$Bz0iJKkhIp8/21/H3a9Cce3TSQ41PIzIHSup3yLCMxwISOZXDcRiC', -- portaria123
        '11111111111',
        'portaria'
    );

SET FOREIGN_KEY_CHECKS = 1;