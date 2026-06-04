<?php
session_start();
include("../conexao.php");

if (!isset($_SESSION['id'])) {
    echo json_encode(["total" => 0]);
    exit;
}

$idCliente = $_SESSION['id'];

$sql = "
    SELECT SUM(produto.preco * carrinho.quantidade) AS total
    FROM carrinho
    JOIN produto ON carrinho.id_produto = produto.id
    WHERE carrinho.id_cliente = $1
";

$result = pg_query_params($conn, $sql, [$idCliente]);
$dados = pg_fetch_assoc($result);

$total = $dados['total'] ?? 0;

echo json_encode([
    "total" => number_format((float)$total, 2, ',', '.')
]);
?>