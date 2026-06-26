<?php
session_start();

include("../conexao.php");
include("../model/Produto.php");

$produtoModel = new Produto($conn);

$limite = 8;
$pagina = $_GET['pagina'] ?? 1;
$pagina = max(1, (int) $pagina);

$offset = ($pagina - 1) * $limite;

$totalProdutos = $produtoModel->contarTodosProdutos();
$totalPaginas = ceil($totalProdutos / $limite);

$result = $produtoModel->listarTodosProdutosPaginado($limite, $offset);
?>

<?php include("../styles/navbarCliente.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Inicial</title>
    <link rel="stylesheet" href="../styles/styleHomeCliente.css">
</head>

<body>
    <div class="container mt-4">
        <div class="row">

            <?php while ($produto = pg_fetch_assoc($result)) { ?>

                <div class="col-md-3 mb-4">

                    <div class="card h-100 shadow">

                        <img src="../uploads/<?php echo $produto['imagem']; ?>" class="card-img-top"
                            style="width: 100%; height: 220px; object-fit: contain; background: #f8f9fa;">

                        <div class="card-body">

                            <h5 class="card-title">
                                <?php echo $produto['nome']; ?>
                            </h5>

                            <p class="card-text">
                                <?php echo $produto['descricao']; ?>
                            </p>

                            <p>
                                Estoque:
                                <?php echo $produto['quantidade']; ?>
                            </p>

                            <p class="fw-bold text-success">
                                R$
                                <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </p>

                            <?php if ($produto['quantidade'] > 0) { ?>

                                <form action="../controller/adicionarCarrinho.php" method="POST">

                                    <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">

                                    <button type="submit" class="btn btn-dark w-100">
                                        🛒 Adicionar ao Carrinho
                                    </button>

                                </form>

                            <?php } else { ?>

                                <button class="btn btn-secondary w-100" disabled>
                                    Produto Indisponível
                                </button>

                            <?php } ?>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>
        <nav class="d-flex justify-content-center mt-4">
            <ul class="pagination">

                <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                    <li class="page-item <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php } ?>

            </ul>
        </nav>
    </div>
</body>

</html>