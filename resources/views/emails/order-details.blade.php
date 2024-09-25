<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido</title>
</head>
<body>
    <h1>Olá {{ $client->nome }},</h1>
    <p>Seu pedido foi realizado com sucesso. Aqui estão os detalhes:</p>

    <ul>
        @foreach ($products as $product)
            <li>{{ $product->nome }} - Quantidade: {{ $product->pivot->quantidade }} - Preço unitário: R$ {{ number_format($product->preco, 2, ',', '.') }} - Subtotal: R$ {{ number_format($product->preco * $product->pivot->quantidade, 2, ',', '.') }}</li>
        @endforeach
    </ul>

    <p><strong>Total do pedido: R$ {{ number_format($total, 2, ',', '.') }}</strong></p>

    <p>Obrigado por comprar conosco!</p>

</body>
</html>
