<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login1.php");
    exit;
}

include("../conexao.php");
include("../model/Produto.php");

$idFornecedor = $_SESSION['id'];
$busca = $_GET['busca'] ?? '';

$produtoModel = new Produto($conn);

$pagina = $_GET['pagina'] ?? 1;

$limite = 5;

$offset = ($pagina - 1) * $limite;

$busca = $_GET['busca'] ?? '';

$result = $produtoModel->listarPaginado(
    $idFornecedor,
    $limite,
    $offset,
    $busca
);

$totalProdutos = $produtoModel->contarProdutos(
    $idFornecedor,
    $busca
);

$totalPaginas = ceil(
    $totalProdutos / $limite
);

?>

<?php include("../styles/navbar.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <title>Estoque</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../styles/styleHomeFornecedor.css">
</head>

<body>

<div class="container mt-4">

    <h2>Gerenciamento de Estoque</h2>

    <form method="GET" class="mb-3">

        <div class="input-group">

            <input type="text" name="busca" class="form-control" placeholder="Buscar produto..." value="<?php echo $_GET['busca'] ?? ''; ?>">
            <button class="btn btn-primary" type="submit">
                Procurar
            </button>

        </div>

    </form>

    <table class="table table-striped table-hover mt-3">

        <thead class="table-dark">

            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody class="table-warning">
        <?php while ($row = pg_fetch_assoc($result)) { ?>

            <tr>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['descricao']; ?></td>
                <td><?php echo $row['quantidade']; ?></td>
                <td>
                    <a href="../controller/editarProduto.php?id=<?php echo $row['id']; ?>"
                       class="btn btn-success btn-sm">
                        ✏️
                    </a>
                    <a href="../controller/excluirProduto.php?id=<?php echo $row['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Tem certeza que deseja excluir?');">
                        🗑️
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>

    <div class="mt-3">

        <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>

            <a href="?pagina=<?php echo $i; ?>&busca=<?php echo $busca; ?>"
               class="btn btn-primary btn-sm">

                <?php echo $i; ?>

            </a>

        <?php } ?>

    </div>

</div>

</body>
</html>