<?php
session_start();

include("../conexao.php");

if (!isset($_SESSION['id'])) {

    header("Location: ../view/login1.php");
    exit;
}

$idCliente = $_SESSION['id'];
$idProduto = $_POST['id_produto'];

$sqlEstoque = "
    SELECT quantidade
    FROM estoque
    WHERE id_produto = $1
";

$resultEstoque = pg_query_params(
    $conn,
    $sqlEstoque,
    [$idProduto]
);

$estoque = pg_fetch_assoc($resultEstoque);

if ($estoque['quantidade'] <= 0) {

    header("Location: ../view/homeCliente.php");
    exit;
}

// verifica se já existe no carrinho
$sqlCheck = "
    SELECT *
    FROM carrinho
    WHERE id_cliente = $1
    AND id_produto = $2
";

$resultCheck = pg_query_params(
    $conn,
    $sqlCheck,
    [$idCliente, $idProduto]
);

if (pg_num_rows($resultCheck) > 0) {

    // aumenta quantidade
    $sqlUpdate = "
        UPDATE carrinho
        SET quantidade = quantidade + 1
        WHERE id_cliente = $1
        AND id_produto = $2
    ";

    pg_query_params(
        $conn,
        $sqlUpdate,
        [$idCliente, $idProduto]
    );

} else {

    // adiciona novo produto
    $sqlInsert = "
        INSERT INTO carrinho
        (id_cliente, id_produto, quantidade)
        VALUES ($1, $2, 1)
    ";

    pg_query_params(
        $conn,
        $sqlInsert,
        [$idCliente, $idProduto]
    );
}

$sqlUpdateEstoque = "
    UPDATE estoque
    SET quantidade = quantidade - 1
    WHERE id_produto = $1
";

pg_query_params(
    $conn,
    $sqlUpdateEstoque,
    [$idProduto]
);

header("Location: ../view/homeCliente.php");
exit;
?>