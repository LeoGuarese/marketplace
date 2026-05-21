<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <a href="cadastrar.html" class="btn-voltar">← Voltar</a>
    <div class="form-container">
    <h2>Cadastro Cliente</h2>

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

    <form action="../controller/salvar.php" method="POST">
        <input type="hidden" name="tipo" value="cliente">
        <input type="text" name="nome" placeholder="Usuário" required>
        <input type="email" name="email" placeholder="email@gmail.com" required>
        <input type="tel" id="telefone" name="telefone" placeholder="(01)23456789" pattern="([0-9]{2})[0-9]{9}" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Cadastrar</button>
    </form>

</div>
</body>
</html>