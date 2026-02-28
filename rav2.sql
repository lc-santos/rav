-- Limpeza e criação do Banco de Dados
DROP DATABASE IF EXISTS rav2;
CREATE DATABASE rav2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rav2;

-- ============================================================
-- 1. TABELA EMPRESAS (Pai de quase todas)
-- ============================================================
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    documento VARCHAR(20) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    endereco VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserção da Empresa Padrão (necessária para os vínculos iniciais)
INSERT INTO empresas (id, nome, documento) 
VALUES (1, 'ETEC Unidade Jardim Angela', '00.000.000/0001-00');

-- ============================================================
-- 2. TABELA USUÁRIOS (Contém o ID de 7 dígitos e Operadores)
-- ============================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_acesso VARCHAR(7) UNIQUE NOT NULL, -- ID de 7 dígitos gerado pelo sistema
    nome_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    role ENUM('admin', 'porteiro', 'visitante') DEFAULT 'visitante',
    id_empresa INT NOT NULL,
    contato_valor VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuario_empresa FOREIGN KEY (id_empresa) REFERENCES empresas(id) ON DELETE CASCADE
);

-- Inserção do Operador Padrão (Lucas Silva - ID 1)
-- Essencial para evitar erros de Foreign Key no Registrar Acesso
INSERT INTO usuarios (id, codigo_acesso, nome_completo, email, senha, cpf, id_empresa, role) 
VALUES (1, '0000001', 'Lucas Silva', 'lucas@email.com', '123', '000.000.000-00', 1, 'admin');

-- ============================================================
-- 3. TABELA UNIDADES (Ex: Guarita, Bloco, Auditório)
-- ============================================================
CREATE TABLE unidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    id_empresa INT NOT NULL,
    CONSTRAINT fk_unidade_empresa FOREIGN KEY (id_empresa) REFERENCES empresas(id) ON DELETE CASCADE
);

-- ============================================================
-- 4. TABELA VEÍCULOS
-- ============================================================
CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) NOT NULL UNIQUE,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    cor VARCHAR(30),
    tipo ENUM('morador','temporario','visitante') DEFAULT 'visitante',
    id_empresa INT NOT NULL,
    CONSTRAINT fk_veiculo_empresa FOREIGN KEY (id_empresa) REFERENCES empresas(id) ON DELETE CASCADE
);

-- ============================================================
-- 5. TABELA REGISTROS DE ACESSO (O Log de Movimentação)
-- ============================================================
CREATE TABLE registros_acesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo INT NOT NULL,
    id_operador INT NULL,
    id_empresa INT NOT NULL,
    id_unidade INT NULL,
    tipo_acesso ENUM('Aluno','Diretoria','Professor','Funcionário','Serviço') NOT NULL,
    nome_condutor VARCHAR(100) NOT NULL,
    contato_tipo ENUM('tel','email') NOT NULL,
    contato_valor VARCHAR(100) NOT NULL,
    observacao TEXT,
    data_entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_saida DATETIME NULL,
    status ENUM('Dentro','Saiu') DEFAULT 'Dentro',
    
    CONSTRAINT fk_registro_veiculo FOREIGN KEY (id_veiculo) REFERENCES veiculos(id) ON DELETE CASCADE,
    CONSTRAINT fk_registro_operador FOREIGN KEY (id_operador) REFERENCES usuarios(id) ON DELETE SET NULL,
    CONSTRAINT fk_registro_empresa FOREIGN KEY (id_empresa) REFERENCES empresas(id) ON DELETE CASCADE,
    CONSTRAINT fk_registro_unidade FOREIGN KEY (id_unidade) REFERENCES unidades(id) ON DELETE SET NULL
);

-- No HeidiSQL, execute:
ALTER TABLE veiculos ADD COLUMN id_usuario INT AFTER id;
ALTER TABLE veiculos ADD CONSTRAINT fk_veiculo_usuario 
FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE;

-- Execute no seu banco rav2:
UPDATE veiculos SET id_usuario = 1 WHERE id_usuario IS NULL;