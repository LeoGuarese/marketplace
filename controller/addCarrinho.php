<?php
session_start();

include("../conexao.php");

if (!isset($_SESSION['id'])) {
    header("Location: ../view/login1.php");
    exit;
}

$idCarrinho = $_GET['id'];
$idCliente = $_SESSION['id'];

// Busca o item do carrinho e o estoque disponível
$sqlBusca = "
    SELECT
        carrinho.id_produto,
        estoque.quantidade AS estoque_disponivel
    FROM carrinho

    JOIN estoque
    ON carrinho.id_produto = estoque.id_produto

    WHERE carrinho.id = $1
    AND carrinho.id_cliente = $2
";

$resultBusca = pg_query_params(
    $conn,
    $sqlBusca,
    [$idCarrinho, $idCliente]
);

if (!$resultBusca || pg_num_rows($resultBusca) == 0) {
    header("Location: ../view/carrinho.php");
    exit;
}

$item = pg_fetch_assoc($resultBusca);

$idProduto = $item['id_produto'];
$estoqueDisponivel = (int)$item['estoque_disponivel'];

// Se não tem estoque, não adiciona
if ($estoqueDisponivel <= 0) {
    $_SESSION['erro'] = "Não há mais unidades disponíveis em estoque.";
    header("Location: ../view/carrinho.php");
    exit;
}

// Aumenta quantidade no carrinho
$sqlCarrinho = "
    UPDATE carrinho
    SET quantidade = quantidade + 1
    WHERE id = $1
    AND id_cliente = $2
";

$resultCarrinho = pg_query_params(
    $conn,
    $sqlCarrinho,
    [$idCarrinho, $idCliente]
);

if (!$resultCarrinho) {
    $_SESSION['erro'] = "Erro ao atualizar carrinho.";
    header("Location: ../view/carrinho.php");
    exit;
}

// Diminui estoque
$sqlEstoque = "
    UPDATE estoque
    SET quantidade = quantidade - 1
    WHERE id_produto = $1
";

$resultEstoque = pg_query_params(
    $conn,
    $sqlEstoque,
    [$idProduto]
);

if (!$resultEstoque) {
    $_SESSION['erro'] = "Erro ao atualizar estoque.";
}

header("Location: ../view/carrinho.php");
exit;
?>