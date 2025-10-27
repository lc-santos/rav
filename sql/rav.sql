DROP TABLE IF EXISTS rav;
-- Cria o banco de dados (se ele não existir)
CREATE DATABASE IF NOT EXISTS rav;

-- Seleciona o banco de dados para usar
USE rav;

--
-- ETAPA DE LIMPEZA (para poder re-executar)
-- Dropamos as tabelas na ORDEM INVERSA da criação (filhos primeiro)
--
DROP TABLE IF EXISTS registros_acesso;

DROP TABLE IF EXISTS veiculos;

DROP TABLE IF EXISTS usuarios;

DROP TABLE IF EXISTS empresas;

DROP TABLE IF EXISTS dados_empresa;
-- Garantia de limpar sua tabela antiga

--
-- ETAPA DE CRIAÇÃO (Pais primeiro, filhos depois)
--

-- 1. Tabela 'empresas' (Pai)
-- Armazena os dados da pessoa jurídica (condomínio, empresa, etc.)
--
CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresaNome varchar(50) not null,
    tipoDocumento varchar(50) not null,
    documento varchar(50) not null,
    telefone varchar(50) not null,
    endereco varchar(50) not null
);

-- 2. Tabela 'usuarios' (Filho de 'empresas')
-- Armazena TODOS os logins (Admins e Porteiros)
--
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,     -- UNIQUE garante que não haja dois emails iguais
    senha VARCHAR(255) NOT NULL,            -- Tamanho para o password_hash()
    cpf VARCHAR(14) NOT NULL UNIQUE,        -- UNIQUE garante que não haja dois CPFs iguais
    datanasc DATE NULL,                     -- NULL (opcional)
    role VARCHAR(20) NOT NULL DEFAULT 'admin', -- Ex: 'admin' ou 'porteiro'
    id_empresa INT NOT NULL,                -- Chave Estrangeira que liga ao 'pai'
    FOREIGN KEY (id_empresa) REFERENCES empresas(id) 
    );

-- 3. Tabela 'veiculos' (Filho de 'empresas')
-- Armazena o cadastro completo dos veículos
--
CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) NOT NULL UNIQUE, -- UNIQUE garante que a placa é única
    marca VARCHAR(50) NULL,
    modelo VARCHAR(50) NULL,
    cor VARCHAR(30) NULL,
    tipo VARCHAR(20) NOT NULL DEFAULT 'visitante', -- Ex: 'morador', 'temporario', 'visitante'
    obs TEXT NULL, -- Observações (ex: "Apto 101")
    id_empresa INT NOT NULL, -- Chave Estrangeira que liga ao 'pai'
    FOREIGN KEY (id_empresa) REFERENCES empresas (id)
);

-- 4. Tabela 'registros_acesso' (Filho de 'empresas', 'usuarios', 'veiculos')
-- Armazena o histórico de entradas e saídas (o "registro rápido")
--
CREATE TABLE registros_acesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) NOT NULL,             -- Placa digitada (não é UNIQUE aqui)
    data_hora_entrada DATETIME NOT NULL,
    data_hora_saida DATETIME NULL,          -- Fica NULL até o carro sair
    id_usuario_registro INT NOT NULL,       -- Chave Estrangeira: Quem registrou
    id_veiculo INT NULL,                    -- Chave Estrangeira: O veículo (se cadastrado)
    id_empresa INT NOT NULL,                -- Chave Estrangeira: A empresa
    FOREIGN KEY (id_usuario_registro) REFERENCES usuarios(id),
    FOREIGN KEY (id_veiculo) REFERENCES veiculos(id),
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);