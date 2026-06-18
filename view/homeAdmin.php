<?php

session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin') {
    header("Location: login1.php");
    exit;
}

include("../conexao.php");
include("../model/Admin.php");

$admin = new Admin($conn);

$limite = 5;

$paginaClientes = $_GET['paginaClientes'] ?? 1;
$paginaFornecedores = $_GET['paginaFornecedores'] ?? 1;

$paginaClientes = max(1, (int) $paginaClientes);
$paginaFornecedores = max(1, (int) $paginaFornecedores);

$offsetClientes = ($paginaClientes - 1) * $limite;
$offsetFornecedores = ($paginaFornecedores - 1) * $limite;

$totalClientes = $admin->contarClientes();
$totalFornecedores = $admin->contarFornecedores();

$totalPaginasClientes = ceil($totalClientes / $limite);
$totalPaginasFornecedores = ceil($totalFornecedores / $limite);

$clientes = $admin->listarClientesPaginado($limite, $offsetClientes);
$fornecedores = $admin->listarFornecedoresPaginado($limite, $offsetFornecedores);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/homeAdmin.css">
</head>
<body>

    <!-- Header -->
    <div class="header-section">
        <div class="container">
            <div class="header-content">
                <div>
                    <h1>Painel do Administrador</h1>
                    <p>Gerenciar clientes, fornecedores e pedidos</p>
                </div>
                <div>
                    <a href="cadastrar.html" class="btn-back">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <!-- Botões de Ação -->
        <div class="btn-group-actions">
            <a href="consultaPedidos.php" class="btn btn-primary">
                Consultar Pedidos
            </a>
        </div>

        <!-- Grid com 2 colunas -->
        <div class="grid-container">

            <!-- Card Clientes -->
            <div class="card">
                <div class="card-header-custom">
                    Gerenciar Clientes
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($cliente = pg_fetch_assoc($clientes)) { ?>
                                    <tr>
                                        <td><strong>#<?php echo $cliente['id']; ?></strong></td>
                                        <td><?php echo $cliente['nome']; ?></td>
                                        <td><?php echo $cliente['email']; ?></td>
                                        <td><?php echo $cliente['telefone']; ?></td>
                                        <td>
                                            <a href="../controller/excluirUsuario.php?id=<?php echo $cliente['id']; ?>&tipo=cliente"
                                                class="btn-delete" 
                                                onclick="return confirm('Tem certeza que deseja excluir este cliente?');">
                                                Excluir
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação Clientes -->
                    <?php if ($totalPaginasClientes > 1) { ?>
                        <nav>
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $totalPaginasClientes; $i++) { ?>
                                    <li class="page-item <?php echo ($i == $paginaClientes) ? 'active' : ''; ?>">
                                        <a class="page-link"
                                            href="?paginaClientes=<?php echo $i; ?>&paginaFornecedores=<?php echo $paginaFornecedores; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>
                    <?php } ?>
                </div>
            </div>

            <!-- Card Fornecedores -->
            <div class="card">
                <div class="card-header-custom">
                    Gerenciar Fornecedores
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($fornecedor = pg_fetch_assoc($fornecedores)) { ?>
                                    <tr>
                                        <td><strong>#<?php echo $fornecedor['id']; ?></strong></td>
                                        <td><?php echo $fornecedor['nome']; ?></td>
                                        <td><?php echo $fornecedor['email']; ?></td>
                                        <td><?php echo $fornecedor['telefone']; ?></td>
                                        <td>
                                            <a href="../controller/excluirUsuario.php?id=<?php echo $fornecedor['id']; ?>&tipo=fornecedor"
                                                class="btn-delete"
                                                onclick="return confirm('Tem certeza que deseja excluir este fornecedor?');">
                                                Excluir
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação Fornecedores -->
                    <?php if ($totalPaginasFornecedores > 1) { ?>
                        <nav>
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $totalPaginasFornecedores; $i++) { ?>
                                    <li class="page-item <?php echo ($i == $paginaFornecedores) ? 'active' : ''; ?>">
                                        <a class="page-link"
                                            href="?paginaClientes=<?php echo $paginaClientes; ?>&paginaFornecedores=<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>
                    <?php } ?>
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>