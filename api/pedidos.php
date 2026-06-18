<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Lidar com requisições OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Conexão com o banco de dados
$conn = pg_connect("host=localhost dbname=projetoWeb user=postgres password=123");

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro na conexão com o banco de dados'
    ]);
    exit;
}

// Função para buscar pedidos
function buscarPedidos($conn, $filtro = null, $tipo = null) {
    $params = [];
    $where = "WHERE 1=1";
    
    if ($filtro !== null && $tipo !== null) {
        if ($tipo === 'id') {
            // Buscar por número do pedido
            $where .= " AND pedido.id = $1";
            $params[] = (int)$filtro;
        } elseif ($tipo === 'cliente') {
            // Buscar por nome do cliente
            $where .= " AND cliente.nome ILIKE $1";
            $params[] = "%$filtro%";
        }
    }
    
    $sql = "
        SELECT
            pedido.id,
            pedido.data_pedido,
            pedido.valor_total,
            pedido.status,
            pedido.data_envio,
            pedido.data_cancelamento,
            cliente.id AS id_cliente,
            cliente.nome AS nome_cliente,
            cliente.email AS email_cliente,
            cliente.telefone AS telefone_cliente
        FROM pedido
        JOIN cliente ON pedido.id_cliente = cliente.id
        $where
        ORDER BY pedido.id DESC
    ";
    
    $result = pg_query_params($conn, $sql, $params);
    
    if (!$result) {
        return null;
    }
    
    $pedidos = [];
    while ($row = pg_fetch_assoc($result)) {
        $pedidos[] = $row;
    }
    
    return $pedidos;
}

// Função para buscar itens de um pedido
function buscarItensPedido($conn, $idPedido) {
    $sql = "
        SELECT
            item_pedido.id,
            produto.id AS id_produto,
            produto.nome AS nome_produto,
            produto.descricao,
            produto.imagem,
            item_pedido.quantidade,
            item_pedido.preco_unitario,
            (item_pedido.quantidade * item_pedido.preco_unitario) AS total_item
        FROM item_pedido
        JOIN produto ON item_pedido.id_produto = produto.id
        WHERE item_pedido.id_pedido = $1
        ORDER BY item_pedido.id
    ";
    
    $result = pg_query_params($conn, $sql, [$idPedido]);
    
    if (!$result) {
        return null;
    }
    
    $itens = [];
    while ($row = pg_fetch_assoc($result)) {
        $itens[] = $row;
    }
    
    return $itens;
}

try {
    // Verificar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Método não permitido. Use GET.'
        ]);
        exit;
    }
    
    // Verificar parâmetros
    $id = isset($_GET['id']) ? trim($_GET['id']) : null;
    $cliente = isset($_GET['cliente']) ? trim($_GET['cliente']) : null;
    $detalhar = isset($_GET['detalhar']) ? strtolower(trim($_GET['detalhar'])) === 'true' : false;
    
    $pedidos = [];
    
    // Se nenhum filtro foi fornecido, retorna todos os pedidos
    if ($id === null && $cliente === null) {
        $pedidos = buscarPedidos($conn);
    } elseif ($id !== null) {
        // Buscar por ID do pedido
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'ID do pedido deve ser um número'
            ]);
            exit;
        }
        $pedidos = buscarPedidos($conn, $id, 'id');
    } elseif ($cliente !== null) {
        // Buscar por nome do cliente
        if (strlen($cliente) < 2) {
            http_response_code(400);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Nome do cliente deve ter pelo menos 2 caracteres'
            ]);
            exit;
        }
        $pedidos = buscarPedidos($conn, $cliente, 'cliente');
    }
    
    // Se houver erro na busca
    if ($pedidos === null) {
        http_response_code(500);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao buscar pedidos'
        ]);
        exit;
    }
    
    // Se nenhum pedido encontrado
    if (empty($pedidos)) {
        http_response_code(404);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Nenhum pedido encontrado',
            'pedidos' => []
        ]);
        exit;
    }
    
    // Se detalhar foi solicitado, adicionar itens do pedido
    if ($detalhar) {
        foreach ($pedidos as &$pedido) {
            $itens = buscarItensPedido($conn, $pedido['id']);
            $pedido['itens'] = $itens !== null ? $itens : [];
        }
        unset($pedido);
    }
    
    // Resposta de sucesso
    http_response_code(200);
    echo json_encode([
        'sucesso' => true,
        'total' => count($pedidos),
        'pedidos' => $pedidos
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno do servidor: ' . $e->getMessage()
    ]);
}

pg_close($conn);
?>
