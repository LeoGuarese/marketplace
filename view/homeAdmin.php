<?php

session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin') {
    header("Location: login1.php");
    exit;
}

include("../conexao.php");
include("../model/Admin.php");

$admin = new Admin($conn);

$clientes = $admin->listarClientes();
$fornecedores = $admin->listarFornecedores();

?>

<h2>Clientes</h2>

<table border="1">

<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Email</th>
    <th>Telefone</th>
</tr>

<?php while ($cliente = pg_fetch_assoc($clientes)) { ?>

<tr>
    <td><?php echo $cliente['id']; ?></td>
    <td><?php echo $cliente['nome']; ?></td>
    <td><?php echo $cliente['email']; ?></td>
    <td><?php echo $cliente['telefone']; ?></td>
    <td>
    <a href="../controller/excluirUsuario.php?id=<?php echo $cliente['id']; ?>&tipo=cliente"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Excluir cliente?');">

        🗑️
    </a>
</td>
</tr>

<?php } ?>

</table>

<br><br>

<h2>Fornecedores</h2>

<table border="1">

<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Email</th>
    <th>Telefone</th>
</tr>

<?php while ($fornecedor = pg_fetch_assoc($fornecedores)) { ?>

<tr>
    <td><?php echo $fornecedor['id']; ?></td>
    <td><?php echo $fornecedor['nome']; ?></td>
    <td><?php echo $fornecedor['email']; ?></td>
    <td><?php echo $fornecedor['telefone']; ?></td>
    <td>
    <a href="../controller/excluirUsuario.php?id=<?php echo $fornecedor['id']; ?>&tipo=fornecedor"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Excluir fornecedor?');">

        🗑️

    </a>
</td>
</tr>

<?php } ?>

</table>