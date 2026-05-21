<?php

class Fornecedor {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function emailExiste($email) {

        $sql = "SELECT id
                FROM fornecedor
                WHERE email = $1";

        $result = pg_query_params(
            $this->conn,
            $sql,
            [$email]
        );

        return pg_num_rows($result) > 0;
    }

    public function cadastrar($nome, $telefone, $email, $senha) {

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO fornecedor
                (nome, telefone, email, senha)
                VALUES ($1, $2, $3, $4)";

        return pg_query_params(
            $this->conn,
            $sql,
            [$nome, $telefone, $email, $senhaHash]
        );
    }

    public function buscarPorEmail($email) {

    $sql = "SELECT *
            FROM fornecedor
            WHERE email = $1";

    $result = pg_query_params(
        $this->conn,
        $sql,
        [$email]
    );

    return pg_fetch_assoc($result);
}
}
?>