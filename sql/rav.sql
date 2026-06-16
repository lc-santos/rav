-- =========================================================================
-- SISTEMA RAV (REGISTRO DE ACESSO DE VEÍCULOS)
-- Script de Criação e Refinamento do Banco de Dados para MySQL / Workbench
-- =========================================================================

CREATE DATABASE IF NOT EXISTS rav CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rav;

-- Desativa checagem de chaves estrangeiras para recriação limpa
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS registros_acesso;
DROP TABLE IF EXISTS veiculos;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS enderecos;
DROP TABLE IF EXISTS unidades;
DROP TABLE IF EXISTS fale_conosco;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================================
-- 1. UNIDADES (ETECs)
-- Armazena as unidades escolares, dados do gestor e credenciais de acesso.
-- =========================================================================
CREATE TABLE unidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Dados da Unidade (Institucional)
    empresaNome VARCHAR(100) NOT NULL,
    tipoDocumento VARCHAR(50) NOT NULL,
    documento VARCHAR(50) NOT NULL UNIQUE, -- CNPJ ou INEP
    telefone VARCHAR(20) NULL,
    
    -- Dados do Gestor Principal
    nome_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    datanasc DATE NULL,
    
    -- Credenciais de Acesso da Unidade (Guarita/Admin)
    codigo_identificador VARCHAR(20) NOT NULL UNIQUE, -- Código de Login da Unidade (ex: etec123)
    senha_admin VARCHAR(255) NOT NULL,    -- Hash bcrypt para acesso administrativo
    senha_portaria VARCHAR(255) NOT NULL, -- Hash bcrypt para acesso operacional (guarita)
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================================================
-- 2. ENDEREÇOS
-- Armazena o endereço físico de cada unidade escolar (Relacionamento 1:1)
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
) ENGINE=InnoDB;

