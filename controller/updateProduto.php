<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../view/login.php");
    exit;
}

include("../conexao.php");
include("../model/Produto.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$quantidade = $_POST['quantidade'];

$idFornecedor = $_SESSION['id'];

$produto = new Produto($conn);

$produto->atualizar(
    $id,
    $nome,
    $descricao,
    $quantidade,
    $idFornecedor
);

header("Location: ../view/GerenciamentoEstoque.php");
exit;

?>