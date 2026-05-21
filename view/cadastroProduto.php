<?php session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login1.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">


    <title>Cadastro Produto</title>
</head>

<body>
    <a href="homeFornecedor.php" class="btn-voltar">← Voltar</a>

    <div class="form-container">
        <h2>Cadastro Produto</h2>
        <?php
        if (isset($_SESSION['sucesso'])) {
            echo "<p style='color:green'>" . $_SESSION['sucesso'] . "</p>";
            unset($_SESSION['sucesso']);
        }
        if (isset($_SESSION['erro'])) {
            echo "<p style='color:red'>" . $_SESSION['erro'] . "</p>";
            unset($_SESSION['erro']);
        }
        ?>
        <form action="../controller/salvarProduto.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="nome" placeholder="Nome Produto" required>
            <input type="text" name="descricao" min="1" max="100" placeholder="Descrição" required>
            <input type="number" name="quantidade" placeholder="Quantidade" required>
            <input type="number" name="preco" step="0.01" placeholder="Preço" required>
            <input type="file" name="imagem" required>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>

</html>