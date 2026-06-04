<?php
session_start();

include("../conexao.php");
include("../model/Pedido.php");

if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin') {
    header("Location: ../view/login1.php");
    exit;
}

$idPedido = $_POST['id_pedido'];
$status = $_POST['status'];

$pedidoModel = new Pedido($conn);

$pedidoModel->atualizarStatus($idPedido, $status);

header("Location: ../view/consultaPedidos.php");
exit;
?>