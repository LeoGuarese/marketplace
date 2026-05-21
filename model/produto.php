<?php
class produto {
private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function cadastrar($nome, $descricao, $quantidade, $idFornecedor, $imagem) {

    // gera nome único
    $nomeImagem = uniqid() . "_" . $imagem['name'];

    // caminho da pasta
    $caminho = "../uploads/" . $nomeImagem;

    // move imagem
    if (!move_uploaded_file($imagem['tmp_name'], $caminho)) {
        return false;
    }

    // cadastra produto
    $sqlProduto = "INSERT INTO produto
                    (nome, descricao, id_fornecedor, imagem)
                    VALUES ($1, $2, $3, $4)
                    RETURNING id";

    $resultProduto = pg_query_params(
        $this->conn,
        $sqlProduto,
        [
            $nome,
            $descricao,
            $idFornecedor,
            $nomeImagem
        ]
    );

    if (!$resultProduto) {
        return false;
    }

    $produto = pg_fetch_assoc($resultProduto);
    $idProduto = $produto['id'];

    // cadastra estoque
    $sqlEstoque = "INSERT INTO estoque
                    (quantidade, id_produto)
                    VALUES ($1, $2)";

    return pg_query_params(
        $this->conn,
        $sqlEstoque,
        [
            $quantidade,
            $idProduto
        ]
    );
}


    public function atualizar($id, $nome, $descricao, $quantidade, $idFornecedor) {

        // atualiza produto
        $sqlProduto = "UPDATE produto
                        SET nome = $1,
                            descricao = $2
                        WHERE id = $3
                        AND id_fornecedor = $4";

        $resultProduto = pg_query_params(
            $this->conn,
            $sqlProduto,
            [$nome, $descricao, $id, $idFornecedor]
        );

        if (!$resultProduto) {
            return false;
        }

        // atualiza estoque
        $sqlEstoque = "UPDATE estoque
                        SET quantidade = $1
                        WHERE id_produto = $2";

        return pg_query_params(
            $this->conn,
            $sqlEstoque,
            [$quantidade, $id]
        );
    }


    public function buscarPorId($idProduto, $idFornecedor) {

    $sql = "SELECT
                produto.id,
                produto.nome,
                produto.descricao,
                estoque.quantidade
            FROM produto
            JOIN estoque
            ON produto.id = estoque.id_produto
            WHERE produto.id = $1
            AND produto.id_fornecedor = $2";

    $result = pg_query_params(
        $this->conn,
        $sql,
        [$idProduto, $idFornecedor]
    );

    return pg_fetch_assoc($result);
}

public function excluir($idProduto, $idFornecedor) {

    // remove estoque
    $sqlEstoque = "DELETE FROM estoque
                    WHERE id_produto = $1";

    $resultEstoque = pg_query_params(
        $this->conn,
        $sqlEstoque,
        [$idProduto]
    );

    if (!$resultEstoque) {
        return false;
    }

    // remove produto
    $sqlProduto = "DELETE FROM produto
                    WHERE id = $1
                    AND id_fornecedor = $2";

    return pg_query_params(
        $this->conn,
        $sqlProduto,
        [$idProduto, $idFornecedor]
    );
}


public function listarPorFornecedor($idFornecedor, $busca = '') {

    if ($busca != '') {

        $sql = "SELECT
                    produto.id,
                    produto.nome,
                    produto.descricao,
                    estoque.quantidade
                FROM produto
                JOIN estoque
                ON produto.id = estoque.id_produto
                WHERE produto.id_fornecedor = $1
                AND produto.nome ILIKE $2";

        $result = pg_query_params(
            $this->conn,
            $sql,
            [$idFornecedor, "%$busca%"]
        );

    } else {

        $sql = "SELECT
                    produto.id,
                    produto.nome,
                    produto.descricao,
                    estoque.quantidade
                FROM produto
                JOIN estoque
                ON produto.id = estoque.id_produto
                WHERE produto.id_fornecedor = $1";

        $result = pg_query_params(
            $this->conn,
            $sql,
            [$idFornecedor]
        );
    }

    return $result;
}


public function listarPaginado($idFornecedor, $limite, $offset, $busca = '') {

    if ($busca != '') {

        $sql = "SELECT
                    produto.id,
                    produto.nome,
                    produto.descricao,
                    estoque.quantidade
                FROM produto
                JOIN estoque
                ON produto.id = estoque.id_produto
                WHERE produto.id_fornecedor = $1
                AND produto.nome ILIKE $2
                ORDER BY produto.id
                LIMIT $3
                OFFSET $4";

        return pg_query_params(
            $this->conn,
            $sql,
            [
                $idFornecedor,
                "%$busca%",
                $limite,
                $offset
            ]
        );
    }

    $sql = "SELECT
                produto.id,
                produto.nome,
                produto.descricao,
                estoque.quantidade
            FROM produto
            JOIN estoque
            ON produto.id = estoque.id_produto
            WHERE produto.id_fornecedor = $1
            ORDER BY produto.id
            LIMIT $2
            OFFSET $3";

    return pg_query_params(
        $this->conn,
        $sql,
        [
            $idFornecedor,
            $limite,
            $offset
        ]
    );
}

public function contarProdutos(
    $idFornecedor,
    $busca = ''
) {

    if ($busca != '') {

        $sql = "SELECT COUNT(*) AS total
                FROM produto
                WHERE id_fornecedor = $1
                AND nome ILIKE $2";

        $result = pg_query_params(
            $this->conn,
            $sql,
            [
                $idFornecedor,
                "%$busca%"
            ]
        );

    } else {

        $sql = "SELECT COUNT(*) AS total
                FROM produto
                WHERE id_fornecedor = $1";

        $result = pg_query_params(
            $this->conn,
            $sql,
            [$idFornecedor]
        );
    }

    $dados = pg_fetch_assoc($result);

    return $dados['total'];
}


public function listarTodosProdutos() {

    $sql = "SELECT
                produto.id,
                produto.nome,
                produto.descricao,
                produto.imagem,
                estoque.quantidade
            FROM produto
            JOIN estoque
            ON produto.id = estoque.id_produto";

    return pg_query($this->conn, $sql);
}

}
?>