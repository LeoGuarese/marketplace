<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<nav class="navbar navbar-expand-sm navbar-custom fixed-top">

    <div class="container-fluid">
        <ul class="navbar-nav">
            <a class="navbar-brand">Logo</a>
            <li class="nav-item">
                <a class="nav-link" href="../view/homeCliente.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../view/carrinho.php">Carrinho</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../view/consultaPedidos.php">Meus Pedidos</a>
            </li>
        </ul>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <?php if (isset($_SESSION['nome'])) { ?>

                    <p>
                        Olá, <?php echo $_SESSION['nome']; ?>
                    </p>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../controller/logout.php">Sair</a></li>
                    </ul>

                <?php } else { ?>

                    <p>
                        Olá, visitante
                    </p>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../view/cadastrar.html">Sair</a></li>
                    </ul>

                <?php } ?>
            </a>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="../controller/logout.php">Sair</a></li>
            </ul>
        </li>
    </div>

</nav>