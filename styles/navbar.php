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
            <a class="nav-link" href="../view/homeFornecedor.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../view/cadastroProduto.php">Cadastro Produto</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../view/gerenciamentoEstoque.php">Gerenciamento Estoque</a>
        </li>
        </ul>
        
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Olá, <?php echo $_SESSION['nome']; ?>
            </a>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="../controller/logout.php">Sair</a></li>
            </ul>
        </li>
    </div>

    </nav>