-- =========================================================================
-- 3. USUÁRIOS / CONDUTORES
-- Pessoas cadastradas no sistema (Visitantes, Alunos, Professores ou Administradores)
-- =========================================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_acesso VARCHAR(20) NOT NULL UNIQUE, -- Identificador único de 7 dígitos gerado pelo sistema
    nome_completo VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL DEFAULT '123',
    cpf VARCHAR(14) NOT NULL UNIQUE,
    id_empresa INT NULL, -- Associação com a unidade à qual pertence
    role VARCHAR(50) DEFAULT 'visitante', -- Nível de acesso/perfil
    contato_valor VARCHAR(100) NULL, -- Telefone ou meio de contato principal
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuario_unidade
        FOREIGN KEY (id_empresa)
        REFERENCES unidades(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================================================================
-- 4. VEÍCULOS
-- Veículos associados a uma unidade e vinculados a um condutor cadastrado
-- =========================================================================
CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_unidade INT NOT NULL,
    id_usuario INT NULL, -- Dono/Condutor associado na tabela usuarios
    id_empresa INT NULL, -- Campo redundante legado para compatibilidade do PHP
    placa VARCHAR(10) NOT NULL UNIQUE,
    modelo VARCHAR(80) NOT NULL,
    cor VARCHAR(30) NULL,
    tipo_veiculo VARCHAR(50) NOT NULL, -- Tipo do veículo (ex: Carro, Moto, Van, Pedestre)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_veiculo_unidade
        FOREIGN KEY (id_unidade)
        REFERENCES unidades(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_veiculo_empresa
        FOREIGN KEY (id_empresa)
        REFERENCES unidades(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_veiculo_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================================
-- 5. REGISTROS DE ACESSO (FLUXO DO PÁTIO)
-- Histórico e controle em tempo real das entradas e saídas de veículos na portaria
-- =========================================================================
CREATE TABLE registros_acesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo INT NOT NULL,
    id_usuario_registro INT NULL, -- Operador que registrou (opcional)
    id_empresa INT NULL, -- Unidade da Etec à qual pertence o registro
    tipo_acesso VARCHAR(50) NOT NULL, -- Perfil (ex: Aluno, Equipe, Outros)
    curso VARCHAR(50) NULL, -- Acadêmico (opcional)
    periodo VARCHAR(50) NULL, -- Acadêmico (opcional)
    funcao VARCHAR(50) NULL, -- Acadêmico (opcional)
    nome_condutor VARCHAR(150) NOT NULL,
    contato_tipo VARCHAR(20) DEFAULT 'tel',
    contato_valor VARCHAR(50) NULL,
    observacao TEXT NULL,
    status VARCHAR(20) DEFAULT 'Dentro', -- Estado (Dentro, Saiu)
    data_hora_entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_hora_saida DATETIME NULL,
    CONSTRAINT fk_registro_veiculo
        FOREIGN KEY (id_veiculo)
        REFERENCES veiculos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_registro_empresa
        FOREIGN KEY (id_empresa)
        REFERENCES unidades(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================================
-- 6. FALE CONOSCO
-- Armazena as mensagens enviadas através do formulário de contato do site
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
) ENGINE=InnoDB;

-- =========================================================================
-- 7. ÍNDICES DE PERFORMANCE E PESQUISA RÁPIDA
-- =========================================================================
CREATE INDEX idx_veiculo_placa ON veiculos(placa);
CREATE INDEX idx_usuario_cpf ON usuarios(cpf);
CREATE INDEX idx_usuario_codigo ON usuarios(codigo_acesso);
CREATE INDEX idx_registro_status ON registros_acesso(status);
CREATE INDEX idx_registro_entrada ON registros_acesso(data_hora_entrada);

-- =========================================================================
-- 8. CARGA INICIAL DE DADOS (AMBIENTE DE TESTE / APRESENTAÇÃO)
-- Credenciais padrões do sistema:
-- Código identificador: etec123
-- Senha de Admin: admin123 (Hash correspondente gerado via bcrypt)
-- Senha de Portaria: portaria123 (Hash correspondente gerado via bcrypt)
-- =========================================================================

-- Inserindo Unidade Escolar de Teste
INSERT INTO unidades (id, empresaNome, tipoDocumento, documento, telefone, nome_completo, email, cpf, datanasc, codigo_identificador, senha_admin, senha_portaria)
VALUES (
    1,
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

-- Inserindo Endereço da Unidade
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

-- Inserindo Condutores Fictícios (Visitante/Aluno)
INSERT INTO usuarios (id, codigo_acesso, nome_completo, email, senha, cpf, id_empresa, role, contato_valor)
VALUES 
(
    1,
    '1234567',
    'Lucas Silva',
    'lucas@gmail.com',
    '123',
    '11111111111',
    1,
    'visitante',
    '11999999999'
),
(
    2,
    '7654321',
    'Mariana Costa',
    'mariana@gmail.com',
    '123',
    '22222222222',
    1,
    'visitante',
    '11988888888'
);

-- Inserindo Veículos Vinculados aos Condutores
INSERT INTO veiculos (id, id_unidade, id_usuario, id_empresa, placa, modelo, cor, tipo_veiculo)
VALUES 
(
    1,
    1,
    1,
    1,
    'ABC1D23',
    'Honda Civic',
    'Preto',
    'Carro'
),
(
    2,
    1,
    2,
    1,
    'XYZ9H87',
    'CG 160 Fan',
    'Vermelho',
    'Moto'
);

-- Inserindo Registros Iniciais de Fluxo (Estacionamento populado)
INSERT INTO registros_acesso (id, id_veiculo, id_usuario_registro, id_empresa, tipo_acesso, curso, periodo, funcao, nome_condutor, contato_tipo, contato_valor, observacao, status, data_hora_entrada, data_hora_saida)
VALUES
(
    1,
    1,
    1,
    1,
    'Aluno',
    'DSI',
    'Noturno',
    NULL,
    'Lucas Silva',
    'tel',
    '11999999999',
    'Nenhuma avaria visível.',
    'Dentro',
    NOW() - INTERVAL 1 HOUR,
    NULL
),
(
    2,
    2,
    1,
    1,
    'Aluno',
    'RHIII',
    'Matutino',
    NULL,
    'Mariana Costa',
    'tel',
    '11988888888',
    'Capacete no guidão.',
    'Saiu',
    NOW() - INTERVAL 4 HOUR,
    NOW() - INTERVAL 3 HOUR
);