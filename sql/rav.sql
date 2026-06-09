-- Active: 1780611310422@@127.0.0.1@3306@rav

CREATE DATABASE IF NOT EXISTS rav CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rav;

-- Desativa checagem de chaves estrangeiras para recriação limpa
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
DROP TABLE IF EXISTS fale_conosco;

-- =========================================================================
-- 1. UNIDADES
-- Armazena as unidades escolares, dados do gestor e credenciais de acesso.
-- Unifica as tabelas antigas de empresas e usuários para simplificação de TCC.
-- =========================================================================
CREATE TABLE unidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Dados da Unidade (Institucional)
    empresaNome VARCHAR(100) NOT NULL,
    tipoDocumento VARCHAR(50) NOT NULL,
    documento VARCHAR(50) NOT NULL UNIQUE, -- CNPJ ou número identificador único
    telefone VARCHAR(20) NULL,
    
    -- Dados do Gestor Principal
    nome_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    datanasc DATE NULL,
    
    -- Credenciais de Acesso Simplificadas
    codigo_identificador VARCHAR(20) NOT NULL UNIQUE, -- Login da Etec
    senha_admin VARCHAR(255) NOT NULL,    -- Hash bcrypt para acesso administrativo
    senha_portaria VARCHAR(255) NOT NULL, -- Hash bcrypt para acesso de portaria
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================================
-- 1.1 ENDEREÇOS
-- Armazena os endereços das unidades. Relacionamento 1 para 1 com unidades.
-- =========================================================================
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
    CONSTRAINT fk_endereco_unidade
        FOREIGN KEY (id_unidade)
        REFERENCES unidades(id)
        ON DELETE CASCADE
);

-- =========================================================================
-- 2. DOCUMENTOS
-- Cadastro unificado de documentos de condutores
-- =========================================================================
CREATE TABLE documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_documento VARCHAR(20) NOT NULL,
    numero_documento VARCHAR(30) NOT NULL UNIQUE
);

-- =========================================================================
-- 3. CURSOS
-- =========================================================================
CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
);

-- =========================================================================
-- 4. PERÍODOS
-- =========================================================================
CREATE TABLE periodos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(50) NOT NULL UNIQUE
);

-- =========================================================================
-- 5. FUNÇÕES / CARGOS (Ex: Aluno, Professor, Funcionário, Visitante)
-- =========================================================================
CREATE TABLE funcoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
);

-- =========================================================================
-- 6. CONDUTORES
-- Pessoas autorizadas a acessar a Etec com veículo
-- =========================================================================
CREATE TABLE condutores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    id_documento INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_condutor_documento
        FOREIGN KEY (id_documento)
        REFERENCES documentos(id)
        ON DELETE SET NULL
);

-- =========================================================================
-- 7. CONTATOS
-- Vínculo direto de contatos telefônicos, e-mails ou WhatsApp ao Condutor
-- =========================================================================
CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_condutor INT NOT NULL,
    tipo ENUM('EMAIL', 'TELEFONE', 'WHATSAPP') NOT NULL,
    valor VARCHAR(150) NOT NULL,
    CONSTRAINT fk_contato_condutor
        FOREIGN KEY (id_condutor)
        REFERENCES condutores(id)
        ON DELETE CASCADE
);

-- =========================================================================
-- 8. VEÍCULOS
-- Veículos associados à Unidade e opcionalmente vinculados a um condutor
-- =========================================================================
CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_unidade INT NOT NULL,
    id_condutor INT NULL,
    id_usuario INT NULL,     -- Campo legado
    id_empresa INT NULL,     -- Campo legado
    placa VARCHAR(10) NOT NULL UNIQUE,
    marca VARCHAR(80) NULL,  -- Permitir NULL para compatibilidade legado
    modelo VARCHAR(80) NOT NULL,
    cor VARCHAR(30) NULL,
    ano INT NULL,
    tipo_veiculo ENUM('CARRO', 'MOTO', 'CAMINHAO', 'ONIBUS', 'VAN', 'OUTRO') NOT NULL,
    observacoes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_veiculo_unidade
        FOREIGN KEY (id_unidade)
        REFERENCES unidades(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_veiculo_condutor
        FOREIGN KEY (id_condutor)
        REFERENCES condutores(id)
        ON DELETE SET NULL
);

