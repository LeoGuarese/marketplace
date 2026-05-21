<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../view/login.php");
    exit;
}

include("../conexao.php");
include("../model/Produto.php");

$idProduto = $_GET['id'];
$idFornecedor = $_SESSION['id'];

$produto = new Produto($conn);

$produto->excluir(
    $idProduto,
    $idFornecedor
);

header("Location: ../view/GerenciamentoEstoque.php");
exit;

?>