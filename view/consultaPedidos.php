<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login1.php");
    exit;
}

$tipoUsuario = $_SESSION['tipo'];
$idUsuario = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #E5EEE4;
            font-family: Arial, sans-serif;
            padding-bottom: 30px;
        }

        .header-section {
            background: linear-gradient(135deg, #DC9B9B 0%, #d88585 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .header-section h1 {
            margin: 0;
            font-weight: bold;
            font-size: 2.5rem;
        }

        .header-section p {
            margin: 0;
            opacity: 0.95;
            font-size: 1.1rem;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .search-section {
            margin-bottom: 25px;
        }

        .search-section .input-group {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .search-section .input-group input {
            border: none;
            padding: 12px;
        }

        .search-section .input-group button {
            background: linear-gradient(135deg, #DC9B9B 0%, #d88585 100%);
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
            padding: 12px 30px;
        }

        .search-section .input-group button:hover {
            background: #d88585;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 25px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #DC9B9B 0%, #d88585 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
            font-weight: bold;
            border: none !important;
        }

        .card-header strong {
            display: block;
            margin-bottom: 5px;
        }

        .pedido-info {
            padding: 20px;
        }

        .pedido-info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
        }

        .info-label {
            font-weight: 600;
            color: #333;
        }

        .info-value {
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-pendente {
            background: #fff3cd;
            color: #856404;
        }

        .status-enviado {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelado {
            background: #f8d7da;
            color: #721c24;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        .btn-detalhes {
            background: #0dcaf0;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-detalhes:hover {
            background: #0ba5dc;
            transform: scale(1.05);
            color: white;
        }

        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            padding: 8px;
        }

        .btn-status {
            background: #ffc107;
            color: #333;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 600;
        }

        .btn-status:hover {
            background: #e0a800;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #DC9B9B;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 1.1rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pagination .page-link {
            color: #DC9B9B;
            border-color: #DC9B9B;
            transition: all 0.2s;
        }

        .pagination .page-link:hover {
            background-color: #DC9B9B;
            color: white;
        }

        .pagination .page-item.active .page-link {
            background-color: #DC9B9B;
            border-color: #DC9B9B;
        }

        .detalhes-content {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .detalhes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .detalhes-table thead {
            background: #f0f0f0;
        }

        .detalhes-table th,
        .detalhes-table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 1.8rem;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .pedido-info-row {
                grid-template-columns: 1fr;
            }

            .card-actions {
                flex-direction: column;
            }

            .btn-detalhes,
            .btn-status {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header-section">
        <div class="container">
            <div class="header-content">
                <div>
                    <h1>Consulta de Pedidos</h1>
                    <p>Visualize e gerencie seus pedidos</p>
                </div>
                <div>
                    <button class="btn-back" onclick="history.back();">Voltar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <!-- Barra de Pesquisa -->
        <div class="search-section">
            <form id="formBusca" onsubmit="buscarPedidos(event)">
                <div class="input-group">
                    <input
                        type="text"
                        id="inputBusca"
                        name="busca"
                        class="form-control"
                        placeholder="Buscar por número do pedido ou nome do cliente"
                    >
                    <button class="btn" type="submit">Procurar</button>
                </div>
            </form>
        </div>

        <!-- Indicador de carregamento -->
        <div id="carregando" style="display: none; text-align: center; padding: 40px;">
            <div class="loading-spinner"></div>
            <p class="mt-3">Carregando pedidos...</p>
        </div>

        <!-- Container de pedidos -->
        <div id="containerPedidos"></div>

        <!-- Paginação -->
        <nav id="paginacao" style="display: none;">
            <ul class="pagination" id="ulPaginacao"></ul>
        </nav>

        <!-- Mensagem sem resultados -->
        <div id="semResultados" class="no-results" style="display: none;">
            Nenhum pedido encontrado
        </div>

    </div>

<script>
    const tipoUsuario = '<?php echo $tipoUsuario; ?>';
    const idUsuario = <?php echo $idUsuario; ?>;
    const limiteItens = 5;
    let todosOsPedidos = [];
    let paginaAtual = 1;

    // Carregar pedidos ao iniciar a página
    window.addEventListener('load', () => {
        buscarPedidos(null, true);
    });

    // Obter os parâmetros da URL
    function obterParametrosUrl() {
        const params = new URLSearchParams(window.location.search);
        return {
            busca: params.get('busca') || '',
            pagina: parseInt(params.get('pagina')) || 1
        };
    }

    // Buscar pedidos da API
    async function buscarPedidos(event, carregarDoUrl = false) {
        if (event) event.preventDefault();

        const params = obterParametrosUrl();
        const busca = carregarDoUrl ? params.busca : document.getElementById('inputBusca').value;
        paginaAtual = carregarDoUrl ? params.pagina : 1;

        document.getElementById('inputBusca').value = busca;
        document.getElementById('carregando').style.display = 'block';
        document.getElementById('containerPedidos').innerHTML = '';
        document.getElementById('paginacao').style.display = 'none';
        document.getElementById('semResultados').style.display = 'none';

        try {
            let url = '../api/pedidos.php';

            if (busca) {
                // Tentar primeiro por ID
                if (/^\d+$/.test(busca)) {
                    url += '?id=' + encodeURIComponent(busca);
                } else {
                    // Se não for número, buscar por cliente
                    url += '?cliente=' + encodeURIComponent(busca);
                }
            }

            const response = await fetch(url);
            const data = await response.json();

            document.getElementById('carregando').style.display = 'none';

            if (!data.sucesso || !data.pedidos || data.pedidos.length === 0) {
                document.getElementById('semResultados').style.display = 'block';
                document.getElementById('paginacao').style.display = 'none';
                return;
            }

            // Se for cliente, filtrar apenas seus pedidos
            if (tipoUsuario === 'cliente') {
                todosOsPedidos = data.pedidos.filter(p => parseInt(p.id_cliente) === idUsuario);
            } else {
                todosOsPedidos = data.pedidos;
            }

            // Renderizar página atual
            renderizarPagina(paginaAtual);

            // Atualizar URL
            const novaUrl = new URL(window.location);
            novaUrl.searchParams.set('busca', busca);
            novaUrl.searchParams.set('pagina', paginaAtual);
            window.history.pushState({}, '', novaUrl);

        } catch (error) {
            document.getElementById('carregando').style.display = 'none';
            document.getElementById('containerPedidos').innerHTML = '<div class="alert alert-danger">Erro ao carregar pedidos: ' + error.message + '</div>';
        }
    }

    // Renderizar página de pedidos
    function renderizarPagina(pagina) {
        const inicio = (pagina - 1) * limiteItens;
        const fim = inicio + limiteItens;
        const pedidosPagina = todosOsPedidos.slice(inicio, fim);

        const container = document.getElementById('containerPedidos');
        container.innerHTML = '';

        pedidosPagina.forEach(pedido => {
            const dataFormatada = new Date(pedido.data_pedido).toLocaleString('pt-BR');
            const totalFormatado = parseFloat(pedido.valor_total).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            
            // Status badge
            const statusClass = 'status-' + pedido.status;

            const cardHtml = `
                <div class="card">
                    <div class="card-header">
                        <strong>Pedido #${pedido.id}</strong><br>
                        <small>${pedido.nome_cliente}</small>
                    </div>
                    <div class="pedido-info">
                        <div class="pedido-info-row">
                            <div>
                                <div class="info-item">
                                    <span class="info-label">Cliente:</span>
                                    <span class="info-value">${pedido.nome_cliente}</span>
                                </div>
                                <div class="info-item" style="margin-top: 10px;">
                                    <span class="info-label">Data:</span>
                                    <span class="info-value">${dataFormatada}</span>
                                </div>
                            </div>
                            <div>
                                <div class="info-item">
                                    <span class="info-label">Total:</span>
                                    <span class="info-value" style="font-weight: bold; color: #28a745;">${totalFormatado}</span>
                                </div>
                                <div class="info-item" style="margin-top: 10px;">
                                    <span class="info-label">Status:</span>
                                    <span class="status-badge ${statusClass}">${pedido.status}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-actions">
                            ${tipoUsuario === 'admin' ? `
                                <form action="../controller/alterarStatusPedido.php" method="POST" style="flex: 1; display: flex; gap: 8px;">
                                    <input type="hidden" name="id_pedido" value="${pedido.id}">
                                    <select name="status" class="form-select" style="flex: 1;">
                                        <option value="pendente" ${pedido.status === 'pendente' ? 'selected' : ''}>Pendente</option>
                                        <option value="enviado" ${pedido.status === 'enviado' ? 'selected' : ''}>Enviado</option>
                                        <option value="cancelado" ${pedido.status === 'cancelado' ? 'selected' : ''}>Cancelado</option>
                                    </select>
                                    <button class="btn-status" type="submit">Alterar</button>
                                </form>
                            ` : ''}
                            <button class="btn-detalhes" onclick="carregarItens(${pedido.id})">Ver Detalhes</button>
                        </div>
                    </div>
                    <div class="detalhes-content" id="detalhes-${pedido.id}" style="display:none; margin: 0;">
                        <div style="text-align: center;">
                            <div class="loading-spinner"></div>
                            <p style="margin-top: 10px;">Carregando...</p>
                        </div>
                    </div>
                </div>
            `;

            container.innerHTML += cardHtml;
        });

        // Renderizar paginação
        renderizarPaginacao();
    }

    // Renderizar paginação
    function renderizarPaginacao() {
        const totalPaginas = Math.ceil(todosOsPedidos.length / limiteItens);

        if (totalPaginas <= 1) {
            document.getElementById('paginacao').style.display = 'none';
            return;
        }

        const ulPaginacao = document.getElementById('ulPaginacao');
        ulPaginacao.innerHTML = '';

        for (let i = 1; i <= totalPaginas; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === paginaAtual ? 'active' : ''}`;

            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = i;
            a.onclick = (e) => {
                e.preventDefault();
                paginaAtual = i;
                renderizarPagina(i);

                // Atualizar URL
                const busca = document.getElementById('inputBusca').value;
                const novaUrl = new URL(window.location);
                novaUrl.searchParams.set('busca', busca);
                novaUrl.searchParams.set('pagina', i);
                window.history.pushState({}, '', novaUrl);

                // Scroll para o topo
                window.scrollTo(0, 0);
            };

            li.appendChild(a);
            ulPaginacao.appendChild(li);
        }

        document.getElementById('paginacao').style.display = 'flex';
    }

    // Carregar itens de um pedido (mantém a chamada ao controlador PHP)
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
            })
            .catch(error => {
                div.innerHTML = '<div class="alert alert-danger">Erro ao carregar itens: ' + error.message + '</div>';
                div.style.display = "block";
            });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>