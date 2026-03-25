-- Active: 1774397556316@@127.0.0.1@3306
-- 1. LIMPEZA TOTAL (Para evitar erros ao re-executar)
SET FOREIGN_KEY_CHECKS = 0;
DROP DATABASE IF EXISTS rav;
CREATE DATABASE rav CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rav;
SET FOREIGN_KEY_CHECKS = 1;

-- 2. TABELA 'EMPRESAS'
-- Mescla campos das duas versões para suportar tanto o cadastro jurídico quanto a identificação simples.
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresaNome VARCHAR(100) NOT NULL,
    tipoDocumento VARCHAR(50) NOT NULL, -- Ex: CNPJ ou Instituição
    documento VARCHAR(50) NOT NULL UNIQUE,
    telefone VARCHAR(50) NULL,
    endereco VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserção de exemplo para teste inicial
INSERT INTO empresas (id, empresaNome, tipoDocumento, documento) 
VALUES (1, 'ETEC Unidade Jardim Angela', 'CNPJ', '00.000.000/0001-00');

-- 3. TABELA 'USUARIOS'
-- Mantém a estrutura de login do 'rav' (email/senha/cpf) e adiciona o 'codigo_acesso' do 'rav2'.
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_acesso VARCHAR(10) UNIQUE NULL, -- Novo campo do rav2
    nome_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    datanasc DATE NULL,
    role ENUM('admin', 'porteiro', 'visitante') DEFAULT 'admin',
    id_empresa INT NOT NULL,
    contato_valor VARCHAR(100) NULL,
    CONSTRAINT fk_usuario_empresa FOREIGN KEY (id_empresa) REFERENCES empresas(id) ON DELETE CASCADE
);

-- 4. TABELA 'VEICULOS'
-- Unifica os campos de identificação (cor/modelo) e o vínculo com o usuário condutor.
CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NULL, -- Vínculo opcional com condutor cadastrado
    placa VARCHAR(10) NOT NULL UNIQUE,
    marca VARCHAR(50) NULL,
    modelo VARCHAR(50) NULL,
    cor VARCHAR(30) NULL,
    tipo VARCHAR(20) NOT NULL DEFAULT 'visitante', -- Campo do rav original
    tipo_veiculo ENUM('Carro', 'Moto', 'Bicicleta', 'Caminhão', 'Outros') DEFAULT 'Carro', -- Detalhamento do rav2
    obs TEXT NULL,
    id_empresa INT NOT NULL,
    CONSTRAINT fk_veiculo_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_veiculo_empresa FOREIGN KEY (id_empresa) REFERENCES empresas(id) ON DELETE CASCADE
);

-- 5. TABELA 'REGISTROS_ACESSO'
-- Esta é a tabela que mais muda, pois o rav2 é muito mais detalhado no controle de fluxo.
CREATE TABLE registros_acesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo INT NOT NULL,
    id_usuario_registro INT NULL, -- O operador/porteiro que registrou
    id_empresa INT NOT NULL,
    tipo_acesso ENUM('Aluno','Diretoria','Professor','Funcionário','Serviço', 'Visitante') NOT NULL,
    nome_condutor VARCHAR(100) NOT NULL, -- Para casos onde o condutor não é o dono do carro
    contato_tipo ENUM('tel','email') NULL,
    contato_valor VARCHAR(100) NULL,
    observacao TEXT,
    data_hora_entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_hora_saida DATETIME NULL,
    status ENUM('Dentro','Saiu') DEFAULT 'Dentro',
    CONSTRAINT fk_registro_veiculo FOREIGN KEY (id_veiculo) REFERENCES veiculos(id) ON DELETE CASCADE,
    CONSTRAINT fk_registro_operador FOREIGN KEY (id_usuario_registro) REFERENCES usuarios(id) ON DELETE SET NULL,
    CONSTRAINT fk_registro_empresa FOREIGN KEY (id_empresa) REFERENCES empresas(id) ON DELETE CASCADE
);

DELETE FROM usuarios WHERE email = 'etec@adm.com';

INSERT INTO usuarios (nome_completo, email, senha, cpf, id_empresa, role) 
VALUES ('Administrador ETEC', 'etec@adm.com', 'etecguar', '000.000.000-00', 1, 'admin');