-- =========================================================================
-- 9. ACESSOS
-- Registro histórico de fluxo de entradas e saídas de veículos na portaria
-- =========================================================================
CREATE TABLE acessos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_unidade INT NOT NULL,
    id_veiculo INT NOT NULL,
    id_condutor INT NOT NULL,
    tipo_acesso ENUM('ENTRADA', 'SAIDA', 'ENTRADA_SAIDA') NOT NULL,
    data_hora_entrada DATETIME NOT NULL,
    data_hora_saida DATETIME NULL,
    status ENUM('ABERTO', 'EM_ANDAMENTO', 'FINALIZADO', 'CANCELADO') DEFAULT 'ABERTO',
    observacao TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_acesso_unidade
        FOREIGN KEY (id_unidade)
        REFERENCES unidades(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_acesso_veiculo
        FOREIGN KEY (id_veiculo)
        REFERENCES veiculos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_acesso_condutor
        FOREIGN KEY (id_condutor)
        REFERENCES condutores(id)
        ON DELETE CASCADE
);

-- =========================================================================
-- 10. DADOS ACADÊMICOS DO ACESSO
-- Detalhes acadêmicos opcionais vinculados a um registro de acesso
-- =========================================================================
CREATE TABLE acesso_academico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_acesso INT NOT NULL,
    id_curso INT NULL,
    id_periodo INT NULL,
    id_funcao INT NULL,
    CONSTRAINT fk_academico_acesso
        FOREIGN KEY (id_acesso)
        REFERENCES acessos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_academico_curso
        FOREIGN KEY (id_curso)
        REFERENCES cursos(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_academico_periodo
        FOREIGN KEY (id_periodo)
        REFERENCES periodos(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_academico_funcao
        FOREIGN KEY (id_funcao)
        REFERENCES funcoes(id)
        ON DELETE SET NULL
);

-- =========================================================================
-- 11. FALE CONOSCO
-- =========================================================================
CREATE TABLE fale_conosco (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NULL,
    assunto VARCHAR(150) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pendente', 'Lido', 'Respondido') DEFAULT 'Pendente'
);

-- =========================================================================
-- 11.1 USUÁRIOS (LEGADO)
-- Necessário para o funcionamento do painel admin legado e cadastro de condutores
-- =========================================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_acesso VARCHAR(20) NOT NULL UNIQUE,
    nome_completo VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    id_empresa INT NULL,
    role VARCHAR(50) DEFAULT 'visitante',
    contato_valor VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================================
-- 11.2 REGISTROS DE ACESSO (LEGADO)
-- Utilizado pelo painel operacional guarita, relatórios e controle de pátio
-- =========================================================================
CREATE TABLE registros_acesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo INT NOT NULL,
    id_usuario_registro INT NULL,
    id_empresa INT NULL,
    tipo_acesso VARCHAR(50) NOT NULL,
    curso VARCHAR(50) NULL,
    periodo VARCHAR(50) NULL,
    funcao VARCHAR(50) NULL,
    nome_condutor VARCHAR(150) NOT NULL,
    contato_tipo VARCHAR(20) NULL,
    contato_valor VARCHAR(50) NULL,
    observacao TEXT NULL,
    status VARCHAR(20) DEFAULT 'Dentro',
    data_hora_entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_hora_saida DATETIME NULL,
    CONSTRAINT fk_registro_veiculo
        FOREIGN KEY (id_veiculo)
        REFERENCES veiculos(id)
        ON DELETE CASCADE
);

-- =========================================================================
-- 12. ÍNDICES DE PERFORMANCE
-- =========================================================================
CREATE INDEX idx_veiculo_unidade ON veiculos(id_unidade);
CREATE INDEX idx_veiculo_placa ON veiculos(placa);
CREATE INDEX idx_acesso_entrada ON acessos(data_hora_entrada);
CREATE INDEX idx_acesso_status ON acessos(status);
CREATE INDEX idx_condutor_nome ON condutores(nome);

-- =========================================================================
-- 13. REGISTROS DE EXEMPLO (UNIDADE TESTE)
-- Credenciais padrões para fins de teste e apresentação de TCC:
-- Código identificador: etec123
-- Senha de Admin: admin123 (Hash correspondente gerado via bcrypt)
-- Senha de Portaria: portaria123 (Hash correspondente gerado via bcrypt)
-- =========================================================================
INSERT INTO unidades (empresaNome, tipoDocumento, documento, telefone, nome_completo, email, cpf, datanasc, codigo_identificador, senha_admin, senha_portaria)
VALUES (
    'ETEC Centro Paula Souza',
    'CNPJ',
    '00000000000100',
    '1133243000',
    'Gestor Principal Teste',
    'gestor@etec.sp.gov.br',
    '00000000000',
    '1985-05-15',
    'etec123',
    '$2y$10$Tosc652lOCjvqzMKX9ap5uHPZJOW6A3SgegPpXu7B9sK0O3IIr6ny', -- admin123
    '$2y$10$Bz0iJKkhIp8/21/H3a9Cce3TSQ41PIzIHSup3yLCMxwISOZXDcRiC'  -- portaria123
);

INSERT INTO enderecos (id_unidade, cep, logradouro, numero, complemento, bairro, cidade, uf)
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

SET FOREIGN_KEY_CHECKS = 1;