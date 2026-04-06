# Beehive Hub PHP SDK

SDK oficial para integração com a API Beehive Hub. Aceite pagamentos de forma simples e rápida.

[![Packagist Version](https://img.shields.io/packagist/v/paybeehive/beehivehub-php-sdk)](https://packagist.org/packages/paybeehive/beehivehub-php-sdk)
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue)](https://www.php.net)

## Índice

- [Instalação](#instalação)
- [Quick Start](#quick-start)
- [Autenticação](#autenticação)
- [Recursos](#recursos)
  - [Transactions](#transactions)
  - [Customers](#customers)
  - [Transfers](#transfers)
  - [Balance](#balance)
  - [Recipients](#recipients)
  - [Bank Accounts](#bank-accounts)
  - [Company](#company)
  - [Payment Links](#payment-links)
- [Tratamento de Erros](#tratamento-de-erros)
- [Valores em Centavos](#valores-em-centavos)
- [Boas Práticas de Segurança](#boas-práticas-de-segurança)
- [Suporte](#suporte)
- [Licença](#licença)

## Instalação

```bash
composer require paybeehive/beehivehub-php-sdk
```

**Requisitos:** PHP 8.1+, extensões `curl` e `json`.

## Quick Start

```php
<?php

require_once 'vendor/autoload.php';

use BeehiveHub\SDK\BeehiveHubClient;

$beehive = new BeehiveHubClient('your_secret_key');

$transaction = $beehive->transactions->create([
    'amount'        => 10000, // R$ 100,00 em centavos
    'paymentMethod' => 'pix',
    'customer'      => [
        'name'     => 'João Silva',
        'email'    => 'joao@example.com',
        'document' => ['type' => 'cpf', 'number' => '00000000191'],
        'phone'    => '11999999999',
    ],
    'items' => [
        ['title' => 'Produto Teste', 'unitPrice' => 10000, 'quantity' => 1, 'tangible' => true],
    ],
    'postbackUrl' => 'https://example.com/webhook',
]);

echo "Transação criada: " . $transaction['id'];
```

## Autenticação

O SDK usa **Basic Authentication**. Forneça sua **SECRET_KEY** ao instanciar o cliente.

### Obtendo suas credenciais

1. Acesse o [dashboard Beehive Hub](https://app.conta.paybeehive.com.br)
2. Navegue em **Configurações → Credenciais de API**
3. Copie sua **SECRET_KEY**

```php
$beehive = new BeehiveHubClient('your_secret_key_here');
```

### Ambiente Sandbox

```php
$beehive = new BeehiveHubClient('your_secret_key', ['environment' => 'sandbox']);
```

**Importante:** Nunca exponha sua secret key em código client-side ou repositórios públicos. Use variáveis de ambiente:

```php
$beehive = new BeehiveHubClient($_ENV['BEEHIVE_SECRET_KEY']);
```

## Recursos

### Transactions

#### Criar uma transação

```php
$transaction = $beehive->transactions->create([
    'amount'        => 10000,
    'paymentMethod' => 'pix',
    'customer'      => [
        'name'     => 'João Silva',
        'email'    => 'joao@example.com',
        'document' => ['type' => 'cpf', 'number' => '00000000191'],
        'phone'    => '11999999999',
    ],
    'items' => [
        ['title' => 'Produto Teste', 'unitPrice' => 10000, 'quantity' => 1, 'tangible' => true],
    ],
    'postbackUrl' => 'https://example.com/webhook',
    'metadata'    => ['orderId' => '1234567890'],
]);
```

#### Listar transações

```php
$transactions = $beehive->transactions->list([
    'limit'       => 100,
    'offset'      => 0,
    'createdFrom' => '2026-01-01T00:00:00',
]);
```

#### Buscar uma transação

```php
$transaction = $beehive->transactions->get(123456);
```

#### Estornar uma transação

```php
// Estorno total
$refund = $beehive->transactions->refund(123456);

// Estorno parcial
$partialRefund = $beehive->transactions->refund(123456, 5000);
```

#### Atualizar status de entrega

```php
$updated = $beehive->transactions->updateDelivery(123456, [
    'status'       => 'in_transit',
    'trackingCode' => 'BR123456789',
]);
```

### Customers

#### Criar um cliente

```php
$customer = $beehive->customers->create([
    'name'     => 'Maria Santos',
    'email'    => 'maria@example.com',
    'document' => ['type' => 'cpf', 'number' => '98765432100'],
    'phone'    => '11988888888',
    'address'  => [
        'street'       => 'Rua Teste',
        'streetNumber' => '456',
        'complement'   => 'Apto 101',
        'neighborhood' => 'Jardins',
        'zipCode'      => '01234567',
        'city'         => 'São Paulo',
        'state'        => 'SP',
        'country'      => 'br',
    ],
]);
```

#### Listar clientes

O parâmetro `email` é obrigatório pela API.

```php
$customers = $beehive->customers->list(['email' => 'cliente@example.com']);
```

#### Buscar um cliente

```php
$customer = $beehive->customers->get(123456);
```

### Transfers

#### Criar uma transferência

```php
$transfer = $beehive->transfers->create([
    'amount'      => 50000,
    'recipientId' => 916,
]);

// Com conta bancária opcional
$transferWithAccount = $beehive->transfers->create([
    'amount'      => 50000,
    'recipientId' => 916,
    'bankAccount' => [
        'bankCode'       => '001',
        'agencyNumber'   => '1234',
        'accountNumber'  => '12345',
        'accountDigit'   => '6',
        'type'           => 'conta_corrente',
        'legalName'      => 'Destinatário Teste',
        'documentNumber' => '12345678900',
        'documentType'   => 'cpf',
    ],
]);
```

#### Buscar uma transferência

```php
$transfer = $beehive->transfers->get(123456);
```

### Balance

```php
$balance = $beehive->balance->get();
echo "Disponível: R$ " . ($balance['amount'] / 100);
echo "Recipient ID: " . $balance['recipientId'];
```

### Recipients

#### Criar um recipient

```php
$recipient = $beehive->recipients->create([
    'legalName'        => 'Recebedor Teste Ltda',
    'document'         => ['type' => 'cnpj', 'number' => '58593776000142'],
    'transferSettings' => [
        'transferEnabled'                => true,
        'automaticAnticipationEnabled'   => false,
        'anticipatableVolumePercentage'  => 100,
    ],
    'bankAccount' => [
        'bankCode'       => '001',
        'agencyNumber'   => '1234',
        'accountNumber'  => '12345',
        'accountDigit'   => '6',
        'type'           => 'conta_corrente',
        'legalName'      => 'Recebedor Teste Ltda',
        'documentNumber' => '58593776000142',
        'documentType'   => 'cnpj',
    ],
]);
```

#### Listar recipients

```php
$recipients = $beehive->recipients->list();
```

#### Buscar um recipient

```php
$recipient = $beehive->recipients->get(916);
```

#### Atualizar um recipient

```php
$updated = $beehive->recipients->update(916, ['legalName' => 'Novo Nome Ltda']);
```

### Bank Accounts

#### Adicionar conta bancária

```php
$bankAccount = $beehive->bankAccounts->create(916, [
    'bankCode'       => '341',
    'agencyNumber'   => '9876',
    'accountNumber'  => '54321',
    'accountDigit'   => '0',
    'type'           => 'conta_poupanca',
    'legalName'      => 'Empresa Teste Ltda',
    'documentNumber' => '60572883000136',
    'documentType'   => 'cnpj',
]);
```

#### Listar contas bancárias

```php
$accounts = $beehive->bankAccounts->list(916);
```

### Company

#### Buscar dados da empresa

```php
$company = $beehive->company->get();
```

#### Atualizar dados da empresa

```php
$updated = $beehive->company->update([
    'invoiceDescriptor' => 'Beehive Hub',
    'details'           => [
        'averageRevenue'      => 10000,
        'averageTicket'       => 100.50,
        'physicalProducts'    => true,
        'productsDescription' => 'Produtos físicos',
        'siteUrl'             => 'https://www.meusite.com.br',
        'phone'               => '11999999999',
        'email'               => 'contato@meusite.com.br',
    ],
]);
```

### Payment Links

O SDK adiciona `url` às respostas (create, get, list, update) quando há `alias`:
- **Produção:** `https://link.conta.paybeehive.com.br/{alias}`
- **Sandbox:** `https://link.sandbox.hopysplit.com.br/{alias}`

#### Criar um link de pagamento

Se `alias` não for informado, o SDK gera automaticamente um código alfanumérico de 10 caracteres.

```php
$paymentLink = $beehive->paymentLinks->create([
    'title'    => 'Meu Link de Pagamento',
    'amount'   => 1000,
    'settings' => [
        'defaultPaymentMethod' => 'credit_card',
        'requestAddress'       => true,
        'requestPhone'         => true,
        'traceable'            => true,
        'boleto'               => ['enabled' => true, 'expiresInDays' => 3],
        'pix'                  => ['enabled' => true, 'expiresInDays' => 1],
        'card'                 => ['enabled' => true, 'freeInstallments' => 1, 'maxInstallments' => 12],
    ],
]);
// $paymentLink['url'] já vem montada
```

#### Listar links de pagamento

```php
$paymentLinks = $beehive->paymentLinks->list();
```

#### Buscar um link de pagamento

```php
$paymentLink = $beehive->paymentLinks->get(247);
```

#### Atualizar um link de pagamento

Aceita atualizações parciais (apenas os campos que deseja alterar).

```php
$updated = $beehive->paymentLinks->update(247, [
    'title'  => 'Título Atualizado',
    'amount' => 2000,
]);
```

#### Deletar um link de pagamento

```php
$beehive->paymentLinks->delete(247);
```

## Tratamento de Erros

O SDK lança exceções específicas para cada cenário:

| Exceção | HTTP | Descrição |
|---|---|---|
| `BeehiveHubAuthenticationError` | 401 | Chave de API inválida |
| `BeehiveHubValidationError` | 400 | Erro de validação dos dados |
| `BeehiveHubNotFoundError` | 404 | Recurso não encontrado |
| `BeehiveHubRateLimitError` | 429 | Limite de requisições excedido |
| `BeehiveHubAPIError` | outros | Erros gerais da API |
| `BeehiveHubNetworkError` | — | Erro de rede/conexão |

```php
use BeehiveHub\SDK\BeehiveHubClient;
use BeehiveHub\SDK\Exceptions\BeehiveHubAuthenticationError;
use BeehiveHub\SDK\Exceptions\BeehiveHubValidationError;
use BeehiveHub\SDK\Exceptions\BeehiveHubAPIError;
use BeehiveHub\SDK\Exceptions\BeehiveHubNetworkError;

$beehive = new BeehiveHubClient($_ENV['BEEHIVE_SECRET_KEY']);

try {
    $transaction = $beehive->transactions->create([...]);
    echo "Transação criada: " . $transaction['id'];
} catch (BeehiveHubAuthenticationError $e) {
    echo "Chave de API inválida: " . $e->getMessage();
} catch (BeehiveHubValidationError $e) {
    echo "Erro de validação: " . $e->getMessage();
    print_r($e->details);
} catch (BeehiveHubAPIError $e) {
    echo "Erro da API [{$e->statusCode}]: " . $e->getMessage();
} catch (BeehiveHubNetworkError $e) {
    echo "Erro de rede: " . $e->getMessage();
}
```

## Valores em Centavos

Todos os valores monetários na API são expressos em **centavos**.

```php
// R$ 100,00 = 10000 centavos
'amount' => 10000

// R$ 1,50 = 150 centavos
'amount' => 150

// Converter reais para centavos
$reais = 100.0;
$centavos = (int) round($reais * 100); // 10000
```

## Boas Práticas de Segurança

1. **Nunca exponha sua SECRET_KEY** — use variáveis de ambiente
2. **Valide os dados do usuário** antes de enviar para a API
3. **Use HTTPS** em todas as conexões
4. **Implemente webhooks** para receber notificações de mudança de status

```php
// .env
BEEHIVE_SECRET_KEY=your_secret_key_here

// app.php
use BeehiveHub\SDK\BeehiveHubClient;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$beehive = new BeehiveHubClient($_ENV['BEEHIVE_SECRET_KEY']);
```

## Documentação Adicional

- [Documentação Oficial da API](https://paybeehive.readme.io/reference)
- [Guia de Integração](https://paybeehive.readme.io/docs)

## Suporte

Para sugestões, bugs ou dúvidas:

- **E-mail:** contato@paybeehive.com.br
- **Documentação:** https://paybeehive.readme.io

## Licença

Este projeto está licenciado sob a Licença MIT — veja o arquivo [LICENSE](LICENSE) para detalhes.
