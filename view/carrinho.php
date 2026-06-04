<?php
session_start();

if (!isset($_SESSION['id'])) {

    header("Location: login1.php");
    exit;
}

include("../conexao.php");
include("../styles/navbarCliente.php");

$idCliente = $_SESSION['id'];

$sql = "
    SELECT
        carrinho.id,
        produto.nome,
        produto.descricao,
        produto.imagem,
        carrinho.quantidade
    FROM carrinho

    JOIN produto
    ON carrinho.id_produto = produto.id

    WHERE carrinho.id_cliente = $1
";

$result = pg_query_params(
    $conn,
    $sql,
    [$idCliente]
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Carrinho</title>

    <link rel="stylesheet" href="../styles/styleHomeCliente.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<script>
    function calcularTotalCarrinho() {
        fetch("../controller/calcularTotalCarrinho.php")
            .then(response => response.json())
            .then(data => {
                document.getElementById("totalCarrinho").innerText = data.total;
            })
            .catch(error => {
                console.error("Erro ao calcular total:", error);
            });
    }

    calcularTotalCarrinho();
</script>


<body style="background: #E5EEE4;">

    <div class="container mt-5">

        <h2 class="mb-4">
            Meu Carrinho
        </h2>

        <?php if (pg_num_rows($result) > 0) { ?>

            <div class="row">

                <?php while ($item = pg_fetch_assoc($result)) { ?>

                    <div class="col-md-4 mb-4">

                        <div class="card shadow h-100">

                            <img src="../uploads/<?php echo $item['imagem']; ?>" class="card-img-top" style="
                            height: 250px;
                            object-fit: cover;
                        ">

                            <div class="card-body">

                                <h5 class="card-title">
                                    <?php echo $item['nome']; ?>
                                </h5>

                                <p class="card-text">
                                    <?php echo $item['descricao']; ?>
                                </p>

                                <p>
                                    Quantidade:
                                    <?php echo $item['quantidade']; ?>
                                </p>

                                <a href="../controller/removerCarrinho.php?id=<?php echo $item['id']; ?>"
                                    class="btn btn-danger w-100" onclick="return confirm('Remover item?');">
                                    Remover
                                </a>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

        <?php } else { ?>

            <div class="alert alert-warning">
                Seu carrinho está vazio.
            </div>

        <?php } ?>

    </div>

    <div class="card shadow p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Total: R$ <span id="totalCarrinho">0,00</span></h4>

            <a href="../controller/finalizarPedido.php" class="btn btn-success">
                Finalizar Pedido
            </a>
        </div>
    </div>

</body>

<?php
if (isset($_SESSION['sucesso'])) {
    ?>
    <div class="alert alert-success text-center mt-3">
        <?php
        echo $_SESSION['sucesso'];
        unset($_SESSION['sucesso']);
        ?>
    </div>
    <?php
}

if (isset($_SESSION['erro'])) {
    ?>
    <div class="alert alert-danger text-center mt-3">
        <?php
        echo $_SESSION['erro'];
        unset($_SESSION['erro']);
        ?>
    </div>
    <?php
}
?>

</html>