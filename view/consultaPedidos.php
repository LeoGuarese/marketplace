<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login1.php");
    exit;
}

include("../conexao.php");
include("../model/Pedido.php");

$pedidoModel = new Pedido($conn);

$busca = $_GET['busca'] ?? '';
$pagina = $_GET['pagina'] ?? 1;
$pagina = max(1, (int)$pagina);

$limite = 5;
$offset = ($pagina - 1) * $limite;

$idCliente = null;

if ($_SESSION['tipo'] == 'cliente') {
    $idCliente = $_SESSION['id'];
}

$total = $pedidoModel->contarPedidos($busca, $idCliente);
$totalPaginas = ceil($total / $limite);

$pedidos = $pedidoModel->listarPedidos($busca, $limite, $offset, $idCliente);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#E5EEE4;">

<div class="container mt-5">

    <div class="card shadow p-4">

        <h2 class="text-center mb-4">Consulta de Pedidos</h2>

        <form method="GET" class="mb-4">
            <div class="input-group">
                <input
                    type="text"
                    name="busca"
                    class="form-control"
                    placeholder="Buscar por número do pedido ou nome do cliente"
                    value="<?php echo $busca; ?>"
                >

                <button class="btn btn-primary" type="submit">
                    Procurar
                </button>
            </div>
        </form>

        <?php while ($pedido = pg_fetch_assoc($pedidos)) { ?>

            <div class="card mb-3 shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>
                        <strong>Pedido #<?php echo $pedido['id']; ?></strong><br>
                        Cliente: <?php echo $pedido['nome_cliente']; ?><br>
                        Data: <?php echo $pedido['data_pedido']; ?><br>
                        Total: R$ <?php echo number_format((float)$pedido['valor_total'], 2, ',', '.'); ?><br>
                        Status: <strong><?php echo $pedido['status']; ?></strong>
                    </div>

                    <div>
                        <?php if ($_SESSION['tipo'] == 'admin') { ?>
                            <form action="../controller/alterarStatusPedido.php" method="POST" class="mb-2">
                                <input type="hidden" name="id_pedido" value="<?php echo $pedido['id']; ?>">

                                <select name="status" class="form-select mb-2">
                                    <option value="pendente">Pendente</option>
                                    <option value="enviado">Enviado</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>

                                <button class="btn btn-warning btn-sm" type="submit">
                                    Alterar Status
                                </button>
                            </form>
                        <?php } ?>

                        <button
                            class="btn btn-info btn-sm"
                            onclick="carregarItens(<?php echo $pedido['id']; ?>)"
                        >
                            Ver Detalhes
                        </button>
                    </div>
                </div>

                <div class="card-body" id="detalhes-<?php echo $pedido['id']; ?>" style="display:none;">
                    Carregando...
                </div>

            </div>

        <?php } ?>

        <nav class="d-flex justify-content-center mt-4">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                    <li class="page-item <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>&busca=<?php echo $busca; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>

    </div>

</div>

<script>
function carregarItens(idPedido) {
    const div = document.getElementById("detalhes-" + idPedido);

    if (div.style.display === "block") {
        div.style.display = "none";
        return;
    }

    fetch("../controller/carregarItensPedidos.php?id_pedido=" + idPedido)
        .then(response => response.text())
        .then(html => {
            div.innerHTML = html;
            div.style.display = "block";
        });
}
</script>

</body>
</html>