<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'fornecedor') {
    http_response_code(401);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Não autorizado']);
    exit;
}

$conn = pg_connect("host=localhost dbname=projetoWeb user=postgres password=123");

if (!$conn) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na conexão']);
    exit;
}

$idFornecedor = $_SESSION['id'];

// Buscar todos os pedidos que contêm produtos deste fornecedor
$sql = "
    SELECT DISTINCT
        pedido.id,
        pedido.data_pedido,
        pedido.valor_total,
        pedido.status,
        cliente.nome AS nome_cliente,
        cliente.email AS email_cliente,
        cliente.telefone AS telefone_cliente
    FROM pedido
    JOIN cliente ON pedido.id_cliente = cliente.id
    JOIN item_pedido ON pedido.id = item_pedido.id_pedido
    JOIN produto ON item_pedido.id_produto = produto.id
    WHERE produto.id_fornecedor = $1
    ORDER BY pedido.id DESC
";

$result = pg_query_params($conn, $sql, [$idFornecedor]);

if (!$result) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na consulta']);
    exit;
}

$pedidos = [];
while ($row = pg_fetch_assoc($result)) {
    $pedidos[] = $row;
}

echo json_encode([
    'sucesso' => true,
    'total' => count($pedidos),
    'pedidos' => $pedidos
]);

pg_close($conn);
?>
