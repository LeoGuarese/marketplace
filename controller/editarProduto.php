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

$produtoModel = new Produto($conn);

$produto = $produtoModel->buscarPorId(
    $idProduto,
    $idFornecedor
);

?>

<link rel="stylesheet" href="../styles/style.css">

<div class="form-container">

    <form action="updateProduto.php" method="POST">

        <h2>Editar Produto</h2>

        <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

        <scan>Nome</scan>
        <input type="text" name="nome" value="<?php echo $produto['nome']; ?>">

        <scan>Descrição</scan>
        <input type="text" name="descricao" value="<?php echo $produto['descricao']; ?>">

        <scan>Quantidade</scan>
        <input type="number" name="quantidade" value="<?php echo $produto['quantidade']; ?>">

        <button type="submit">
            Salvar
        </button>

    </form>

</div>