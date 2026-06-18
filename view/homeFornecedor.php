<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login1.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Fornecedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styleHomeFornecedor.css">
</head>
<body>

<?php include("../styles/navbar.php"); ?>

    <div class="container" style="padding-top: 30px;">
        <div class="grid-container">
            <div>
                <div id="carregandoPedidos" class="loading-container" style="display: none;">
                    <div class="loading-spinner"></div>
                    <p class="mt-3">Carregando pedidos...</p>
                </div>

                <div id="containerPedidos"></div>

                <div id="semResultados" class="no-results" style="display: none;">
                    Nenhum pedido encontrado
                </div>
            </div>
        </div>
    </div>

<script>
    const idFornecedor = <?php echo $_SESSION['id']; ?>;

    // Carregar pedidos ao iniciar
    window.addEventListener('load', carregarPedidosFornecedor);

    async function carregarPedidosFornecedor() {
        const divCarregando = document.getElementById('carregandoPedidos');
        const divPedidos = document.getElementById('containerPedidos');
        const divSemResultados = document.getElementById('semResultados');

        divCarregando.style.display = 'block';
        divPedidos.innerHTML = '';
        divSemResultados.style.display = 'none';

        try {
            const response = await fetch('../api/pedidosFornecedor.php');
            const data = await response.json();

            divCarregando.style.display = 'none';

            if (!data.sucesso || data.total === 0) {
                divSemResultados.style.display = 'block';
                return;
            }

            // Renderizar pedidos
            data.pedidos.forEach(pedido => {
                const dataFormatada = new Date(pedido.data_pedido).toLocaleString('pt-BR');
                const totalFormatado = parseFloat(pedido.valor_total).toLocaleString('pt-BR', { 
                    style: 'currency', 
                    currency: 'BRL' 
                });

                const statusClass = 'status-' + pedido.status;

                const cardHtml = `
                    <div class="card">
                        <div class="card-header">
                            <strong>Pedido #${pedido.id}</strong><br>
                            <small>${pedido.nome_cliente}</small>
                        </div>
                        <div class="pedido-info">
                            <div class="pedido-info-row">
                                <div class="info-item">
                                    <span class="info-label">Cliente:</span>
                                    <span class="info-value">${pedido.nome_cliente}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email:</span>
                                    <span class="info-value">${pedido.email_cliente}</span>
                                </div>
                            </div>

                            <div class="pedido-info-row">
                                <div class="info-item">
                                    <span class="info-label">Data do Pedido:</span>
                                    <span class="info-value">${dataFormatada}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Telefone:</span>
                                    <span class="info-value">${pedido.telefone_cliente || 'N/A'}</span>
                                </div>
                            </div>

                            <div class="pedido-info-row">
                                <div class="info-item">
                                    <span class="info-label">Valor Total:</span>
                                    <span class="info-value" style="font-weight: bold; color: #28a745; font-size: 1.1rem;">
                                        ${totalFormatado}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status:</span>
                                    <span class="status-badge ${statusClass}">${pedido.status}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                divPedidos.innerHTML += cardHtml;
            });

        } catch (error) {
            divCarregando.style.display = 'none';
            divPedidos.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    Erro ao carregar pedidos: ${error.message}
                </div>
            `;
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>