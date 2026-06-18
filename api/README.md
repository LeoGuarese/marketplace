# API REST de Pedidos - Documentação

## Visão Geral
A API REST permite consultar pedidos do sistema através de requisições GET em JSON.

## Endpoint Base
```
http://localhost/TrabCorrigidoParte1/api/pedidos.php
```

## Endpoints Disponíveis

### 1. Listar Todos os Pedidos
**Requisição:**
```
GET /api/pedidos.php
```

**Resposta de Sucesso (200):**
```json
{
  "sucesso": true,
  "total": 5,
  "pedidos": [
    {
      "id": "5",
      "data_pedido": "2024-01-15 10:30:45",
      "valor_total": "150.50",
      "status": "enviado",
      "data_envio": "2024-01-16 14:20:00",
      "data_cancelamento": null,
      "id_cliente": "2",
      "nome_cliente": "João Silva",
      "email_cliente": "joao@email.com",
      "telefone_cliente": "11999999999"
    }
  ]
}
```

---

### 2. Buscar Pedido por ID
**Requisição:**
```
GET /api/pedidos.php?id=5
```

**Parâmetros:**
- `id` (obrigatório): Número do pedido (deve ser numérico)

**Resposta de Sucesso (200):**
```json
{
  "sucesso": true,
  "total": 1,
  "pedidos": [
    {
      "id": "5",
      "data_pedido": "2024-01-15 10:30:45",
      "valor_total": "150.50",
      "status": "enviado",
      "data_envio": "2024-01-16 14:20:00",
      "data_cancelamento": null,
      "id_cliente": "2",
      "nome_cliente": "João Silva",
      "email_cliente": "joao@email.com",
      "telefone_cliente": "11999999999"
    }
  ]
}
```

**Resposta quando não encontrado (404):**
```json
{
  "sucesso": false,
  "mensagem": "Nenhum pedido encontrado",
  "pedidos": []
}
```

---

### 3. Buscar Pedidos por Nome do Cliente
**Requisição:**
```
GET /api/pedidos.php?cliente=João
```

**Parâmetros:**
- `cliente` (obrigatório): Nome ou parte do nome do cliente (mínimo 2 caracteres, case-insensitive)

**Resposta de Sucesso (200):**
```json
{
  "sucesso": true,
  "total": 2,
  "pedidos": [
    {
      "id": "5",
      "data_pedido": "2024-01-15 10:30:45",
      "valor_total": "150.50",
      "status": "enviado",
      "id_cliente": "2",
      "nome_cliente": "João Silva",
      "email_cliente": "joao@email.com",
      "telefone_cliente": "11999999999"
    }
  ]
}
```

---

### 4. Buscar Pedidos com Detalhamento de Itens
**Requisição:**
```
GET /api/pedidos.php?id=5&detalhar=true
```

**Parâmetros:**
- `id` ou `cliente` (obrigatório): Filtro de busca
- `detalhar` (opcional): Defina como `true` para incluir os itens do pedido

**Resposta de Sucesso (200):**
```json
{
  "sucesso": true,
  "total": 1,
  "pedidos": [
    {
      "id": "5",
      "data_pedido": "2024-01-15 10:30:45",
      "valor_total": "150.50",
      "status": "enviado",
      "id_cliente": "2",
      "nome_cliente": "João Silva",
      "email_cliente": "joao@email.com",
      "telefone_cliente": "11999999999",
      "itens": [
        {
          "id": "1",
          "id_produto": "3",
          "nome_produto": "Produto A",
          "descricao": "Descrição do produto",
          "imagem": "path/to/image.jpg",
          "quantidade": "2",
          "preco_unitario": "50.00",
          "total_item": "100.00"
        },
        {
          "id": "2",
          "id_produto": "5",
          "nome_produto": "Produto B",
          "descricao": "Outro produto",
          "imagem": "path/to/image2.jpg",
          "quantidade": "1",
          "preco_unitario": "50.50",
          "total_item": "50.50"
        }
      ]
    }
  ]
}
```

---

## Códigos de Status HTTP

| Código | Descrição |
|--------|-----------|
| 200 | Requisição bem-sucedida |
| 400 | Erro na validação dos parâmetros |
| 404 | Nenhum pedido encontrado |
| 405 | Método HTTP não permitido (use GET) |
| 500 | Erro no servidor |

---

## Exemplos de Uso com Plugin REST

### Usando o Insomnia ou Postman:

1. **Listar todos os pedidos:**
   - Método: GET
   - URL: `http://localhost/TrabCorrigidoParte1/api/pedidos.php`

2. **Buscar pedido #5:**
   - Método: GET
   - URL: `http://localhost/TrabCorrigidoParte1/api/pedidos.php?id=5`

3. **Buscar pedidos do cliente "Maria":**
   - Método: GET
   - URL: `http://localhost/TrabCorrigidoParte1/api/pedidos.php?cliente=Maria`

4. **Buscar pedido #5 com detalhes dos itens:**
   - Método: GET
   - URL: `http://localhost/TrabCorrigidoParte1/api/pedidos.php?id=5&detalhar=true`

---

## Validações

- **ID do Pedido**: Deve ser um número inteiro válido
- **Nome do Cliente**: Deve ter pelo menos 2 caracteres
- **Parâmetro detalhar**: Aceita `true` (case-insensitive)
- **CORS**: A API permite requisições de qualquer origem

---

## Tratamento de Erros

Todos os erros retornam JSON com os seguintes campos:
- `sucesso`: `false`
- `mensagem`: Descrição do erro
- HTTP Status Code: Apropriado para o erro

Exemplo de erro:
```json
{
  "sucesso": false,
  "mensagem": "ID do pedido deve ser um número"
}
```

---

## Segurança

⚠️ **Observações Importantes:**
- A API retorna informações de contato dos clientes. Considere implementar autenticação/autorização em produção.
- Adicione validação de token JWT ou similar para restringir o acesso.
- Use HTTPS em ambiente de produção.

---

## Testando com cURL

```bash
# Listar todos os pedidos
curl "http://localhost/TrabCorrigidoParte1/api/pedidos.php"

# Buscar pedido #5
curl "http://localhost/TrabCorrigidoParte1/api/pedidos.php?id=5"

# Buscar pedidos do cliente "João"
curl "http://localhost/TrabCorrigidoParte1/api/pedidos.php?cliente=Jo%C3%A3o"

# Buscar com detalhamento
curl "http://localhost/TrabCorrigidoParte1/api/pedidos.php?id=5&detalhar=true"
```
