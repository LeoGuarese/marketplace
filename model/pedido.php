<?php

class Pedido
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function finalizarPedido($idCliente)
    {

        $sqlCarrinho = "
            SELECT
                carrinho.id_produto,
                carrinho.quantidade,
                produto.preco
            FROM carrinho
            JOIN produto ON carrinho.id_produto = produto.id
            WHERE carrinho.id_cliente = $1
        ";

        $resultCarrinho = pg_query_params($this->conn, $sqlCarrinho, [$idCliente]);

        if (!$resultCarrinho || pg_num_rows($resultCarrinho) == 0) {
            return false;
        }

        $total = 0;
        $itens = [];

        while ($item = pg_fetch_assoc($resultCarrinho)) {
            $subtotal = $item['preco'] * $item['quantidade'];
            $total += $subtotal;
            $itens[] = $item;
        }

        $sqlPedido = "
            INSERT INTO pedido (id_cliente, valor_total)
            VALUES ($1, $2)
            RETURNING id
        ";

        $resultPedido = pg_query_params($this->conn, $sqlPedido, [$idCliente, $total]);

        if (!$resultPedido) {
            return false;
        }

        $pedido = pg_fetch_assoc($resultPedido);
        $idPedido = $pedido['id'];

        foreach ($itens as $item) {
            $sqlItem = "
                INSERT INTO item_pedido
                (id_pedido, id_produto, quantidade, preco_unitario)
                VALUES ($1, $2, $3, $4)
            ";

            $resultItem = pg_query_params($this->conn, $sqlItem, [
                $idPedido,
                $item['id_produto'],
                $item['quantidade'],
                $item['preco']
            ]);

            if (!$resultItem) {
                return false;
            }
        }

        $sqlLimparCarrinho = "
            DELETE FROM carrinho
            WHERE id_cliente = $1
        ";

        $resultLimpar = pg_query_params($this->conn, $sqlLimparCarrinho, [$idCliente]);

        if (!$resultLimpar) {
            return false;
        }

        return true;
    }

    public function listarPedidos($busca = '', $limite = 5, $offset = 0, $idCliente = null)
    {

        $params = [];
        $where = "WHERE 1=1";

        if ($idCliente != null) {
            $where .= " AND pedido.id_cliente = $" . (count($params) + 1);
            $params[] = $idCliente;
        }

        if ($busca != '') {
            $where .= " AND (CAST(pedido.id AS TEXT) ILIKE $" . (count($params) + 1);
            $params[] = "%$busca%";

            $where .= " OR cliente.nome ILIKE $" . (count($params) + 1) . ")";
            $params[] = "%$busca%";
        }

        $params[] = $limite;
        $params[] = $offset;

        $sql = "
        SELECT
            pedido.id,
            pedido.data_pedido,
            pedido.valor_total,
            pedido.status,
            pedido.data_envio,
            pedido.data_cancelamento,
            cliente.nome AS nome_cliente
        FROM pedido
        JOIN cliente ON pedido.id_cliente = cliente.id
        $where
        ORDER BY pedido.id DESC
        LIMIT $" . (count($params) - 1) . "
        OFFSET $" . count($params);

        return pg_query_params($this->conn, $sql, $params);
    }

    public function contarPedidos($busca = '', $idCliente = null)
    {

        $params = [];
        $where = "WHERE 1=1";

        if ($idCliente != null) {
            $where .= " AND pedido.id_cliente = $" . (count($params) + 1);
            $params[] = $idCliente;
        }

        if ($busca != '') {
            $where .= " AND (CAST(pedido.id AS TEXT) ILIKE $" . (count($params) + 1);
            $params[] = "%$busca%";

            $where .= " OR cliente.nome ILIKE $" . (count($params) + 1) . ")";
            $params[] = "%$busca%";
        }

        $sql = "
        SELECT COUNT(*) AS total
        FROM pedido
        JOIN cliente ON pedido.id_cliente = cliente.id
        $where
    ";

        $result = pg_query_params($this->conn, $sql, $params);
        $dados = pg_fetch_assoc($result);

        return $dados['total'];
    }

    public function buscarItensPedido($idPedido)
    {

        $sql = "
        SELECT
            produto.nome,
            produto.descricao,
            produto.imagem,
            item_pedido.quantidade,
            item_pedido.preco_unitario,
            (item_pedido.quantidade * item_pedido.preco_unitario) AS total_item
        FROM item_pedido
        JOIN produto ON item_pedido.id_produto = produto.id
        WHERE item_pedido.id_pedido = $1
    ";

        return pg_query_params($this->conn, $sql, [$idPedido]);
    }

    public function atualizarStatus($idPedido, $status)
    {

        if ($status == 'enviado') {
            $sql = "
            UPDATE pedido
            SET status = $1,
                data_envio = CURRENT_TIMESTAMP,
                data_cancelamento = NULL
            WHERE id = $2
        ";
        } else if ($status == 'cancelado') {
            $sql = "
            UPDATE pedido
            SET status = $1,
                data_cancelamento = CURRENT_TIMESTAMP
            WHERE id = $2
        ";
        } else {
            $sql = "
            UPDATE pedido
            SET status = $1
            WHERE id = $2
        ";
        }

        return pg_query_params($this->conn, $sql, [$status, $idPedido]);
    }

    
}
?>