CREATE TABLE cliente (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE fornecedor (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE produto (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255),
    id_fornecedor INT NOT NULL,

    FOREIGN KEY (id_fornecedor)
    REFERENCES fornecedor(id)
);

CREATE TABLE estoque (
    id SERIAL PRIMARY KEY,
    quantidade INT NOT NULL,
    id_produto INT UNIQUE NOT NULL,

    FOREIGN KEY (id_produto)
    REFERENCES produto(id)
);

CREATE TABLE admin (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

ALTER TABLE produto
ADD COLUMN imagem VARCHAR(255);

CREATE TABLE carrinho (

    id SERIAL PRIMARY KEY,

    id_cliente INT NOT NULL,

    id_produto INT NOT NULL,

    quantidade INT DEFAULT 1,

    FOREIGN KEY (id_cliente)
    REFERENCES cliente(id),

    FOREIGN KEY (id_produto)
    REFERENCES produto(id)
);