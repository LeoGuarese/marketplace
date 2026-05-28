<?php

class Admin {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function buscarPorEmail($email) {

        $sql = "SELECT *
                FROM admin
                WHERE email = $1";

        $result = pg_query_params(
            $this->conn,
            $sql,
            [$email]
        );

        return pg_fetch_assoc($result);
    }

    public function listarClientes() {

        $sql = "SELECT
                    id,
                    nome,
                    email,
                    telefone
                FROM cliente";

        return pg_query($this->conn, $sql);
    }

    public function listarFornecedores() {

        $sql = "SELECT
                    id,
                    nome,
                    email,
                    telefone
                FROM fornecedor";

        return pg_query($this->conn, $sql);
    }
    
    public function excluirCliente($id) {

    $sql = "DELETE FROM cliente
            WHERE id = $1";

    $result = pg_query_params(
        $this->conn,
        $sql,
        [$id]
    );

    if (!$result) {

        die(
            "Erro ao excluir cliente: " .
            pg_last_error($this->conn)
        );
    }

    return true;
}

public function excluirFornecedor($id) {

    // remove estoque dos produtos do fornecedor
    $sqlEstoque = "
        DELETE FROM estoque
        WHERE id_produto IN (
            SELECT id
            FROM produto
            WHERE id_fornecedor = $1
        )
    ";

    $resultEstoque = pg_query_params(
        $this->conn,
        $sqlEstoque,
        [$id]
    );

    if (!$resultEstoque) {

        die(
            "Erro ao excluir estoque: " .
            pg_last_error($this->conn)
        );
    }

    // remove produtos do fornecedor
    $sqlProduto = "
        DELETE FROM produto
        WHERE id_fornecedor = $1
    ";

    $resultProduto = pg_query_params(
        $this->conn,
        $sqlProduto,
        [$id]
    );

    if (!$resultProduto) {

        die(
            "Erro ao excluir produtos: " .
            pg_last_error($this->conn)
        );
    }

    // remove fornecedor
    $sqlFornecedor = "
        DELETE FROM fornecedor
        WHERE id = $1
    ";

    $resultFornecedor = pg_query_params(
        $this->conn,
        $sqlFornecedor,
        [$id]
    );

    if (!$resultFornecedor) {

        die(
            "Erro ao excluir fornecedor: " .
            pg_last_error($this->conn)
        );
    }

    return true;
}

public function listarClientesPaginado($limite, $offset) {
    $sql = "SELECT id, nome, email, telefone
            FROM cliente
            ORDER BY id
            LIMIT $1 OFFSET $2";

    return pg_query_params($this->conn, $sql, [$limite, $offset]);
}

public function contarClientes() {
    $sql = "SELECT COUNT(*) AS total FROM cliente";
    $result = pg_query($this->conn, $sql);
    $dados = pg_fetch_assoc($result);
    return $dados['total'];
}

public function listarFornecedoresPaginado($limite, $offset) {
    $sql = "SELECT id, nome, email, telefone
            FROM fornecedor
            ORDER BY id
            LIMIT $1 OFFSET $2";

    return pg_query_params($this->conn, $sql, [$limite, $offset]);
}

public function contarFornecedores() {
    $sql = "SELECT COUNT(*) AS total FROM fornecedor";
    $result = pg_query($this->conn, $sql);
    $dados = pg_fetch_assoc($result);
    return $dados['total'];
}

}
?>