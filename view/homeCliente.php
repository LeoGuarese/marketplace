<?php
session_start();

include("../conexao.php");
include("../model/Produto.php");

$produtoModel = new Produto($conn);

$result = $produtoModel->listarTodosProdutos();


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
                            style="height: 200px; object-fit: cover;">

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
    </div>
</body>

</html>