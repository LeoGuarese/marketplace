<?php
session_start();

include("../conexao.php");
include("../model/Produto.php");

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$quantidade = $_POST['quantidade'];
$preco = $_POST['preco'];
$imagem = $_FILES['imagem'];

$idFornecedor = $_SESSION['id'];

$produto = new Produto($conn);

$result = $produto->cadastrar(
    $nome,
    $descricao,
    $quantidade,
    $idFornecedor,
    $preco,
    $imagem
);

if ($result) {
    $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
} else {
    $_SESSION['erro'] = pg_last_error($conn);
}

header("Location: ../view/cadastroProduto.php");

?>