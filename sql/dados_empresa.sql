-- Active: 1756842331079@@127.0.0.1@3306@cadastro
create database if not exists `cadastro`;
use cadastro;

create table dados_empresa(
id int not null auto_increment,
empresaNome varchar(50) not null,
tipoDocumento varchar(50) not null,
documento varchar(50) not null,
telefone varchar(50) not null,
endereco varchar(50) not null,
nomeAdmin varchar(50) not null,
cpfadmin varchar(20) not null,
datanasc date not null,
emailAdmin varchar(50) not null,
senha varchar(255) not null,
primary key(id) 
);