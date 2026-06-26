<?php
session_start();

include("../conexao.php");
include("../model/Pedido.php");

if (!isset($_SESSION['id'])) {
    $_SESSION['erro'] = "Faça login para finalizar o pedido.";
    header("Location: ../view/login1.php");
    exit;
}

$idCliente = $_SESSION['id'];

$pedido = new Pedido($conn);

$result = $pedido->finalizarPedido($idCliente);

if ($result) {
    $_SESSION['sucesso'] = "Pedido feito com sucesso!";
} else {
    $_SESSION['erro'] = "Não foi possível finalizar o pedido.";
}

header("Location: ../view/carrinho.php");
exit;
?>