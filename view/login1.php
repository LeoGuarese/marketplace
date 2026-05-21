<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <a href="cadastrar.html" class="btn-voltar">← Voltar</a>
    <div class="form-container">
    <h2>LOGIN</h2>

    <?php
    if (isset($_SESSION['erro'])) {
        echo "<p style='color:red'>" . $_SESSION['erro'] . "</p>";
        unset($_SESSION['erro']);
    }
    ?>

    <form action="../controller/login.php" method="POST">
        <input type="email" name="email" placeholder="email@gmail.com" required>
        <input type="password" name="senha" placeholder="Senha" required>

        <div class="tipo">
            <label>
                <input type="radio" name="tipo" value="cliente" required> Cliente
            </label>

            <label>
                <input type="radio" name="tipo" value="fornecedor" required> Fornecedor
            </label>
        </div>

        <button type="submit">Entrar</button>
    </form>

</div>
</body>
</html>