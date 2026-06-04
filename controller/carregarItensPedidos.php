<?php
session_start();

include("../conexao.php");
include("../model/Pedido.php");

if (!isset($_SESSION['id'])) {
    exit;
}

$idPedido = $_GET['id_pedido'];

$pedidoModel = new Pedido($conn);

$itens = $pedidoModel->buscarItensPedido($idPedido);
?>

<table class="table table-striped text-center align-middle">
    <thead class="table-dark">
        <tr>
            <th>Foto</th>
            <th>Descrição</th>
            <th>Quantidade</th>
            <th>Valor Unitário</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($item = pg_fetch_assoc($itens)) { ?>
            <tr>
                <td>
                    <img
                        src="../uploads/<?php echo $item['imagem']; ?>"
                        width="80"
                        height="80"
                        style="object-fit:cover;"
                    >
                </td>

                <td>
                    <strong><?php echo $item['nome']; ?></strong><br>
                    <?php echo $item['descricao']; ?>
                </td>

                <td><?php echo $item['quantidade']; ?></td>

                <td>
                    R$ <?php echo number_format((float)$item['preco_unitario'], 2, ',', '.'); ?>
                </td>

                <td>
                    R$ <?php echo number_format((float)$item['total_item'], 2, ',', '.'); ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>