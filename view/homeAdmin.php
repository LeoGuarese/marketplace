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

<head>
    <link rel="stylesheet" href="../styles/styleHomeCliente.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="text-center mb-4">
    <a href="consultaPedidos.php" class="btn btn-primary">
        Consultar Pedidos
    </a>
</div>

<div class="container mt-5">

    <div class="card shadow p-4 mb-5">
        <h2 class="text-center mb-4">Clientes</h2>

        <table class="table table-striped table-hover text-center align-middle">
            <thead class="table-dark">
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
                        <td><?php echo $cliente['id']; ?></td>
                        <td><?php echo $cliente['nome']; ?></td>
                        <td><?php echo $cliente['email']; ?></td>
                        <td><?php echo $cliente['telefone']; ?></td>
                        <td>
                            <a href="../controller/excluirUsuario.php?id=<?php echo $cliente['id']; ?>&tipo=cliente"
                                class="btn btn-danger btn-sm" onclick="return confirm('Excluir cliente?');">
                                🗑️
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <nav class="d-flex justify-content-center">
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
    </div>


    <div class="card shadow p-4">
        <h2 class="text-center mb-4">Fornecedores</h2>

        <table class="table table-striped table-hover text-center align-middle">
            <thead class="table-dark">
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
                        <td><?php echo $fornecedor['id']; ?></td>
                        <td><?php echo $fornecedor['nome']; ?></td>
                        <td><?php echo $fornecedor['email']; ?></td>
                        <td><?php echo $fornecedor['telefone']; ?></td>
                        <td>
                            <a href="../controller/excluirUsuario.php?id=<?php echo $fornecedor['id']; ?>&tipo=fornecedor"
                                class="btn btn-danger btn-sm" onclick="return confirm('Excluir fornecedor?');">
                                🗑️
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <nav class="d-flex justify-content-center">
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
    </div>

</div>

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