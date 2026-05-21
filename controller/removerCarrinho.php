<?php
session_start();

include("../conexao.php");

if (!isset($_SESSION['id'])) {

    header("Location: ../view/login1.php");
    exit;
}

$idCarrinho = $_GET['id'];



$sqlBusca = "
    SELECT
        id_produto,
        quantidade
    FROM carrinho
    WHERE id = $1
";

$resultBusca = pg_query_params(
    $conn,
    $sqlBusca,
    [$idCarrinho]
);

$item = pg_fetch_assoc($resultBusca);

$idProduto = $item['id_produto'];
$quantidade = $item['quantidade'];



$sqlEstoque = "
    UPDATE estoque
    SET quantidade = quantidade + 1
    WHERE id_produto = $1
";

pg_query_params(
    $conn,
    $sqlEstoque,
    [$idProduto]
);



if ($quantidade > 1) {

    $sqlUpdate = "
        UPDATE carrinho
        SET quantidade = quantidade - 1
        WHERE id = $1
    ";

    pg_query_params(
        $conn,
        $sqlUpdate,
        [$idCarrinho]
    );

} else {

   
    $sqlDelete = "
        DELETE FROM carrinho
        WHERE id = $1
    ";

    pg_query_params(
        $conn,
        $sqlDelete,
        [$idCarrinho]
    );
}

header("Location: ../view/carrinho.php");
exit;
?>